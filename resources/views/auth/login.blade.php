@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen px-4">
    <div class="w-full max-w-md p-8 rounded-2xl shadow-2xl border" style="background:rgba(13,32,68,0.9);border-color:rgba(240,180,41,0.4);">
        <h2 class="text-3xl font-bold text-center mb-6" style="color:#f0b429;">Connexion</h2>
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            @if($errors->any())
                <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background:rgba(239,68,68,0.2);color:#ef4444;">
                    @foreach($errors->all() as $error)<p>❌ {{ $error }}</p>@endforeach
                </div>
            @endif
            <div class="mb-4">
                <label class="block font-semibold mb-1" style="color:#f0b429;">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-xl px-4 py-3 focus:outline-none" style="background:#0a1628;border-color:#f0b429;color:#f0b429;">
            </div>
            <div class="mb-6">
                <label class="block font-semibold mb-1" style="color:#f0b429;">Mot de passe</label>
                <input type="password" name="password" required class="w-full border rounded-xl px-4 py-3 focus:outline-none" style="background:#0a1628;border-color:#f0b429;color:#f0b429;">
            </div>
            <button type="submit" class="w-full py-3 rounded-full font-bold text-lg transition hover:opacity-80" style="background:#f0b429;color:#0a1628;">
                Se connecter
            </button>
            <p class="text-center mt-4 text-sm" style="color:rgba(240,180,41,0.7);">
                Pas encore de compte ? <a href="{{ route('register') }}" style="color:#f0b429;font-weight:bold;">S inscrire</a>
            </p>
        </form>
    </div>
</div>
@endsection
