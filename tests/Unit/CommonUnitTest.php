<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommonUnitTest extends TestCase
{
    /** @test */
    public function requiredImagesExists()
    {
        $requiredImages = [
            'avatar.png',
            'camera.png',
            'loading.gif',
            'no-image.jpg',
        ];

        foreach ($requiredImages as $image) {
            Storage::assertExists("images/{$image}");
        }
    }
}
