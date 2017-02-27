<?php
return [ 
		
		'gender' => [ 
				'male' => 'MALE',
				'female' => 'FEMALE' 
		],
                'staff'=>[
                    'permissions'=>[
                        'member'=>false,
                        'reservation'=>false,
                        'shop'=>false,
                        'segment'=>false,
                        'beacon'=>false,
                        'offer'=>false,
                        'notification'=>false,
                        'social'=>false,
                        'training'=>false,
                        'coach'=>false,
                        'league'=>false
                    ]
                ],
		'deviceType' => [ 
				'android' => 'Android',
				'iphone' => 'Iphone' 
		],
                'reservation' => [ 
				
				'pending' => 'PENDING',
				'confirmed' => 'CONFIRMED',
				'cancelled' => 'CANCELLED',
                                'na'=>'NA',
				'waiting' => 'WAITING',
                                'reserved' => 'RESERVED',
				'pending_reserved' => 'PENDING RESERVED',
				'pending_waiting' => 'PENDING WAITING',
                                'timeForReservationInSeconds' => 3600,
		],
];