/*jslint browser: true, devel: true, white: true, eqeq: true, plusplus: true, sloppy: true, vars: true*/
/*global $ */

/*************** General ***************/

var updateOutput = function (e) {
  var list = e.length ? e : $(e.target),
      output = list.data('output');
  if (window.JSON) {
    if (output) {
      output.val(window.JSON.stringify(list.nestable('serialize')));
    }
  } else {
    alert('JSON browser support required for this page.');
  }
};

var nestableList = $("#nestable > .dd-list");

/***************************************/


/*************** Delete ***************/

var deleteFromMenuHelper = function (target) {
  if (target.data('new') == 1) {
    // if it's not yet saved in the database, just remove it from DOM
    target.fadeOut(function () {
      target.remove();
      updateOutput($('#nestable').data('output', $('#json-output')));
    });
  } else {
    // otherwise hide and mark it for deletion
    target.appendTo(nestableList); // if children, move to the top level
    target.data('deleted', '1');
    target.fadeOut();
  }
};

var deleteFromMenu = function () {
  var targetId = $(this).data('owner-id');
  var target = $('[data-id="' + targetId + '"]');

  var result = confirm("Delete " + target.data('name') + " and all its subitems ?");
  if (!result) {
    return;
  }

  // Remove children (if any)
  target.find("li").each(function () {
    deleteFromMenuHelper($(this));
  });

  // Remove parent
  deleteFromMenuHelper(target);

  // update JSON
  updateOutput($('#nestable').data('output', $('#json-output')));
};

/***************************************/


/*************** Edit ***************/

var menuEditor = $("#menu-editor");
var editButton = $("#editButton");
var editInputName = $("#editInputName");
var editInputSlug = $("#editInputSlug");
var currentEditName = $("#currentEditName");



// Prepares and shows the Edit Form
var prepareEdit = function () {
  var targetId = $(this).data('owner-id');
  
	
  
  $(".editFormInner").remove();
  $("#edit-div-"+targetId).append(editForm);
  
	if (targetId != "") {
		$.ajax({    //create an ajax request to getEmpDetails.php
			type: "GET",
			url: "includes/getEmpDetails.php?empID="+targetId,  
			dataType: "json",
			cache: false,                
			success: function(empDetail){
				$("#empID").val(targetId);
				$("#telephone").val(empDetail["Telephone"]);
				$("#extension").val(empDetail["Fax"]);
				$("#email").val(empDetail["Email"]);
				$("#password").val(empDetail["U_pass"]);
				$("#commission").val(empDetail["Commission"]);
				$("#priceList").val(empDetail["U_priceList"]);
				$("#wareHouse").val(empDetail["U_branch"]);
				if(empDetail["U_export"] == "Y") {
					$("#export").prop("checked", true);
				}
				if(empDetail["U_discounts"] == "Y") {
					$("#discounts").prop("checked", true);
				}
			}
		});
	}
	
	$("#empCancel").click(function () {
		$(".editFormInner").remove();
	});
	
	// Submit Form
	$("#empDetailsForm").validate({
		rules: {
			telephone: {
				required: true
			}
		},
		submitHandler: function(form) {
			$.post('../../includes/genadmin/empUpdate.php', $("#empDetailsForm").serialize())
			.done(function(data) {
				$("#employeeAdmin").find(".inMessage").remove();
				$("#employeeAdmin").append("<div class='inMessage'>Cambios guardados con &eacute;xito<div class='inCloseMessage'>[Aceptar]</div></div>");
				$(".inCloseMessage").on("click", function() {
					$(this).closest(".inMessage").remove();
				});
			})
			.fail(function(data) {
				console.log(data);
			});
			event.preventDefault();
		}
	});
	
	$("#upEmp").click(function () {
		$("#empDetailsForm").submit();
	});
};

// Edits the Menu item and hides the Edit Form
var editMenuItem = function () {
  var targetId = $(this).data('owner-id');
  var target = $('[data-id="' + targetId + '"]');

  var newName = editInputName.val();
  var newSlug = editInputSlug.val();

  target.data("name", newName);
  target.data("slug", newSlug);

  target.find("> .dd-handle").html(newName);

  menuEditor.fadeOut();

  // update JSON
  updateOutput($('#nestable').data('output', $('#json-output')));
};

/***************************************/


/*************** Add ***************/

var newIdCount = 1;

var addToMenu = function () {
  var newName = $("#addInputName").val();
  var newSlug = $("#addInputSlug").val();
  var newId = 'new-' + newIdCount;

  nestableList.append(
    '<li class="dd-item" ' +
    'data-id="' + newId + '" ' +
    'data-name="' + newName + '" ' +
    'data-slug="' + newSlug + '" ' +
    'data-new="1" ' +
    'data-deleted="0">' +
    '<div class="dd-handle">' + newName + '</div> ' +
    '<span class="button-delete btn btn-default btn-xs pull-right" ' +
    'data-owner-id="' + newId + '"> ' +
    '<i class="fa fa-times-circle-o" aria-hidden="true"></i> ' +
    '</span>' +
    '<span class="button-edit btn btn-default btn-xs pull-right" ' +
    'data-owner-id="' + newId + '">' +
    '<i class="fa fa-pencil" aria-hidden="true"></i>' +
    '</span>' +
    '</li>'
  );

  newIdCount++;

  // update JSON
  updateOutput($('#nestable').data('output', $('#json-output')));

  // set events
  $("#nestable .button-delete").on("click", deleteFromMenu);
  $("#nestable .button-edit").on("click", prepareEdit);
};



/***************************************/



$(function () {

  // output initial serialised data
  updateOutput($('#nestable').data('output', $('#json-output')));

  // set onclick events
  editButton.on("click", editMenuItem);

  $("#nestable .button-delete").on("click", deleteFromMenu);

  $("#nestable .button-edit").on("click", prepareEdit);

  $("#menu-editor").submit(function (e) {
    e.preventDefault();
  });

  $("#menu-add").submit(function (e) {
    e.preventDefault();
    addToMenu();
  });
  
	$('#nestable-menu').on('click', function(e) {
		var target = $(e.target),
		action = target.data('action');
		if (action === 'expand-all') {
			$('.dd').nestable('expandAll');
		}
		if (action === 'collapse-all') {
			$('.dd').nestable('collapseAll');
		}
	});

});

