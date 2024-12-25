<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listeroles = Role::orderByDesc('created_at')->get();
        return view('roles.index', compact('listeroles'));
    }

    public function store(Request $request)
    {

        // Vérifier si product_id existe dans la requête
        $roleId = $request->input('role_id');

        if ($roleId) {
            // Si product_id existe, on modifie le produit
            $role = Role::find($roleId);

            // Si le produit n'existe pas, le créer
            if (!$role) {
                // Créer un nouveau produit
                return $this->createRole($request);
            }

            // Si le produit existe, procéder à la mise à jour
            return $this->updateRole($role, $request);
        } else {
            // Si product_id est absent, on crée un nouveau produit
            return $this->createRole($request);
        }
    }


    private function updateRole($role, Request $request)
    {
        $data = [
            'libellerole' => $request->libellerole,
        ];
        // Mise à jour de l'utilisateur
        $role->update($data);
        return response()->json(['message' => 'Role mis à jour avec succès', 'role' => $role], 200);
    }




    private function createRole(Request $request)
    {
        $role = Role::create([
            'libellerole' => $request->libellerole, // Correspond au frontend
        ]);

        return response()->json(['message' => 'Role créé avec succès', 'role' => $role], 201);
    }





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
            $role = Role::findOrFail($id);

            // Supprimer le produit
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role supprimé avec succès.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role introuvable.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du role.',
            ], 500);
        }
    }
}
