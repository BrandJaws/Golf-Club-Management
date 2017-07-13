<?php
return [
    'login_email_not_present' => [
        'code' => '1',
        'httpCode' => '412',
        'message' => 'Please provide a valid email'
    ],
    'login_password_not_present' => [
        'code' => '2',
        'httpCode' => '412',
        'message' => 'Please provide your account password'
    ],
    'invalid_email_address' => [
        'code' => '3',
        'httpCode' => '412',
        'message' => 'Invalid email address'
    ],
    'invalid_password' => [
        'code' => '4',
        'httpCode' => '412',
        'message' => 'Invalid password'
    ],
    'email_not_present' => [
        'code' => '5',
        'httpCode' => '412',
        'message' => 'Please provide a valid email'
    ],
    'password_not_present' => [
        'code' => '6',
        'httpCode' => '412',
        'message' => 'Please provide a password'
    ],
    'first_name_not_present' => [
        'code' => '7',
        'httpCode' => '412',
        'message' => 'Please provide first name'
    ],
    'last_name_not_present' => [
        'code' => '8',
        'httpCode' => '412',
        'message' => 'Please provide last name'
    ],
    'club_name_not_present' => [
        'code' => '9',
        'httpCode' => '412',
        'message' => 'Please provide club name'
    ],
    'address_not_present' => [
        'code' => '10',
        'httpCode' => '412',
        'message' => 'Please provide address'
    ],
    'phone_not_present' => [
        'code' => '11',
        'httpCode' => '412',
        'message' => 'Please provide phone number'
    ],
    'club_registered_successfully' => [
        'code' => '12',
        'httpCode' => '200',
        'message' => 'Club registered successfully'
    ],
    'email_already_registered' => [
        'code' => '13',
        'httpCode' => '412',
        'message' => 'Email already registered with us'
    ],
    'invalid_auth_token' => [
        'code' => '14',
        'httpCode' => '412',
        'message' => 'Provided Access Token is not valid'
    ],
    'not_authorized' => [
        'code' => '14',
        'httpCode' => '412',
        'message' => 'This is a secure request'
    ],
    'court_not_available' => [
        'code' => '15',
        'httpCode' => '404',
        'message' => 'There are no courts available'
    ],
    'court_registered_successfully' => [
        'code' => '200',
        'httpCode' => '200',
        'message' => 'Court registered successfully'
    ],
    'court_updated_successfully' => [
        'code' => '200',
        'httpCode' => '200',
        'message' => 'Court updated successfully'
    ],
    'exception' => [
        'code' => '500',
        'httpCode' => '500',
        'message' => 'There is something wrong'
    ],
    'member_updated_successfully' => [
        'code' => '200',
        'httpCode' => '200',
        'message' => 'Member updated successfully'
    ],
    'member_not_exists' => [
        'code' => '16',
        'httpCode' => '404',
        'message' => 'Member not found'
    ],
    'no_members_could_be_found' => [
        'code' => '17',
        'httpCode' => '404',
        'message' => 'No members could be found'
    ],
    'mobile_invalid_club_identifire' => [
        'code' => '18',
        'httpCode' => '404',
        'message' => 'Invalid club identifire must be an integer'
    ],
    'mobile_no_courts_in_club' => [
        'code' => '19',
        'httpCode' => '404',
        'message' => 'No courts found'
    ],
    'gender_not_present' => [
        'code' => '20',
        'httpCode' => '412',
        'message' => 'Please Provide Gender'
    ],
    'member_registered_successfully' => [
        'code' => '21',
        'httpCode' => '200',
        'message' => 'Memebr registered successfully'
    ],
    'member_already_exists' => [
        'code' => '22',
        'httpCode' => '412',
        'message' => 'Memebr already exists'
    ],
    'club_not_found' => [
        'code' => '23',
        'httpCode' => '404',
        'message' => 'Club not found'
    ],
    'mobile_invalid_court_identifire' => [
        'code' => '24',
        'httpCode' => '412',
        'message' => 'Invalid court identifire must be an integer'
    ],
    'mobile_invalid_club' => [
        'code' => '25',
        'httpCode' => '404',
        'message' => 'There is no club present for provided identifier'
    ],
    'mobile_invalid_court' => [
        'code' => '26',
        'httpCode' => '404',
        'message' => 'There is no court present for provided identifier'
    ],
    'mobile_reservation_time_missing' => [
        'code' => '26',
        'httpCode' => '404',
        'message' => 'Resevation time must be present in the request'
    ],
    'mobile_slot_already_reserved' => [
        'code' => '26',
        'httpCode' => '404',
        'message' => 'Resevation time slot already reserved'
    ],
    'mobile_forgot_password_email_sent' => [
        'code' => '27',
        'httpCode' => '200',
        'message' => 'Email sent for forgot password'
    ],
    'mobile_member_not_found' => [
        'code' => '28',
        'httpCode' => '404',
        'message' => 'Member Not found'
    ],
    'member_deleted_successfully' => [
        'code' => '200',
        'httpCode' => '200',
        'message' => 'Member deleted successfully'
    ],
    'mobile_player_key_missing' => [
        'code' => '29',
        'httpCode' => '412',
        'message' => 'Please select atleast one more player to book a court'
    ],
    'mobile_reservation_successfull' => [
        'code' => '30',
        'httpCode' => '412',
        'message' => 'Court resevation was successfull'
    ],
    'mobile_players_are_not_enough' => [
        'code' => '30',
        'httpCode' => '412',
        'message' => 'You need to select atleast 1 or maximum 3 more players to reserve a court'
    ],
    'mobile_device_registration_token_missing' => [
        'code' => '31',
        'httpCode' => '412',
        'message' => 'Device registration token missing'
    ],
    'mobile_device_registration_type_missing' => [
        'code' => '32',
        'httpCode' => '412',
        'message' => 'Device type is missing'
    ],
    'player_id_not_present' => [
        'code' => '33',
        'httpCode' => '412',
        'message' => 'Player Id not present'
    ],
    'cannot_add_yourself_to_favorites' => [
        'code' => '34',
        'httpCode' => '412',
        'message' => 'You cannot add yourself to favorites'
    ],
    'invalid_player_id' => [
        'code' => '35',
        'httpCode' => '412',
        'message' => 'Player Id provided is not valid'
    ],
    'player_already_favorited' => [
        'code' => '36',
        'httpCode' => '412',
        'message' => 'Player already added to favorites'
    ],
    'cannot_remove_yourself_from_favorites' => [
        'code' => '37',
        'httpCode' => '412',
        'message' => 'Cannot remove yourself from favorites'
    ],
    'player_already_not_favorited' => [
        'code' => '37',
        'httpCode' => '412',
        'message' => 'Player is already not in your favorites list'
    ],
    'no_favorite_members' => [
        'code' => '38',
        'httpCode' => '404',
        'message' => 'No members marked as favorite'
    ],
    'court_id_not_present' => [
        'code' => '39',
        'httpCode' => '412',
        'message' => 'Court id not provided'
    ],
    'invalid_court_id' => [
        'code' => '40',
        'httpCode' => '412',
        'message' => 'Invalid court id provided'
    ],
    'court_already_favorited' => [
        'code' => '41',
        'httpCode' => '412',
        'message' => 'Court is already in your favorites list'
    ],
    'court_already_not_favorited' => [
        'code' => '42',
        'httpCode' => '412',
        'message' => 'Court is already not in your favorites list'
    ],
    'no_courts_found' => [
        'code' => '43',
        'httpCode' => '404',
        'message' => 'No courts found'
    ],
    'tennis_reservation_id_missing' => [
        'code' => '44',
        'httpCode' => '412',
        'message' => 'Tennis reservation id is missing'
    ],
    'invalid_reservation' => [
        'code' => '45',
        'httpCode' => '412',
        'message' => 'Reservation not found'
    ],
    'player_missing' => [
        'code' => '46',
        'httpCode' => '412',
        'message' => 'No Players Received'
    ],
    'reservation_record_mismatch' => [
        'code' => '47',
        'httpCode' => '412',
        'message' => 'Data sent for updation doesn\'t match the record'
    ],
    'court_added_to_favorites' => [
        'code' => '48',
        'httpCode' => '200',
        'message' => 'Successfuly added court to favorites'
    ],
    'court_removed_from_favorites' => [
        'code' => '49',
        'httpCode' => '200',
        'message' => 'Successfuly removed court from favorites'
    ],
    'player_added_to_favorites' => [
        'code' => '50',
        'httpCode' => '200',
        'message' => 'Successfuly added player to favorites'
    ],
    'player_removed_from_favorites' => [
        'code' => '51',
        'httpCode' => '200',
        'message' => 'Successfuly removed player from favorites'
    ],
    'no_reservations_found_for_member' => [
        'code' => '52',
        'httpCode' => '404',
        'message' => 'No reservations found'
    ],
    'invalid_date_format' => [
        'code' => '53',
        'httpCode' => '412',
        'message' => 'Invalid date format received'
    ],
    'nothing_to_show_in_beacons' => [
        'code' => '54',
        'httpCode' => '404',
        'message' => 'Nothing show in beacons'
    ],
    'beacon_updated_successfully' => [
        'code' => '55',
        'httpCode' => '200',
        'message' => 'Beacon updated successfully'
    ],
    'beacon_created_successfully' => [
        'code' => '55',
        'httpCode' => '200',
        'message' => 'Beacon configured successfully'
    ],
    'mobile_invalid_device_type' => [
        'code' => '56',
        'httpCode' => '412',
        'message' => 'Invalid device type'
    ],
    'one_or_more_players_already_cancelled' => [
        'code' => '57',
        'httpCode' => '412',
        'message' => 'One or more players selected have already declined the booking'
    ],
    'reservation_parent_missing' => [
        'code' => '58',
        'httpCode' => '412',
        'message' => 'Parent Player Missing'
    ],
    'user_not_parent' => [
        'code' => '59',
        'httpCode' => '412',
        'message' => 'User not the owner of reservation'
    ],
    'already_made_a_reservation' => [
        'code' => '60',
        'httpCode' => '412',
        'message' => 'You have already requested a reservation for this time slot'
    ],
    'one_or_more_players_already_added' => [
        'code' => '61',
        'httpCode' => '412',
        'message' => 'One or more players added are already added to the reservation'
    ],
    'deletion_failed' => [
        'code' => '62',
        'httpCode' => '412',
        'message' => 'Reservation Cancellation Failed'
    ],
    'cancel_reservation_success' => [
        'code' => '63',
        'httpCode' => '200',
        'message' => 'Successfuly cancelled a reservation'
    ],
    'failed_accept' => [
        'code' => '64',
        'httpCode' => '412',
        'message' => 'Failed to accept reservation request'
    ],
    'success_accept' => [
        'code' => '65',
        'httpCode' => '200',
        'message' => 'Successfuly accepted reservation request'
    ],
    'failed_decline' => [
        'code' => '66',
        'httpCode' => '412',
        'message' => 'Failed to decline reservation request'
    ],
    'success_decline' => [
        'code' => '67',
        'httpCode' => '200',
        'message' => 'Successfuly declined reservation request'
    ],
    'beacon_id_missing' => [
        'code' => '68',
        'httpCode' => '412',
        'message' => 'Beacon Id Is Missing'
    ],
    'member_id_missing' => [
        'code' => '69',
        'httpCode' => '412',
        'message' => 'Member Id Is Missing'
    ],
    'already_checked_in' => [
        'code' => '70',
        'httpCode' => '412',
        'message' => 'You have already checked in'
    ],
    'invalid_beacon_id' => [
        'code' => '71',
        'httpCode' => '412',
        'message' => 'Invalid Beacon Id'
    ],
    'checkin_failed' => [
        'code' => '72',
        'httpCode' => '412',
        'message' => 'Failed to check in'
    ],
    'checkin_successful' => [
        'code' => '73',
        'httpCode' => '200',
        'message' => 'Successfuly Checked In'
    ],
    'announcement_body_not_present' => [
        'code' => '74',
        'httpCode' => '412',
        'message' => 'Announcement body not present'
    ],
    'announcement_success' => [
        'code' => '75',
        'httpCode' => '200',
        'message' => 'Announcement made Successfuly'
    ],
    'no_notifications_found' => [
        'code' => '76',
        'httpCode' => '404',
        'message' => 'No notifications found'
    ],
    'notification_deleted_successfuly' => [
        'code' => '77',
        'httpCode' => '200',
        'message' => 'Notification Deleted Successfuly'
    ],
    'reservation_found' => [
        'code' => '78',
        'httpCode' => '200',
        'message' => 'Reservation Found'
    ],
    'reservation_not_found' => [
        'code' => '79',
        'httpCode' => '404',
        'message' => 'Reservation Not Found'
    ],
    'date_time_not_found' => [
        'code' => '80',
        'httpCode' => '412',
        'message' => 'Date not found'
    ],
    'reservation_status_not_final' => [
        'code' => '81',
        'httpCode' => '412',
        'message' => 'Cannot proceed since reservation has not yet reached a decision.'
    ],
    'player_not_in_reservation' => [
        'code' => '82',
        'httpCode' => '412',
        'message' => 'Player not in this reservation'
    ],
    'reservation_updated_successfuly' => [
        'code' => '83',
        'httpCode' => '200',
        'message' => 'Reservation Updated Successfuly'
    ],
    'players_over_allowed_limit' => [
        'code' => '84',
        'httpCode' => '412',
        'message' => ':player_names have consumed their weekly time allowance '
    ],
    'invalid_number_of_bookings' => [
        'code' => '85',
        'httpCode' => '412',
        'message' => 'Number of multiple bookings cannot be more than 2'
    ],
    'newsfeed_creation_successful' => [
        'code' => '86',
        'httpCode' => '200',
        'message' => 'Successfuly Created A News Feed'
    ],
    'newsfeed_not_found' => [
        'code' => '87',
        'httpCode' => '404',
        'message' => 'News Feed Not Found'
    ],
    'newsfeed_deletion_successful' => [
        'code' => '88',
        'httpCode' => '200',
        'message' => 'News Feed Deleted Successfuly'
    ],
    'newsfeed_updation_successful' => [
        'code' => '86',
        'httpCode' => '200',
        'message' => 'Successfuly Updated A News Feed'
    ],
    'players_already_have_booking' => [
        'code' => '87',
        'httpCode' => '412',
        'message' => ':player_names already have a booking during this timeslot'
    ],
    'coach_created_successfully' => [
        'code' => '200',
        'httpCode' => '200',
        'message' => 'Coach registered successfully'
    ],
    'coach_updated_successfully' => [
        'code' => '200',
        'httpCode' => '200',
        'message' => 'Coach updated successfully'
    ],
    'players_less_than_group_size' => [
        'code' => '88',
        'httpCode' => '412',
        'message' => 'Number of players sent is less than group size for reservation'
    ],
    'you_already_have_booking' => [
        'code' => '89',
        'httpCode' => '412',
        'message' => 'You already have a booking during this timeslot'
    ],
    'mobile_not_enough_slots_remaining' => [
        'code' => '90',
        'httpCode' => '412',
        'message' => 'Not enough slots remaining for reservation'
    ],
    'group_already_complete' => [
        'code' => '91',
        'httpCode' => '412',
        'message' => 'The group you were added to has been filled by other players. Do you want us to try reserving your place separate from the group?'
    ],
    'training_created_success' => [
        'code' => '92',
        'httpCode' => '200',
        'message' => 'Training created successfully'
    ],
    'training_updated_success' => [
        'code' => '92',
        'httpCode' => '200',
        'message' => 'Training updated successfully'
    ],
    'no_members_received' => [
        'code' => '93',
        'httpCode' => '412',
        'message' => 'No members received'
    ],
    'group_name_not_received' => [
        'code' => '94',
        'httpCode' => '412',
        'message' => 'Group Name Not Received'
    ],
    'group_added_successfuly' => [
        'code' => '95',
        'httpCode' => '200',
        'message' => 'Group Added Successfuly'
    ],
    'group_id_not_received' => [
        'code' => '96',
        'httpCode' => '412',
        'message' => 'Group Id Not Received'
    ],
    'invalid_group' => [
        'code' => '97',
        'httpCode' => '404',
        'message' => 'Invalid Group Id Provided'
    ],
    'group_updated_successfuly' => [
        'code' => '98',
        'httpCode' => '200',
        'message' => 'Group Updated Successfuly'
    ],
    'user_not_parent_of_group' => [
        'code' => '99',
        'httpCode' => '412',
        'message' => "This Group Doesn't Belong To You"
    ],
    'one_or_more_members_not_friend' => [
        'code' => '100',
        'httpCode' => '412',
        'message' => "One or more members sent are not friends"
    ],
    'member_added_to_group_successfuly' => [
        'code' => '101',
        'httpCode' => '200',
        'message' => "Members Added To Group Successfuly"
    ],
    'member_removed_from_group_successfuly' => [
        'code' => '102',
        'httpCode' => '200',
        'message' => "Members Removed From The Group Successfuly"
    ],
    'no_groups_found' => [
        'code' => '103',
        'httpCode' => '404',
        'message' => "No Groups Found"
    ],
    'group_deleted_successfuly' => [
        'code' => '104',
        'httpCode' => '200',
        'message' => "Group Deleted Successfuly"
    ],
    'no_trainings_found' => [
        'code' => '105',
        'httpCode' => '404',
        'message' => "No Trainings Found"
    ],
    'training_doesnt_belong_to_users_club' => [
        'code' => '106',
        'httpCode' => '412',
        'message' => "Requested training doesn't belong to your club"
    ],
    'training_id_missing' => [
        'code' => '107',
        'httpCode' => '412',
        'message' => "Training ID Is Missing"
    ],
    'training_slots_full' => [
        'code' => '108',
        'httpCode' => '412',
        'message' => "All Places For This Training Have Been Filled"
    ],
    'training_is_not_available' => [
        'code' => '109',
        'httpCode' => '412',
        'message' => "The Training You Requested Is Not Available Anymore"
    ],
    'already_reserved_for_training' => [
        'code' => '110',
        'httpCode' => '412',
        'message' => "You Already Have A Reservation For This Training"
    ],
    'training_reservation_successful' => [
        'code' => '111',
        'httpCode' => '200',
        'message' => "Successfuly Reserved A Place For Training"
    ],
    'not_reserved_for_training' => [
        'code' => '112',
        'httpCode' => '404',
        'message' => "You Aren't Reserved For This Reservation"
    ],
    'reservation_player_id_missing' => [
        'code' => '113',
        'httpCode' => '412',
        'message' => "Reservation Player ID is Missing"
    ],
    'beacon_uuid_missing' => [
        'code' => '114',
        'httpCode' => '412',
        'message' => "Beacon UUID Missing"
    ],
    'beacon_major_missing' => [
        'code' => '115',
        'httpCode' => '412',
        'message' => "Beacon Major Missing"
    ],
    'beacon_minor_missing' => [
        'code' => '116',
        'httpCode' => '412',
        'message' => "Beacon Minor Missing"
    ],
    'beacon_not_trusted' => [
        'code' => '117',
        'httpCode' => '404',
        'message' => "Not A Trusted Beacon"
    ],
    'beacon_not_configured' => [
        'code' => '117',
        'httpCode' => '404',
        'message' => "Beacon not configured yet"
    ],
    'beacon_id_missing' => [
        'code' => '118',
        'httpCode' => '412',
        'message' => "Beacon ID Missing"
    ],
    'beacon_action_missing' => [
        'code' => '119',
        'httpCode' => '412',
        'message' => "Beacon Action Missing"
    ],
    'no_reservations_today' => [
        'code' => '120',
        'httpCode' => '404',
        'message' => "No Reservations Found To Checkin For"
    ],
    'already_checked_in' => [
        'code' => '121',
        'httpCode' => '412',
        'message' => "Already Checked In"
    ],
    'checkin_successful' => [
        'code' => '122',
        'httpCode' => '200',
        'message' => "Checkin Successful"
    ],
    'checkin_club_entry_missing' => [
        'code' => '123',
        'httpCode' => '412',
        'message' => "Please Make A Club Entry Checkin First."
    ],
    'checkin_failed_due_to_late' => [
        'code' => '124',
        'httpCode' => '412',
        'message' => "Cant Checkin Because You Were Late For The Reservation."
    ],
    'not_yet_eligible_for_checkin' => [
        'code' => '125',
        'httpCode' => '412',
        'message' => "Can't Checkin Now. Game Checkin Facility Will Open 10 Minutes Before The Game Starts."
    ],
    'already_accepted' => [
        'code' => '126',
        'httpCode' => '200',
        'message' => "Already Accepted Reservation Request"
    ],
    'cant_move_to_different_type_of_reservation' => [
        'code' => '127',
        'httpCode' => '412',
        'message' => "Can't Move To A Different Type Of Reservation."
    ],
    'notification_not_owned_by_user'=>[
        'code'=>'128',
        'httpCode' => '412',
        'message'=>'The requested notification is not owned by the user'
    ],
    'must_notify_if_on_time'=>[
        'code'=>'129',
        'httpCode' => '412',
        'message'=>'You must notify if you are coming on time'
    ],
    'game_already_started'=>[
        'code'=>'130',
        'httpCode' => '412',
        'message'=>'Game has already been started'
    ],
    'entity_based_notification_id_missing'=>[
        'code'=>'131',
        'httpCode' => '412',
        'message'=>'Entity Based Notification Id Is Missing'
    ],
    'guests_cant_checkin'=>[
        'code'=>'132',
        'httpCode' => '412',
        'message'=>"Guests Can't Check In"
    ],
      'tees_fields_required'=>[
        'code'=>'133',
        'httpCode' => '412',
        'message'=>"Tees Fields Are Required"
      ],
      'holes_fields_required'=>[
        'code'=>'134',
        'httpCode' => '412',
        'message'=>"Hole Details Are Required"
      ],
























    'pushNotificationMessageBodies' => [
        'reservation_confirmation_prompt' => "%s has added you to a reservation at %s on %s for court %s. Are you available?",
        'reservation_confirmation_prompt_final' => "You have a reservation at %s on %s for court %s. Are you still available?",
        'add_more_players_prompt' => 'You need to add more players to keep your reservation',
        'request_declined_prompt' => '%s has declined you request for reservation',
        'add_more_players_prompt_on_timeup' => 'add_more_players_prompt_on_timeup',
        'reservation_promoted' => 'Your reservation request at %s has been promoted. Your status now is %s.',
        'reservation_cancelled_due_to_non_response' => 'Your reservation at %s has been cancelled due to lack of enough players. ',
        'reservation_cancelled_by_parent' => 'Your reservation at %s has been cancelled by the parent player. ',
        'reservation_confirmed' => 'Your reservation request at %s has been confirmed. ',
        'status_reserved' => 'Your status is Reserved.',
        'status_pending' => 'Your status is Pending.'
    ],
    'beacon_messages' => [
        'welcome_without_reservation' => "Welcome to :clubName :memberName.",
        'welcome_with_reservation' =>"Welcome to :clubName :memberName. You have a reservation at course :courseName at :startTime. Would you like to check-in now? ",
        'welcome_with_training' =>"Welcome to :clubName :memberName. You have a training session with :coach_name today. ",
    ],



];
