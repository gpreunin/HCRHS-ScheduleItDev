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
{include file='globalheader.tpl' cssFiles='scripts/css/colorbox.css,css/admin.css,css/jquery.qtip.min.css,scripts/css/timePicker.css'}

<h1>{translate key=ManageReservations}</h1>

<div class="admin">
	<div class="title">
		{translate key=AddBlackout}
	</div>
	<div>
		<form id="addBlackoutForm" method="post">
			<ul>
				<li>
					<label for="addStartDate" class="wideLabel">{translate key=BeginDate}</label>
					<input type="text" id="addStartDate" class="textbox" size="10" value="{formatdate date=$AddStartDate}"/>
					<input {formname key=BEGIN_DATE} id="formattedAddStartDate" type="hidden" value="{formatdate date=$AddStartDate key=system}"/>
					<input {formname key=BEGIN_TIME} type="text" id="addStartTime" class="textbox" size="7" value="12:00 AM" />
				</li>
				<li>
					<label for="addEndDate" class="wideLabel">{translate key=EndDate}</label>
					<input type="text" id="addEndDate" class="textbox" size="10" value="{formatdate date=$AddEndDate}"/>
					<input {formname key=END_DATE} type="hidden" id="formattedAddEndDate" value="{formatdate date=$AddEndDate key=system}"/>
					<input {formname key=END_TIME} type="text" id="addEndTime" class="textbox" size="7"  value="12:00 AM" />
				</li>
				<li>
					<label for="addResourceId" class="wideLabel">{translate key=Resource}</label>
					<select {formname key=RESOURCE_ID} class="textbox" id="addResourceId">
						{object_html_options options=$Resources key='GetId' label="GetName" selected=$ResourceId}
					</select>
					|
					<label for="allResources" style="">{translate key=AllResourcesOn} </label> <input {formname key=BLACKOUT_APPLY_TO_SCHEDULE} type="checkbox" id="allResources" />
					<select {formname key=SCHEDULE_ID} id="addScheduleId" class="textbox" disabled="disabled">
						{object_html_options options=$Schedules key='GetId' label="GetName" selected=$ScheduleId}
					</select>
				</li>
				<li>
					<label for="blackoutReason" class="wideLabel">{translate key=Reason}</label>
					<input {formname key=SUMMARY} type="text" id="blackoutReason" class="textbox required" size="100" maxlength="85"/>
				</li>
				<li>
					<input {formname key=CONFLICT_ACTION} type="radio" id="notifyExisting" name="existingReservations" checked="checked" value="{ReservationConflictResolution::Notify}" />
					<label for="notifyExisting">{translate key=BlackoutShowMe}</label>

					<input {formname key=CONFLICT_ACTION} type="radio" id="deleteExisting" name="existingReservations" value="{ReservationConflictResolution::Delete}" />
					<label for="deleteExisting">{translate key=BlackoutDeleteConflicts}</label>
				</li>
				<li style="margin-top:15px; padding-top:15px; border-top: solid 1px #ededed;">
					<button type="button" class="button save create">
						{html_image src="tick-circle.png"} {translate key='Create'}
					</button>
					<input type="reset" value="Cancel" class="button" style="border: 0;background: transparent;color: blue;cursor:pointer; font-size: 60%" />
				</li>
			</ul>
		</form>
	</div>
</div>

<fieldset>
	<legend><h3>{translate key=Filter}</h3></legend>
	<table style="display:inline;">
		<tr>
			<td>{translate key=Between}</td>
			<td>{translate key=Schedule}</td>
			<td>{translate key=Resource}</td>
		</tr>
		<tr>
			<td>
				<input id="startDate" type="text" class="textbox" value="{formatdate date=$StartDate}"/>
				<input id="formattedStartDate" type="hidden" value="{formatdate date=$StartDate key=system}"/>
				-
				<input id="endDate" type="text" class="textbox" value="{formatdate date=$EndDate}"/>
				<input id="formattedEndDate" type="hidden" value="{formatdate date=$EndDate key=system}"/>
			</td>
			<td>
				<select id="scheduleId" class="textbox">
					<option value="">{translate key=AllSchedules}</option>
					{object_html_options options=$Schedules key='GetId' label="GetName" selected=$ScheduleId}
				</select>
			</td>
			<td>
				<select id="resourceId" class="textbox">
					<option value="">{translate key=AllResources}</option>
					{object_html_options options=$Resources key='GetId' label="GetName" selected=$ResourceId}
				</select>
			</td>
			<td rowspan="2">
				<button id="filter" class="button">{html_image src="search.png"} {translate key=Filter}</button>
			</td>
		</tr>
	</table>
