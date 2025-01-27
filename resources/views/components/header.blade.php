<header class="bg-indigo-600">
    <nav class="border-b">
        <div x-data="{showMenu : false}" class="container max-w-screen-2xl mx-auto flex justify-between h-14 sm:text-gray-700 md:text-white">
            <div class="flex items-center text-white mr-6">
                <a class="flex items-center text-white pl-4 " href="{{ route('index') }}">
                    <svg class="h-6 w-6 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>

                    <span class="mx-3 text-xl">Mark Railton</span>
                </a>
            </div>

            <button @click="showMenu = !showMenu" class="block md:hidden text-gray-700 p-2 rounded hover:border focus:border focus:bg-gray-400 my-2 mr-5" type="button" aria-controls="navbar-main" aria-expanded="false" aria-label="Toggle navigation">
                <svg class="w-5 h-5" fill="none" stroke="white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>

            <ul class="md:flex text-base mr-3 origin-top" :class="{ 'block absolute top-14 border-b bg-white w-full p-2': showMenu, 'hidden': !showMenu}" id="navbar-main" x-cloak>
                <li class="{{ (request()->is('/')) ? 'bg-white text-indigo-600' : '' }} px-3 cursor-pointer hover:bg-white flex items-center hover:text-indigo-600" :class="showMenu && 'py-1'">
                    <a href="{{ route('index') }}">Home</a>
                </li>

                <li class="{{ (request()->is('blog*')) ? 'bg-white text-indigo-600' : '' }} px-3 cursor-pointer hover:bg-white flex items-center hover:text-indigo-600" :class="showMenu && 'py-1'">
                    <a href="{{ route('articles.list') }}">Articles</a>
                </li>

                @auth()
                    <li class="px-3 cursor-pointer hover:bg-white flex items-center hover:text-indigo-600" :class="showMenu && 'py-1'">
                        <a href="{{ route('filament.admin.pages.dashboard') }}">Admin</a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>
</header>
