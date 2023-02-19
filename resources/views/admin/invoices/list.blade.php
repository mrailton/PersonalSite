<x-admin-layout title="Invoices">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">

            </div>
        </div>
        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle">
                    <div class="shadow-sm ring-1 ring-black ring-opacity-5">
                        <table class="min-w-full border-separate" style="border-spacing: 0">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8">Customer</th>
                                <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell">Issued On</th>
                                <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell">Due On</th>
                                <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell">Amount</th>
                                <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell">Balance</th>
                                <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell">Status</th>
                                <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 bg-gray-50 bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell"></th>
                            </tr>
                            </thead>
                            <tbody class="bg-white">
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
                                        {{ $invoice->customer->name }}
                                    </td>
                                    <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                        {{ $invoice->issued_On?->format('jS M Y') ?? 'No Date' }}
                                    </td>
                                    <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                        {{ $invoice->due_on?->format('jS M Y') ?? 'No Date' }}
                                    </td>
                                    <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                        €{{ number_format($invoice->amount / 100, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                        €{{ number_format($invoice->balance / 100, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                        {{ ucfirst($invoice->status) }}
                                    </td>
                                    <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                        <a href="">View</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
