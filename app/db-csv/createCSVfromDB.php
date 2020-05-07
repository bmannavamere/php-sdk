<?php

// Created by Brian Mann 2-14-20
// WHAT THIS FILE DOES ---------------------------------------------------------
// This file queries our database at AvamereMarketing.com and retrives all the
// names & emails of users who submitted web forms. These names and emails are
// then put into .csv files. One file is created for each of our CBC's.
// -----------------------------------------------------------------------------

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include a custom database connection class.
include 'db-connec.php';
// Define the DB credentials to use.
// TEST DB credentials
// $dbhost = 'localhost';
// $dbuser = 'avamerem_form_dev';
// $dbpass = 'pB$?HN#Q.)d}';
// $dbname = 'avamerem_dev_web_forms';
// LIVE DB credentials
$dbhost = 'localhost';
$dbuser = 'avamerem_forms';
$dbpass = '23m2D09ZhkP4eWnnNr';
$dbname = 'avamerem_web_forms';
// Create new instance of the db class.
$db = new db($dbhost, $dbuser, $dbpass, $dbname);

// Begin by querying the database. The query below uses the UNION statement to
// query both the contact_form table and the tour_form table and combine the
// result set. All records have a time_stamp, so I used that for a where clause.

// 5-6-20 .bak of original query
// $data = $db->query('
//                       SELECT page, firstName, lastName, email, time_stamp
//                       FROM contact_form
//                       WHERE contact_form.time_stamp IS NOT NULL
//                       UNION ALL
//                       SELECT page, firstName, lastName, email, time_stamp
//                       FROM tour_form
//                       WHERE tour_form.time_stamp IS NOT NULL
//                    ')->fetchAll();

