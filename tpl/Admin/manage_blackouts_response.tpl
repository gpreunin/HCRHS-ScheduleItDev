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
<div>
	{if $Successful}
		<h3>{translate key=BlackoutCreated}</h3>
		<button class="reload button">{translate key="OK"}</button>
	{else}
		<h3>{translate key=BlackoutNotCreated}</h3>
		<button class="close button">{translate key="OK"}</button>
	{/if}

	{if !empty($Message)}
		<h5>{$Message}</h5>
	{/if}

	{if !empty($Blackouts)}
		<h5>{translate key=BlackoutConflicts}</h5>
		{foreach from=$Blackouts item=blackout}
			{format_date date=$blackout->StartDate timezone=$Timezone}
		{/foreach}
	{/if}

	{if !empty($Reservations)}
		<h5>{translate key=ReservationConflicts}</h5>
        <table class="list" id="reservationTable" style="margin-left: auto; margin-right: auto;">
        	<tr>
        		<th class="id">&nbsp;</th>
        		<th>{translate key='User'}</th>
        		<th>{translate key='Resource'}</th>
        		<th>{translate key='BeginDate'}</th>
        		<th>{translate key='EndDate'}</th>
        		<th>{translate key='ReferenceNumber'}</th>
        	</tr>
        	{foreach from=$Reservations item=reservation}
        	{cycle values='row0,row1' assign=rowCss}
        	<tr class="{$rowCss} editable">
        		<td class="id">{$reservation->ReservationId}</td>
        		<td>{$reservation->FirstName} {$reservation->LastName}</td>
        		<td>{$reservation->ResourceName}</td>
        		<td>{formatdate date=$reservation->StartDate timezone=$Timezone key=res_popup}</td>
        		<td>{formatdate date=$reservation->EndDate timezone=$Timezone key=res_popup}</td>
        		<td class="referenceNumber">{$reservation->ReferenceNumber}</td>
        	</tr>
        	{/foreach}
        </table>
	{/if}
</div>