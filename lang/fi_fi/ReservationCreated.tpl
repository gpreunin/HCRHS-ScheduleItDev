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
{include file='..\..\tpl\Email\emailheader.tpl'}
	
	Varauksen tiedot: 
	<br/>
	<br/>
	
	Alkaa: {formatdate date=$StartDate key=reservation_email}<br/>
	Päättyy: {formatdate date=$EndDate key=reservation_email}<br/>
	Resurssi: {$ResourceName}<br/>
	Otsikko: {$Title}<br/>
	Kuvaus: {$Description|nl2br}<br/>
	
	{if count($RepeatDates) gt 0}
		<br/>
		Varaus toistuu seuraavina päivinä:
		<br/>
	{/if}
	
	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $RequiresApproval}
		<br/>
		Yksi tai useampi varattu resurssi vaatii hyväksynnän ennen käyttöä.  Ole hyvä ja varmista, hyväksytäänkö vai hylätäänkö tämä varauspyyntö.
	{/if}
	
	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Näytä varaus</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Lisää Outlookiin</a> |
	<a href="{$ScriptUrl}">Kirjaudu sovellukseen phpScheduleIt</a>
	
{include file='..\..\tpl\Email\emailfooter.tpl'}
