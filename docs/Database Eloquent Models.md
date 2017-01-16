# Database Eloquent Models

The database eloquent models are a very robust way of stopping any sql injection into your website by turning all your tables into a model. 

# Settings

These are the settings you can override:
Variable | Description
--- | ---
$database | The Database
$table | The table
$columns | The columns that can be viewed and modifed
$columns_readonly | Columns that can be viewed but not modifed
$columns_id | The ID column inside the table (REQUIRED)

These variables are used for tracking row data and what has been changed:
Variable | Description
--- | ---
$columns_values | This contains all the values of the current row
$columns_changed | Contains what columns have been modifed since query, only these columns will be used in the UPDATE query
$exists | Tells the model if it is a new instance or it exists in the database.

# Usage
To create a new row you would create a new instance of the model and then set its data/columns and call `$model->Save();` 

// TODO: Place find comments here...

# Example

SQL: 
```sql
CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_lastedited` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `test` ADD PRIMARY KEY (`id`);
ALTER TABLE `test` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
```

Model:
```php
namespace App\Models;

use lib\CMVC\mvc\MVCEloquentModel;

class Test extends MVCEloquentModel {
	// Model settings
	protected static $table             = "test";
	protected static $useTimeColumns    = true;
	
	// Database columns
	protected static $columns_id        = "id";
	protected static $columns           = ['value'];
	
	// This is columns that cannot be modified but can be
	// viewed when called.
	protected static $columns_readonly  = [''];
	
	public static function findByValue($value) {
		return self::find(['value', '=', $value]);
	}
}
```

Creating a new row: 
```php
$test = new Test();
$test->value = "Hello World!";
$test->save();
```

Finding a column with the value: 
```php
$results = Test::findByValue('Hello World!');
// or
$results = Test::find( ['value', '=', 'Hello World!'] );

if ($results->isEmpty()) {
	echo "No results returned";
    return;
}

if ($results->isCollection()) {
	echo "Array of Test returned. Count=". $results->Count. "<br>";
    
    $array = $results->get();
    
    foreach ($array as $item) {
    	echo "ID: ". $item->getID(). "<br>";
	}
} else {
	$item = $results->get();
	
    // We use getID because it automatically gets the ID
    // column, and incase $columns_id is not id then it will
    // retrieve the actual id.
	echo "Test returned, ID = ". $item->getID();
}
```