<x-admin-layout title="">
    <div
        class="w-full bg-white lg:mb-20 lg:shadow-xl xl:mt-02 xl:mb-20 xl:shadow-xl print:transform print:scale-90"
        x-data="{
            showMarkInvoiceSentModal: false,
            showAddPaymentModal: false,
            closeModals() {
                this.showMarkInvoiceSentModal = false;
                this.showAddPaymentModal = false;
            }
        }"
        @keydown.escape="closeModals()"
    >
        <div class="flex flex-col items-center px-8 pt-20 text-lg text-center bg-white border-t-8 border-indigo-700 md:block lg:block xl:block print:block md:items-start lg:items-start xl:items-start print:items-start md:text-left lg:text-left xl:text-left print:text-left print:pt-8 print:px-2 md:relative lg:relative xl:relative print:relative">
            <div class="flex flex-row mt-12 mb-2 ml-0 text-2xl font-bold md:text-3xl lg:text-4xl xl:text-4xl print:text-2xl lg:ml-12 xl:ml-12">INVOICE
                <div class="text-green-700">
                    <span class="mr-4 text-sm">■ </span> #
                </div>
                <span class="text-gray-500">{{ $invoice->id }}</span>
            </div>
            <div class="flex flex-col lg:ml-12 xl:ml-12 print:text-sm">
                <span>Issue date: {{ $invoice->issued_on->format('d/m/Y') }}</span>
                <span>Paid date: Not Paid</span>
                <span>Due date: {{ $invoice->due_on->format('d/m/Y') }}</span>
            </div>
            @switch($invoice->status)
                @case(\App\Enums\InvoiceStatus::Draft)
                    <div @click="showMarkInvoiceSentModal = true" class="hover:cursor-pointer">
                        <x-invoice-status colour="indigo" :status="$invoice->status" />
                    </div>
                    @break
                @case(\App\Enums\InvoiceStatus::Sent)
                    <div @click="showAddPaymentModal = true" class="hover:cursor-pointer">
                        <x-invoice-status colour="orange" :status="$invoice->status" />
                    </div>
                    @break
                @case(\App\Enums\InvoiceStatus::Overdue)
                    <x-invoice-status colour="red" :status="$invoice->status" />
                    @break
                @case(\App\Enums\InvoiceStatus::Paid)
                    <x-invoice-status colour="green" :status="$invoice->status" />
                    @break
            @endswitch


            <contract class="flex flex-col m-12 text-center lg:m-12 md:flex-none md:text-left md:relative md:m-0 md:mt-16 lg:flex-none lg:text-left lg:relative xl:flex-none xl:text-left xl:relative print:flex-none print:text-left print:relative print:m-0 print:mt-6 print:text-sm">
                <span class="font-extrabold md:hidden lg:hidden xl:hidden print:hidden">FROM</span>
                <from class="flex flex-col">
                    <span class="font-medium">Mark Railton Consulting</span>
                    <div class="flex-row">
                        <span>13 Woodpark</span>,
                        <span>Rathdrum</span>
                    </div>
                    <div class="flex-row">
                        <span>Wicklow</span>,
                        <span>A67 FF66</span>
                    </div>
                    <span>+353 (0) 83 122 1562</span>
                    <span>marksrailton@gmail.com</span>
                </from>
                <span class="mt-12 font-extrabold md:hidden lg:hidden xl:hidden print:hidden">TO</span>
                <to class="flex flex-col md:absolute md:right-0 md:text-right lg:absolute lg:right-0 lg:text-right print:absolute print:right-0 print:text-right">
                    <span class="font-medium">{{ $invoice->customer->name }}</span>
                </to>
            </contract>
        </div>
        <hr class="border-gray-300 md:mt-8 print:hidden">
        <content>
            <div id="content" class="flex justify-center md:p-8 lg:p-20 xl:p-20 print:p-2">
                <table class="w-full text-left table-auto print:text-sm" id="table-items">
                    <thead>
                    <tr class="text-white bg-gray-700 print:bg-gray-300 print:text-black">
                        <th class="px-4 py-2">Item</th>
                        <th class="px-4 py-2 text-right">Qty</th>
                        <th class="px-4 py-2 text-right">Unit Price</th>
                        <th class="px-4 py-2 text-right">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td class="px-4 py-2 border">{{ $item->description }}</td>
                            <td class="px-4 py-2 text-right border tabular-nums slashed-zero">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 text-right border tabular-nums slashed-zero">€{{ $item->amount }}</td>
                            <td class="px-4 py-2 text-right border tabular-nums slashed-zero">€{{ $item->subtotal }}</td>
                        </tr>
                    @endforeach

                    <tr class="text-white bg-gray-700 print:bg-gray-300 print:text-black" >
                        <td class="invisible"></td>
                        <td class="invisible"></td>
                        <td class="px-4 py-2 font-extrabold text-right border">Total</td>
                        <td class="px-4 py-2 text-right border tabular-nums slashed-zero">€{{ $invoice->amount }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </content>

        <footer class="flex flex-col items-center justify-center pb-20 leading-loose text-white bg-gray-700 print:bg-white print:pb-0">
            <span class="mt-4 text-xs print:mt-0">Invoice generated on {{ $invoice->created_at->format('d/m/Y H:i:s') }}</span>
            <span class="mt-4 text-base print:text-xs">© {{ now()->format('Y') }} Mark Railton Consulting.  All rights reserved.</span>
            <span class="print:text-xs">13 Woodpark, Rathdrum, Wicklow, A67 FF66</span>
        </footer>

        <div x-show="showMarkInvoiceSentModal" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
            <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                <div x-cloak @click="showMarkInvoiceSentModal = false" x-show="showMarkInvoiceSentModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"
                ></div>

                <div x-cloak x-show="showMarkInvoiceSentModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl"
                >
                    <div class="flex items-center justify-between space-x-4">
                        <h1 class="text-xl font-medium text-gray-800 ">Mark Invoice Sent</h1>

                        <button @click="showMarkInvoiceSentModal = false" class="text-gray-600 focus:outline-none hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>

                    <form class="mt-5" method="POST" action="{{ route('admin.invoices.mark-sent', ['invoice' => $invoice]) }}">
                        @csrf

                        Are you sure you want to set this invoice as sent?

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-green-500 rounded-md dark:bg-green-600 dark:hover:bg-green-700 dark:focus:bg-indigo-700 hover:bg-green-600 focus:outline-none focus:bg-green-500 focus:ring focus:ring-green-300 focus:ring-opacity-50">
                                Mark Invoice Sent
                            </button>

                            <button type="button" @click="showMarkInvoiceSentModal = false" class="px-3 py-2 ml-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-500 rounded-md dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:bg-indigo-700 hover:bg-indigo-600 focus:outline-none focus:bg-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showAddPaymentModal" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
            <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                <div x-cloak @click="showAddPaymentModal = false" x-show="showAddPaymentModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"
                ></div>

                <div x-cloak x-show="showAddPaymentModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl"
                >
                    <div class="flex items-center justify-between space-x-4">
                        <h1 class="text-xl font-medium text-gray-800 ">Add Payment</h1>

                        <button @click="showAddPaymentModal = false" class="text-gray-600 focus:outline-none hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>

                    <form class="mt-5" method="POST" action="{{ route('admin.invoices.add-payment', ['invoice' => $invoice]) }}">
                        @csrf

                        <div class="mb-6">
                            <label for="paid_on" class="block text-sm text-gray-700">Date</label>
                            <input id="paid_on" name="paid_on" type="date" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40" value="{{ now()->format('Y-m-d') }}">
                        </div>

                        <div class="mb-6">
                            <label for="amount" class="block text-sm text-gray-700">Amount</label>
                            <input id="amount" name="amount" type="number" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40" value="{{ $invoice->balance }}">
                        </div>

                        <div class="mb-6">
                            <label class="block">
                                <span class="text-gray-700">Notes:</span>
                                <textarea class="block w-full mt-1 rounded-md" name="notes" rows="3"></textarea>
                            </label>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-green-500 rounded-md dark:bg-green-600 dark:hover:bg-green-700 dark:focus:bg-indigo-700 hover:bg-green-600 focus:outline-none focus:bg-green-500 focus:ring focus:ring-green-300 focus:ring-opacity-50">
                                Add Payment
                            </button>

                            <button type="button" @click="showAddPaymentModal = false" class="px-3 py-2 ml-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-500 rounded-md dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:bg-indigo-700 hover:bg-indigo-600 focus:outline-none focus:bg-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
