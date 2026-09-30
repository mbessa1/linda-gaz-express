@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <h3 class="text-2xl font-bold text-forestGreen mb-6">👥 Gestion des utilisateurs</h3>

    <div class="overflow-x-auto shadow-lg rounded-2xl bg-white">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-solarYellow text-gray-900 uppercase text-xs tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-3">Nom</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Rôle</th>
                    <th scope="col" class="px-6 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr class="border-b hover:bg-skyBlue/20 transition">
                    <td class="px-6 py-4 font-semibold">{{ $u->name }}</td>
                    <td class="px-6 py-4">{{ $u->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            @if($u->role === 'admin') bg-blue-100 text-blue-700
                            @elseif($u->role === 'vendeur') bg-blue-100 text-blue-700
                            @elseif($u->role === 'client') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form method="POST" action="/user/{{ $u->id }}/delete" onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                            @csrf
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
