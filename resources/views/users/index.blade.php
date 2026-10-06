{{--
    Vue : resources/views/users/index.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Utilisateurs')
@section('page-title','Utilisateurs')

@section('topbar-actions')
    <a href="{{ route('users.create') }}" class="btn btn-orange btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nouvel utilisateur
    </a>
@endsection

@section('content')
<div class="table-card">
    <div class="p-3 border-bottom d-flex gap-2 align-items-center">
        <i class="bi bi-person-gear text-primary fs-5"></i>
        <span class="fw-bold">Gestion des utilisateurs</span>
        <span class="badge bg-secondary">{{ $users->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Créé le</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                 style="width:34px;height:34px;background:#E8650A;font-size:.8rem;">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </div>
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge bg-secondary" style="font-size:.65rem;">Vous</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        <span class="badge" style="background:#EDE9FE;color:#5B21B6;border-radius:6px;">
                            {{ $user->role_libelle }}
                        </span>
                    </td>
                    <td class="text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-light">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline"
                              onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Aucun utilisateur</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="p-3 border-top">{{ $users->links() }}</div>
    @endif
</div>
@endsection
