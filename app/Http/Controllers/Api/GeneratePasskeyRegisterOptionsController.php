<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Serializer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\Exception\InvalidDataException;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;

class GeneratePasskeyRegisterOptionsController extends Controller
{
    public function __invoke(Request $request, Serializer $serializer): JsonResponse|string
    {
        // Create a trusted party entity
        // id is the domain name of the website
        $relatedPartyEntity = new PublicKeyCredentialRpEntity(
            name: config('app.name'),
            id: Uri::of(config('app.url'))->host()
        );

        try {
            // Create a user entity
            // The id must be unique, typically the user's ID or UUID
            // Note: The name should not include sensitive user information, such as email or phone number
            $userEntity = new PublicKeyCredentialUserEntity(
                name: $request->user()->name,
                id: (string) $request->user()->id,
                displayName: $request->user()->name
            );
        } catch (InvalidDataException $e) {
            Log::error(__('Unable to create Webauthn user entity.'), [
                'user_id'   => $request->user()->id,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => __('Unable to create key registration options, please try again later.'),
            ], 500);
        }

        // Verify device settings
        // No platform preference, and user keys must support discoverable credentials
        // Discoverable credentials are now mainstream; if not enforced here, your YubiKey will be unusable
        $authenticatorSelectionCriteria = AuthenticatorSelectionCriteria::create(
            authenticatorAttachment: AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_NO_PREFERENCE,
            userVerification: AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            residentKey: AuthenticatorSelectionCriteria::RESIDENT_KEY_REQUIREMENT_REQUIRED,
        );

        try {
            // Options for registration keys, the frontend will use these options to display the UI for registration keys
            // challenge is a random string used to prevent replay attacks
            $options = new PublicKeyCredentialCreationOptions(
                rp: $relatedPartyEntity,
                user: $userEntity,
                challenge: Str::random(),
                authenticatorSelection: $authenticatorSelectionCriteria
            );
        } catch (InvalidDataException $e) {
            Log::error(__('Unable to create Webauthn registration options'), [
                'user_id'   => $request->user()->id,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => __('Unable to create key registration options, please try again later.'),
            ], 400);
        }

        try {
            // Serialize the $options object, converting it to a JSON string
            $optionsJson = $serializer->toJson($options);
        } catch (SerializerExceptionInterface  $e) {
            Log::error(__('Webauthn registration option serialization failed.'), [
                'user_id'   => $request->user()->id,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => __('Server error occurred, unable to serialize registration options.'),
            ], 400);
        }

        // Store $options in the Flash Session so we can use it in the next step
        // When the user returns the public key credential, we need to retrieve $options from the Session to verify the user's credential
        Session::flash('passkey-registration-options', $optionsJson);

        return $optionsJson;
    }
}
