<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
        $users = User::with('role')->orderByDesc('created_at')->get();
        $listeroles = Role::all();
        return view('users.liste', [
            'users' => $users,
            'listeroles' => $listeroles
        ]);
    }


    public function store(Request $request)
    {

        // Vérifier si product_id existe dans la requête
        $userId = $request->input('user_id');

        if ($userId) {
            // Si product_id existe, on modifie le produit
            $user = User::find($userId);

            // Si le produit n'existe pas, le créer
            if (!$user) {
                // Créer un nouveau produit
                return $this->createUser($request);
            }

            // Si le produit existe, procéder à la mise à jour
            return $this->updateUser($user, $request);
        } else {
            // Si product_id est absent, on crée un nouveau produit
            return $this->createUser($request);
        }
    }


    private function updateUser($user, Request $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        // Mise à jour de l'utilisateur
        $user->update($data);

        $user->load('role');
        return response()->json(['message' => 'Utilisateur mis à jour avec succès', 'user' => $user], 200);
    }



    private function createUser(Request $request)
    {
        // Vérifier si l'email existe déjà
        if (User::where('email', $request->email)->exists()) {
            // Si l'email existe, générer un nouvel email unique
            $email = $this->generateUniqueEmail($request->email);
        } else {
            $email = $request->email;
        }

        // Créer l'utilisateur avec l'email (modifié ou original)
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password) ?? Hash::make(12345),
        ]);

        $user->load('role');

        return response()->json(['message' => 'Utilisateur créé avec succès', 'user' => $user], 201);
    }

    // Méthode pour générer un email unique
    private function generateUniqueEmail($originalEmail)
    {
        $emailParts = explode('@', $originalEmail);
        $newEmail = $emailParts[0] . '-' . uniqid() . '@' . $emailParts[1];

        // Vérifier si l'email généré existe déjà, sinon retourner l'email
        while (User::where('email', $newEmail)->exists()) {
            $newEmail = $emailParts[0] . '-' . uniqid() . '@' . $emailParts[1];
        }

        return $newEmail;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Rechercher le produit par ID
            $user = User::findOrFail($id);

            // Supprimer le produit
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produit introuvable.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du produit.',
            ], 500);
        }
    }
}
