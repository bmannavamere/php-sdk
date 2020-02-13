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
$data = $db->query('SELECT page, name, email, time_stamp FROM contact_form WHERE time_stamp IS NOT NULL ORDER BY time_stamp DESC')->fetchAll();
// Declare empty array to hold all the community names that have submissions
$commsArray = array();

// Loop through the response from the database
foreach ($data as $datas) {
  // put all the community names that have contact submissions into an array
  array_push($commsArray, $datas['page']);
}

// create unique array of the community names, so there are no repeating names
$commsArray = array_unique($commsArray);

// declare empty array
$arrayOfLists = array();
// Declare iterator
$i = 0;
// Loop through the $commsArray and make it into a nicely indexed array in $arrayOfLists
foreach ($commsArray as $data) {
  $arrayOfLists[$i]=[$data];
  $i++;
}

// now backup... query the database again and put the contact form submissions in
// the matching $arrayOfLists's community name
// Note: use the TRIM() function here to remove white spaces before and after the name and email
$data2 = $db->query('SELECT page, TRIM(name), TRIM(email) FROM contact_form WHERE page IS NOT NULL ORDER BY page DESC')->fetchAll();
foreach ($data2 as $datas2) {
  foreach ($arrayOfLists as $key => $value) {
     // define the value (the page path) from $arrayOfLists to find a match
     // for in the database response
     $match = $value[0];

     // if the page paths match
     if ($datas2['page'] == $match) {
       // add all the names/emails in [contacts]
       // use an empty [] after [contacts] so that it auto increments (basically)
       $arrayOfLists[$key]['contacts'][] = $datas2['TRIM(name)'].', '. $datas2['TRIM(email)']; //."\n"
     }
  }
}

// echo '<pre>';
//   print_r($arrayOfLists);
// echo '</pre>';

// Next create .csv files for each community
// do this is a foreach loop to make and save a .csv file for each community
// https://www.php.net/manual/en/function.fputcsv.php
// create an array of the csv file names
$csvFileNamesArr = array();
$i = 0;
foreach ($arrayOfLists as $line) {
  // echo '<pre>';
  //   print_r($line['contacts']);
  // echo '</pre>';

  // grab the community names (aka page paths)
  $page_path = $arrayOfLists[$i][0];
  // echo '<pre>';
  // print_r($arrayOfLists[$i][0]);
  // echo '</pre>';
  array_push($csvFileNamesArr, ltrim($arrayOfLists[$i][0], '/').'.csv');

  // trim off the slash at the beginning
  $csvFile = ltrim($page_path, '/');
  // concat '.csv' to the string
  $csvFilename = $csvFile . '.csv';
  // echo $csvFilename;

  $fp = fopen('../csvContacts/'.$csvFilename, 'w');

  $contactArr = $line['contacts'];
  // fputcsv($fp, $contactArr, "\n");
  // Forget fputcsv! It just will not, not do double quotes.
  fwrite($fp, implode(" \n",$contactArr));

  fclose($fp);

  // add in the column headers required by Constant Contact at the
  // beginning of the csv file
  // https://www.php.net/manual/en/function.file-put-contents.php
  // Open the file to get existing content
  $file = '../csvContacts/'.$csvFilename;
  // Define new content
  $current = "First Name, Email Address\n";
  // Append the file to the new content
  $current .= file_get_contents($file);
  // Write the contents back to the file
  file_put_contents($file, $current);

  $i++;
}

// echo '<pre>';
//   print_r($csvFileNamesArr);
// echo '</pre>';
