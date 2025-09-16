@php
    $footerNavItems = [
        [
            'title' => 'Github Repo',
            'href' => 'https://github.com/laravel/vue-starter-kit',
            'icon' => 'folder',
        ],
        [
            'title' => 'Documentation',
            'href' => 'https://laravel.com/docs/starter-kits#vue',
            'icon' => 'book-open',
        ],
    ];
@endphp

<div class="space-y-1">
    @foreach($footerNavItems as $item)
        <a href="{{ $item['href'] }}" 
           target="_blank" 
           rel="noopener noreferrer"
           class="group flex items-center px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if($item['icon'] === 'folder')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                @elseif($item['icon'] === 'book-open')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                @endif
            </svg>
            {{ $item['title'] }}
        </a>
    @endforeach
</div>
