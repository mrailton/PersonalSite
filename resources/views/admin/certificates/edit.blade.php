<x-admin-layout title="Edit Certificate">
    <div class="px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.certificates.update', ['certificate' => $certificate]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Name:</span>
                    <input type="text" name="name"
                           class="block w-full @error('name') border-red-500 @enderror mt-1 rounded-md"
                           placeholder="" value="{{ $certificate->name }}" />
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
                           placeholder="" value="{{ $certificate->issued_by }}" />
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
                           placeholder="" value="{{ $certificate->issued_on->format('Y-m-d') }}" />
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
                           placeholder="" value="{{ $certificate->expires_on?->format('Y-m-d') }}" />
                </label>
                @error('expires_on')
                <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Notes:</span>
                    <textarea class="block w-full mt-1 rounded-md" name="notes"
                              rows="3">{{ $certificate->notes }}</textarea>
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
