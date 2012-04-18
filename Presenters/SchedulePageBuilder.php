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


interface ISchedulePageBuilder
{

	/**
	 * @param ISchedulePage $page
	 * @param array[int]ISchedule $schedules
	 * @param ISchedule $currentSchedule
	 */
	public function BindSchedules(ISchedulePage $page, $schedules, $currentSchedule);

	/**
	 * @param ISchedulePage $page
	 * @param array[int]ISchedule $schedules
	 * @return ISchedule
	 */
	public function GetCurrentSchedule(ISchedulePage $page, $schedules);

	/**
	 * Returns range of dates to bind in UTC
	 * @param UserSession $userSession
	 * @param ISchedule $schedule
	 * @param ISchedulePage $page
	 * @return DateRange
	 */
	public function GetScheduleDates(UserSession $userSession, ISchedule $schedule, ISchedulePage $page);

	/**
	 * @param ISchedulePage $page
	 * @param DateRange $dateRange display dates
	 * @param UserSession $user
	 */
	public function BindDisplayDates(ISchedulePage $page, DateRange $dateRange, UserSession $userSession,
									 ISchedule $schedule);

	/**
	 * @param ISchedulePage $page
	 * @param array[int]ResourceDto $resources
	 * @param IDailyLayout $dailyLayout
	 */
	public function BindReservations(ISchedulePage $page, $resources, IDailyLayout $dailyLayout);

	/**
	 * @param ISchedulePage $page
	 * @param IDailyLayout $layout
	 */
	public function BindLayout(ISchedulePage $page, IDailyLayout $layout, DateRange $dateRange);
}

class SchedulePageBuilder implements ISchedulePageBuilder
{
	/**
	 * @param ISchedulePage $page
	 * @param array[int]ISchedule $schedules
	 * @param ISchedule $currentSchedule
	 */
	public function BindSchedules(ISchedulePage $page, $schedules, $currentSchedule)
	{
		$page->SetSchedules($schedules);
		$page->SetScheduleId($currentSchedule->GetId());
		$page->SetScheduleName($currentSchedule->GetName());
		$page->SetFirstWeekday($currentSchedule->GetWeekdayStart());
	}

	/**
	 * @param ISchedulePage $page
	 * @param $schedules
	 * @return Schedule
	 */
	public function GetCurrentSchedule(ISchedulePage $page, $schedules)
	{
		if ($page->IsPostBack())
		{
			$schedule = $this->GetSchedule($schedules, $page->GetScheduleId());
		} else
		{
			$schedule = $this->GetDefaultSchedule($schedules);
		}

		return $schedule;
	}

	/**
	 * @see ISchedulePageBuilder::GetScheduleDates()
	 */
	public function GetScheduleDates(UserSession $user, ISchedule $schedule, ISchedulePage $page)
	{
		$userTimezone = $user->Timezone;
		$providedDate = $page->GetSelectedDate();
		$date = empty($providedDate) ? Date::Now() : new Date($providedDate, $userTimezone);
		$selectedDate = $date->ToTimezone($userTimezone)->GetDate();
		$selectedWeekday = $selectedDate->Weekday();

		$scheduleLength = $schedule->GetDaysVisible();

		if ($page->GetShowFullWeek())
		{
			$scheduleLength = 7;
		}

		$startDay = $schedule->GetWeekdayStart();

		/**
		 *  Examples
		 *
		 *  if we are on 3 and we need to start on 6, we need to go back 4 days
		 *  if we are on 3 and we need to start on 5, we need to go back 5 days
		 *  if we are on 3 and we need to start on 4, we need to go back 6 days
		 *  if we are on 3 and we need to start on 3, we need to go back 0 days
		 *  if we are on 3 and we need to start on 2, we need to go back 1 days
		 *  if we are on 3 and we need to start on 1, we need to go back 2 days
		 *  if we are on 3 and we need to start on 0, we need to go back 3 days
		 */

		$adjustedDays = ($startDay - $selectedWeekday);

		if ($selectedWeekday < $startDay)
		{
			$adjustedDays = $adjustedDays - 7;
		}

		$startDate = $selectedDate->AddDays($adjustedDays);
		$applicableDates = new DateRange($startDate, $startDate->AddDays($scheduleLength));

		return $applicableDates;
	}

	/**
	 * @see ISchedulePageBuilder::BindDisplayDates()
	 */
	public function BindDisplayDates(ISchedulePage $page,
									 DateRange $dateRange,
									 UserSession $userSession,
									 ISchedule $schedule)
	{
		$scheduleLength = $schedule->GetDaysVisible();
		if ($page->GetShowFullWeek())
		{
			$scheduleLength = 7;
		}

		// we don't want to display the last date in the range (it will be midnight of the last day)
		$adjustedDateRange = new DateRange($dateRange->GetBegin()->ToTimezone($userSession->Timezone), $dateRange->GetEnd()->ToTimezone($userSession->Timezone)->AddDays(-1));

		$page->SetDisplayDates($adjustedDateRange);

		$startDate = $adjustedDateRange->GetBegin();

		$adjustment = max($scheduleLength, 7);
		$prevAdjustment = 7 * floor($adjustment / 7); // ie, if 10, we only want to go back 7 days so there is overlap

		$page->SetPreviousNextDates($startDate->AddDays(-$prevAdjustment), $startDate->AddDays($adjustment));
		$page->ShowFullWeekToggle($scheduleLength < 7);
	}

	/**
	 * @see ISchedulePageBuilder::BindReservations()
	 */
	public function BindReservations(ISchedulePage $page, $resources, IDailyLayout $dailyLayout)
	{
		$page->SetResources($resources);
		$page->SetDailyLayout($dailyLayout);
	}

	/**
	 * @see ISchedulePageBuilder::BindLayout()
	 */
	public function BindLayout(ISchedulePage $page, IDailyLayout $layout, DateRange $dateRange)
	{
		$page->SetLayout($layout->GetLabels($dateRange->GetBegin()));
	}

	/**
	 * @param array[int]Schedule $schedules
	 * @return Schedule
	 */
	private function GetDefaultSchedule($schedules)
	{
		foreach ($schedules as $schedule)
		{
			if ($schedule->GetIsDefault())
			{
				return $schedule;
			}
		}
	}

	/**
	 * @param array[int]Schedule $schedules
	 * @param int $scheduleId
	 * @return Schedule
	 */
	private function GetSchedule($schedules, $scheduleId)
	{
		foreach ($schedules as $schedule)
		{
			/** @var $schedule Schedule */
			if ($schedule->GetId() == $scheduleId)
			{
				return $schedule;
			}
		}
	}

}

?>