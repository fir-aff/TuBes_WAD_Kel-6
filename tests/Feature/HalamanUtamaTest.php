<?php

namespace Tests\Feature;

use Tests\TestCase;

class HalamanUtamaTest extends TestCase
{
    public function test_halaman_utama_bisa_diakses()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}