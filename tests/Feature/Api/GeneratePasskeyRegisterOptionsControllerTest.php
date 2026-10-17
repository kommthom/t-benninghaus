<?php

use App\Models\User;
use App\Services\Serializer;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('it returns register options', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->getJson(route('passkeys.register-options'))
        ->assertStatus(200)
        ->assertJsonStructure([
            'rp',
            'user',
            'challenge',
            'pubKeyCredParams',
            'authenticatorSelection',
            'excludeCredentials',
        ]);
});

test('it returns error when user is not authenticated', function () {
    getJson(route('passkeys.register-options'))
        ->assertStatus(401);
});

test('it returns 400 and logs error when serialization fails in returning register options', function () {
    $user = User::factory()->create();

    $serializerException = new class extends Exception implements SerializerExceptionInterface {};

    // Simulate Serializer throwing an exception
    $serializerMock = Mockery::mock(Serializer::class);
    $serializerMock->shouldReceive('toJson')
        ->andThrow(new $serializerException('Serialization failed'));

    $this->app->instance(Serializer::class, $serializerMock);

    Log::shouldReceive('error')
        ->once()
        ->with('Webauthn registration option serialization failed.', Mockery::on(function ($context) use ($user) {
            return $context['user_id'] === $user->id && $context['exception'] === 'Serialization failed';
        }));

    actingAs($user)
        ->getJson(route('passkeys.register-options'))
        ->assertStatus(400)
        ->assertJson([
            'error' => 'Server error occurred, unable to serialize registration options. ',
        ]);
});
