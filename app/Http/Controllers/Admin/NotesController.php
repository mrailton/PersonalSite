<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Notes\StoreNoteRequest;
use App\Models\Note;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;
use Illuminate\Routing\Controller;

class NotesController extends Controller
{
    public function list(Request $request): View
    {
        $notes = Note::query()->paginate(10);

        return view('admin.notes.list', ['notes' => $notes]);
    }

    public function store(StoreNoteRequest $request): RedirectResponse
    {
        Note::query()->create($request->validated());

        return redirect()->route('admin.notes.list');
    }

    public function show(Request $request, Note $note): View
    {
        $html = (new CommonMarkConverter())->convert($note->content);

        return view('admin.notes.show', ['note' => $note, 'html' => $html]);
    }

    public function update(StoreNoteRequest $request, Note $note): RedirectResponse
    {
        $note->update($request->validated());

        return redirect()->route('admin.notes.show', ['note' => $note]);
    }

    public function delete(Request $request, Note $note): RedirectResponse
    {
        $note->delete();

        return redirect()->route('admin.notes.list');
    }
}
