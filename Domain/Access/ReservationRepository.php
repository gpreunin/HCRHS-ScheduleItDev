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

require_once(ROOT_DIR . 'Domain/namespace.php');

class ReservationRepository implements IReservationRepository
{
    public function LoadById($reservationId)
    {
        Log::Debug("ReservationRepository::LoadById() - ReservationID: $reservationId");

        return $this->Load(new GetReservationByIdCommand($reservationId));
    }

    public function LoadByReferenceNumber($referenceNumber)
    {
        Log::Debug("ReservationRepository::LoadByReferenceNumber() - ReferenceNumber: $referenceNumber");

        return $this->Load(new GetReservationByReferenceNumberCommand($referenceNumber));
    }

    private function Load(SqlCommand $loadSeriesCommand)
    {
        $reader = ServiceLocator::GetDatabase()->Query($loadSeriesCommand);

        if ($reader->NumRows() != 1)
        {
            Log::Debug('Reservation not found. ID');
            return null;
        }

        $series = $this->BuildSeries($reader);
        $this->PopulateInstances($series);
        $this->PopulateResources($series);
        $this->PopulateParticipants($series);
        $this->PopulateAccessories($series);

        return $series;
    }

    public function Add(ReservationSeries $reservationSeries)
    {
        $database = ServiceLocator::GetDatabase();

        $seriesId = $this->InsertSeries($reservationSeries);

        $reservationSeries->SetSeriesId($seriesId);

        $instances = $reservationSeries->Instances();

        foreach ($instances as $reservation)
        {
            $command = new InstanceAddedEventCommand($reservation, $reservationSeries);
            $command->Execute($database);
        }
    }

    public function Update(ExistingReservationSeries $reservationSeries)
    {
        $database = ServiceLocator::GetDatabase();

        if ($reservationSeries->RequiresNewSeries())
        {
            $currentId = $reservationSeries->SeriesId();
            $newSeriesId = $this->InsertSeries($reservationSeries);
            Log::Debug('Series branched from seriesId: %s to seriesId: %s', $currentId, $newSeriesId);

            $reservationSeries->SetSeriesId($newSeriesId);

            /** @var $instance Reservation */
            foreach ($reservationSeries->Instances() as $instance)
            {
                $updateReservationCommand = new UpdateReservationCommand($instance->ReferenceNumber(), $newSeriesId, $instance->StartDate(), $instance->EndDate());

                $database->Execute($updateReservationCommand);
            }
        } else
        {
            Log::Debug('Updating existing series (seriesId: %s)', $reservationSeries->SeriesId());

            $updateSeries = new UpdateReservationSeriesCommand($reservationSeries->SeriesId(), $reservationSeries->Title(), $reservationSeries->Description(), $reservationSeries->RepeatOptions()->RepeatType(), $reservationSeries->RepeatOptions()->ConfigurationString(), Date::Now(), $reservationSeries->StatusId(), $reservationSeries->UserId());

            $database->Execute($updateSeries);
        }

        $this->ExecuteEvents($reservationSeries);
    }


    /**
     * @param ReservationSeries $reservationSeries
     * @return int newly created series_id
     */
    private function InsertSeries(ReservationSeries $reservationSeries)
    {
        $database = ServiceLocator::GetDatabase();

        $insertReservationSeries = new AddReservationSeriesCommand(Date::Now(), $reservationSeries->Title(), $reservationSeries->Description(), $reservationSeries->RepeatOptions()->RepeatType(), $reservationSeries->RepeatOptions()->ConfigurationString(), ReservationTypes::Reservation, $reservationSeries->StatusId(), $reservationSeries->UserId());

        $reservationSeriesId = $database->ExecuteInsert($insertReservationSeries);

        $insertReservationResource = new AddReservationResourceCommand($reservationSeriesId, $reservationSeries->ResourceId(), ResourceLevel::Primary);

        $database->Execute($insertReservationResource);

        foreach ($reservationSeries->AdditionalResources() as $resource)
        {
            $insertReservationResource = new AddReservationResourceCommand($reservationSeriesId, $resource->GetResourceId(), ResourceLevel::Additional);

            $database->Execute($insertReservationResource);
        }

        foreach ($reservationSeries->Accessories() as $accessory)
        {
            $insertAccessory = new AddReservationAccessoryCommand($accessory->AccessoryId, $accessory->QuantityReserved, $reservationSeriesId);
            $database->Execute($insertAccessory);
        }

        return $reservationSeriesId;
    }


