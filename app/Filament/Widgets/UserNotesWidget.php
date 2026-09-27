<?php

namespace App\Filament\Widgets;

use App\Models\Note;
use Filament\Widgets\Widget;

class UserNotesWidget extends Widget
{
    protected string $view = 'filament.widgets.user-notes-widget';
    
    protected int | string | array $columnSpan = 'full';

    public string $content = '';
    public ?int $editingId = null;

    public function saveNote(): void
    {
        $this->validate([
            'content' => 'required|string|max:1000',
        ]);

        if ($this->editingId) {
            // Update existing note
            Note::where('user_id', auth()->id())
                ->where('id', $this->editingId)
                ->update(['content' => $this->content]);
        } else {
            // Create new note
            Note::create([
                'user_id' => auth()->id(),
                'content' => $this->content,
            ]);
        }

        $this->reset(['content', 'editingId']);
        $this->dispatch('note-saved');
    }

    public function editNote($id): void
    {
        $note = Note::where('user_id', auth()->id())->where('id', $id)->first();
        if ($note) {
            $this->editingId = $note->id;
            $this->content = $note->content;
            $this->dispatch('note-editing');
        }
    }

    public function deleteNote($id): void
    {
        Note::where('user_id', auth()->id())->where('id', $id)->delete();
    }

    public function getNotesProperty()
    {
        return Note::where('user_id', auth()->id())->latest()->get();
    }
}