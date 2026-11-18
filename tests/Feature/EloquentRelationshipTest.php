<?php

use App\Models\Group;
use App\Models\User;

test('the relationships page loads', function () {
    $response = $this->get('/eloquent-relationships');

    $response->assertOk()->assertSee('Eloquent Relationships Demo');
});

test('a user and house can be created through the one to one form', function () {
    $this->post(route('eloquent.one-to-one.store'), [
        'oto_name' => 'Jane Doe',
        'oto_address' => '123 Main St',
    ])->assertRedirect();

    $user = User::where('name', 'Jane Doe')->first();

    expect($user)->not->toBeNull();
    expect($user->house->address)->toBe('123 Main St');

    $this->get('/eloquent-relationships')
        ->assertOk()
        ->assertSee('Jane Doe')
        ->assertSee('123 Main St');
});

test('houses can be added to an existing user through the one to many form', function () {
    $user = User::create(['name' => 'John Doe']);

    $this->post(route('eloquent.one-to-many.store'), [
        'otm_name' => 'John Doe',
        'otm_address' => '1 First St',
    ])->assertRedirect();

    $this->post(route('eloquent.one-to-many.store'), [
        'otm_name' => 'John Doe',
        'otm_address' => '2 Second St',
    ])->assertRedirect();

    expect($user->fresh()->houses)->toHaveCount(2);
});

test('a user can be attached to groups through the many to many form', function () {
    $this->post(route('eloquent.many-to-many.store'), [
        'mtm_name' => 'Group User',
        'mtm_groups' => ['Home', 'Work'],
    ])->assertRedirect();

    $user = User::where('name', 'Group User')->firstOrFail();

    expect($user->groups->pluck('name')->sort()->values()->all())
        ->toBe(['Home', 'Work']);
    expect(Group::count())->toBe(4);

    $this->get('/eloquent-relationships')
        ->assertOk()
        ->assertSee('Group User')
        ->assertSee('Home')
        ->assertSee('Work');
});

test('the relationship forms validate required input', function () {
    $this->post(route('eloquent.one-to-one.store'), [])
        ->assertSessionHasErrors(['oto_name', 'oto_address']);

    $this->post(route('eloquent.many-to-many.store'), ['mtm_name' => 'No Groups'])
        ->assertSessionHasErrors(['mtm_groups']);
});
