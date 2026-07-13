<?php

namespace Tests\Feature;

use Tests\TestCase;

class DocumentManipulationTest extends TestCase
{
    /** @test */
    public function it_loads_the_landing_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('VizzioDocs');
    }

    /** @test */
    public function it_loads_all_tool_pages()
    {
        $tools = [
            '/compress',
            '/merge',
            '/split',
            '/jpg-to-pdf',
            '/png-to-pdf',
            '/pdf-to-jpg',
            '/rotate',
            '/word-to-pdf',
            '/pdf-to-word',
            '/excel-to-pdf',
        ];

        foreach ($tools as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
        }
    }
}
