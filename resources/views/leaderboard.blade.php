@extends('layouts.base')

@section('body')
    <div>
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="{{ route('home') }}">
                <x-logo class="w-auto h-16 mx-auto text-lopi-purple-600" />
            </a>

            <div class="text-center">
                <a href="{{ route('home') }}" class="text-sm font-medium text-lopi-purple-600 hover:text-lopi-purple-400 focus:outline-none focus:underline transition ease-in-out duration-150">
                    Poke the bunny more
                </a>
            </div>

            <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
                Leader Board
            </h2>
        </div>
        <div class="relative mx-auto mt-2 mb-20 max-w-4xl overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 bg-lopi-purple-700">
                <thead class="text-xs text-white uppercase">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Display Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Account Created
                        </th>
                        <th scope="col" class="px-6 py-3 text-right">
                            Lopi Count
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr @class([
                            'bg-white border-b' => $loop->odd,
                            'bg-lopi-purple-100 border-b' => $loop->even,
                        ])>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $user->name }}
                            </th>
                            <td class="px-6 py-4">
                                {{-- date and time for created_at --}}
                                {{ $user->created_at->format('M j, Y') }}
                                {{ $user->created_at->format('g:i A') }}
                            </td>
                            <td class="px-6 py-4 tabular-nums text-right">
                                {{ $user->lopi_count }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="max-w-4xl mx-auto fixed inset-x-0 bottom-0 bg-gray-50">
                {{ $users->links() }}
            </div>
        </div>

    </div>
@endsection