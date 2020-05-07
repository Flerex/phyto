<?php

namespace Tests\Unit\Services;

use App\Notifications\ActivateAccount;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Role;
use App\Services\UserService;
use App\User;
use App\Enums\Roles;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserServiceTest extends TestCase
{


    // Testing values
    private const TESTING_USER_EMAIL = "valid.email@phyto.test";
    private const TESTING_USER_NAME = "Testing Name";
    private const INVALID_USER_KEY = -1;
    private const INVALID_ROLE_NAME = "invalid_role";

    /** @var UserService $userService */
    protected $userService;

    /**
     * Initial configuration for this testing class.
     */
    public function setUp() : void
    {
        parent::setUp();

        // Inject UserService from the service container
        $this->userService = $this->app->make(UserService::class);
    }

    /**
     * Creates a testing user
     *
     * @return User
     */
    protected static function create_user()
    {
        return User::create([
            'name' => static::TESTING_USER_NAME,
            'email' => static::TESTING_USER_EMAIL,
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
    }

    /**
     * @test Test the creation of a user through the createUser() method
     */
    public function user_creation()
    {
        Event::fake();

        // Get the role model
        $role = Role::findByName(Roles::TAGGER);

        // Create a user
        $id = $this->userService->createUser(static::TESTING_USER_NAME, static::TESTING_USER_EMAIL, $role);

        Event::dispatched(Registered::class);

        // Retrieve the user from DB
        $user = User::find($id);

        // Check it contains the same values
        $this->assertEquals(static::TESTING_USER_NAME, $user->name);
        $this->assertEquals(static::TESTING_USER_EMAIL, $user->email);
        $this->assertTrue($user->hasRole(Roles::TAGGER));

    }

    /**
     * @test Test the creation of a user sends a notification to their email
     */
    public function user_creation_sends_email()
    {
        Notification::fake();

        // Create a user
        $role = Role::findByName(Roles::TAGGER);
        $id = $this->userService->createUser(static::TESTING_USER_NAME, static::TESTING_USER_EMAIL, $role);

        // Retrieve user from DB
        $user = User::find($id);

        Notification::assertSentTo($user, ActivateAccount::class);

    }

    /**
     * @test Test the reset process of a user's password
     */
    public function reset_a_users_password()
    {
        $user = self::create_user();

        Notification::fake();

        $this->userService->resetPassword($user->getKey());

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    /**
     * @test Test that sending a reset email to a non-existent user throws an exception
     */
    public function reset_an_invalid_users_password()
    {
        $this->expectException(ModelNotFoundException::class);

        Notification::fake();

        $this->userService->resetPassword(self::INVALID_USER_KEY);

    }

}
