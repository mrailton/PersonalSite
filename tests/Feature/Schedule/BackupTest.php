<?php

declare(strict_types=1);

use Illuminate\Console\Events\ScheduledTaskFinished;
use Illuminate\Support\Facades\Event;

test('laravel backup cleanup runs at 03:15', function () {
    Event::fake();

    $this->travelTo(now()->setHour(3)->setMinute(15));
    $this->artisan('schedule:run');

    Event::assertDispatched(ScheduledTaskFinished::class, function ($event) {
        return str_contains($event->task->command, 'backup:clean');
    });
});

test('laravel backup runs at 03:30', function () {
    Event::fake();

    $this->travelTo(now()->setHour(3)->setMinute(30));
    $this->artisan('schedule:run');

    Event::assertDispatched(ScheduledTaskFinished::class, function ($event) {
        return str_contains($event->task->command, 'backup:run');
    });
});
