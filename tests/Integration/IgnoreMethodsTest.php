<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;

class IgnoreMethodsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->config->set([
            'enlighten.tests.ignore' => [
                'does_not_export_test_methods_ignored_in_the_configuration',
                '*use_wildcards_to_ignore*',
            ],
        ]);
    }

    /** @test */
    function does_not_export_test_methods_ignored_in_the_configuration()
    {
        $this->assertExampleIsNotCreated();
    }

    /** @test */
    function can_use_wildcards_to_ignore_a_test_method_in_the_configuration()
    {
        $this->assertExampleIsNotCreated();
    }

    /**
     * @test
     * @enlighten {"ignore": true}
     */
    function does_not_export_test_methods_with_the_enlighten_ignore_annotation()
    {
        $this->assertExampleIsNotCreated();
    }
}
