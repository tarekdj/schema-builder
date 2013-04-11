<?php

use Mockery as m;
use Robbo\SchemaBuilder\Blueprint;

class SchemaBlueprintTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testToSqlRunsCommandsFromBlueprint()
	{
		$conn = m::mock('Robbo\SchemaBuilder\Connection');
		$conn->shouldReceive('statement')->once()->with('foo');
		$conn->shouldReceive('statement')->once()->with('bar');
		$grammar = m::mock('Robbo\SchemaBuilder\Grammar\MySqlGrammar');
		$blueprint = $this->getMock('Robbo\SchemaBuilder\Blueprint', array('toSql'), array('users'));
		$blueprint->expects($this->once())->method('toSql')->with($this->equalTo($conn), $this->equalTo($grammar))->will($this->returnValue(array('foo', 'bar')));

		$blueprint->build($conn, $grammar);
	}


	public function testIndexDefaultNames()
	{
		$blueprint = new Blueprint('users');
		$blueprint->unique(array('foo', 'bar'));
		$commands = $blueprint->getCommands();
		$this->assertEquals('users_foo_bar_unique', $commands[0]->index);

		$blueprint = new Blueprint('users');
		$blueprint->index('foo');
		$commands = $blueprint->getCommands();
		$this->assertEquals('users_foo_index', $commands[0]->index);
	}

}