    public function Delete(ExistingReservationSeries $existingReservationSeries)
    {
        $this->ExecuteEvents($existingReservationSeries);
    }

    private function ExecuteEvents(ExistingReservationSeries $existingReservationSeries)
    {
        $database = ServiceLocator::GetDatabase();
        $events = $existingReservationSeries->GetEvents();

        foreach ($events as $event)
        {
            $command = $this->GetReservationCommand($event, $existingReservationSeries);

            if ($command != null)
            {
                $command->Execute($database);
            }
        }
    }

    /**
     * @return EventCommand
     */
    private function GetReservationCommand($event, $series)
    {
        return ReservationEventMapper::Instance()->Map($event, $series);
    }

    /// LOAD BY ID HELPER FUNCTIONS

    /**
     * @param IReader $reader
     * @return ExistingReservationSeries
     */
    private function BuildSeries($reader)
    {
        $series = new ExistingReservationSeries();
        if ($row = $reader->GetRow())
        {
            $repeatType = $row[ColumnNames::REPEAT_TYPE];
            $configurationString = $row[ColumnNames::REPEAT_OPTIONS];

            $repeatOptions = $this->BuildRepeatOptions($repeatType, $configurationString);
            $series->WithRepeatOptions($repeatOptions);

            $seriesId = $row[ColumnNames::SERIES_ID];
            $title = $row[ColumnNames::RESERVATION_TITLE];
            $description = $row[ColumnNames::RESERVATION_DESCRIPTION];

            $series->WithId($seriesId);
            $series->WithTitle($title);
            $series->WithDescription($description);
            $series->WithOwner($row[ColumnNames::RESERVATION_OWNER]);
            $series->WithStatus($row[ColumnNames::RESERVATION_STATUS]);

            $startDate = Date::FromDatabase($row[ColumnNames::RESERVATION_START]);
            $endDate = Date::FromDatabase($row[ColumnNames::RESERVATION_END]);
            $duration = new DateRange($startDate, $endDate);

            $instance = new Reservation($series, $duration, $row[ColumnNames::RESERVATION_INSTANCE_ID], $row[ColumnNames::REFERENCE_NUMBER]);

            $series->WithCurrentInstance($instance);
        }
        $reader->Free();

        return $series;
    }

    private function PopulateInstances(ExistingReservationSeries $series)
    {
        // get all series instances
        $getInstancesCommand = new GetReservationSeriesInstances($series->SeriesId());
        $reader = ServiceLocator::GetDatabase()->Query($getInstancesCommand);
        while ($row = $reader->GetRow())
        {
            $start = Date::FromDatabase($row[ColumnNames::RESERVATION_START]);
            $end = Date::FromDatabase($row[ColumnNames::RESERVATION_END]);

            $reservation = new Reservation($series, new DateRange($start, $end), $row[ColumnNames::RESERVATION_INSTANCE_ID], $row[ColumnNames::REFERENCE_NUMBER]);

            $series->WithInstance($reservation);
        }
        $reader->Free();
    }

