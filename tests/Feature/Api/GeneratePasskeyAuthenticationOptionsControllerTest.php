<?php

declare(strict_types=1);


use App\Services\Serializer;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;

test('it returns authentication options', function () {
    $this
        ->getJson(route('passkeys.authentication-options'))
        ->assertStatus(200)
        ->assertJsonStructure([
            'challenge',
            'rpId',
            'allowCredentials' => [],
        ]);
});

test('it returns 400 and logs error when serialization fails in returning authentication options', function () {
    $serializerException = new class extends Exception implements SerializerExceptionInterface {};

    // Simulate Serializer throwing an exception
    $serializerMock = Mockery::mock(Serializer::class);
    $serializerMock->shouldReceive('toJson')
        ->andThrow(new $serializerException('Serialization failed'));

    $this->app->instance(Serializer::class, $serializerMock);

    Log::shouldReceive('error')
        ->once()
        ->with('Webauthn authentication option serialization failed', Mockery::on(function ($context) {
            return $context['exception'] === 'Serialization failed';
        }));

    $this
        ->getJson(route('passkeys.authentication-options'))
        ->assertStatus(400)
        ->assertJson([
            'error' => 'An error occurred, and the credential options could not be serialized. ',
        ]);
});
