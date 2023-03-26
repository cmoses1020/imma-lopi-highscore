@extends('layouts.app')

@section('content')

    <div class="flex flex-col items-center justify-center min-h-screen">
        <div class="max-w-lg w-full">
            <livewire:high-score-board maxRank="100" :paginate="true" />
        </div>
    </div>

@endsection