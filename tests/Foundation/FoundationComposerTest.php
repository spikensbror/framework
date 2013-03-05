<?php

use Mockery as m;

class FoundationComposerTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testDumpAutoloadRunsTheCorrectCommand()
	{
		$composer = $this->getMock('Illuminate\Foundation\Composer', array('getProcess'), array($files = m::mock('Illuminate\Filesystem\Filesystem'), __DIR__));
		$files->shouldReceive('exists')->once()->with(__DIR__.'/composer.phar')->andReturn(true);
		$process = m::mock('stdClass');
		$composer->expects($this->once())->method('getProcess')->will($this->returnValue($process));
		$process->shouldReceive('setCommandLine')->once()->with('php \''.__DIR__.'/composer.phar\' dump-autoload --optimize');
		$process->shouldReceive('run')->once();

		$composer->dumpAutoloads();
	}


	public function testDumpAutoloadRespectsCustomPath()
	{
		$composer = $this->getMock('Illuminate\Foundation\Composer', array('getProcess'), array($files = m::mock('Illuminate\Filesystem\Filesystem'), __DIR__));
		$files->shouldReceive('exists')->once()->with(__DIR__.'/composer.phar')->andReturn(true);
		$process = m::mock('stdClass');
		$composer->expects($this->once())->method('getProcess')->with('custom')->will($this->returnValue($process));
		$process->shouldReceive('setCommandLine')->once()->with('php \''.__DIR__.'/composer.phar\' dump-autoload --optimize');
		$process->shouldReceive('run')->once();

		$composer->dumpAutoloads('custom');
	}


	public function testDumpAutoloadRunsTheCorrectCommandWhenComposerIsntPresent()
	{
		$composer = $this->getMock('Illuminate\Foundation\Composer', array('getProcess'), array($files = m::mock('Illuminate\Filesystem\Filesystem'), __DIR__));
		$files->shouldReceive('exists')->once()->with(__DIR__.'/composer.phar')->andReturn(false);
		$process = m::mock('stdClass');
		$composer->expects($this->once())->method('getProcess')->will($this->returnValue($process));
		$process->shouldReceive('setCommandLine')->once()->with('composer dump-autoload --optimize');
		$process->shouldReceive('run')->once();

		$composer->dumpAutoloads();
	}

}