</fieldset>

<div>&nbsp;</div>

<table class="list" id="blackoutTable">
	<tr>
		<th class="id">&nbsp;</th>
		<th>{translate key=Resource}</th>
		<th>{translate key=BeginDate}</th>
		<th>{translate key=EndDate}</th>
		<th>{translate key=CreatedBy}</th>
		<th>{translate key=Delete}</th>
	</tr>
	{foreach from=$blackouts item=blackout}
	{cycle values='row0,row1' assign=rowCss}
	<tr class="{$rowCss} editable">
		<td class="id">{$blackout->InstanceId}</td>
		<td>{$blackout->ResourceName}</td>
		<td>{formatdate date=$blackout->StartDate timezone=$Timezone key=res_popup}</td>
		<td>{formatdate date=$blackout->EndDate timezone=$Timezone key=res_popup}</td>
		<td>{$blackout->FirstName} {$blackout->LastName}</td>
		<td align="center"><a href="#" class="update delete">{html_image src='cross-button.png'}</a></td>
	</tr>
	{/foreach}
</table>

{pagination pageInfo=$PageInfo}

<div id="deleteDialog" class="dialog" style="display:none;" title="{translate key=Delete}">
	<form id="deleteForm" method="post">
		<div class="error" style="margin-bottom: 25px;">
			<h3>{translate key=DeleteWarning}</h3>
		</div>
		<button type="button" class="button save">{html_image src="cross-button.png"} {translate key='Delete'}</button>
		<button type="button" class="button cancel">{html_image src="slash.png"} {translate key='Cancel'}</button>
	</form>
</div>

{html_image src="admin-ajax-indicator.gif" class="indicator" style="display:none;"}

<script type="text/javascript" src="{$Path}scripts/js/jquery.qtip.min.js"></script>
<script type="text/javascript" src="{$Path}scripts/js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="{$Path}scripts/js/jquery.form-2.43.js"></script>
<script type="text/javascript" src="{$Path}scripts/js/jquery.timePicker.min.js"></script>

<script type="text/javascript" src="{$Path}scripts/reservationPopup.js"></script>

<script type="text/javascript" src="{$Path}scripts/admin/edit.js"></script>
<script type="text/javascript" src="{$Path}scripts/admin/blackouts.js"></script>

<script type="text/javascript">

$(document).ready(function() {

	var updateScope = {};
	updateScope['btnUpdateThisInstance'] = '{SeriesUpdateScope::ThisInstance}';
	updateScope['btnUpdateAllInstances'] = '{SeriesUpdateScope::FullSeries}';
	updateScope['btnUpdateFutureInstances'] = '{SeriesUpdateScope::FutureInstances}';

	var actions = {};
		
	var blackoutOpts = {
		reservationUrlTemplate: "{$Path}reservation.php?{QueryStringKeys::REFERENCE_NUMBER}=[refnum]",
		updateScope: updateScope,
		actions: actions,
		deleteUrl: '{$smarty.server.SCRIPT_NAME}?action={ManageBlackoutsActions::DELETE}&{QueryStringKeys::BLACKOUT_ID}=',
		addUrl: '{$smarty.server.SCRIPT_NAME}?action={ManageBlackoutsActions::ADD}',
        reservationUrlTemplate: "{$Path}reservation.php?{QueryStringKeys::REFERENCE_NUMBER}=[refnum]",
		popupUrl: "{$Path}ajax/respopup.php"
	};

	
	var blackoutManagement = new BlackoutManagement(blackoutOpts);
	blackoutManagement.init();

	
});
</script>

{control type="DatePickerSetupControl" ControlId="startDate" AltId="formattedStartDate"}
{control type="DatePickerSetupControl" ControlId="endDate" AltId="formattedEndDate"}
{control type="DatePickerSetupControl" ControlId="addStartDate" AltId="formattedAddStartDate"}
{control type="DatePickerSetupControl" ControlId="addEndDate" AltId="formattedAddEndDate"}

<div id="createDiv" style="display:none;text-align:center; top:15%;position:relative;">
	<div id="creating">
		<h3>{translate key=Working}...</h3>
		{html_image src="reservation_submitting.gif"}
	</div>
	<div id="result" style="display:none;"></div>
</div>

{include file='globalfooter.tpl'}