<x-admin-layout title="Create Invoice">
    <div class="px-4 sm:px-6 lg:px-8"
         x-data="{
            addItemModalOpen: false,
            description: '',
            quantity: 0,
            amount: 0,
            subtotal: 0,
            invoiceSubtotal: 0,
            items: [],
            issuedOn: null,
            dueOn: null,
            customer: '',
            hourlyRates: {{ json_encode($hourlyRates) }},
            setHourlyRate() {
                 this.amount = this.hourlyRates[this.customer];
            },
            updateDueOn() {
                const date = new Date(this.issuedOn);
                date.setDate(date.getDate() + 7);
                this.dueOn = date.toISOString().split('T')[0];
            },
            updateItemSubtotal() {
                this.subtotal = this.quantity * this.amount;
            },
            addItemToInvoice() {
                this.items.push({
                    description: this.description,
                    amount: this.amount,
                    quantity: this.quantity,
                    subtotal: this.subtotal
                });

                this.invoiceSubtotal += this.subtotal;
                this.resetItems();
                this.setHourlyRate();
                this.addItemModalOpen = false;
            },
            resetItems() {
                this.description = '';
                this.quantity = 0;
                this.amount = 0;
                this.subtotal = 0;
            }
         }"
         @keydown.escape="addItemModalOpen = false">
        <form method="POST" action="{{ route('admin.invoices.store') }}">
            @csrf

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Customer:</span>
                    <select type="text" x-model="customer" @change="setHourlyRate()" name="customer_id" id="customer_id" class="block @error('customer_id') border-red-500 @enderror w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        <option disabled selected value="">Select A Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </label>
                @error('customer_id')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Issued On:</span>
                    <input type="date" @change="updateDueOn" x-model="issuedOn" name="issued_on"
                           class="block w-full @error('issued_on') border-red-500 @enderror mt-1 rounded-md"
                           placeholder="" value="{{old('issued_on')}}" />
                </label>
                @error('issued_on')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Due On:</span>
                    <input type="date" x-model="dueOn" name="due_on"
                           class="block w-full @error('due_on') border-red-500 @enderror mt-1 rounded-md"
                           placeholder="" value="{{old('due_on')}}" />
                </label>
                @error('due_on')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block">
                    <button @click="addItemModalOpen = !addItemModalOpen" type="button" class="text-white bg-indigo-600 rounded text-sm px-3 py-1.5" id="addItemButton">Add Item</button>
                    <table class="min-w-full border-separate mt-4">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell">Description</th>
                                <th class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell">Unit Price</th>
                                <th class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell">Quantity</th>
                                <th class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                        <input x-bind:name="`items[${index}][description]`" type="text" readonly x-model="item.description">
                                    </td>
                                    <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                        <input x-bind:name="`items[${index}][amount]`" type="number" readonly x-model="item.amount">
                                    </td>
                                    <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                        <input x-bind:name="`items[${index}][quantity]`" type="number" readonly x-model="item.quantity">
                                    </td>
                                    <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                        <input type="number" readonly x-model="item.subtotal">
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </label>
            </div>

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Invoice Total:</span>
                    <input type="number" x-model="invoiceSubtotal" readonly class="block w-full mt-1 rounded-md"/>
                </label>
            </div>

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Notes:</span>
                    <textarea class="block w-full mt-1 rounded-md" name="notes"
                              rows="3">{{ old('notes') }}</textarea>
                </label>
                @error('notes')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit"
                    class="text-white bg-indigo-600 rounded text-sm px-5 py-2.5">Submit</button>

        </form>

        <div x-show="addItemModalOpen" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
            <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                <div x-cloak @click="addItemModalOpen = false" x-show="addItemModalOpen"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"
                ></div>

                <div x-cloak x-show="addItemModalOpen"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl"
                >
                    <div class="flex items-center justify-between space-x-4">
                        <h1 class="text-xl font-medium text-gray-800 ">Add Invoice Item</h1>

                        <button @click="addItemModalOpen = false" class="text-gray-600 focus:outline-none hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>

                    <form class="mt-5">
                        <div class="mb-6">
                            <label for="description" class="block text-sm text-gray-700">Description</label>
                            <input id="description" x-model="description" type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
                        </div>

                        <div class="mb-6">
                            <label for="amount" class="block text-sm text-gray-700">Unit Price</label>
                            <input id="amount" x-model="amount" type="number" x-on:change="updateItemSubtotal" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
                        </div>

                        <div class="mb-6">
                            <label for="quantity" class="block text-sm text-gray-700">Quantity</label>
                            <input id="quantity" x-model="quantity" type="number" x-on:change="updateItemSubtotal" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
                        </div>

                        <div class="mb-6">
                            <label for="subtotal" class="block text-sm text-gray-700">Subtotal</label>
                            <input id="subtotal" x-model="subtotal" readonly type="number" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" @click="addItemToInvoice" class="px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-500 rounded-md dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:bg-indigo-700 hover:bg-indigo-600 focus:outline-none focus:bg-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50">
                                Add Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
