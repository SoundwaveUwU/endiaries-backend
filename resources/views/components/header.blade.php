<div class="container mx-auto flex items-center justify-between h-16 mb-2 w-full text-white">
    <div class="flex items-center">
        <a href="{{ route('home') }}" class="mr-2">NewTumbler</a>
        <a href="{{ route('home') }}" >
            <span class="fa fa-home"></span>
        </a>
    </div>
    <div class="flex items-center">
        @guest
            <x-button href="/account/login"
                        icon="sign-in-alt">
                Sign in
            </x-button>
            <x-button href="/account/create"
                        background="bg-blue-500"
                        text="text-white"
                        icon="user-plus">
                Create account
            </x-button>
        @else
            Here will be blog switcher
            <form action="{{ route('logout') }}">
                <x-button icon="sign-out-alt">
                    Sign out
                </x-button>
            </form>
        @endguest
    </div>
</div>
