@extends('layout.app')
@section('title', 'Menu - PsOne')

@section('content')
    {{-- Kasir: kelola menu (CRUD). Pelanggan: lihat menu tersedia & memesan. --}}
    @if (auth()->user()->role === 'kasir')
        @livewire('menu-component')
    @else
        @livewire('pesan-menu-component')
    @endif
@endsection
