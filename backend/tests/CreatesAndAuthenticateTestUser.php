<?php

namespace Tests;

use Illuminate\Support\Facades\URL;
use App\Models\User;

trait CreatesAndAuthenticateTestUser
{
    public function createTestUser(): void
    {   
        $user = [
            'name' => 'TestUser',
            'email' => 'testuser@testuser.test',
            'password' => bcrypt('TestUser@Test'),
        ];

        $created = User::create($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $created->id, 'hash' => sha1($created->email)]
        );

        $this->actingAs($created)->get($verificationUrl);

        $this->post(route('login'), ['email' => $user['email'], 'password' => $user['password']]);

        $this->assertAuthenticated();
    }
}