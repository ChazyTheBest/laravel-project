<?php

use App\Models\User;
use App\Models\Profile;

it('can retrieve a user profile', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    mock(Profile::class)
        ->shouldReceive('findByUserId')
        ->with($user->id)
        ->andReturn($profile);

    $retrievedProfile = $user->profile;

    expect($retrievedProfile->id)->toBe($profile->id);
});
