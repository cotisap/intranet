<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="/css/datepicker.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<div id="filter">
<form>
<select name="venue" id="venue">
	<option>Alejandro Sanz</option>
</select>
<!-- Fecha de inicio <input type="text" name="start" id="start" required /> Fecha final <input type="text" name="end" id="end" required />-->
</form>
</div>
<!--script>
  $(function() {
    $( "#start" ).datepicker({
	  dateFormat: 'dd-mm-yy',
      defaultDate: "+1w",
	  dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
      onClose: function( selectedDate ) {
        $( "#end" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#end" ).datepicker({
	  dateFormat: 'dd-mm-yy',
      defaultDate: "+1w",
	  dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
      onClose: function( selectedDate ) {
        $( "#start" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
  });
  </script-->