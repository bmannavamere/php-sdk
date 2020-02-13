<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db-connec.php';

$dbhost = 'localhost';
$dbuser = 'avamerem_form_dev';
$dbpass = 'pB$?HN#Q.)d}';
$dbname = 'avamerem_dev_web_forms';

$db = new db($dbhost, $dbuser, $dbpass, $dbname);

// Query the web_forms database, contact_form table
$data = $db->query('SELECT TRIM(name), TRIM(email) FROM contact_form ORDER BY page')->fetchAll();

// function trim_value(&$value)
// {
//     $value = trim($value);
// }
//
// $fruit = array('apple','banana ', ' cranberry ');
// // var_dump($fruit);
// echo '<pre>';
//   print_r($fruit);
// echo '</pre>';
//
// array_walk($fruit, 'trim_value');
// // var_dump($fruit);
// echo '<pre>';
//   print_r($fruit);
// echo '</pre>';
//
echo '<pre>';
  print_r($data);
echo '</pre>';

// $list = array (
//     array('aaa', 'bbb', 'ccc', 'dddd'),
//     array('123', '456', '789'),
//     array('aaa', 'bbb')
// );
//
$fp = fopen('file.csv', 'w');

foreach ($data as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);



// $list = array (
//     array('First Name', 'Last Name', 'Email Address'),
//
//     array('first', 'last', 'email'),
//
//     array('123', '45v 6', '789'),
//     array('"aaa"', '"bbb"')
// );
//
// $fp = fopen('file.csv', 'w');
//
// foreach ($list as $fields) {
//     fputcsv($fp, $fields);
// }
//
// fclose($fp);
?>
