<div class="relative z-40 md:hidden" role="dialog" aria-modal="true" x-show="open">
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>

    <div class="fixed inset-0 z-40 flex">
        <div class="relative flex w-full max-w-xs flex-1 flex-col bg-indigo-700">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button x-on:click="open = ! open" type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
                <div class="flex flex-shrink-0 items-center px-4 text-indigo-100">
                    <svg class="mr-3 h-6 w-6 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Mark Railton
                </div>
                <nav class="mt-5 space-y-1 px-2">
                    <a href="{{ route('admin.dashboard') }}" class="{{ (request()->is('admin')) ? 'bg-indigo-800' : '' }} text-indigo-100 hover:text-white hover:bg-indigo-600 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <svg class="mr-4 h-6 w-6 flex-shrink-0 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.articles.list') }}" class="{{ (request()->is('admin/articles*')) ? 'bg-indigo-800' : '' }} text-indigo-100 hover:text-white hover:bg-indigo-600 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <svg class="mr-4 h-6 w-6 flex-shrink-0 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        Articles
                    </a>
                </nav>
            </div>
            <div class="flex flex-shrink-0 border-t border-indigo-800 p-4">
                <div class="group block w-full flex-shrink-0">
                    <div class="flex items-center">
                        <div class="ml-3">
                            <a class="text-xs font-medium text-indigo-200 hover:text-white" href="{{ route('index') }}">View Site</a>
                        </div>
                        <div class="ml-3">
                            <a class="text-xs font-medium text-indigo-200 hover:text-white" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-14 flex-shrink-0" aria-hidden="true">
            <!-- Force sidebar to shrink to fit close icon -->
        </div>
    </div>
</div>

<!-- Static sidebar for desktop -->
<div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
    <!-- Sidebar component, swap this element with another sidebar if you like -->
    <div class="flex min-h-0 flex-1 flex-col bg-indigo-700">
        <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
            <div class="flex flex-shrink-0 items-center px-4 text-indigo-100">
                <svg class="mr-3 h-6 w-6 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Mark Railton
            </div>
            <nav class="mt-5 flex-1 space-y-1 px-2">
                <a href="{{ route('admin.dashboard') }}" class="{{ (request()->is('admin')) ? 'bg-indigo-800' : '' }} text-indigo-100 hover:text-white hover:bg-indigo-600 group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.articles.list') }}" class="{{ (request()->is('admin/articles*')) ? 'bg-indigo-800' : '' }} text-indigo-100 hover:text-white hover:bg-indigo-600 group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>

                    Articles
                </a>
            </nav>
        </div>
        <div class="flex flex-shrink-0 border-t border-indigo-800 p-4">
            <div class="group block w-full flex-shrink-0">
                <div class="flex items-center">
                    <div class="ml-3">
                        <a class="text-xs font-medium text-indigo-200 hover:text-white" href="{{ route('index') }}">View Site</a>
                    </div>
                    <div class="ml-3">
                        <a class="text-xs font-medium text-indigo-200 hover:text-white" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
