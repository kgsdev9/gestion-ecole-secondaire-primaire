<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GenerateCodeService
{
    /**
     * Génère un code numérique unique pour une table et une colonne données.
     *
     * @param string $table Le nom de la table
     * @param string $column Le nom de la colonne
     * @param int $length La longueur du code (par défaut: 5)
     * @return string Le code unique
     */
    public function generateUniqueCode(string $table, string $column, int $length = 5): string
    {
        do {
            $code = $this->generateNumericCode($length);
        } while ($this->codeExists($table, $column, $code));

        return $code;
    }

    /**
     * Vérifie si un code existe déjà dans la table/colonne spécifiée.
     *
     * @param string $table
     * @param string $column
     * @param string $code
     * @return bool
     */
    protected function codeExists(string $table, string $column, string $code): bool
    {
        return DB::table($table)->where($column, $code)->exists();
    }

    /**
     * Génère un code numérique aléatoire.
     *
     * @param int $length
     * @return string
     */
    protected function generateNumericCode(int $length): string
    {
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}
