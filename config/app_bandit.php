<?php

return [
    'Bandit' => [
        'emails' => [
            'referee' => ['referee@banditmatch.com' => 'Referee (Bandit Match)']
        ],
        'email_preferences' => [
            'challenge_created' => true,
            'match_added' => true,
            'weekly_digest' => true
        ],
        'initialRating' => 1200,
        'levels' => [
            [
                'name' => 'Beginner',
                'slug' => 'beginner',
                'from' => -INF,
                'to' => 49
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'from' => 50,
                'to' => 149
            ],
            [
                'name' => 'Amateur',
                'slug' => 'amateur',
                'from' => 150,
                'to' => 249
            ],
            [
                'name' => 'Rookie',
                'slug' => 'rookie',
                'from' => 250,
                'to' => 349
            ],
            [
                'name' => 'Learner',
                'slug' => 'learner',
                'from' => 350,
                'to' => 449
            ],
            [
                'name' => 'Novice',
                'slug' => 'novice',
                'from' => 450,
                'to' => 549
            ],
            [
                'name' => 'Student',
                'slug' => 'student',
                'from' => 550,
                'to' => 649
            ],
            [
                'name' => 'Apprentice',
                'slug' => 'apprentice',
                'from' => 650,
                'to' => 749
            ],
            [
                'name' => 'Trainee',
                'slug' => 'trainee',
                'from' => 750,
                'to' => 849
            ],
            [
                'name' => 'Recruit',
                'slug' => 'recruit',
                'from' => 850,
                'to' => 949
            ],
            [
                'name' => 'Junior',
                'slug' => 'junior',
                'from' => 950,
                'to' => 1049
            ],
            [
                'name' => 'Scout',
                'slug' => 'scout',
                'from' => 1050,
                'to' => 1149
            ],
            [
                'name' => 'Fighter',
                'slug' => 'fighter',
                'from' => 1150,
                'to' => 1249
            ],
            [
                'name' => 'Gladiator',
                'slug' => 'gladiator',
                'from' => 1250,
                'to' => 1349
            ],
            [
                'name' => 'Warrior',
                'slug' => 'warrior',
                'from' => 1350,
                'to' => 1449
            ],
            [
                'name' => 'Assassin',
                'slug' => 'assassin',
                'from' => 1450,
                'to' => 1549
            ],
            [
                'name' => 'Samurai',
                'slug' => 'samurai',
                'from' => 1550,
                'to' => 1649
            ],
            [
                'name' => 'Ninja',
                'slug' => 'ninja',
                'from' => 1650,
                'to' => 1749
            ],
            [
                'name' => 'Monster',
                'slug' => 'monster',
                'from' => 1750,
                'to' => 1849
            ],
            [
                'name' => 'Mammoth',
                'slug' => 'mammoth',
                'from' => 1850,
                'to' => 1949
            ],
            [
                'name' => 'Beast',
                'slug' => 'beast',
                'from' => 1950,
                'to' => 2049
            ],
            [
                'name' => 'Oracle',
                'slug' => 'oracle',
                'from' => 2050,
                'to' => 2149
            ],
            [
                'name' => 'Ultra',
                'slug' => 'ultra',
                'from' => 2150,
                'to' => 2249
            ],
            [
                'name' => 'Mega',
                'slug' => 'mega',
                'from' => 2250,
                'to' => 2349
            ],
            [
                'name' => 'Mythical',
                'slug' => 'mythical',
                'from' => 2350,
                'to' => 2449
            ],
            [
                'name' => 'Legendary',
                'slug' => 'legendary',
                'from' => 2450,
                'to' => 2549
            ],
            [
                'name' => 'God',
                'slug' => 'god',
                'from' => 2550,
                'to' => INF
            ]
        ],
        'emailStyles' => [
            'h1' => 'font-family: \'Helvetica Neue\', \'Helvetica\', sans-serif; font-weight: 500; font-size: 25px; color: #FFFFFF; line-height: 37px;',
            'h2' => 'font-family: \'Helvetica Neue\', \'Helvetica\', sans-serif; font-weight: 500; font-size: 21px; color: #FFFFFF; line-height: 37px;',
            'p' => 'font-family: \'Helvetica Neue\', \'Helvetica\', sans-serif; font-size: 17px; color: #ADC8FA; line-height: 25px;',
            'button' => 'background-color:#1D2438; text-decoration: none; border:2px solid #F98646;border-radius:3px;display:block; height: 48px; width:100%;',
            'buttonText' => 'display: block; width: 100%; border-top: 2px solid #626F94; line-height:42px; text-align:center; text-decoration: none; font-family: \'Helvetica Neue\', \'Helvetica\', sans-serif; font-weight: 500;font-size:16px;color:#fff;white-space: nowrap;'
        ]
    ]
];
