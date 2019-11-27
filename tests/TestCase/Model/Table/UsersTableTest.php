<?php

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;

use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class UsersTableTest extends TestCase
{

    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * @var array
     */
    public $fixtures = ['app.Users'];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Users = TableRegistry::get('Users');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testValidationAcceptTerms()
    {
        // Required
        $data = [];
        $errors = $this->Users->getValidator('acceptTerms')->errors($data);

        $expected = [
            'has_accepted_terms' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $errors);

        // Empty
        $data = [
            'has_accepted_terms' => ''
        ];
        $errors = $this->Users->getValidator('acceptTerms')->errors($data);

        $expected = [
            'has_accepted_terms' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($expected, $errors);

        // Invalid
        $data = [
            'has_accepted_terms' => false
        ];
        $errors = $this->Users->getValidator('acceptTerms')->errors($data);

        $expected = [
            'has_accepted_terms' => [
                'invalid' => 'You must accept the terms of service'
            ]
        ];

        $this->assertEquals($expected, $errors);

        // Valid
        $data = [
            'has_accepted_terms' => true
        ];
        $errors = $this->Users->getValidator('acceptTerms')->errors($data);

        $expected = [];

        $this->assertEquals($expected, $errors);
    }

    /**
     * @return void
     */
    public function testValidationAdd()
    {
        // Required
        $data = [];
        $errors = $this->Users->getValidator('add')->errors($data);

        $expected = [
            'first_name' => [
                '_required' => 'This field is required'
            ],
            'last_name' => [
                '_required' => 'This field is required'
            ],
            'email' => [
                '_required' => 'This field is required'
            ],
            'password' => [
                '_required' => 'This field is required'
            ],
            'has_accepted_terms' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $errors);

        // Empty
        $data = [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'password' => '',
            'has_accepted_terms' => ''
        ];
        $errors = $this->Users->getValidator('add')->errors($data);

        $expected = [
            'first_name' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'last_name' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'email' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'password' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'has_accepted_terms' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($expected, $errors);

        // Not accepted terms
        $data = [
            'first_name' => 'Christy',
            'last_name' => 'Quinn',
            'email' => 'christy@banditmatch.com',
            'password' => 'password',
            'has_accepted_terms' => false
        ];
        $errors = $this->Users->getValidator('add')->errors($data);

        $expected = [
            'has_accepted_terms' => [
                'invalid' => 'You must accept the terms of service'
            ]
        ];

        $this->assertEquals($expected, $errors);

        // Valid
        $data = [
            'first_name' => 'Christy',
            'last_name' => 'Quinn',
            'email' => 'christy@banditmatch.com',
            'password' => 'password',
            'has_accepted_terms' => true
        ];
        $errors = $this->Users->getValidator('add')->errors($data);

        $this->assertEmpty($errors);
    }

    /**
     * @return void
     */
    public function testValidationInvite()
    {
        // Required
        $data = [];
        $errors = $this->Users->getValidator('invite')->errors($data);

        $expected = [
            'email' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $errors);

        // Empty
        $data = [
            'email' => ''
        ];
        $errors = $this->Users->getValidator('invite')->errors($data);

        $expected = [
            'email' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($expected, $errors);

        // Valid
        $data = [
            'email' => 'christy@banditmatch.com'
        ];
        $errors = $this->Users->getValidator('invite')->errors($data);

        $this->assertEmpty($errors);
    }

    /**
     * @return void
     */
    public function testPatchEntityAdd()
    {
        // Validation
        $user = $this->Users->newEntity();
        $data = [];

        $this->Users->patchEntityAdd($user, $data);

        $expected = [
            'first_name' => [
                '_required' => 'This field is required'
            ],
            'last_name' => [
                '_required' => 'This field is required'
            ],
            'email' => [
                '_required' => 'This field is required'
            ],
            'password' => [
                '_required' => 'This field is required'
            ],
            'has_accepted_terms' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $user->getErrors());

        // Valid new
        $user = $this->Users->newEntity();
        $data = [
            'first_name' => 'Bob',
            'last_name' => 'Geldof',
            'email' => 'bob@banditmatch.com',
            'password' => 'password',
            'has_accepted_terms' => true
        ];
        $this->Users->patchEntityAdd($user, $data);

        $this->assertEmpty($user->getErrors());
        $this->assertNull($user->id);
        $this->assertEquals($user->email_preferences, [
            'challenge_created' => true,
            'match_added' => true,
            'weekly_digest' => true
        ]);

        // Valid existing activated
        $user = $this->Users->newEntity();
        $data = [
            'first_name' => 'Christy',
            'last_name' => 'Quinn',
            'email' => 'christy@banditmatch.com',
            'password' => 'password',
            'has_accepted_terms' => true
        ];
        $this->Users->patchEntityAdd($user, $data);

        $this->assertEmpty($user->getErrors());
        $this->assertNull($user->id);
        $this->assertEquals($user->email_preferences, [
            'challenge_created' => true,
            'match_added' => true,
            'weekly_digest' => true
        ]);

        // Valid existing unactivated
        $this->Users->updateAll(['password' => null], ['email' => 'christy@banditmatch.com']);

        $user = $this->Users->newEntity();
        $data = [
            'first_name' => 'Christy',
            'last_name' => 'Quinn',
            'email' => 'christy@banditmatch.com',
            'password' => 'password',
            'has_accepted_terms' => true
        ];
        $this->Users->patchEntityAdd($user, $data);

        $this->assertEmpty($user->getErrors());
        $this->assertEquals(1, $user->id);
        $this->assertEquals($user->email_preferences, [
            'challenge_created' => true,
            'match_added' => true,
            'weekly_digest' => true
        ]);
    }

    /**
     * @return void
     */
    public function testPatchEntityClearToken()
    {
        $user = $this->Users->newEntity();

        $this->Users->patchEntitySetToken($user);

        $this->assertNotNull($user->token);
        $this->assertNotNull($user->token_sent);

        $this->Users->patchEntityClearToken($user);

        $this->assertNull($user->token);
        $this->assertNull($user->token_sent);
    }

    /**
     * @return void
     */
    public function testPatchEntityEdit()
    {
        // Required
        $user = $this->Users->get(1);
        $data = [];

        $this->Users->patchEntityEdit($user, $data);

        $expected = [];

        $this->assertEquals($expected, $user->getErrors());

        // Missing new_password
        $user = $this->Users->get(1);
        $data = [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'email_preferences' => '',
            'current_password' => 'password'
        ];

        $this->Users->patchEntityEdit($user, $data);

        $expected = [
            'first_name' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'last_name' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'email' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'email_preferences' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'new_password' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $user->getErrors());

        // Empty new password
        $user = $this->Users->get(1);
        $data = [
            'current_password' => 'password',
            'new_password' => ''
        ];

        $this->Users->patchEntityEdit($user, $data);

        $expected = [
            'new_password' => [
                '_empty' => 'You must enter a new password'
            ]
        ];

        $this->assertEquals($expected, $user->getErrors());

        // Missing current_password
        $user = $this->Users->get(1);
        $data = [
            'new_password' => 'newpassword'
        ];

        $this->Users->patchEntityEdit($user, $data);

        $expected = [
            'current_password' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $user->getErrors());

        // Empty current_password
        $user = $this->Users->get(1);
        $data = [
            'current_password' => '',
            'new_password' => 'newpassword'
        ];

        $this->Users->patchEntityEdit($user, $data);

        $expected = [
            'current_password' => [
                '_empty' => 'You must enter your current password'
            ]
        ];

        $this->assertEquals($expected, $user->getErrors());

        // Invalid password
        $user = $this->Users->get(1);
        $data = [
            'current_password' => 'notmypassword',
            'new_password' => 'newpassword'
        ];

        $this->Users->patchEntityEdit($user, $data);

        $expected = [
            'current_password' => [
                'match' => 'The password you entered was incorrect'
            ]
        ];

        $this->assertEquals($expected, $user->getErrors());
        $this->assertFalse($user->isDirty('password'));

        // Valid
        $user = $this->Users->get(1);
        $data = [
            'current_password' => 'password',
            'new_password' => 'newpassword'
        ];

        $this->Users->patchEntityEdit($user, $data);

        $this->assertEmpty($user->getErrors());
        $this->assertTrue($user->isDirty('password'));

        // Email preferences required
        $user = $this->Users->get(1);
        $data = [
            'email_preferences' => []
        ];

        $expected = [
            'email_preferences' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->Users->patchEntityEdit($user, $data);

        $this->assertEquals($user->getErrors(), $expected);

        // Email preferences bool
        $user = $this->Users->get(1);
        $data = [
            'email_preferences' => [
                'challenge_created' => 'true',
                'match_added' => 'true',
                'weekly_digest' => 'true'
            ]
        ];

        $expected = [
            'email_preferences' => [
                'challenge_created' => [
                    'boolean' => 'The provided value is invalid'
                ],
                'match_added' => [
                    'boolean' => 'The provided value is invalid'
                ],
                'weekly_digest' => [
                    'boolean' => 'The provided value is invalid'
                ]
            ]
        ];

        $this->Users->patchEntityEdit($user, $data);

        $this->assertEquals($user->getErrors(), $expected);

        // Email preferences valid
        $user = $this->Users->get(1);
        $data = [
            'email_preferences' => [
                'challenge_created' => false,
                'match_added' => false,
                'weekly_digest' => false
            ]
        ];

        $this->Users->patchEntityEdit($user, $data);

        $this->assertEmpty($user->getErrors());
        $this->assertFalse($user->email_preferences['challenge_created']);
        $this->assertFalse($user->email_preferences['match_added']);
        $this->assertFalse($user->email_preferences['weekly_digest']);
    }

    /**
     * @return void
     */
    public function testPatchEntityResetPassword()
    {
        // Required
        $user = $this->Users->newEntity();
        $data = [];

        $this->Users->patchEntitySetToken($user);
        $this->Users->patchEntityResetPassword($user, $data);

        $expected = [
            'password' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $user->getErrors());
        $this->assertNotNull($user->token);
        $this->assertNotNull($user->token_sent);

        // Empty
        $user = $this->Users->newEntity();
        $data = [
            'password' => ''
        ];

        $this->Users->patchEntitySetToken($user);
        $this->Users->patchEntityResetPassword($user, $data);

        $expected = [
            'password' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($expected, $user->getErrors());
        $this->assertNotNull($user->token);
        $this->assertNotNull($user->token_sent);

        // Valid
        $data = [
            'password' => 'password'
        ];

        $this->Users->patchEntityResetPassword($user, $data);

        $this->assertEmpty($user->getErrors());
        $this->assertNull($user->token);
        $this->assertNull($user->token_sent);
    }

    /**
     * @return void
     */
    public function testPatchEntitySetToken()
    {
        $user = $this->Users->newEntity();

        $this->Users->patchEntitySetToken($user);

        $this->assertNotNull($user->token);
        $this->assertNotNull($user->token_sent);
    }

    /**
     * @return void
     */
    public function testAfterSave()
    {
        $user = $this->Users->get(1);

        $this->Users->patchEntitySetToken($user);

        $usersTableMock = $this->getMockForModel(
            'App\Model\Table\UsersTable',
            ['getMailer'],
            ['alias' => 'UsersTable', 'table' => 'users']
        );

        $emailMock = $this->getMockBuilder('Cake\Mailer\Email')
            ->setMethods(['send'])
            ->getMock();

        $mailerMock = $this->getMockBuilder('App\Mailer\UserMailer')
            ->setConstructorArgs([$emailMock])
            ->setMethods(['resetPassword'])
            ->getMock();

        $mailerMock
            ->expects($this->once())
            ->method('resetPassword');

        $usersTableMock
            ->expects($this->once())
            ->method('getMailer')
            ->will($this->returnValue($mailerMock));

        $usersTableMock->save($user);
    }

    /**
     * @return void
     */
    public function testUpdateReputation()
    {
        $userId = 1;
        $user = $this->Users->get($userId);

        $currentReputation = $user->reputation;
        $difference = 10;

        $this->Users->updateReputation($userId, $difference);

        $user = $this->Users->get($userId);

        $this->assertEquals($currentReputation + $difference, $user->reputation);

        $currentReputation = $user->reputation;
        $difference = -10;

        $this->Users->updateReputation($userId, $difference);

        $user = $this->Users->get($userId);

        $this->assertEquals($currentReputation + $difference, $user->reputation);
    }

    /**
     * @return void
     */
    public function testGetByToken()
    {
        $user = $this->Users->get(1);

        $this->Users->patchEntitySetToken($user);

        $this->Users->save($user);

        $userByToken = $this->Users->getByToken($user->token);

        $this->assertEquals($userByToken->id, $user->id);

        // Expired token
        $user = $this->Users->get(1);

        $this->Users->patchEntitySetToken($user);

        $user->token_sent->modify('-3 hours');

        $this->Users->save($user);

        $usersTableMock = $this->getMockForModel(
            'App\Model\Table\UsersTable',
            ['getMailer'],
            ['alias' => 'UsersTable', 'table' => 'users']
        );

        $emailMock = $this->getMockBuilder('Cake\Mailer\Email')
            ->setMethods(['send'])
            ->getMock();

        $mailerMock = $this->getMockBuilder('App\Mailer\UserMailer')
            ->setConstructorArgs([$emailMock])
            ->setMethods(['resetPassword'])
            ->getMock();

        $mailerMock
            ->expects($this->once())
            ->method('resetPassword');

        $usersTableMock
            ->expects($this->once())
            ->method('getMailer')
            ->will($this->returnValue($mailerMock));

        $this->expectException(UnauthorizedException::class);

        $usersTableMock->getByToken($user->token);
    }
}
