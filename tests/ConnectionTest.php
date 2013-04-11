<?php

use Mockery as m;

class ConnectionTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testStatementProperlyCallsPDO()
	{
		// TODO
	}

	public function testPretendOnlyLogsQueries()
	{
		$connection = new Robbo\SchemaBuilder\Connection(new ConnectionTestMockPDO);//$this->getMockConnection();
		$connection->setSchemaGrammar(new ConnectionTestSchemaGrammar);
		$queries = $connection->pretend(function($connection)
		{
			$connection->getSchemaBuilder()->create('foo_bar', function($table)
			{
				$table->string('foo')->default('bar');
			});
		});
		$this->assertEquals('foo bar', $queries[0]['query']);
	}


	public function testSchemaBuilderCanBeCreated()
	{
		$connection = $this->getMockConnection(array('getDefaultSchemaGrammar'));
		$schema = $connection->getSchemaBuilder();
		$this->assertInstanceOf('Robbo\SchemaBuilder\Builder', $schema);
		$this->assertTrue($connection === $schema->getConnection());
	}


	protected function getMockConnection($methods = array(), $pdo = null)
	{
		$pdo = $pdo ?: new ConnectionTestMockPDO;
		return $this->getMock('Robbo\SchemaBuilder\Connection', $methods, array($pdo));
	}

}

class ConnectionTestMockPDO extends PDO { 

	public static $prepareHack;

	public function __construct() {} 

	public function prepare() 
	{
		return static::$prepareHack;
	}
}

class ConnectionTestSchemaGrammar extends Robbo\SchemaBuilder\Grammar {

	public function compileCreate(Robbo\SchemaBuilder\Blueprint $blueprint, Robbo\SchemaBuilder\Fluent $command)
	{
		return 'foo bar';
	}
}