{*
Copyright 2011-2012 Nick Korbel

This file is part of phpScheduleIt.

phpScheduleIt is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

phpScheduleIt is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with phpScheduleIt.  If not, see <http://www.gnu.org/licenses/>.
*}
<script type="text/javascript">
$(function(){
  $("#{$ControlId}").datepicker({ldelim} 
		 numberOfMonths: {$NumberOfMonths},
		 showButtonPanel: {$ShowButtonPanel},
		 onSelect: {$OnSelect},
		 dayNames: {$DayNames},
		 dayNamesShort: {$DayNamesShort},
		 dayNamesMin: {$DayNamesMin},
		 dateFormat: '{$DateFormat}',
		 firstDay: {$FirstDay},
		 monthNames: {$MonthNames},
		 monthNamesShort: {$MonthNamesShort},
		 currentText: "{translate key='Today'}"
	  	 {if $AltId neq ''}
		   ,
	  		altField: "#{$AltId}",
	  	 	altFormat: '{$AltFormat}'
		  {/if}
  {rdelim});

  {if $AltId neq ''}
	$("#{$ControlId}").change(function() {
 		if ($(this).val() == '') {
			$("#{$AltId}").val('');
		}
		else{
			var dateVal = $("#{$ControlId}").datepicker('getDate');
			var dateString = dateVal.getFullYear() + '/' + (dateVal.getMonth()+1) + '/' + dateVal.getDate();
			$("#{$AltId}").val(dateString);
		}
  	});
  {/if}

});
</script>