{{--
    Vue : resources/views/notifications/index.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title', 'Notifications')
@section('page-title', 'Boîte de réception')

@section('topbar-actions')
    <form method="POST" action="{{ route('notifications.toutLire') }}" class="d-inline">
        @csrf
        <button class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-check2-all me-1"></i>Tout marquer lu
        </button>
    </form>
    <form method="POST" action="{{ route('notifications.viderLues') }}" class="d-inline ms-1"
          onsubmit="return confirm('Supprimer toutes les notifications lues ?')">
        @csrf
        <button class="btn btn-sm btn-outline-danger">
            <i class="bi bi-trash me-1"></i>Vider les lues
        </button>
    </form>
@endsection

@section('content')

@php
    $nonLues = $notifications->where('lue', false)->count();
@endphp

@if($nonLues > 0)
<div class="alert d-flex align-items-center gap-2 mb-4"
     style="background:#FFF3E0;border-left:4px solid #E8650A;border-radius:10px;">
    <i class="bi bi-bell-fill" style="color:#E8650A;"></i>
    <span><strong>{{ $nonLues }}</strong> notification(s) non lue(s)</span>
</div>
@endif

<div class="table-card">
    @forelse($notifications as $notif)
    <div class="d-flex align-items-start gap-3 p-3 border-bottom
                {{ !$notif->lue ? 'bg-light' : '' }}"
         style="transition:.2s;">

        {{-- Icône --}}
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
             style="width:44px;height:44px;
                background:{{ match($notif->couleur) {
                    'success' => '#D1FAE5',
                    'danger'  => '#FEE2E2',
                    'warning' => '#FEF3C7',
                    default   => '#DBEAFE'
                } }};
                color:{{ match($notif->couleur) {
                    'success' => '#065F46',
                    'danger'  => '#991B1B',
                    'warning' => '#92400E',
                    default   => '#1E40AF'
                } }};">
            <i class="bi {{ $notif->icone }} fs-5"></i>
        </div>

        {{-- Contenu --}}
        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2">
                <strong style="font-size:.92rem;">{{ $notif->titre }}</strong>
                @if(!$notif->lue)
                    <span class="badge rounded-pill"
                          style="background:#E8650A;font-size:.65rem;">Nouveau</span>
                @endif
            </div>
            <div class="text-muted" style="font-size:.85rem;">{{ $notif->message }}</div>
            <div class="text-muted mt-1" style="font-size:.75rem;">
                <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-1 flex-shrink-0">
            @if($notif->lien)
                <a href="{{ route('notifications.lire', $notif) }}"
                   class="btn btn-sm btn-light" title="Ouvrir">
                    <i class="bi bi-arrow-right"></i>
                </a>
            @endif
            <form method="POST" action="{{ route('notifications.destroy', $notif) }}" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-light text-danger" title="Supprimer">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-bell-slash fs-1 d-block mb-3" style="opacity:.3;"></i>
        <p class="mb-0 fw-semibold">Aucune notification</p>
        <small>Vous êtes à jour !</small>
    </div>
    @endforelse
</div>

@if($notifications->hasPages())
<div class="mt-3">{{ $notifications->links() }}</div>
@endif

@endsection
