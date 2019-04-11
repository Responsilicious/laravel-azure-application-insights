<?php

namespace Mondago\ApplicationInsights\Tests;


use ApplicationInsights\Channel\Telemetry_Channel;
use ApplicationInsights\Telemetry_Client;
use ApplicationInsights\Telemetry_Context;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Orchestra\Testbench\TestCase;
use Mondago\ApplicationInsights\ApplicationInsights;

class ApplicationInsightsTest extends TestCase
{

    public function test_that_it_handles_disabled_correctly()
    {
        $telemetry = \Mockery::mock(Telemetry_Client::class);

        $telemetry->shouldNotReceive('flush');
        $telemetry->shouldNotReceive('trackRequest');
        $telemetry->shouldNotReceive('trackException');
        $telemetry->shouldNotReceive('getContext');


        $insights = new ApplicationInsights($telemetry, 'notaninstrumentationkey', false);
        $insights->shouldThrowExceptions(true);

        $insights->trackRequest(new Request(), new Response());
        $insights->trackException(new \Exception("test"));

        $this->assertFalse($insights->isEnabled());
    }


    public function test_that_it_handles_enabled_correctly()
    {
        $exception = new \Exception("Test");
        $key = 'notaninstrumentationkey';

        $telemetryContext = \Mockery::mock(Telemetry_Context::class);
        $telemetryContext->shouldReceive('setInstrumentationKey')->once()->with($key);

        $telemetryChannel = \Mockery::mock(Telemetry_Channel::class);
        $telemetryChannel->shouldReceive('setSendGzipped')->once()->with(true);

        $telemetry = \Mockery::mock(Telemetry_Client::class);
        $telemetry->shouldReceive('getContext')->once()->andReturn($telemetryContext);
        $telemetry->shouldReceive('getChannel')->once()->andReturn($telemetryChannel);
        $telemetry->shouldReceive('flush')->atLeast()->twice()->andReturn(null);
        $telemetry->shouldReceive('trackRequest')->once()->andReturn(null);
        $telemetry->shouldReceive('trackException')->once()->with($exception)->andReturn(null);


        $insights = new ApplicationInsights($telemetry, $key);
        $insights->shouldThrowExceptions(true);

        $insights->trackRequest(new Request(), new Response());
        $insights->trackException($exception);

        $this->assertTrue($insights->isEnabled());
    }
}