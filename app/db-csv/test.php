<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include a custom database connection class.
include 'db-connec.php';
// Define the DB credentials to use.
// TEST DB credentials
$dbhost = 'localhost';
$dbuser = 'avamerem_form_dev';
$dbpass = 'pB$?HN#Q.)d}';
$dbname = 'avamerem_dev_web_forms';
// LIVE DB credentials
// $dbhost = 'localhost';
// $dbuser = 'avamerem_forms';
// $dbpass = '23m2D09ZhkP4eWnnNr';
// $dbname = 'avamerem_web_forms';
// Create new instance of the db class.
$db = new db($dbhost, $dbuser, $dbpass, $dbname);


// SELECT page, firstName, lastName, email, time_stamp
// FROM contact_form
// WHERE contact_form.time_stamp IS NOT NULL
// UNION ALL
// SELECT page, firstName, lastName, email, time_stamp
// FROM tour_form
// WHERE tour_form.time_stamp IS NOT NULL
// ORDER BY time_stamp DESC

$data = $db->query('
                      SELECT page, firstName, lastName, email, time_stamp
                      FROM contact_form
                      WHERE contact_form.time_stamp >= now() - INTERVAL 1 DAY
                      UNION ALL
                      SELECT page, firstName, lastName, email, time_stamp
                      FROM tour_form
                      WHERE tour_form.time_stamp >= now() - INTERVAL 1 DAY
                      ORDER BY time_stamp DESC
                   ')->fetchAll();
// Uncomment to view the queries result set.
echo '<pre>';
  print_r($data);
echo '</pre>';
