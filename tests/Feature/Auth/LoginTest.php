<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function user_can_access_login_screen()
    {
        $response = $this->get('/login');

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function user_is_redirected_to_home_on_login()
    {
    	$user = factory(User::class)->make();
    	$response = $this->actingAs($user)->get('/login');
    	$response->assertRedirect('/home');
    }

    /** @test */
    public function test_user_can_login_with_correct_credentials()
    {
    	$user = factory(User::class)->create([
    		'password'	=>	bcrypt($password = 'testuser'),
    	]);
    	$response = $this->post('/login', [
    		'email'		=> $user->email,
    		'password'	=> $password,
    	]);

    	$response->assertRedirect('/home');
    	$this->assertAuthenticatedAs($user);
    }

    /** @test */
  public function test_user_cannot_login_with_incorrect_password()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('i-love-laravel'),
        ]);
        
        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);
        
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
    /** @test */
    public function test_remember_me_function()
    {
    	$user = factory(User::class)->create([
    		'id'		=> random_int(1, 100),
    		'password'	=>	bcrypt($password = 'testing_again'),
    	]);

    	$response = $this->post('/login', [
    		'email'		=> $user->email,
    		'password'	=> $password,
    		'remember'	=> 'on',
    	]);

    	$response->assertRedirect('/home');
    	$response->assertCookie(\Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
    		$user->id,
    		$user->getRememberToken(),
    		$user->password,
		]));
    	$this->assertAuthenticatedAs($user);
    }



}