    private function PopulateResources(ExistingReservationSeries $series)
    {
        // get all reservation resources
        $getResourcesCommand = new GetReservationResourcesCommand($series->SeriesId());
        $reader = ServiceLocator::GetDatabase()->Query($getResourcesCommand);
        while ($row = $reader->GetRow())
        {
            $resource = new BookableResource($row[ColumnNames::RESOURCE_ID], $row[ColumnNames::RESOURCE_NAME], $row[ColumnNames::RESOURCE_LOCATION], $row[ColumnNames::RESOURCE_CONTACT], $row[ColumnNames::RESOURCE_NOTES], $row[ColumnNames::RESOURCE_MINDURATION], $row[ColumnNames::RESOURCE_MAXDURATION], $row[ColumnNames::RESOURCE_AUTOASSIGN], $row[ColumnNames::RESOURCE_REQUIRES_APPROVAL], $row[ColumnNames::RESOURCE_ALLOW_MULTIDAY], $row[ColumnNames::RESOURCE_MAX_PARTICIPANTS], $row[ColumnNames::RESOURCE_MINNOTICE], $row[ColumnNames::RESOURCE_MAXNOTICE], $row[ColumnNames::RESOURCE_DESCRIPTION], $row[ColumnNames::SCHEDULE_ID]);

            if ($row[ColumnNames::RESOURCE_LEVEL_ID] == ResourceLevel::Primary)
            {
                $series->WithPrimaryResource($resource);
            } else
            {
                $series->WithResource($resource);
            }
        }
        $reader->Free();
    }

    private function PopulateParticipants(ExistingReservationSeries $series)
    {
        $getSeriesParticipants = new GetReservationSeriesParticipantsCommand($series->SeriesId());

        $reader = ServiceLocator::GetDatabase()->Query($getSeriesParticipants);
        while ($row = $reader->GetRow())
        {
            if ($row[ColumnNames::RESERVATION_USER_LEVEL] == ReservationUserLevel::PARTICIPANT)
            {
                $series->GetInstance($row[ColumnNames::REFERENCE_NUMBER])->WithParticipant($row[ColumnNames::USER_ID]);
            }
            if ($row[ColumnNames::RESERVATION_USER_LEVEL] == ReservationUserLevel::INVITEE)
            {
                $series->GetInstance($row[ColumnNames::REFERENCE_NUMBER])->WithInvitee($row[ColumnNames::USER_ID]);
            }
        }
        $reader->Free();
    }

    private function PopulateAccessories(ExistingReservationSeries $series)
    {
        $getResourcesCommand = new GetReservationAccessoriesCommand($series->SeriesId());
        $reader = ServiceLocator::GetDatabase()->Query($getResourcesCommand);
        while ($row = $reader->GetRow())
        {
            $series->WithAccessory(new ReservationAccessory($row[ColumnNames::ACCESSORY_ID], $row[ColumnNames::QUANTITY]));
        }
        $reader->Free();
    }

    private function BuildRepeatOptions($repeatType, $configurationString)
    {
        $configuration = RepeatConfiguration::Create($repeatType, $configurationString);
        $factory = new RepeatOptionsFactory();
        return $factory->Create($repeatType, $configuration->Interval, $configuration->TerminationDate,
                                $configuration->Weekdays, $configuration->MonthlyType);
    }

    // LOAD BY ID HELPER FUNCTIONS END
}

class ReservationEventMapper
{
    private $buildMethods = array();
    private static $instance;

    private function __construct()
    {
        $this->buildMethods['SeriesDeletedEvent'] = 'BuildDeleteSeriesCommand';
        $this->buildMethods['OwnerChangedEvent'] = 'OwnerChangedCommand';

        $this->buildMethods['InstanceAddedEvent'] = 'BuildAddReservationCommand';
        $this->buildMethods['InstanceRemovedEvent'] = 'BuildRemoveReservationCommand';
        $this->buildMethods['InstanceUpdatedEvent'] = 'BuildUpdateReservationCommand';

        $this->buildMethods['ResourceRemovedEvent'] = 'BuildRemoveResourceCommand';
        $this->buildMethods['ResourceAddedEvent'] = 'BuildAddResourceCommand';

        $this->buildMethods['AccessoryAddedEvent'] = 'BuildAddAccessoryCommand';
        $this->buildMethods['AccessoryRemovedEvent'] = 'BuildRemoveAccessoryCommand';
    }

