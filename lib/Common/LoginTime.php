<?php
/**
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
*/

class LoginTime
{
	public static $Now = null;
	
	private static $_format = 'Y-m-d H:i:s';
	
	public static function Now()
	{
		if (empty(self::$Now))
		{
			$date = new Date();
			return $date->Format(self::$_format);
		}
		else 
		{
			return date(self::$_format, self::$Now);
		}
	}
}
?>