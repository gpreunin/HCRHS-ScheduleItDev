<?php
/**
Copyright 2012 Nick Korbel

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
 */

define('ROOT_DIR', '../../');

require_once(ROOT_DIR . 'Pages/Admin/GroupAdminManageUsersPage.php');
require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

$page = new SecureActionPageDecorator(new GroupAdminManageUsersPage());
if ($page->TakingAction())
{
	$page->ProcessAction();
}
else if ($page->RequestingData())
{
	$page->ProcessDataRequest();
}
else
{
	$page->PageLoad();
}
?>