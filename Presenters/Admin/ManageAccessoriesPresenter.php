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

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');

class ManageAccessoriesActions
{
	const Add = 'addAccessory';
	const Change = 'changeAccessory';
	const Delete = 'deleteAccessory';
}

class ManageAccessoriesPresenter extends ActionPresenter
{
	/**
	 * @var IManageAccessoriesPage
	 */
	private $page;

	/**
	 * @var IAccessoryRepository
	 */
	private $accessoryRepository;

	/**
	 * @var IResourceRepository
	 */
	private $resourceRepository;

	/**
	 * @param IManageAccessoriesPage $page
	 * @param IResourceRepository $resourceRepository
	 * @param IAccessoryRepository $accessoryRepository
	 */
	public function __construct(IManageAccessoriesPage $page, IResourceRepository $resourceRepository, IAccessoryRepository $accessoryRepository)
	{
		parent::__construct($page);

		$this->page = $page;
		$this->resourceRepository = $resourceRepository;
		$this->accessoryRepository = $accessoryRepository;

		$this->AddAction(ManageAccessoriesActions::Add, 'AddAccessory');
		$this->AddAction(ManageAccessoriesActions::Change, 'ChangeAccessory');
		$this->AddAction(ManageAccessoriesActions::Delete, 'DeleteAccessory');
	}

	public function PageLoad()
	{
		$accessories = $this->resourceRepository->GetAccessoryList();

		$this->page->BindAccessories($accessories);
	}

	public function AddAccessory()
	{
		$name = $this->page->GetAccessoryName();
		$quantity = $this->page->GetQuantityAvailable();
		
		Log::Debug('Adding new accessory with name %s and quantity %s', $name, $quantity);

		$this->accessoryRepository->Add(Accessory::Create($name, $quantity));
	}

	public function ChangeAccessory()
	{
		$id = $this->page->GetAccessoryId();
		$name = $this->page->GetAccessoryName();
		$quantity = $this->page->GetQuantityAvailable();
		
		Log::Debug('Changing accessory with id %s to name %s and quantity %s', $id, $name, $quantity);

		$accessory = $this->accessoryRepository->LoadById($id);
		$accessory->SetName($name);
		$accessory->SetQuantityAvailable($quantity);
		
		$this->accessoryRepository->Update($accessory);
	}
	
	public function DeleteAccessory()
	{
		$id = $this->page->GetAccessoryId();
		
		Log::Debug('Deleting accessory with id %s', $id);

		$this->accessoryRepository->Delete($id);
	}
}
?>