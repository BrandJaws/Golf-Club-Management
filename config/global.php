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
        'announcement' => 'announcement'
    ],

    'contentType' => [
        'image' => 'IMAGE',
        'video' => 'VIDEO'
    ]
];