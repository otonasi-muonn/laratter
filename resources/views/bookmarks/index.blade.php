<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Bookmarks') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          @if ($tweets->count())
            <div class="mb-4">
              {{ $tweets->links() }}
            </div>

            @foreach ($tweets as $tweet)
              <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <p class="text-gray-800 dark:text-gray-300">{{ $tweet->tweet }}</p>
                <a href="{{ route('profile.show', $tweet->user) }}">
                  <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $tweet->user->name }}</p>
                </a>
                <a href="{{ route('tweets.show', $tweet) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>
                <div class="flex mt-2">
                  <form action="{{ route('tweets.unbookmark', $tweet) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700">Unbookmark</button>
                  </form>
                </div>
              </div>
            @endforeach

            <div class="mt-4">
              {{ $tweets->links() }}
            </div>
          @else
            <p>No bookmarks yet.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