// NEW DB QUERY 5-6-20 - to just get records from the last 24 hours.
$data = $db->query('
                      SELECT page, firstName, lastName, email, time_stamp
                      FROM contact_form
                      WHERE contact_form.time_stamp >= now() - INTERVAL 1 DAY
                      UNION ALL
                      SELECT page, firstName, lastName, email, time_stamp
                      FROM tour_form
                      WHERE tour_form.time_stamp >= now() - INTERVAL 1 DAY
                   ')->fetchAll();

// Uncomment to view the queries result set.
// echo '<pre>';
//   print_r($data);
// echo '</pre>';

// Declare empty array which wil hold all the community names.
$commsArray = array();

// Loop through the result set from the database.
foreach ($data as $datas) {
  // Push all the community names into the $commsArray.
  array_push($commsArray, $datas['page']);
}
// Uncomment to view the $commsArray.
// echo '<pre>';
//   print_r($commsArray);
// echo '</pre>';

// Create an array w/ unique community names, so there are no repeating names.
$commsArray = array_unique($commsArray);
// Uncomment to view the $commsArray w/ only unique values.
// echo '<pre>';
//   print_r($commsArray);
// echo '</pre>';

// Declare an empty array to use for cleaning up the indexes on the $commsArray.
$arrayOfLists = array();
$i = 0;
// Loop through the $commsArray and make it into a nicely indexed array.
foreach ($commsArray as $data) {
  $arrayOfLists[$i]=[$data];
  $i++;
}
// Uncomment to view the $arrayOfLists, which is the previous $commsArray,
// only now it is nicely indexed.
// echo '<pre>';
//   print_r($arrayOfLists);
// echo '</pre>';

// Now we backup, query the database again, and put the users names & emails
// from the form submissions into the $arrayOfLists.

// 5-6-20 .bak of original query
// $data2 = $db->query('
//                       SELECT page, TRIM(firstName), TRIM(lastName), TRIM(email)
//                       FROM contact_form
//                       WHERE contact_form.page IS NOT NULL
//                       UNION ALL
//                       SELECT page, TRIM(firstName), TRIM(lastName), TRIM(email)
//                       FROM tour_form
//                       WHERE tour_form.page IS NOT NULL
//                     ')->fetchAll();

// NEW DB QUERY 5-6-20 - to just get records from the last 24 hours.
$data2 = $db->query('
                      SELECT page, TRIM(firstName), TRIM(lastName), TRIM(email), time_stamp
                      FROM contact_form
                      WHERE contact_form.time_stamp >= now() - INTERVAL 1 DAY
                      UNION ALL
                      SELECT page, TRIM(firstName), TRIM(lastName), TRIM(email), time_stamp
                      FROM tour_form
                      WHERE tour_form.time_stamp >= now() - INTERVAL 1 DAY
                    ')->fetchAll();


// Uncomment to view the $data2 result set from the query. Note that the named
// indexes include the trim function reference like so --> [TRIM(name)]
// echo '<pre>';
//   print_r($data2);
// echo '</pre>';

// Loop through the result set:
foreach ($data2 as $datas2) {
  // For each result, we need to find a matching 'page' value in the previously
  // defined array of community names (page_path's).
  foreach ($arrayOfLists as $key => $value) {
     // Define the value (the page path) from $arrayOfLists from which we
     // will use to find a match.
     $match = $value[0];

     // If the page paths match:
     if ($datas2['page'] == $match) {
       // Add the name and email to a new array ['contacts']
       // Use an empty [] after [contacts] so that it will auto increment.
       // Seperate the name and email with a comma and space for the CSV file.
       $arrayOfLists[$key]['contacts'][] = $datas2['TRIM(firstName)'].' '.$datas2['TRIM(lastName)'].', '. $datas2['TRIM(email)'];
     }
  }
}
// Uncomment the below to see that the user names & emails have been added.
// echo '<pre>';
//   print_r($arrayOfLists);
// echo '</pre>';

// Next, within a for each loop, we will create one csv file for each community.
// Refer to: https://www.php.net/manual/en/function.fputcsv.php Although I ended
// up not using fputcsv(), the other file functions were used.

// Declare an empty array which will hold the csv file names. Note, this array
// will be used in uploadTheListsToCC.php file and not in this file.
$csvFileNamesArr = array();

$i = 0;
// Loop through the previously created $arrayOfLists, which now contains all
// the desired user contact info.
foreach ($arrayOfLists as $line) {

  // Define the community names (aka page paths) in a variable.
  $page_path = $arrayOfLists[$i][0];

  // Create each csv file name by pushing the page_path's into the array to be
  // used in uploadTheListsToCC.php
  // Also trim the forward slash at the beginning and concatenate .csv at end.
  array_push($csvFileNamesArr, ltrim($arrayOfLists[$i][0], '/').'.csv');

  // Trim off the slash at the beginning of the filename.
  $csvFile = ltrim($page_path, '/');
  // Concat '.csv' to the end of the filename.
  $csvFilename = $csvFile . '.csv';

  // $fp = fopen('../../csvContacts/'.$csvFilename, 'w');
  // UNCOMMENT BELOW, AND COMMENT ABOVE to run from the directory above this 1.
  // $fp = fopen('../csvContacts/'.$csvFilename, 'w');
  // Absolute path from /home for cron job to be able to run this
  $fp = fopen('/home/avameremarketing/public_html/pushToConstantContact/csvContacts/'.$csvFilename, 'w');

  $contactArr = $line['contacts'];
  //
  fwrite($fp, implode(" \n",$contactArr));
  // Close the file.
  fclose($fp);

  // Now we add in the column headers required by Constant Contact on the first
  // line of each csv file.

  // Open the file to get existing content
  // $file = '../../csvContacts/'.$csvFilename;
  // UNCOMMENT BELOW, AND COMMENT ABOVE to run from the above dir
  // $file = '../csvContacts/'.$csvFilename;
  // Absolute path from /home for cron job to be able to run this.
  $file = '/home/avameremarketing/public_html/pushToConstantContact/csvContacts/'.$csvFilename;

  // Define new content to add.
  $current = "First Name, Email Address\n";
  // Append the file to the new content.
  $current .= file_get_contents($file);
  // Write the contents back to the file.
  // For Ref: https://www.php.net/manual/en/function.file-put-contents.php
  file_put_contents($file, $current);

  $i++;
}