    /**
     * @static
     * @return ReservationEventMapper
     */
    public static function Instance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new ReservationEventMapper();
        }

        return self::$instance;
    }

    /**
     * @param $event mixed
     * @param $series ExistingReservationSeries
     * @return EventCommand
     */
    public function Map($event, ExistingReservationSeries $series)
    {
        $eventType = get_class($event);
        if (!isset($this->buildMethods[$eventType]))
        {
            Log::Debug("No command event mapper found for event $eventType");
            return null;
        }

        $method = $this->buildMethods[$eventType];
        return $this->$method($event, $series);
    }

    private function BuildDeleteSeriesCommand(SeriesDeletedEvent $event)
    {
        return new DeleteSeriesEventCommand($event->Series());
    }

    private function BuildAddReservationCommand(InstanceAddedEvent $event, ExistingReservationSeries $series)
    {
        return new InstanceAddedEventCommand($event->Instance(), $series);
    }

    private function BuildRemoveReservationCommand(InstanceRemovedEvent $event, ExistingReservationSeries $series)
    {
        return new InstanceRemovedEventCommand($event->Instance(), $series);
    }

    private function BuildUpdateReservationCommand(InstanceUpdatedEvent $event, ExistingReservationSeries $series)
    {
        return new InstanceUpdatedEventCommand($event->Instance(), $series);
    }

    private function BuildRemoveResourceCommand(ResourceRemovedEvent $event, ExistingReservationSeries $series)
    {
        return new EventCommand(new RemoveReservationResourceCommand($series->SeriesId(), $event->ResourceId()), $series);
    }

    private function BuildAddResourceCommand(ResourceAddedEvent $event, ExistingReservationSeries $series)
    {
        return new EventCommand(new AddReservationResourceCommand($series->SeriesId(), $event->ResourceId(), $event->ResourceLevel()), $series);
    }

    private function BuildAddAccessoryCommand(AccessoryAddedEvent $event, ExistingReservationSeries $series)
    {
        return new EventCommand(new AddReservationAccessoryCommand($event->AccessoryId(), $event->Quantity(), $series->SeriesId()), $series);
    }

    private function BuildRemoveAccessoryCommand(AccessoryRemovedEvent $event, ExistingReservationSeries $series)
    {
        return new EventCommand(new RemoveReservationAccessoryCommand($series->SeriesId(), $event->AccessoryId()), $series);
    }

    private function OwnerChangedCommand(OwnerChangedEvent $event, ExistingReservationSeries $series)
    {
        return new OwnerChangedEventCommand($event);
    }
}

class EventCommand
{
    /**
     * @var ISqlCommand
     */
    private $command;

    /**
     * @var ExistingReservationSeries
     */
    private $series;

    public function __construct(ISqlCommand $command, ExistingReservationSeries $series)
    {
        $this->command = $command;
        $this->series = $series;
    }

    public function Execute(Database $database)
    {
        if (!$this->series->RequiresNewSeries())
        {
            $database->Execute($this->command);
        }
    }
}

class DeleteSeriesEventCommand extends EventCommand
{
    public function __construct(ExistingReservationSeries $series)
    {
        $this->series = $series;
    }

    public function Execute(Database $database)
    {
        $database->Execute(new DeleteSeriesCommand($this->series->SeriesId()));
    }
}

class InstanceRemovedEventCommand extends EventCommand
{
    /**
     * @var Reservation
     */
    private $instance;

    /**
     * @var ReservationSeries
     */
    private $series;

    public function __construct(Reservation $instance, ReservationSeries $series)
    {
        $this->instance = $instance;
        $this->series = $series;
    }

    public function Execute(Database $database)
    {
        $database->Execute(new RemoveReservationCommand($this->instance->ReferenceNumber()));
    }
}

class InstanceAddedEventCommand extends EventCommand
{
    /**
     * @var Reservation
     */
    private $instance;

    /**
     * @var ReservationSeries
     */
    private $series;

    public function __construct(Reservation $instance, ReservationSeries $series)
    {
        $this->instance = $instance;
        $this->series = $series;
    }

    public function Execute(Database $database)
    {
        $insertReservation = new AddReservationCommand($this->instance->StartDate(), $this->instance->EndDate(), $this->instance->ReferenceNumber(), $this->series->SeriesId());

        $reservationId = $database->ExecuteInsert($insertReservation);

        $insertReservationUser = new AddReservationUserCommand($reservationId, $this->series->UserId(), ReservationUserLevel::OWNER);

        $database->Execute($insertReservationUser);

        foreach ($this->instance->AddedParticipants() as $participantId)
        {
            $insertReservationUser = new AddReservationUserCommand($reservationId, $participantId, ReservationUserLevel::PARTICIPANT);

            $database->Execute($insertReservationUser);
        }

        foreach ($this->instance->AddedInvitees() as $inviteeId)
        {
            $insertReservationUser = new AddReservationUserCommand($reservationId, $inviteeId, ReservationUserLevel::INVITEE);

            $database->Execute($insertReservationUser);
        }
    }
}

class InstanceUpdatedEventCommand extends EventCommand
{
    /**
     * @var Reservation
     */
    private $instance;

    /**
     * @var ExistingReservationSeries
     */
    private $series;

    public function __construct(Reservation $instance, ExistingReservationSeries $series)
    {
        $this->instance = $instance;
        $this->series = $series;
    }

    public function Execute(Database $database)
    {
        $instanceId = $this->instance->ReservationId();
        $updateReservationCommand = new UpdateReservationCommand($this->instance->ReferenceNumber(), $this->series->SeriesId(), $this->instance->StartDate(), $this->instance->EndDate());

        $database->Execute($updateReservationCommand);

        foreach ($this->instance->RemovedParticipants() as $participantId)
        {
            $removeReservationUser = new RemoveReservationUserCommand($instanceId, $participantId);

            $database->Execute($removeReservationUser);
        }

        foreach ($this->instance->RemovedInvitees() as $inviteeId)
        {
            $insertReservationUser = new RemoveReservationUserCommand($instanceId, $inviteeId);

            $database->Execute($insertReservationUser);
        }

        foreach ($this->instance->AddedParticipants() as $participantId)
        {
            $insertReservationUser = new AddReservationUserCommand($instanceId, $participantId, ReservationUserLevel::PARTICIPANT);

            $database->Execute($insertReservationUser);
        }

        foreach ($this->instance->AddedInvitees() as $inviteeId)
        {
            $insertReservationUser = new AddReservationUserCommand($instanceId, $inviteeId, ReservationUserLevel::INVITEE);

            $database->Execute($insertReservationUser);
        }
    }
}

class OwnerChangedEventCommand extends EventCommand
{
    /**
     * @var OwnerChangedEvent
     */
    private $event;

    public function __construct(OwnerChangedEvent $event)
    {
        $this->event = $event;
    }

    public function Execute(Database $database)
    {
        $oldOwnerId = $this->event->OldOwnerId();
        $newOwnerId = $this->event->NewOwnerId();

        $instances = $this->event->Series()->_Instances();

        /** @var Reservation $instance */
        foreach ($instances as $instance)
        {
            if (!$instance->IsNew())
            {
                $id = $instance->ReservationId();
                $database->Execute(new RemoveReservationUserCommand($id, $oldOwnerId));
                $database->Execute(new RemoveReservationUserCommand($id, $newOwnerId));
                $database->Execute(new AddReservationUserCommand($id, $newOwnerId, ReservationUserLevel::OWNER));
            }
        }
    }
}

interface IReservationRepository
{
    /**
     * Insert a new reservation
     *
     * @param ReservationSeries $reservation
     * @return void
     */
    public function Add(ReservationSeries $reservation);

    /**
     * Return an existing reservation series
     *
     * @param int $reservationInstanceId
     * @return ExistingReservationSeries or null if no reservation found
     */
    public function LoadById($reservationInstanceId);

    /**
     * Return an existing reservation series
     *
     * @param string $referenceNumber
     * @return ExistingReservationSeries or null if no reservation found
     */
    public function LoadByReferenceNumber($referenceNumber);

    /**
     * Update an existing reservation
     *
     * @param ExistingReservationSeries $existingReservationSeries
     * @return void
     */
    public function Update(ExistingReservationSeries $existingReservationSeries);

    /**
     * Delete all or part of an existing reservation
     *
     * @param ExistingReservationSeries $existingReservationSeries
     * @return void
     */
    public function Delete(ExistingReservationSeries $existingReservationSeries);
}

?>