<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    // Formulaire login

    public function loginForm() {
        dd('toktok');
        return view( 'auth.login' );
    }

    // Connexion

    public function login( Request $request ) {
        $credentials = $request->only( 'email', 'password' );

        if ( Auth::attempt( $credentials ) ) {
            $user = Auth::user();

            switch ( $user->role ) {
                case 'client':
                return redirect()->route( 'catalogue' );
                case 'vendeur':
                return redirect()->route( 'vendeur.stats' );
                case 'livreur':
                return redirect()->route( 'livreur.commandes' );
                case 'admin':
                return redirect()->route( 'admin.users' );
                default:
                return redirect( '/' );
                // fallback
            }
        }

        return back()->with( 'error', 'Email ou mot de passe incorrect' );
    }

    // Formulaire inscription

    public function registerForm() {
        return view( 'auth.register' );
    }

    // Inscription

    public function register( Request $request ) {
        $request->validate( [
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed',
            'role' => 'required',
        ] );

        User::create( [
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make( $request->password ),
            'role' => $request->role,
            'latitude' => $request->latitude ?? null,
            'longitude' => $request->longitude ?? null,
            'ville' => $request->ville ?? null,
            'quartier' => $request->quartier ?? null,
        ] );

        return redirect( '/login' )->with( 'success', 'Compte créé avec succès' );
    }

    // Déconnexion

    public function logout() {
        Auth::logout();
        return redirect( '/' );
    }
}
