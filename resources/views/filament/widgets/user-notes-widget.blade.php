<x-filament-widgets::widget>
    <div
        x-data="{
            modal: null,
            openCreate() {
                this.modal = 'form';
                $wire.editingId = null;
                $wire.content = '';
            },
            openEdit(id) {
                $wire.editNote(id);
                this.modal = 'form';
            },
            close() {
                this.modal = null;
            }
        }"
        @note-saved.window="modal = null"
        @note-editing.window="modal = 'form'"
        class="space-y-4"
    >

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    Sticky Notes
                </h2>
                <p class="text-xs text-gray-500">
                    {{ $this->notes->count() }} {{ Str::plural('note', $this->notes->count()) }}
                </p>
            </div>

            <button
                type="button"
                @click="openCreate()"
                class="flex h-9 w-9 items-center justify-center rounded-full bg-yellow-400 text-gray-900 shadow-sm transition hover:bg-yellow-300 cursor-pointer"
                title="Create note"
            >
                <x-heroicon-o-plus class="h-5 w-5" />
            </button>
        </div>


        {{-- NOTE COLORS --}}
        @php
            $colors = [
                'bg-[#FFF1A8]',
                'bg-[#BFE3FA]',
                'bg-[#F8C4DE]',
                'bg-[#C9EDC0]',
                'bg-[#DDD0F5]',
            ];
        @endphp


        {{-- DASHBOARD NOTES --}}
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">

            @forelse($this->notes->take(4) as $i => $note)

                @php
                    $lines = preg_split('/\r\n|\r|\n/', trim($note->content));
                    $title = $lines[0] ?? '';
                    $body = implode(' ', array_slice($lines, 1));
                @endphp

                <div
                    class="{{ $colors[$i % count($colors)] }} group relative min-h-[145px] overflow-hidden rounded-sm p-4 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md text-gray-900"
                >
                    {{-- TIME --}}
                    <span class="absolute right-3 top-3 text-[10px] text-gray-600">
                        {{ $note->created_at->format('g:i A') }}
                    </span>

                    {{-- CONTENT (Clicking opens edit/view modal) --}}
                    <button
                        type="button"
                        @click="openEdit({{ $note->id }})"
                        class="block w-full pr-8 text-left cursor-pointer"
                    >
                        <h3 class="line-clamp-2 text-sm font-bold text-gray-900">
                            {{ $title }}
                        </h3>

                        @if($body)
                            <p class="mt-2 line-clamp-4 text-xs leading-5 text-gray-800">
                                {{ $body }}
                            </p>
                        @endif
                    </button>

                    {{-- ACTIONS --}}
                    <div class="absolute bottom-2 right-2 flex gap-1 opacity-0 transition group-hover:opacity-100">
                        <button
                            type="button"
                            @click.stop="openEdit({{ $note->id }})"
                            class="rounded-md bg-white/80 p-1.5 text-gray-700 shadow-sm transition hover:text-blue-600 cursor-pointer"
                            title="Edit"
                        >
                            <x-heroicon-o-pencil class="h-3.5 w-3.5" />
                        </button>

                        <button
                            type="button"
                            wire:click="deleteNote({{ $note->id }})"
                            wire:confirm="Delete this note?"
                            class="rounded-md bg-white/80 p-1.5 text-gray-700 shadow-sm transition hover:text-red-600 cursor-pointer"
                            title="Delete"
                        >
                            <x-heroicon-o-trash class="h-3.5 w-3.5" />
                        </button>
                    </div>

                    {{-- FOLD CORNER --}}
                    <span class="absolute bottom-0 right-0 border-l-[18px] border-t-[18px] border-l-transparent border-t-black/10"></span>
                </div>

            @empty
                {{-- EMPTY STATE --}}
                <button
                    type="button"
                    @click="openCreate()"
                    class="col-span-full rounded-xl border-2 border-dashed border-gray-200 py-8 text-sm text-gray-400 transition hover:border-yellow-400 hover:text-yellow-600 dark:border-gray-700 cursor-pointer"
                >
                    <x-heroicon-o-plus class="mx-auto mb-2 h-5 w-5" />
                    Create your first sticky note
                </button>

            @endforelse

        </div>


        {{-- VIEW ALL --}}
        @if($this->notes->count() > 4)
            <button
                type="button"
                @click="modal = 'all'"
                class="text-xs font-medium text-gray-500 transition hover:text-yellow-600 cursor-pointer"
            >
                View all {{ $this->notes->count() }} notes →
            </button>
        @endif


        {{-- MODAL CONTAINER --}}
        <div
            x-show="modal"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
        >
            {{-- BACKDROP --}}
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="close()"></div>

            {{-- MODAL BOX --}}
            <div
                @click.stop
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="scale-95 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                class="relative z-10 w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900"
            >

                {{-- CREATE / EDIT FORM MODAL --}}
                <div x-show="modal === 'form'" x-cloak>
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                                <span x-show="$wire.editingId">Edit Sticky Note</span>
                                <span x-show="!$wire.editingId">New Sticky Note</span>
                            </h2>
                            <p class="mt-0.5 text-xs text-gray-500">Write something you want to remember.</p>
                        </div>

                        <button
                            type="button"
                            @click="close()"
                            class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 cursor-pointer"
                        >
                            <x-heroicon-o-x-mark class="h-5 w-5" />
                        </button>
                    </div>

                    <form wire:submit="saveNote" class="p-6">
                        <textarea
                            wire:model="content"
                            rows="7"
                            autofocus
                            placeholder="Write your note here..."
                            class="block box-border w-full resize-none rounded-xl border border-gray-300 bg-white p-4 text-sm leading-6 text-gray-800 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-yellow-400"
                        ></textarea>

                        @error('content')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                        <div class="mt-4 flex items-center justify-end gap-2">
                            <button
                                type="button"
                                @click="close()"
                                class="rounded-lg px-4 py-2 text-sm text-gray-600 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 cursor-pointer"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="rounded-lg bg-yellow-400 px-5 py-2 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-yellow-300 disabled:cursor-not-allowed disabled:opacity-60 cursor-pointer"
                            >
                                <span wire:loading.remove>
                                    <span x-show="$wire.editingId">Update Note</span>
                                    <span x-show="!$wire.editingId">Save Note</span>
                                </span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>


                {{-- ALL NOTES MODAL --}}
                <div x-show="modal === 'all'" x-cloak>
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">All Sticky Notes</h2>
                            <p class="text-xs text-gray-500">{{ $this->notes->count() }} {{ Str::plural('note', $this->notes->count()) }}</p>
                        </div>

                        <button
                            type="button"
                            @click="close()"
                            class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 cursor-pointer"
                        >
                            <x-heroicon-o-x-mark class="h-5 w-5" />
                        </button>
                    </div>

                    <div class="grid max-h-[70vh] grid-cols-1 gap-4 overflow-y-auto p-6 sm:grid-cols-2">
                        @foreach($this->notes as $i => $note)
                            @php
                                $lines = preg_split('/\r\n|\r|\n/', trim($note->content));
                                $title = $lines[0] ?? '';
                                $body = implode(' ', array_slice($lines, 1));
                            @endphp

                            <div class="{{ $colors[$i % count($colors)] }} group relative min-h-[150px] rounded-sm p-4 shadow-sm text-gray-900">
                                <div class="flex items-start justify-between gap-3 pr-1">
                                    <h3 class="line-clamp-2 text-sm font-bold text-gray-900">{{ $title }}</h3>
                                    <span class="shrink-0 text-[10px] text-gray-600">{{ $note->created_at->format('g:i A') }}</span>
                                </div>

                                @if($body)
                                    <p class="mt-2 whitespace-pre-wrap text-xs leading-5 text-gray-800">{{ $body }}</p>
                                @endif

                                <div class="absolute bottom-3 right-3 flex gap-1 opacity-0 transition group-hover:opacity-100">
                                    <button
                                        type="button"
                                        @click="openEdit({{ $note->id }})"
                                        class="rounded-md bg-white/80 p-1.5 text-gray-700 shadow-sm transition hover:text-blue-600 cursor-pointer"
                                        title="Edit"
                                    >
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="deleteNote({{ $note->id }})"
                                        wire:confirm="Delete this note?"
                                        class="rounded-md bg-white/80 p-1.5 text-gray-700 shadow-sm transition hover:text-red-600 cursor-pointer"
                                        title="Delete"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>

                                <span class="absolute bottom-0 right-0 border-l-[18px] border-t-[18px] border-l-transparent border-t-black/10"></span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-filament-widgets::widget>