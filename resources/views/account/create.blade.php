@extends('layouts.default')

@section('content')
    <div class="flex flex-col flex-grow justify-center items-center">
        <x-card class="w-1/3 mx-auto p-5">
            <form action="{{ route('register') }}"
                  class="flex flex-col"
                  method="POST">
                @csrf

                <x-input autocomplete="username"
                         type="email"
                         placeholder="me@example.com"
                         required
                         name="email">{{ __('Email') }}</x-input>
                <x-input autocomplete="new-password"
                         type="password"
                         required
                         name="password">{{ __('Password') }}</x-input>
                <div class="-mx-1 mt-4 justify-center flex">
                    <x-button type="submit"
                              class="w-1/2"
                              background="bg-blue-500"
                              text="text-white"
                              icon="sign-in-alt">
                        {{ __('Sign up') }}
                    </x-button>
                </div>
            </form>
        </x-card>
        <div class="text-center text-white my-3">
            {{ __('OR') }}
        </div>
        <x-card class="w-1/3 mx-auto p-5 grid grid-cols-2">
            <x-button :icon="['fab', 'google']"
                      size="large">
                Google
            </x-button>
            <x-button :icon="['fab', 'twitter']"
                      size="large"
                      background="bg-blue-500">
                Twitter
            </x-button>
            <x-button :icon="['fab', 'facebook']"
                      size="large"
                      text="text-white"
                      background="bg-indigo-500">
                Facebook
            </x-button>
            <x-button :icon="['fab', 'vk']"
                      size="large"
                      text="text-white"
                      background="bg-blue-600">
                VK
            </x-button>
        </x-card>
    </div>
@endsection
