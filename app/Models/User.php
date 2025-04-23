<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PragmaRX\Google2FA\Google2FA;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'codereset',
        'email',
        'password',
        'role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // Méthode pour générer le secret de 2FA
    public function generateTwoFactorSecret()
    {
        $google2fa = new Google2FA();
        return $google2fa->generateSecretKey(); // Génère un secret unique
    }

    // Méthode pour vérifier le code 2FA
    public function verifyTwoFactorCode($code)
    {
        $google2fa = new Google2FA();
        return $google2fa->verifyKey($this->two_factor_secret, $code); // Vérifie si le code est correct
    }

    // Méthode pour générer les codes de récupération
    public function generateTwoFactorRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(str_random(10)); // Code de récupération aléatoire
        }

        return implode(',', $codes);
    }
}

