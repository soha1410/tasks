<?php

namespace Tests\Feature;

use App\Models\Activation;
use Tests\TestCase;
use Faker\Factory as Faker;

class AuthTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->faker = Faker::create();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $data = $this->test_register();
        $response = $this->post('/api/login', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);
    }
    public function test_register()
    {
        $password = uniqid();
        $username = $this->faker->username;
        $data = [
            'username' => $username,
            'password' => $password,
        ];
        $response = $this->post('/api/register', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);
        return ['password' => $password, 'username' => $username];
    }
}
