<?php
return [
    
    'gender' => [
        'male' => 'MALE',
        'female' => 'FEMALE'
    ],
    'staff' => [
        'permissions' => [
            'member' => false,
            'reservation' => false,
            'shop' => false,
            'segment' => false,
            'beacon' => false,
            'offer' => false,
            'notification' => false,
            'social' => false,
            'training' => false,
            'coach' => false,
            'league' => false,
            'course' => false
        ]
    ],
    'deviceType' => [
        'android' => 'Android',
        'iphone' => 'Iphone'
    ],
    'reservation' => [
        'pending' => 'PENDING',
        'confirmed' => 'CONFIRMED',
        'dropped' => 'DROPPED',
        'new_addition' => 'NEW ADDITION',
        'waiting' => 'WAITING',
        'reserved' => 'RESERVED',
        'pending_reserved' => 'PENDING RESERVED',
        'pending_waiting' => 'PENDING WAITING',
        'timeForReservationInSeconds' => 3600
    ],
    'queues' => [
        'announcements' => 'announcements',
        'reservations' => 'reservations'
    ],
    'jobDelays' => [
        'initialProcess' => 60,
        'finalProcess' => 60,
        'forceCancel' => 120
    ],
    'timeDifferenceTriggerToStartFinalProcess' => 3600,
    'reservationsProcessTypes' => [
        'initial' => 'INITIAL',
        'final' => 'FINAL'
    ],
    'gameStatuses' => [
        'not_started' => 'NOT STARTED',
        'started' => 'STARTED',
        'ended' => 'ENDED',
    ],
    'comingOnTime' => [
        'not_responded' => 'NOT RESPONDED',
        'yes' => 'YES',
        'no' => 'NO',
    ],
    'status'=>[
        'active'=>'ACTIVE',
        'inactive'=>'INACTIVE',
        'closed'=>'CLOSED',
        'open'=>'OPEN'
    ],
    'pushNotificationsUseCases' => [
        'reservation_confirmation_prompt' => 'reservation_confirmation_prompt',
        'add_more_players_prompt_on_decline' => 'add_more_players_prompt_on_decline',
        'request_declined_prompt' => 'request_declined_prompt',
        'add_more_players_prompt_on_timeup' => 'add_more_players_prompt_on_timeup',
        'reservation_promoted' => 'reservation_promoted',
        'reservation_cancelled_due_to_non_response' => 'reservation_cancelled_due_to_non_response',
        'reservation_cancelled_by_parent' => 'reservation_cancelled_by_parent',
        'reservation_confirmed' => 'reservation_confirmed',
        'announcement' => 'announcement',
        'score_management_overtaken' => 'score_management_overtaken',
    ],

    'contentType' => [
        'image' => 'IMAGE',
        'video' => 'VIDEO'
    ],
    'image_path' => [
        'user_profile_path' => 'uploads/profile/{member_id}/',
        'employee_profile_path' => 'uploads/employee/{employee_id}/',
        'friend_groups_image_path'=>'uploads/profile/{member_id}/groups/',
    ],
    'beacon_actions' => [
        'welcomeMessage' => 'Welcome Message',
        'clubEntry' => 'Club Entry',
        'gameEntry' => 'Game Entry',
        'clubHouse' => 'Club House',
        'gameExit' => 'Game Exit',
        'customMessage'=>'Custom Message',
    ],
    'entityBasedNotificationsEvents' => [
        "ReservationUpdation"=>"ReservationUpdation",
        
    ],
    'score' => [
        "handicap_options"=>["yes"=>"YES", "no"=>"NO"],
        "scorecard_types"=>["strokePlay"=>"STROKE PLAY"],
        "fairway"=>  ["left"=>"LEFT","center"=>"CENTER","right"=>"RIGHT"],
        "latePenaltyStrokesAbovePar"=>2,



    ],

];