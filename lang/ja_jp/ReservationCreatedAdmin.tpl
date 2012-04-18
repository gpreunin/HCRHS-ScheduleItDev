{* -*-coding:utf-8-*- 
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
	
	予約の詳細: 
	<br/>
	<br/>
	
	ユーザー: {$UserName}
	開始: {formatdate date=$StartDate key=reservation_email}<br/>
	終了: {formatdate date=$EndDate key=reservation_email}<br/>
	リソース: {$ResourceName}<br/>
	件名: {$Title}<br/>
	説明: {$Description}<br/>
	
	{if count($RepeatDates) gt 0}
		<br/>
		下記まで繰り返されています:
		<br/>
	{/if}
	
	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $RequiresApproval}
		<br/>
		承認が必要なリソースの予約が含まれています。 そのため承認されるまでは保留状態となります。
	{/if}
	
	<br/>
	<a href="{$ScriptUrl}{$ReservationUrl}">予約の表示</a> | <a href="{$ScriptUrl}">phpScheduleItへログイン</a>
	
{include file='..\..\tpl\Email\emailfooter.tpl'}