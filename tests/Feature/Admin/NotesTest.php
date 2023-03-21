<?php

use App\Models\Note;

test('an authenticated user can create a new note', function () {
    expect(Note::count())->toBe(0);

    $this->actingAs(user())
        ->post(route('admin.notes.store', ['title' => 'New Note', 'content' => 'This is a new note']))
        ->assertRedirectToRoute('admin.notes.list')
        ->assertSessionDoesntHaveErrors();

    expect(Note::count())->toBe(1);
});

test('an authenticated user can view a list of notes', function () {
    $notes = Note::factory()->count(3)->create();

    $this->actingAs(user())
        ->get(route('admin.notes.list'))
        ->assertSee($notes[0]->title)
        ->assertSee($notes[1]->title)
        ->assertSee($notes[2]->title);
});

test('an authenticated user can view a note', function () {
    $note = Note::factory()->create();

    $this->actingAs(user())
        ->get(route('admin.notes.show', ['note' => $note]))
        ->assertSee($note->title)
        ->assertSee($note->content);
});

test('an authenticated user can update a note', function () {
    $note = Note::factory()->create();

    $this->actingAs(user())
        ->put(route('admin.notes.update', ['note' => $note]), ['title' => 'Updated Note', 'content' => 'This is an updated note'])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.notes.show', ['note' => $note]);

    $this->actingAs(user())
        ->get(route('admin.notes.show', ['note' => $note]))
        ->assertSee('Updated Note')
        ->assertSee('This is an updated note');
});

test('an authenticated user can delete a note', function () {
    $note = Note::factory()->create();

    expect(Note::count())->toBe(1);

    $this->actingAs(user())
        ->delete(route('admin.notes.delete', ['note' => $note]))
        ->assertRedirectToRoute('admin.notes.list')
        ->assertSessionDoesntHaveErrors();

    expect(Note::count())->toBe(0);
});
