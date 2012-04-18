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

interface IRegistration
{
	/**
	 * @param string $login
	 * @param string $email
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $password unencrypted password
	 * @param string $timezone name of user timezone
	 * @param string $language preferred language code
	 * @param int $homepageId lookup id of the page to redirect the user to on login
	 * @param array $additionalFields key value pair of additional fields to use during registration
	 */
	public function Register($login, $email, $firstName, $lastName, $password, $timezone, $language, $homepageId, $additionalFields = array());
	
	/**
	 * @param string $loginName
	 * @param string $emailAddress
	 * @return bool if the user exists or not
	 */
	public function UserExists($loginName, $emailAddress);

	/**
	 * Add or update a user who has already been authenticated
	 * @param AuthenticatedUser $user
	 * @return void
	 */
	public function Synchronize(AuthenticatedUser $user);
}
?>