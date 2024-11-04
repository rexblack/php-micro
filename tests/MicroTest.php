<?php

// tests/MicroTest.php
namespace benignware\micro\tests;

use benignware\micro\Micro;
use PHPUnit\Framework\TestCase;

class MicroTest extends TestCase {
    public function testDispatch() {
        // Create a mock request object
        $requestMock = (object) [
            'path' => '/test',
            'method' => 'GET',
            'query' => '',
            'params' => [],
            'headers' => []
        ];

        // Instantiate the Micro framework
        $micro = new Micro();

        // Define a test action
        $micro->get('/test', function($request, $response) {
            return 'Test Success';
        });

        // Dispatch the mock request
        $response = $micro->dispatch($requestMock);

        // Assert that the output is as expected
        $this->assertEquals('Test Success', $response->body);
        $this->assertEquals(200, $response->status);
    }

    public function testNotFound() {
        // Create a mock request object
        $requestMock = (object) [
            'path' => '/notfound',
            'method' => 'GET',
            'query' => '',
            'params' => [],
            'headers' => []
        ];

        // Instantiate the Micro framework
        $micro = new Micro();

        // Dispatch the mock request
        $response = $micro->dispatch($requestMock);

        // Assert that the output is 404 Not Found
        $this->assertEquals('404 Not Found', $response->body);
        $this->assertEquals(404, $response->status);
    }
}
