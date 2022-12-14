<x-app-layout>
    <div class="max-w-4xl px-6 pb-20 mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-4">{{ $article->title }}</h1>
        <span
            class="block text-gray-600 font-light text-sm mb-8">Posted: {{ Carbon\Carbon::parse($article->published_at)->format('jS F Y') }}</span>
        <div class="prose lg:prose-xl">{!! $html !!}</div>
    </div>
</x-app-layout>
