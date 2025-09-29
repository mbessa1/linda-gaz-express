<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Liste utilisateurs
    public function index()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // Supprimer utilisateur
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success','Utilisateur supprimé');
    }
}
