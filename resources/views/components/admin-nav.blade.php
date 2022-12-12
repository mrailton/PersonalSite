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
                <div class="flex flex-shrink-0 items-center px-4">
                    <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=300" alt="Your Company">
                </div>
                <nav class="mt-5 space-y-1 px-2">
                    <!-- Current: "bg-indigo-800 text-white", Default: "text-white hover:bg-indigo-600 hover:bg-opacity-75" -->
                    <a href="#" class="bg-indigo-800 text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <!-- Heroicon name: outline/home -->
                        <svg class="mr-4 h-6 w-6 flex-shrink-0 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>
                </nav>
            </div>
            <div class="flex flex-shrink-0 border-t border-indigo-800 p-4">
                <a href="#" class="group block flex-shrink-0">
                    <div class="flex items-center">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-indigo-200 group-hover:text-white">Logout</p>
                        </div>
                    </div>
                </a>
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
            <div class="flex flex-shrink-0 items-center px-4">
                <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=300" alt="Your Company">
            </div>
            <nav class="mt-5 flex-1 space-y-1 px-2">
                <!-- Current: "bg-indigo-800 text-white", Default: "text-white hover:bg-indigo-600 hover:bg-opacity-75" -->
                <a href="#" class="bg-indigo-800 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <!-- Heroicon name: outline/home -->
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>


            </nav>
        </div>
        <div class="flex flex-shrink-0 border-t border-indigo-800 p-4">
            <a href="#" class="group block w-full flex-shrink-0">
                <div class="flex items-center">
                    <div class="ml-3">
                        <p class="text-xs font-medium text-indigo-200 group-hover:text-white">Logout</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
