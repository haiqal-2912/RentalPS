@extends(auth()->check() ? 'layout.app' : 'layout.public')
@section('title', 'Jadwal Unit - PsOne')

@section('content')
    @livewire('jadwal-slot-component')
@endsection
