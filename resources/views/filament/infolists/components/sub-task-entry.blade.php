<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div {{ $getExtraAttributeBag() }}>
        @livewire('sub-task-entry.infolist-component', [
            'parentTask' => $record,
            'subTasks' => $record->subTasks->toArray(),
        ])

</div>
</x-dynamic-component>
