var getInventory = function(ind) {
	var code = $(".itemCode:eq("+ind+")").val();
	if (code.length >= 3) {
		var lPrice = 0;
		var fPrice = 0;
		var nPrice = 0;
		$.ajax({
			type: "GET",
			url: "includes/prodDetails.php?code="+encodeURI(code),  
			dataType: "json",
			cache: false,                
			success: function(prodDetail){
				// Write stock
				var totOnHand = 0;
				<?php
				foreach($whCodes as $whCode) {
					echo "$('.oH".$whCode.":eq('+ind+')').html(parseInt(prodDetail['e".$whCode."']));";
					echo "totOnHand += parseInt(prodDetail['e".$whCode."']);";
					echo "$('.oO".$whCode.":eq('+ind+')').find('span').html(parseInt(prodDetail['p".$whCode."']));";
					echo "$('.oO".$whCode.":eq('+ind+')').find('.divOOSD').html(prodDetail['qo".$whCode."']);";
				}
				?>
				$(".oHTotal:eq("+ind+")").html(totOnHand);
			}
		});
	}
};