@php
    $links = [
        [
            'name' => 'Home',
            'href' => '/',
            'current' => true,
        ],
        [
            'name' => 'Schematics',
            'href' => '/schematics',
            'current' => false,
        ],
    ];
@endphp


<nav class="bg-base-300 px-2 sm:px-4 py-2.5  shadow-pink-500 shadow-[inset 0px 0px 0px 2px]">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <x-animated-logo-svg class="flex items-center justify-center w-8  rounded-full bg-primary-500" />
        </a>
        <div class="flex md:order-2">
            @auth
                <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    <button type="button" class="flex text-sm rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 "
                        id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                        data-dropdown-placement="bottom">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="User avatar" />
                    </button>
                    <!-- Dropdown menu -->
                    <div class="z-50 hidden my-4 text-base list-none divide-y divide-gray-100 rounded-lg shadow0 dark:divide-gray-600"
                        id="user-dropdown">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-gray-900 dark:text-white">
                                {{ Auth::user()->name }}
                            </span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <button data-collapse-toggle="navbar-user" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:focus:ring-gray-600"
                        aria-controls="navbar-user" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="text-sm text-gray-700 rounded-lg md:bg-transparent md:text-blue-700 md:p-2 dark:text-white">{{ __('Log in') }}</a>
                <a href="{{ route('register') }}"
                    class="text-sm text-gray-700 rounded-lg md:bg-transparent md:text-blue-700 md:p-2 dark:text-white">{{ __('Register') }}</a>

            @endauth
            <button data-collapse-toggle="mobile-menu" type="button"
                class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="mobile-menu" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>
        </div>
        <div class="hidden w-full md:block md:w-auto" id="mobile-menu">
            <ul class="flex flex-col mt-4 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium">
                @foreach ($links as $link)
                    <li>
                        <a href="{{ $link['href'] }}" wire:navigate.hover
                            class="block py-2 pr-4 pl-3 text-gray-700 rounded  md:p-0 text-white hover:text-pink-500"
                            aria-current="{{ $link['current'] ? 'page' : '' }}">{{ $link['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
