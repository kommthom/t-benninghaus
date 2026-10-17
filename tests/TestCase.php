<?php

namespace Tests;

use Illuminate\Foundation\Testing;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $connection = DB::connection();
        if (is_a($connection, SQLiteConnection::class)) {
            $connection->getPdo()->createFunction(
                'IF', 
                function ($condition, $value1, $value2) {
                    return $condition ? $value1 : $value2;
                });
         }       
    }
}