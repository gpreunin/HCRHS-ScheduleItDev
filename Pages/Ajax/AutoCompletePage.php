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

require_once(ROOT_DIR . 'Domain/Access/namespace.php' );
require_once(ROOT_DIR . 'Pages/SecurePage.php');

class AutoCompletePage extends SecurePage
{
	private $listMethods = array();

	public function __construct()
	{
		parent::__construct();
		
	    $this->listMethods[AutoCompleteType::User] = 'GetUsers';
	    $this->listMethods[AutoCompleteType::MyUsers] = 'GetMyUsers';
	    $this->listMethods[AutoCompleteType::Group] = 'GetGroups';
	}

	public function PageLoad()
	{
		$results = $this->GetResults($this->GetType(), $this->GetSearchTerm());

		Log::Debug(sprintf('AutoComplete: %s results found for search type: %s, term: %s', count($results), $this->GetType(), $this->GetSearchTerm()));

		$this->SetJson($results);
	}

	private function GetResults($type, $term)
	{
		if (array_key_exists($type, $this->listMethods))
		{
			$method = $this->listMethods[$type];
			return $this->$method($term);
		}

		Log::Debug("AutoComplete for type: $type not defined");
		
		return '';
	}

	public function GetType()
	{
		return $this->GetQuerystring(QueryStringKeys::AUTOCOMPLETE_TYPE);
	}

	public function GetSearchTerm()
	{
		return $this->GetQuerystring(QueryStringKeys::AUTOCOMPLETE_TERM);
	}

	/**
	 * @param $term string
	 * @return array|AutocompleteUser[]
	 */
	private function GetUsers($term)
	{
		$filter = new SqlFilterLike(ColumnNames::FIRST_NAME, $term);
		$filter->_Or(new SqlFilterLike(ColumnNames::LAST_NAME, $term));

		$users = array();

		$r = new UserRepository();
		$results = $r->GetList(1, 100, null, null, $filter)->Results();

		/** @var $result UserItemView */
		foreach($results as $result)
		{
			$users[] = new AutocompleteUser($result->Id	, $result->First, $result->Last);
		}

		return $users;
	}

	private function GetGroups($term)
	{
		$filter = new SqlFilterLike(ColumnNames::GROUP_NAME, $term);
		$r = new GroupRepository();
		return $r->GetList(1, 100, null, null, $filter)->Results();
	}

	/**
	 * @param $term string
	 * @return array|AutocompleteUser[]
	 */
	private function GetMyUsers($term)
	{
		$userSession = ServiceLocator::GetServer()->GetUserSession();
		if ($userSession->IsAdmin)
		{
			return $this->GetUsers($term);
		}

		$userRepo = new UserRepository();
		$user = $userRepo->LoadById($userSession->UserId);

		$groupIds = array();

		foreach ($user->GetAdminGroups() as $group)
		{
            $groupIds[] = $group->GroupId;
		}

		$users = array();
		if (!empty($groupIds))
		{
			$userFilter = new SqlFilterLike(ColumnNames::FIRST_NAME, $term);
			$userFilter->_Or(new SqlFilterLike(ColumnNames::LAST_NAME, $term));
					
			$groupRepo = new GroupRepository();
			$results = $groupRepo->GetUsersInGroup($groupIds, null, null, $userFilter)->Results();

			/** @var $result UserItemView */
			foreach ($results as $result)
			{
				// consolidates results by user id if the user is in multiple groups
				$users[$result->Id] = new AutocompleteUser($result->Id, $result->First, $result->Last);
			}
		}

		return array_values($users);
	}
}

class AutocompleteUser
{
	public $Id;
	public $First;
	public $Last;
	public $Name;

	public function __construct($userId, $firstName, $lastName)
	{
		$this->Id = $userId;
		$this->First = $firstName;
		$this->Last = $lastName;
		$this->Name = $firstName . ' ' . $lastName;
	}
}

class AutoCompleteType
{
	const User = 'user';
	const Group = 'group';
	const MyUsers = 'myUsers';
}

?>