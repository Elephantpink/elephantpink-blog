<?php

namespace EPink\Blog\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $apiHeaders = [
        'Accept' => 'application/json'
    ];
}
