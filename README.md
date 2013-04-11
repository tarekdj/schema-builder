# Schema Builder

This is a port of [http://github.com/illuminate/database](illuminate/database) to provide the schema builder without all the extra features.

The idea behind this is allowing the scheme builder to be used in projects that already have a database layer. Personally it is to use it with XenForo of which I will show how in the examples below.

All you need to use this is a `PDO` instance. If you create it manually you might want to consider using [http://github.com/robclancy/db-connector](robclancy/db-connector) which is another fork of [http://github.com/illuminate/database](illuminate/database) but to just connect to the database. With your PDO instance you create a `Robbo\SchemaBuilder\Connection` instance and then call `->getSchemaBuilder()` to get started. Then you use the builder as described in the [http://four.laravel.com/docs/schema](Laravel docs) however substitute your instance for `Schema::`.

### Native example

Following example will assume you have a `PDO` instance stored in `$pdo`.

```php

// Create the connection
$connection = new Robbo\SchemaBuilder\Connection\MySqlConnection($pdo, 'database_name', 'tableprefix_');

// Alternatively you can use a factory method to create an instance depending on the driver
// Drivers supported: mysql, pgsql, sqlite and sqlsrv
$connection = Robbo\SchemaBuilder\Connection::create('mysql', $pdo, 'database_name', 'tableprefix_')

$builder = $connection->getSchemaBuilder();

// Now you can use it like in the laravel docs...
$builder->create('users', function($table)
{
    $table->increments('id');
});

```

Support for a dry run is also supported using the functionality from illuminate/database. Use it like so...

```php

// $builder and $connection is the same from above

$queries = $connection->pretend(function($connection) use ($builder)
{
	// Could use $connection->getSchemaBuilder() here in place of $builder
	$builder->create('users', function($table)
	{
	    $table->increments('id');
	});
});

// Now $queries will contain all queries that were created
```