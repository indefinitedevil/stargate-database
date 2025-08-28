<x-app-layout>
    <x-slot name="title">{{ __('Research Projects') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Research Projects') }}
        </h2>
    </x-slot>
    @can('add research projects')
        <x-slot name="sidebar2">
            <x-dropdown-link href="{{ route('research.create') }}">
                <i class="fa-solid fa-plus min-w-8"></i>
                {{ __('Create') }}
            </x-dropdown-link>
        </x-slot>
    @endcan

    @if ($projects->isEmpty())
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                {{ __('No research projects available') }}
            </div>
        </div>
    @else
        <div class="sm:grid sm:grid-cols-3 sm:gap-6">
            @foreach($projects as $project)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                        <p>
                            @can ('edit research projects')
                                <x-link-button href="{{ route('research.edit', ['projectId' => $project->id]) }}"
                                               class="float-right">{{ __('Edit') }}</x-link-button>
                            @endcan
                            <a href="{{ $project->getViewRoute() }}"
                               class="underline"><strong>{{ $project->name }}</strong></a>
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{!! process_inline_markdown(__('**Subject:** :subject', ['subject' => $project->research_subject])) !!}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{!! process_inline_markdown(__('**Status:** :status', ['status' => $project->status_name])) !!}</p>

                        @can ('edit research projects')
                            <p class="text-sm text-gray-600 dark:text-gray-400">{!! process_inline_markdown(__('**Visibility:** :visibility', ['visibility' => $project->visibility_name])) !!}</p>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $projects->links() }}
        </div>
    @endif
</x-app-layout>
