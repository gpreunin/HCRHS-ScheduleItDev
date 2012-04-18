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

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Domain/BookableResource.php');
require_once(ROOT_DIR . 'Domain/Reservation.php');
require_once(ROOT_DIR . 'Domain/Values/ReservationAccessory.php');

class ReservationSeries
{
	/**
	 * @var int
	 */
	protected $seriesId;
	
	/**
	 * @return int
	 */
	public function SeriesId()
	{
		return $this->seriesId;
	}

	/**
	 * @param int $seriesId
	 */
	public function SetSeriesId($seriesId)
	{
		$this->seriesId = $seriesId;
	}
	
	/**
	 * @var int
	 */
	protected $_userId;

	/**
	 * @return int
	 */
	public function UserId()
	{
		return $this->_userId;
	}

	/**
	 * @var UserSession
	 */
	protected $_bookedBy;

	/**
	 * @return UserSession
	 */
	public function BookedBy()
	{
		return $this->_bookedBy;
	}

	/**
	 * @var BookableResource
	 */
	 protected $_resource;

	/**
	 * @return int
	 */
	public function ResourceId()
	{
		return $this->_resource->GetResourceId();
	}

	/**
	 * @return BookableResource
	 */
	public function Resource()
	{
		return $this->_resource;
	}
	
	/**
	 * @return int
	 */
	public function ScheduleId()
	{
		return $this->_resource->GetScheduleId();
	}

	/**
	 * @var string
	 */
	protected $_title;

	/**
	 * @return string
	 */
	public function Title()
	{
		return $this->_title;
	}

	/**
	 * @var string
	 */
	protected $_description;

	/**
	 * @return string
	 */
	public function Description()
	{
		return $this->_description;
	}

	/**
	 * @var IRepeatOptions
	 */
	protected $_repeatOptions;
	
	/**
	 * @return IRepeatOptions
	 */
	public function RepeatOptions()
	{
		return $this->_repeatOptions;
	}

	/**
	 * @var array|BookableResource[]
	 */
	protected $_additionalResources = array();
	
	/**
	 * @return array|BookableResource[]
	 */
	public function AdditionalResources()
	{
		return $this->_additionalResources;
	}
	
	/**
	 * @return int[]
	 */
	public function AllResourceIds()
	{
		$ids = array($this->ResourceId());
		foreach ($this->_additionalResources as $resource)
		{
			$ids[] = $resource->GetResourceId();
		}
		return $ids;
	}

	/**
	 * @return array|BookableResource[]
	 */
	public function AllResources()
	{
		return array_merge(array($this->Resource()), $this->AdditionalResources());
	}

	/**
	 * @var array|Reservation[]
	 */
	protected $instances = array();
	
	/**
	 * @return Reservation[]
	 */
	public function Instances()
	{
		return $this->instances;
	}

	/**
	 * @var array|ReservationAccessory[]
	 */
	protected $_accessories = array();

	/**
	 * @return array|ReservationAccessory[]
	 */
	public function Accessories()
	{
		return $this->_accessories;
	}
	
	/**
	 * @var Date
	 */
	private $currentInstanceKey;
	
	protected function __construct()
	{
		$this->_repeatOptions = new RepeatNone();
	}
	
	/**
	 * @param int $userId
	 * @param BookableResource $resource
	 * @param string $title
	 * @param string $description
	 * @param DateRange $reservationDate
	 * @param IRepeatOptions $repeatOptions
	 * @param UserSession $bookedBy
	 * @return ReservationSeries
	 */
	public static function Create(
								$userId, 
								BookableResource $resource,
								$title, 
								$description, 
								$reservationDate, 
								$repeatOptions,
								UserSession $bookedBy)
	{
		
		$series = new ReservationSeries();
		$series->_userId = $userId;
		$series->_resource = $resource;
		$series->_title = $title;
		$series->_description = $description;
		$series->_bookedBy = $bookedBy;
		$series->UpdateDuration($reservationDate);
		$series->Repeats($repeatOptions);
		
		return $series;
	}

	/**
	 * @param DateRange $reservationDate
	 */
	protected function UpdateDuration(DateRange $reservationDate)
	{
		$this->AddNewCurrentInstance($reservationDate);
	}
	
	/**
	 * @param IRepeatOptions $repeatOptions
	 */
	protected function Repeats(IRepeatOptions $repeatOptions)
	{
		$this->_repeatOptions = $repeatOptions;
		
		$dates = $repeatOptions->GetDates($this->CurrentInstance()->Duration());
		
		if (empty($dates))
		{
			return;
		}
		
		foreach ($dates as $date)
		{
			$this->AddNewInstance($date);
		}
	}

