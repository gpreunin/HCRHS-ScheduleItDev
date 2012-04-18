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

interface ISchedule
{
	public function GetId();
	public function GetName();
	public function GetIsDefault();
	public function GetWeekdayStart();
	public function GetDaysVisible();
	public function GetTimezone();
	public function GetLayoutId();
    public function GetIsCalendarSubscriptionAllowed();
    public function GetPublicId();
}

class Schedule implements ISchedule
{	
	private $_id;
	private $_name;
	private $_isDefault;
	private $_weekdayStart;
	private $_daysVisible;
	private $_timezone;
	private $_layoutId;
    private $_isCalendarSubscriptionAllowed = false;
    private $_publicId;

	public function __construct(
		$id, 
		$name, 
		$isDefault, 
		$weekdayStart, 
		$daysVisible,
		$timezone = null,
		$layoutId = null)
	{
		$this->_id = $id;
		$this->_name = $name;
		$this->_isDefault = $isDefault;
		$this->_weekdayStart = $weekdayStart;
		$this->_daysVisible = $daysVisible;
		$this->_timezone = $timezone;
		$this->_layoutId = $layoutId;
	}
	
	public function GetId()
	{
		return $this->_id;
	}
	
	public function SetId($value)
	{
		$this->_id = $value;
	}
	
	public function GetName()
	{
		return $this->_name;
	}
	
	public function SetName($value)
	{
		$this->_name = $value;
	}
	
	public function GetIsDefault()
	{
		return $this->_isDefault;
	}
	
	public function SetIsDefault($value)
	{
		$this->_isDefault = $value;
	}
	
	public function GetWeekdayStart()
	{
		return $this->_weekdayStart;
	}
	
	public function SetWeekdayStart($value)
	{
		$this->_weekdayStart = $value;
	}
	
	public function GetDaysVisible()
	{
		return $this->_daysVisible;
	}
	
	public function SetDaysVisible($value)
	{
		$this->_daysVisible = $value;
	}
		
	public function GetTimezone()
	{
		return $this->_timezone;
	}
	
	public function GetLayoutId()
	{
		return $this->_layoutId;
	}

	public function SetTimezone($timezone)
	{
		$this->_timezone = $timezone;
	}

    protected function SetIsCalendarSubscriptionAllowed($isAllowed)
    {
        $this->_isCalendarSubscriptionAllowed = $isAllowed;
    }

    public function GetIsCalendarSubscriptionAllowed()
    {
        return (bool)$this->_isCalendarSubscriptionAllowed;
    }

    protected function SetPublicId($publicId)
    {
        $this->_publicId = $publicId;
    }

    public function GetPublicId()
    {
        return $this->_publicId;
    }

    public function EnableSubscription()
    {
        $this->SetIsCalendarSubscriptionAllowed(true);
        if (empty($this->_publicId))
        {
            $this->SetPublicId(uniqid());
        }
    }

    public function DisableSubscription()
    {
        $this->SetIsCalendarSubscriptionAllowed(false);
    }

    /**
     * @static
     * @return Schedule
     */
    public static function Null()
    {
        return new Schedule(null, null, false, null, null);
    }

    /**
     * @static
     * @param array $row
     * @return Schedule
     */
    public static function FromRow($row)
    {
        $schedule = new Schedule($row[ColumnNames::SCHEDULE_ID],
                        $row[ColumnNames::SCHEDULE_NAME],
                        $row[ColumnNames::SCHEDULE_DEFAULT],
                        $row[ColumnNames::SCHEDULE_WEEKDAY_START],
                        $row[ColumnNames::SCHEDULE_DAYS_VISIBLE],
                        $row[ColumnNames::TIMEZONE_NAME],
                        $row[ColumnNames::LAYOUT_ID]);

        $schedule->WithSubscription($row[ColumnNames::ALLOW_CALENDAR_SUBSCRIPTION]);
        $schedule->WithPublicId($row[ColumnNames::PUBLIC_ID]);

        return $schedule;
    }

    /**
     * @param bool $allowSubscription
     * @internal
     */
    public function WithSubscription($allowSubscription)
    {
        $this->SetIsCalendarSubscriptionAllowed($allowSubscription);
    }

    /**
     * @param string $publicId
     * @internal
     */
    public function WithPublicId($publicId)
    {
        $this->SetPublicId($publicId);
    }
}
?>