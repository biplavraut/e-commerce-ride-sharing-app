<?php

namespace Tests;

use App\Admin;
use App\Company;
use App\Exceptions\Handler;
use App\Role;
use App\User;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Telescope\Telescope;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $admin;
    protected $user;
    protected $company;

    protected function setUp()
    {
        parent::setUp();

        Telescope::stopRecording();

        $this->company = factory(Company::class)->create();
        $this->admin   = factory(Admin::class)->create();
        $this->user    = factory(User::class)->create();
    }

    protected function userCannotVisitUrl($user, $url, $httpMethod = 'get', $redirectTo = '/')
    {
        $this->actingAs($user)->$httpMethod($url)
            ->assertStatus(302)
            ->assertRedirect($redirectTo)
        ;
    }

    protected function aGuestCannotVisit($url, $httpMethod = 'get', $data = [])
    {
        $this->disableExceptionHandling();

        $this->expectException(AuthenticationException::class);

        $this->$httpMethod($url, $data);
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler
        {
            public function __construct()
            {
            }

            public function report(\Exception $exception)
            {
            }

            public function render($request, Exception $exception)
            {
                throw $exception;
            }
        });
    }

}
