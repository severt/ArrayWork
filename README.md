[![version](https://img.shields.io/badge/Version-1.1.0-brightgreen.svg)](https://github.com/Fukotaku/fufu/releases/tag/1.1.0)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/Fukotaku/array-work.svg?branch=master)](https://travis-ci.org/Fukotaku/array-work)
[![GitHub license](https://img.shields.io/badge/license-New%20BSD-blue.svg)](https://github.com/Fukotaku/fufu/blob/master/LICENSE)
# array-work
Php library for easy sorting of data, generate html table and more.

```bash
composer require fukotaku/array-work
```

## Example

```php
<?php
// index.php
require "vendor/autoload.php";

$data = array(
  array("id" => 2,
        "name" => "example 4"),
  array("id" => 1,
        "name" => "example 5"),
  array("id" => 3,
        "name" => "example 3"),
  array("id" => 6,
        "name" => "example 8"),
  array("id" => 5,
        "name" => "example 7"),
  array("id" => 4,
        "name" => "example 6"),
  array("id" => 7,
        "name" => "example 2"),
  array("id" => 8,
        "name" => "example 1"),
  array("id" => 9,
        "name" => "example 9")
);

if (isset($_GET['p'])) {
  $page = $_GET['p'];
} else {
  $page = 1;
}

// Init object with data, number by page and current page number
$obj = new ArrayWork($data, 3, $page);

// Sort data
$obj->dataSort("id", "ASC");

// Filter columns to take into keep or skip
$paramFilter = array("ActionFilter" => "skip", "id");
$obj->dataFilter($paramFilter);

// Css class for the table balise (example with bootstrap 3)
$cssClass = ['table', 'table-striped'];

// Settings to pager buttom
$pager = array(
  "position" => "bottom",
  "cssClass" => array("pager"),
  "url" => "index.php?p={}"
);

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ArrayWork</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <?php
        // Generate html table (with pager on second parameter)
        echo $obj->generateTable($cssClass, $pager);
      ?>
    </div>
  </body>
</html>

```

#### Future features and documentation in progress...