	/**
	 * @param DateRange $reservationDate
	 * @return bool
	 */
	protected function InstanceStartsOnDate(DateRange $reservationDate)
	{
		/** @var $instance Reservation */
		foreach ($this->instances as $instance)
		{
			if ($instance->StartDate()->DateEquals($reservationDate->GetBegin()))
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * @param DateRange $reservationDate
	 * @return Reservation newly created instance
	 */
	protected function AddNewInstance(DateRange $reservationDate)
	{
		$newInstance = new Reservation($this, $reservationDate);
		$this->AddInstance($newInstance);
		
		return $newInstance;
	}
	
	protected function AddNewCurrentInstance(DateRange $reservationDate)
	{
		$currentInstance = new Reservation($this, $reservationDate);
		$this->AddInstance($currentInstance);
		$this->SetCurrentInstance($currentInstance);
	}
	
	protected function AddInstance(Reservation $reservation)
	{
		$key = $this->CreateInstanceKey($reservation);
		$this->instances[$key] = $reservation;
	}
	
	protected function CreateInstanceKey(Reservation $reservation)
	{
		return $this->GetNewKey($reservation);
	}
	
	protected function GetNewKey(Reservation $reservation)
	{
		return $reservation->ReferenceNumber();
	}
	
	/**
	 * @param BookableResource $resource
	 */
	public function AddResource(BookableResource $resource)
	{
		$this->_additionalResources[] = $resource;
	}
	
	/**
	 * @return bool
	 */
	public function IsRecurring()
	{
		return $this->RepeatOptions()->RepeatType() != RepeatType::None;
	}

	/**
	 * @return int|ReservationStatus
	 */
	public function StatusId()
	{
		if ($this->_bookedBy->IsAdmin)
		{
			return ReservationStatus::Created;
		}

		foreach ($this->AllResources() as $resource)
		{
			if ($resource->GetRequiresApproval())
			{
				return ReservationStatus::Pending;
			}
		}

		return ReservationStatus::Created;
	}

	public function RequiresApproval()
	{
		return $this->StatusId() == ReservationStatus::Pending;
	}
	
	/**
	 * @param string $referenceNumber
	 * @return Reservation
	 */
	public function GetInstance($referenceNumber)
	{
		return $this->instances[$referenceNumber];
	}
	
	/**
	 * @return Reservation
	 */
	public function CurrentInstance()
	{ 
		$instance = $this->GetInstance($this->GetCurrentKey());
		if (!isset($instance))
		{
			throw new Exception("Current instance not found. Missing Reservation key {$this->GetCurrentKey()}");
		}
		return $instance;
	}

	/**
	 * @param int[] $participantIds
	 * @return void
	 */
	public function ChangeParticipants($participantIds)
	{
		/** @var Reservation $instance */
		foreach ($this->Instances() as $instance)
		{
			$instance->ChangeParticipants($participantIds);
		}
	}

	/**
	 * @param int[] $inviteeIds
	 * @return void
	 */
	public function ChangeInvitees($inviteeIds)
	{
		/** @var Reservation $instance */
		foreach ($this->Instances() as $instance)
		{
			$instance->ChangeInvitees($inviteeIds);
		}
	}

	/**
	 * @param Reservation $current
	 * @return void
	 */
	protected function SetCurrentInstance(Reservation $current)
	{
		$this->currentInstanceKey = $this->GetNewKey($current);
	}
	
	/**
	 * @return Date
	 */
	protected function GetCurrentKey()
	{
		return $this->currentInstanceKey;
	}

	/**
	 * @param Reservation $instance
	 * @return bool
	 */
	protected function IsCurrent(Reservation $instance)
	{
		return $instance->ReferenceNumber() == $this->CurrentInstance()->ReferenceNumber();
	}

	/**
	 * @param int $resourceId
	 * @return bool
	 */
	public function ContainsResource($resourceId)
	{
		return in_array($resourceId, $this->AllResourceIds());
	}

	/**
	 * @param ReservationAccessory $accessory
	 * @return void
	 */
	public function AddAccessory(ReservationAccessory $accessory)
	{
		$this->_accessories[] = $accessory;
	}

	public function IsMarkedForDelete($reservationId)
	{
		return false;
	}

	public function IsMarkedForUpdate($reservationId)
	{
		return false;
	}
}
?>