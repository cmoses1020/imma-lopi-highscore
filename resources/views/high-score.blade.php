@extends('layouts.app')

@section('content')

    <div class="flex flex-col mt-20 items-center min-h-screen">
        <div class="max-w-lg w-full">
            <livewire:high-score-board maxRank="20" />
        </div>
    </div>

@endsection