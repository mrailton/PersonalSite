<x-admin-layout title="Update Customer">
    <div class="px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.customers.update', ['customer' => $customer]) }}">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Name:</span>
                    <input type="text" name="name"
                           class="block w-full @error('name') border-red-500 @enderror mt-1 rounded-md"
                           placeholder="" value="{{ $customer->name }}" />
                </label>
                @error('name')
                <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit"
                    class="text-white bg-indigo-600 rounded text-sm px-5 py-2.5">Submit</button>

        </form>
    </div>
</x-admin-layout>
