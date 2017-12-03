# lava
A secure PHP ORM

## Usage

``` php
<?php
$users = new Lava($db, "users");

$users->insert(array(
	"name" => "Monty",
	"age" => 16
));

$users->update(array(
	"age" => "17",
), array(
	"name" => "Monty"
));

var_dump($users->find(array(
	"name" => "Monty"
)));
```
