<x-admin-layout title="Show Customer">
    <div class="sm:flex sm:items-center py-5 px-4 sm:px-6 lg:px-8">
        <div class="sm:flex-auto">

        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.customers.edit', ['customer' => $customer]) }}">
                <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                    Update Customer
                </button>
            </a>

            <button type="button" onclick="confirmDelete()" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                Delete Customer
            </button>
        </div>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-gray-200">
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Name</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $customer->name }}</dd>
            </div>
        </dl>

        <dl class="sm:divide-y sm:divide-gray-200">
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Balance</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">€{{ number_format($customer->balance / 100, 2) }}</dd>
            </div>
        </dl>

        <dl class="sm:divide-y sm:divide-gray-200">
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Paid to Date</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">€{{ number_format($customer->paid_to_date / 100, 2) }}</dd>
            </div>
        </dl>
    </div>

    <form id="delete-customer-form" action="{{ route('admin.customers.delete', ['customer' => $customer]) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            function confirmDelete() {
                if (confirm('Are you sure you want to delete this customer?')) {
                    document.getElementById('delete-customer-form').submit();
                }
            }
        </script>
    @endpush

</x-admin-layout>
