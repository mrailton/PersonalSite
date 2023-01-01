<x-admin-layout title="Create New Article">
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    @endpush

    <div class="px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.articles.store') }}">
            @csrf

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Title</span>
                    <input type="text" name="title"
                           class="block w-full @error('title') border-red-500 @enderror mt-1 rounded-md"
                           placeholder="" value="{{old('title')}}" />
                </label>
                @error('title')
                <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Publish At</span>
                    <input type="datetime-local" name="published_at"
                           class="block w-full @error('published_at') border-red-500 @enderror mt-1 rounded-md"
                           placeholder="" value="{{old('published_at')}}" />
                </label>
                @error('published_at')
                <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block">
                    <span class="text-gray-700">Content</span>
                    <textarea id="markdown-editor" class="block w-full mt-1 rounded-md" name="content"
                              rows="3">{{ old('content') }}</textarea>
                </label>
                @error('content')
                <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit"
                    class="text-white bg-indigo-600 rounded text-sm px-5 py-2.5">Submit</button>

        </form>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
        <script>
            const easyMDE = new EasyMDE({
                showIcons: ['strikethrough', 'code', 'table', 'redo', 'heading', 'undo', 'heading-bigger', 'heading-smaller', 'heading-1', 'heading-2', 'heading-3', 'clean-block', 'horizontal-rule'],
                element: document.getElementById('markdown-editor')
            });
        </script>
    @endpush
</x-admin-layout>
