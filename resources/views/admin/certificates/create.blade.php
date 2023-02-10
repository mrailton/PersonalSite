<x-admin-layout title="Create New Article">
    <div class="px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.certificates.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Name:</span>
                    <input type="text" name="name"
                           class="block w-full @error('name') border-red-500 @enderror mt-1 rounded-md"
                           placeholder="" value="{{old('name')}}" />
                </label>
                @error('name')
                <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Issued By:</span>
                    <input type="text" name="issued_by"
                           class="block w-full @error('issued_by') border-red-500 @enderror mt-1 rounded-md"
                           placeholder="" value="{{old('issued_by')}}" />
                </label>
                @error('issued_by')
                <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Issued On:</span>
                    <input type="date" name="issued_on"
                           class="block w-full @error('issued_on') border-red-500 @enderror mt-1 rounded-md"
                           placeholder="" value="{{old('issued_on')}}" />
                </label>
                @error('issued_on')
                <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Expires On:</span>
                    <input type="date" name="expires_on"
                           class="block w-full @error('expires_on') border-red-500 @enderror mt-1 rounded-md"
                           placeholder="" value="{{old('expires_on')}}" />
                </label>
                @error('expires_on')
                <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Image:</span>
                    <input type="file" name="image" class="block w-full @error('image') border-red-500 @enderror mt-1 rounded-md" value="{{old('image')}}" accept=".pdf,.jpeg,.jpg,.png" />
                </label>
                @error('image')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
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
    </div>
</x-admin-layout>
