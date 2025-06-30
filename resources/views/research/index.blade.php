<x-app-layout>
    <x-slot name="title">{{ __('Research Projects') }}</x-slot>
    <x-slot name="header">
        @can('add research projects')
            <a href="{{ route('research.create') }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
            >{{ __('Create') }}</a>
        @endcan
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Research Projects') }}
        </h2>
    </x-slot>


    @if ($projects->isEmpty())
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                {{ __('No research projects available') }}
            </div>
        </div>
    @else
        <div class="sm:grid sm:grid-cols-3 sm:gap-6">
            @foreach($projects as $project)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                        <p>
                            @can ('edit research projects')
                                <a href="{{ route('research.edit', ['projectId' => $project->id]) }}"
                                   class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                >{{ __('Edit') }}</strong></a>
                            @endcan
                            <a href="{{ $project->getViewRoute() }}" class="underline"><strong>{{ $project->name }}</strong></a>
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
