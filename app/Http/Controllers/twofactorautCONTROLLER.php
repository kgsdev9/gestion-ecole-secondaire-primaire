<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class twofactorautCONTROLLER extends Controller
{
//     use BaconQrCode\Writer;
// use PragmaRX\Google2FA\Google2FA;

// public function showTwoFactorForm()
// {
//     $google2fa = new Google2FA();
//     $user = auth()->user();
//     $secret = $user->generateTwoFactorSecret();

//     // Enregistrer le secret dans la base de données
//     $user->two_factor_secret = $secret;
//     $user->save();

//     // Générer le QR Code
//     $google2fa_url = $google2fa->getQRCodeUrl(
//         'Nom de ton application',
//         $user->email,
//         $secret
//     );

//     $writer = new Writer();
//     $qrCode = $writer->writeString($google2fa_url);

//     return view('two_factor.show', compact('qrCode'));
// }

// public function enableTwoFactor()
// {
//     $user = auth()->user();
//     $user->two_factor_enabled = true;
//     $user->save();

//     return redirect()->route('dashboard')->with('status', 'Authentification à deux facteurs activée!');
// }


// public function verifyTwoFactorCode(Request $request)
// {
//     $user = auth()->user();

//     // Vérifier si l'authentification à deux facteurs est activée
//     if ($user->two_factor_enabled && !$user->verifyTwoFactorCode($request->input('code'))) {
//         return back()->withErrors(['code' => 'Le code de sécurité est invalide.']);
//     }

//     // Code valide, permettre la connexion
//     return redirect()->route('dashboard');
// }


}
