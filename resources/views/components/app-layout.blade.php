@props(['breadcrumbs' => []])

<div class="flex min-h-screen w-full">
    <x-app-sidebar />
    
    <div class="flex flex-1 flex-col">
        <x-app-header />
        
        <main class="flex-1 p-6">
            @if(count($breadcrumbs) > 0)
                <x-breadcrumbs :items="$breadcrumbs" class="mb-6" />
            @endif
            
            {{ $slot }}
        </main>
    </div>
</div>
