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

require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/PreReservationFactory.php');

require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/IReservationValidationFactory.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/IReservationValidationResult.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/IReservationValidationRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/IUpdateReservationValidationRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/IReservationValidationService.php');

require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ReservationValidationRuleProcessor.php');

require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/AdminExcludedRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ResourceAvailabilityRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ExistingResourceAvailabilityRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ReservationDateTimeRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ReservationStartTimeRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/PermissionValidationRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ReservationRuleResult.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ReservationValidationFactory.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ReservationValidationResult.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ResourceMinimumNoticeRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ResourceMaximumNoticeRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ResourceMinimumDurationRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/ResourceMaximumDurationRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/QuotaRule.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/AccessoryAvailabilityRule.php');

require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/AddReservationValidationService.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/UpdateReservationValidationService.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/DeleteReservationValidationService.php');

require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/IBlackoutValidationResult.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/BlackoutValidationResult.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/BlackoutDateTimeValidationResult.php');
?>