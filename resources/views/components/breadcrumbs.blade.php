@props(['items' => []])

@if(count($items) > 0)
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            @foreach($items as $index => $item)
                <li class="inline-flex items-center">
                    @if($index > 0)
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                    
                    @if($index === count($items) - 1)
                        <!-- Current page -->
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">
                            {{ $item['name'] }}
                        </span>
                    @else
                        <!-- Link -->
                        <a href="{{ $item['url'] }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                            {{ $item['name'] }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif