﻿<?php
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

require_once('Language.php');
require_once('en_us.php');

class ja_jp extends en_us
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    protected function _LoadDates()
    {
        $dates = parent::_LoadDates();

        $dates['general_date'] = 'Y/m/d';
        $dates['general_datetime'] = 'Y/m/d H:i:s';
        $dates['schedule_daily'] = 'l, Y/m/d';
        $dates['reservation_email'] = 'Y/m/d @ g:i A (e)';
        $dates['res_popup'] = 'Y/m/d g:i A';
        $dates['dashboard'] = 'l, Y/m/d g:i A';
        $dates['period_time'] = "g:i A";
        $dates['general_date_js'] = "yy/mm/dd";
        $dates['calendar_time'] = 'h:mmt';
        $dates['calendar_dates'] = 'M/d';

        $this->Dates = $dates;

        return $this->Dates;
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = '名';
        $strings['LastName'] = '姓';
        $strings['Timezone'] = 'タイムゾーン';
        $strings['Edit'] = '編集';
        $strings['Change'] = '変更';
        $strings['Rename'] = '名称変更';
        $strings['Remove'] = '削除';
        $strings['Delete'] = '削除';
        $strings['Update'] = '更新';
        $strings['Cancel'] = 'キャンセル';
        $strings['Add'] = '追加';
        $strings['Name'] = '名前';
        $strings['Yes'] = 'はい';
        $strings['No'] = 'いいえ';
        $strings['FirstNameRequired'] = '名(first name)が必要です。';
        $strings['LastNameRequired'] = '姓(last name)が必要です。';
        $strings['PwMustMatch'] = '新しいパスワード欄とパスワード確認欄は同じものを入力してください。';
        $strings['PwComplexity'] = 'パスワードは文字(アルファベット)、数字、記号の組み合わせで6文字以上にしてください。';
        $strings['ValidEmailRequired'] = '有効なメールアドレスが必要です。';
        $strings['UniqueEmailRequired'] = 'そのメールアドレスはすでに登録されています。';
        $strings['UniqueUsernameRequired'] = 'そのユーザー名はすでに登録されています。';
        $strings['UserNameRequired'] = 'ユーザー名が必要です。';
        $strings['CaptchaMustMatch'] = 'セキュリティ画像の文字を正確に入力してください。';
        $strings['Today'] = '今日へ移動';
        $strings['Week'] = '週を表示';
        $strings['Month'] = '月を表示';
        $strings['BackToCalendar'] = 'カレンダーに戻る';
        $strings['BeginDate'] = '開始';
        $strings['EndDate'] = '終了';
        $strings['Username'] = 'ユーザー名';
        $strings['Password'] = 'パスワード';
        $strings['PasswordConfirmation'] = 'パスワード確認';
        $strings['DefaultPage'] = 'デフォルトページ';
        $strings['MyCalendar'] = 'マイ カレンダー';
        $strings['ScheduleCalendar'] = 'Schedule Calendar';
        $strings['Registration'] = '登録(Registration)';
        $strings['NoAnnouncements'] = 'お知らせはありません';
        $strings['Announcements'] = 'お知らせ';
        $strings['NoUpcomingReservations'] = '近日中の予約はありません';
        $strings['UpcomingReservations'] = '近日中の予約';
        $strings['ShowHide'] = '表示/非表示';
        $strings['Error'] = 'エラー';
        $strings['ReturnToPreviousPage'] = '直近のページへ戻る';
        $strings['UnknownError'] = '不明なエラー';
        $strings['InsufficientPermissionsError'] = 'このリソースを操作する権限がありません';
        $strings['MissingReservationResourceError'] = 'リソースが選択されていません';
        $strings['MissingReservationScheduleError'] = 'スケジュールが選択されていません';
        $strings['DoesNotRepeat'] = '繰り返さない';
        $strings['Daily'] = '日を単位に';
        $strings['Weekly'] = '週を単位に';
        $strings['Monthly'] = '月を単位に';
        $strings['Yearly'] = '年を単位に';
        $strings['RepeatPrompt'] = '繰り返し';
        $strings['hours'] = '時間';
        $strings['days'] = '日';
        $strings['weeks'] = '週';
        $strings['months'] = 'か月';
        $strings['years'] = '年';
        $strings['day'] = '日';
        $strings['week'] = '週';
        $strings['month'] = 'か月';
        $strings['year'] = '年';
        $strings['repeatDayOfMonth'] = '同じ日付';
        $strings['repeatDayOfWeek'] = '同じ曜日';
        $strings['RepeatUntilPrompt'] = 'この日まで';
        $strings['RepeatEveryPrompt'] = '毎';
        $strings['RepeatDaysPrompt'] = 'この曜日に';
        $strings['CreateReservationHeading'] = '予約の作成';
        $strings['EditReservationHeading'] = '予約の変更 %s';
        $strings['ViewReservationHeading'] = '予約の表示 %s';
        $strings['ReservationErrors'] = '予約の変更';
        $strings['Create'] = '作成';
        $strings['ThisInstance'] = 'この回だけ';
        $strings['AllInstances'] = 'すべての回';
        $strings['FutureInstances'] = 'この回から先';
        $strings['Print'] = '印刷';
        $strings['ShowHideNavigation'] = '表示切り替えナビ';
        $strings['ReferenceNumber'] = '照会番号';
        $strings['Tomorrow'] = '明日';
        $strings['LaterThisWeek'] = '今週(明後日以後)';
        $strings['NextWeek'] = '翌週';
        $strings['SignOut'] = 'サインアウト';
        $strings['LayoutDescription'] = '一度に %s から、 %s 日間を表示';
        $strings['AllResources'] = '全てのリソース';
        $strings['TakeOffline'] = 'オフラインにする';
        $strings['BringOnline'] = 'オンラインにする';
        $strings['AddImage'] = '画像を添付';
        $strings['NoImage'] = '画像なし';
        $strings['Move'] = '移動する';
        $strings['AppearsOn'] = '表示するのは %s';
        $strings['Location'] = '場所';
        $strings['NoLocationLabel'] = '(未設定)';
        $strings['Contact'] = '連絡先';
        $strings['NoContactLabel'] = '(未設定)';
        $strings['Description'] = '説明';
        $strings['NoDescriptionLabel'] = '(未設定)';
        $strings['Notes'] = '備考';
        $strings['NoNotesLabel'] = '(未設定)';
        $strings['NoTitleLabel'] = '(未設定)';
        $strings['UsageConfiguration'] = '運用規則';
        $strings['ChangeConfiguration'] = '設定変更';
        $strings['ResourceMinLength'] = '予約は最小でも %s 以上にしてください';
        $strings['ResourceMinLengthNone'] = '予約の最小時間は設定されていません';
        $strings['ResourceMaxLength'] = '%s を超える予約はできません';
        $strings['ResourceMaxLengthNone'] = '予約の最大時間は設定されていません';
        $strings['ResourceRequiresApproval'] = '予約には承認が必要です';
        $strings['ResourceRequiresApprovalNone'] = '予約に承認は必要ありません';
        $strings['ResourcePermissionAutoGranted'] = '新規ユーザーは自動的に利用可能になります';
        $strings['ResourcePermissionNotAutoGranted'] = '新規ユーザーには個別に利用許可が必要です';
        $strings['ResourceMinNotice'] = '開始時刻よりも %s 以前の予約が必要です';
        $strings['ResourceMinNoticeNone'] = '現在時刻まで予約ができます';
        $strings['ResourceMaxNotice'] = '終了時刻が現在時刻より %s 先の予約はできません';
        $strings['ResourceMaxNoticeNone'] = '予約終了時刻は(未来の)いつでもかまいません';
        $strings['ResourceAllowMultiDay'] = '日をまたいで予約できます';
        $strings['ResourceNotAllowMultiDay'] = '日をまたいでの予約はできません';
        $strings['ResourceCapacity'] = 'このリソースは %s 人まで使えます';
        $strings['ResourceCapacityNone'] = 'このリソースに人数の上限はありません';
        $strings['AddNewResource'] = '新規リソースの追加';
        $strings['AddNewUser'] = '新規ユーザーの追加';
        $strings['AddUser'] = 'ユーザー追加';
        $strings['Schedule'] = 'スケジュール';
        $strings['AddResource'] = 'リソース追加';
        $strings['Capacity'] = '人数制限';
        $strings['Access'] = 'アクセス';
        $strings['Duration'] = '期間';
        $strings['Active'] = 'アクティブ';
        $strings['Inactive'] = 'インアクティブ';
        $strings['ResetPassword'] = 'パスワードをリセット';
        $strings['LastLogin'] = '直近のログイン';
        $strings['Search'] = '検索';
        $strings['ResourcePermissions'] = 'リソース利用権限';
        $strings['Reservations'] = '予約';
        $strings['Groups'] = 'グループ';
        $strings['ResetPassword'] = 'パスワードをリセット';
        $strings['AllUsers'] = '全ユーザー';
        $strings['AllGroups'] = '全グループ';
        $strings['AllSchedules'] = '全スケジュール';
        $strings['UsernameOrEmail'] = 'ユーザー名またはメールアドレス';
        $strings['Members'] = 'メンバー';
        $strings['QuickSlotCreation'] = '予約枠を %s 分毎に %s から %s まで生成する';
        $strings['ApplyUpdatesTo'] = '更新を適用するのは';
        $strings['CancelParticipation'] = '出席を取り消す';
        $strings['Attending'] = '出席しますか';
        $strings['QuotaConfiguration'] = '%s の %s において %s のユーザーは %s %s を１ %s 内の上限とする。';
        $strings['reservations'] = '予約';
        $strings['ChangeCalendar'] = 'カレンダーを変更';
        $strings['AddQuota'] = '量制限を追加';
        $strings['FindUser'] = 'ユーザーを見つける';
        $strings['Created'] = '作成日時';
        $strings['LastModified'] = '最終更新日時';
        $strings['GroupName'] = 'グループ名';
        $strings['GroupMembers'] = 'グループメンバー';
        $strings['GroupRoles'] = 'グループロール(役割)';
        $strings['GroupAdmin'] = 'グループ管理者';
        $strings['Actions'] = '操作';
        $strings['CurrentPassword'] = '現在のパスワード';
        $strings['NewPassword'] = '新しいパスワード';
        $strings['InvalidPassword'] = 'パスワードが間違っています';
        $strings['PasswordChangedSuccessfully'] = 'パスワードは変更されました';
        $strings['SignedInAs'] = 'サインイン中 ';
        $strings['NotSignedIn'] = 'サインインしていません';
        $strings['ReservationTitle'] = '件名';
        $strings['ReservationDescription'] = '説明';
        $strings['ResourceList'] = 'リソース一覧';
        $strings['Accessories'] = '備品';
        $strings['Add'] = '追加';
        $strings['ParticipantList'] = '出席者一覧';
        $strings['InvitationList'] = '招待者一覧';
        $strings['AccessoryName'] = '備品名称';
        $strings['QuantityAvailable'] = '数量';
        $strings['Resources'] = 'リソース';
        $strings['Participants'] = '出席者';
        $strings['User'] = 'ユーザー';
        $strings['Resource'] = 'リソース';
        $strings['Status'] = '状態';
        $strings['Approve'] = '承認';
        $strings['Page'] = 'ページ';
        $strings['Rows'] = '列';
        $strings['Unlimited'] = '制限なし';
        $strings['Email'] = 'メール';
        $strings['EmailAddress'] = 'メールアドレス';
        $strings['Phone'] = '電話';
        $strings['Organization'] = '所属';
        $strings['Position'] = '役職';
        $strings['Language'] = '言語';
        $strings['Permissions'] = '権限';
        $strings['Reset'] = 'リセット';
        $strings['FindGroup'] = 'グループを探す';
        $strings['Manage'] = '管理';
        $strings['None'] = 'なし';
        $strings['AddToOutlook'] = 'Outlookに追加';
        $strings['Done'] = '決定';
        $strings['RememberMe'] = '記憶しておいてください';
        $strings['FirstTimeUser?'] = '初めてですか?';
        $strings['CreateAnAccount'] = 'アカウントを作成する';
        $strings['ViewSchedule'] = 'スケジュールを見る';
        $strings['ForgotMyPassword'] = 'パスワードを忘れました';
        $strings['YouWillBeEmailedANewPassword'] = 'ランダムに生成されたパスワードがメールで送られます';
        $strings['Close'] = '閉じる';
        $strings['ExportToCSV'] = 'CSVで出力する';
        $strings['OK'] = 'OK';
        $strings['Working'] = '作業中';
        $strings['Login'] = 'ログイン';
        $strings['AdditionalInformation'] = '追加情報';
        $strings['AllFieldsAreRequired'] = '全ての項目を入力してください';
        $strings['Optional'] = '任意';
        $strings['YourProfileWasUpdated'] = 'プロフィールを更新しました';
        $strings['YourSettingsWereUpdated'] = '設定を更新しました';
        $strings['Register'] = '登録';
        $strings['SecurityCode'] = 'セキュリティコード';
        $strings['ReservationCreatedPreference'] = '私または誰かが代わりに予約をしたとき';
        $strings['ReservationDeletedPreference'] = '私または誰かが代わりに予約を取り消したとき';
        $strings['ReservationUpdatedPreference'] = '私または誰かが代わりに予約を変更したとき';
        $strings['ReservationApprovalPreference'] = '私の予約が承認されたとき';
        $strings['PreferenceSendEmail'] = '私にメールを送ってください';
        $strings['PreferenceNoEmail'] = '通知はいりません';
        $strings['ReservationCreated'] = '予約を作成しました!';
        $strings['ReservationUpdated'] = '予約を変更しました!';
        $strings['ReservationRemoved'] = '予約を取り消しました';
        $strings['YourReferenceNumber'] = '照会番号は %s です';
        $strings['UpdatingReservation'] = '予約の変更中';
        $strings['ChangeUser'] = 'ユーザーを変更';
        $strings['MoreResources'] = '別のリソースも一緒に予約する';
        $strings['ReservationLength'] = '予約時間';
        $strings['ParticipantList'] = '出席者一覧';
        $strings['AddParticipants'] = '出席者追加';
        $strings['InviteOthers'] = '招待する';
        $strings['AddResources'] = 'リソースを追加する';
        $strings['AddAccessories'] = '備品を追加する';
        $strings['Accessory'] = '備品';
        $strings['QuantityRequested'] = '数量を指定してください';
        $strings['CreatingReservation'] = '予約作成中';
        $strings['UpdatingReservation'] = '予約変更中';
        $strings['DeleteWarning'] = 'この操作は取り消しできません!';
        $strings['DeleteAccessoryWarning'] = 'この備品は全ての予約からも削除されます。';
        $strings['AddAccessory'] = '備品を追加する';
        $strings['AddBlackout'] = '利用不能時間を追加';
        $strings['AllResourcesOn'] = 'スケジュール中の全リソースを対象';
        $strings['Reason'] = '理由';
        $strings['BlackoutShowMe'] = 'ぶつかっている予約を表示する';
        $strings['BlackoutDeleteConflicts'] = 'ぶつかっている予約は削除する';
        $strings['Filter'] = 'フィルター(条件)';
        $strings['Between'] = '期間';
        $strings['CreatedBy'] = '作成者';
        $strings['BlackoutCreated'] = '利用不能時間を設定しました!';
        $strings['BlackoutNotCreated'] = '利用不能時間を設定できませんでした!';
        $strings['BlackoutConflicts'] = '利用不能時間がぶつかっています';
        $strings['ReservationConflicts'] = '予約がぶつかっています';
        $strings['UsersInGroup'] = 'このグループのユーザー';
        $strings['Browse'] = '表示';
        $strings['DeleteGroupWarning'] = 'グループを削除するとグループによって与えられていた許可も削除されます。このグループのユーザーがリソースにアクセスできなくなることがあります。';
        $strings['WhatRolesApplyToThisGroup'] = '何のロールがこのこのグループに割り当てられるか?';
        $strings['WhoCanManageThisGroup'] = '誰がこのグループを管理するか?';
        $strings['AddGroup'] = 'グループ追加';
        $strings['AllQuotas'] = '全ての量制限';
        $strings['QuotaReminder'] = '注意: 量制限はそれぞれのスケジュールのタイムゾーンによって計算されます。';
        $strings['AllReservations'] = '全ての予約';
        $strings['PendingReservations'] = '保留中の予約';
        $strings['Approving'] = '認証中';
        $strings['MoveToSchedule'] = 'スケジュールへ移動';
        $strings['DeleteResourceWarning'] = 'このリソースを削除すると関連する次のものを含む全てのデータも削除されます。';
        $strings['DeleteResourceWarningReservations'] = '当リソースの全ての過去、現在、未来の予約';
        $strings['DeleteResourceWarningPermissions'] = '当リソースの全ての権限の割り当て';
        $strings['DeleteResourceWarningReassign'] = '消したくないものについては、操作を行う前に再割り当てしてください。';
        $strings['ScheduleLayout'] = '時間枠設定 (時間は %s)';
        $strings['ReservableTimeSlots'] = '予約可能な時間枠';
        $strings['BlockedTimeSlots'] = '予約できない時間枠';
        $strings['ThisIsTheDefaultSchedule'] = 'これはデフォルトスケジュールです';
        $strings['DefaultScheduleCannotBeDeleted'] = 'デフォルトスケジュールは削除できません';
        $strings['MakeDefault'] = 'デフォルトにする';
        $strings['BringDown'] = 'Bring Down';
        $strings['ChangeLayout'] = '時間枠の変更';
        $strings['AddSchedule'] = 'スケジュールを追加';
        $strings['StartsOn'] = '週の最初の曜日';
        $strings['NumberOfDaysVisible'] = '表示する日数';
        $strings['UseSameLayoutAs'] = '同じ時間枠を使う';
        $strings['Format'] = '書式';
        $strings['OptionalLabel'] = 'ラベル(なくても可)';
        $strings['LayoutInstructions'] = '一行に一つの時間枠を記入してください。 時間枠は一日の最初から最後(12:00 AM)までの24時間全てを網羅するようにしてください。';
        $strings['AddUser'] = 'ユーザーを追加';
        $strings['UserPermissionInfo'] = '実際のリソース利用権限は、ユーザーロール、グループでの許可、その他の許可設定によって変わってきます。';
        $strings['DeleteUserWarning'] = 'このユーザーを削除すると、現在、未来、過去の予約も削除することになります。';
        $strings['AddAnnouncement'] = 'お知らせの追加';
        $strings['Announcement'] = 'お知らせ';
        $strings['Priority'] = '優先度';
        $strings['Reservable'] = '予約できます';
        $strings['Unreservable'] = '使用できません';
        $strings['Reserved'] = '予約されています';
        $strings['MyReservation'] = '私の予約';
        $strings['Pending'] = '保留中';
        $strings['Past'] = '過去の予約';
        $strings['Restricted'] = '予約できません';
        $strings['ViewAll'] = '全て表示';
        $strings['MoveResourcesAndReservations'] = 'リソースと予約を移動';
        $strings['TurnOffSubscription'] = 'カレンダーの購読を禁止する';
        $strings['TurnOnSubscription'] = 'このカレンダーの購読を許可する';
        $strings['SubscribeToCalendar'] = 'このカレンダーを購読する';
        $strings['SubscriptionsAreDisabled'] = '管理者がカレンダーの購読を無効にしています';
        $strings['NoResourceAdministratorLabel'] = '(リソースの管理者はいません)';
        $strings['WhoCanManageThisResource'] = 'だれがこのリソースを管理できますか?';
        $strings['ResourceAdministrator'] = 'リソース管理者';
        $strings['Private'] = 'プライベート';
        $strings['Accept'] = '受理';
        $strings['Decline'] = '辞退';
        // End Strings

        // Errors
        $strings['LoginError'] = 'ユーザー名またはパスワードが一致しません';
        $strings['ReservationFailed'] = '予約出来ませんでした';
        $strings['MinNoticeError'] = 'このリソースでは早めの予約が必要です。 今から予約できるのは %s 以降のぶんです。';
        $strings['MaxNoticeError'] = '指定時刻まで時間があるため、このリソースは予約できません。今できるのは %s までの予約です。';
        $strings['MinDurationError'] = '一こまの予約は少なくとも %s 以上にしてください。';
        $strings['MaxDurationError'] = '一こまの予約は %s を超えないようにしてください。';
        $strings['ConflictingAccessoryDates'] = '備品が希望数に足りません:';
        $strings['NoResourcePermission'] = 'リソースを使用する権限がありません';
        $strings['ConflictingReservationDates'] = '次の日時で予約がぶつかっています:';
        $strings['StartDateBeforeEndDateRule'] = '開始日時を終了よりも前にしてください。';
        $strings['StartIsInPast'] = '開始時刻を過ぎていいます。';
        $strings['EmailDisabled'] = '管理者がメールでの通知を無効にしています。';
        $strings['ValidLayoutRequired'] = '時間枠は一日の最初から最後(12:00 AM)までの24時間全てを網羅するようにしてください。';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = '予約の作成';
        $strings['EditReservation'] = '予約の編集';
        $strings['LogIn'] = 'ログイン';
        $strings['ManageReservations'] = '予約';
        $strings['AwaitingActivation'] = 'アクティベーション待ち';
        $strings['PendingApproval'] = '保留中の承認';
        $strings['ManageSchedules'] = 'スケジュール管理';
        $strings['ManageResources'] = 'リソース';
        $strings['ManageAccessories'] = '備品';
        $strings['ManageUsers'] = 'ユーザー';
        $strings['ManageGroups'] = 'グループ';
        $strings['ManageQuotas'] = '制限';
        $strings['ManageBlackouts'] = '利用不能時間';
        $strings['MyDashboard'] = 'マイ ダッシュボード';
        $strings['ServerSettings'] = 'サーバー設定';
        $strings['Dashboard'] = 'ダッシュボード';
        $strings['Help'] = 'ヘルプ';
        $strings['Bookings'] = '予約状況';
        $strings['Schedule'] = 'スケジュール';
        $strings['Reservations'] = '予約';
        $strings['Account'] = 'アカウント';
        $strings['EditProfile'] = 'プロフィール編集';
        $strings['FindAnOpening'] = '出欠未決を検索';
        $strings['OpenInvitations'] = 'まだ返答していない招待';
        $strings['MyCalendar'] = 'マイ・カレンダー';
        $strings['ResourceCalendar'] = 'リソースカレンダー';
        $strings['Reservation'] = '新規予約';
        $strings['Install'] = 'インストール';
        $strings['ChangePassword'] = 'パスワード変更';
        $strings['MyAccount'] = 'マイ アカウント';
        $strings['Profile'] = 'プロフィール';
        $strings['ApplicationManagement'] = '管理';
        $strings['ForgotPassword'] = 'パスワードを忘れました';
        $strings['NotificationPreferences'] = '通知設定';
        $strings['ManageAnnouncements'] = 'お知らせ';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = '日';
        $strings['DayMondaySingle'] = '月';
        $strings['DayTuesdaySingle'] = '火';
        $strings['DayWednesdaySingle'] = '水';
        $strings['DayThursdaySingle'] = '木';
        $strings['DayFridaySingle'] = '金';
        $strings['DaySaturdaySingle'] = '土';

        $strings['DaySundayAbbr'] = '日曜';
        $strings['DayMondayAbbr'] = '月曜';
        $strings['DayTuesdayAbbr'] = '火曜';
        $strings['DayWednesdayAbbr'] = '水曜';
        $strings['DayThursdayAbbr'] = '木曜';
        $strings['DayFridayAbbr'] = '金曜';
        $strings['DaySaturdayAbbr'] = '土曜';

        // Email Subjects
        $strings['ReservationApprovedSubject'] = '予約が承認されました';
        $strings['ReservationCreatedSubject'] = '予約されました';
        $strings['ReservationUpdatedSubject'] = '予約が変更されました';
        $strings['ReservationDeletedSubject'] = 'Your Reservation Was Removed';
        $strings['ReservationCreatedAdminSubject'] = 'Notification: 予約作成';
        $strings['ReservationUpdatedAdminSubject'] = 'Notification: 予約変更';
        $strings['ReservationDeleteAdminSubject'] = 'Notification: 予約削除';
        $strings['ParticipantAddedSubject'] = '出席登録のお知らせ';
        $strings['ParticipantDeletedSubject'] = '予約は取り消されました';
        $strings['InviteeAddedSubject'] = '参加のお願い';
        $strings['ResetPassword'] = 'パスワードリセット要求';
        $strings['ForgotPasswordEmailSent'] = '指定されたメールアドレスへパスワードのリセット方法を送信しました。';
        //

        $this->Strings = $strings;

        return $this->Strings;
    }

    /**
     * @return array
     */
    protected function _LoadDays()
    {
        $days = parent::_LoadDays();

        /***
        DAY NAMES
        All of these arrays MUST start with Sunday as the first element
        and go through the seven day week, ending on Saturday
         ***/
        // The full day name
        $days['full'] = array('日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日');
        // The three letter abbreviation
        $days['abbr'] = array('日曜', '月曜', '火曜', '水曜', '木曜', '金曜', '土曜');
        // The two letter abbreviation
        $days['two'] = array('日', '月', '火', '水', '木', '金', '土');
        // The one letter abbreviation
        $days['letter'] = array('日', '月', '火', '水', '木', '金', '土');

        $this->Days = $days;

        return $this->Days;
    }

    /**
     * @return array
     */
    protected function _LoadMonths()
    {
         $months = parent::_LoadMonths();

        /***
        MONTH NAMES
        All of these arrays MUST start with January as the first element
        and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = array('1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月');
        // The three letter month name
        $months['abbr'] = array('1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月');

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'ja';
    }
}

?>