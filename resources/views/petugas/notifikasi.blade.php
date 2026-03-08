@extends('layouts.petugas')

@section('title', 'Notifikasi')

@section('content')

<h3>Notifikasi</h3>

@if($notifikasi->isEmpty())
    <p>Tidak ada notifikasi.</p>
@endif

<ul class="list-group">
@foreach($notifikasi as $n)
    <li class="list-group-item">
        {{ $n->pesan }}
        <br>
        <small class="text-muted">
            {{ $n->created_at->diffForHumans() }}
        </small>
    </li>
@endforeach
</ul>

@endsection
