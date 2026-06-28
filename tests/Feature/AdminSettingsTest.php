<?php

use App\Models\Setting;
use App\Models\User;

test('settings form does not render placeholder hashes as url input values', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    Setting::create([
        'key' => 'facebook_url',
        'value' => '#',
        'group' => 'social',
    ]);

    $response = $this->withoutVite()
        ->actingAs($admin)
        ->get(route('admin.settings.index'));

    $response
        ->assertOk()
        ->assertSee('id="setting_facebook_url"', false)
        ->assertSee('value=""', false)
        ->assertDontSee('value="#"', false);
});

test('settings update stores placeholder hashes as empty values', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    Setting::create([
        'key' => 'facebook_url',
        'value' => '#',
        'group' => 'social',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
        'settings' => [
            'facebook_url' => '#',
        ],
        'groups' => [
            'facebook_url' => 'social',
        ],
    ]);

    $response->assertRedirect(route('admin.settings.index'));

    expect(Setting::where('key', 'facebook_url')->value('value'))->toBe('');
});
