<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Serializer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\Exception\InvalidDataException;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;

class GeneratePasskeyRegisterOptionsController extends Controller
{
    /**
     * @throws ExceptionInterface
     * @throws InvalidDataException
     */
    public function __invoke(Request $request): string
    {
        // Erstellen einer Relying Party Entität 
        // Die ID ist der Domainname der Website
        $relatedPartyEntity = new PublicKeyCredentialRpEntity(
            name: config('app.name'),
            id: Uri::of(config('app.url'))->host()
        );

        // Erstellen einer Benutzerentität
        // Die ID muss eindeutig sein, normalerweise die Benutzer-ID oder UUID
        // Bitte beachten Sie, dass der Name keine sensiblen Benutzerinformationen wie E-Mail oder Telefonnummer enthalten darf
        $userEntity = new PublicKeyCredentialUserEntity(
            name: $request->user()->name,
            id: (string) $request->user()->id,
            displayName: $request->user()->name
        );

        // Konfiguration zur Gerätevalidierung
        // Keine Präferenz für eine Plattform, und es wird vorausgesetzt, dass der Schlüssel des Benutzers auffindbare Anmeldeinformationen unterstützt
        // Derzeit sind auffindbare Anmeldeinformationen Mainstream. Wenn dies hier nicht erzwungen wird, kann Ihr YubiKey nicht verwendet werden
        $authenticatorSelectionCriteria = AuthenticatorSelectionCriteria::create(
            authenticatorAttachment: AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_NO_PREFERENCE,
            userVerification: AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            residentKey: AuthenticatorSelectionCriteria::RESIDENT_KEY_REQUIREMENT_REQUIRED,
        );

        // Optionen für die Registrierung des Schlüssels. Das Frontend verwendet diese Optionen, um die UI für die Registrierung des Schlüssels anzuzeigen
        // Challenge ist eine zufällige Zeichenkette, die verwendet wird, um Replay-Angriffe zu verhindern
        $options = new PublicKeyCredentialCreationOptions(
            rp: $relatedPartyEntity,
            user: $userEntity,
            challenge: Str::random(),
            authenticatorSelection: $authenticatorSelectionCriteria
        );

        // Serialisierung des $options-Objekts und Umwandlung in eine JSON-Zeichenkette
        $options = Serializer::make()->toJson($options);

        // Speichern von $options in der Flash-Session, damit wir es im nächsten Schritt verwenden können
        // Wenn der Benutzer ein Public-Key-Zertifikat zurückgibt, müssen wir $options aus der Session abrufen, um das Zertifikat des Benutzers zu validieren
        Session::flash('passkey-registration-options', $options);

        return $options;
    }
}
