<?php

use Illuminate\Support\Facades\Artisan;

// phpcs:disable
abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        // Load .env.testing if exists
        if (file_exists(dirname(__DIR__) . '/.env.testing')) {
            $dotenv = Dotenv\Dotenv::create(dirname(__DIR__), '.env.testing');
            $dotenv->overload();
        }

        return require __DIR__.'/../bootstrap/app.php';
    }

    public function setUp():void
    {
        parent::setUp();
        \DB::beginTransaction();
        $this->prepareForTests();
    }

    private function prepareForTests()
    {
        Artisan::call('migrate', [
            '--force' => true,
        ]);
    }

    public function tearDown():void
    {
        \DB::rollback();
        parent::tearDown();
    }
}
