<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db-connec.php';

$dbhost = 'localhost';
$dbuser = 'avamerem_forms';
$dbpass = '23m2D09ZhkP4eWnnNr';
$dbname = 'avamerem_web_forms';

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

$arrayOfLists = array();
// Declare iterator
$i = 0;
// Loop through the $commsArray and make it into a nicely indexed array in $arrayOfLists
foreach ($commsArray as $data) {
  $arrayOfLists[$i]=[$data];
  $i++;
}

// now backup, query the database again and put the contact form submissions in
// the matching $arrayOfLists[] (community name)
$data2 = $db->query('SELECT page, name, email, time_stamp FROM contact_form WHERE time_stamp IS NOT NULL ORDER BY time_stamp DESC')->fetchAll();
foreach ($data2 as $datas2) {
  foreach ($arrayOfLists as $key => $value) {
     // define the value (the page path) from $arrayOfLists to find a match
     // for in the database response
     $match = $value[0];

     // if the page paths match
     if ($datas2['page'] == $match) {
       // add all the names/emails in [contacts]
       $arrayOfLists[$key]['contacts'][] = $datas2['name'].', '. $datas2['email'];
     }
  }
}

echo '<pre>';
  print_r($arrayOfLists);
echo '</pre>';

// Next create .csv files for each community
// https://www.php.net/manual/en/function.fputcsv.php
// do this is a foreach loop to make and save a .csv file for each community
$i = 0;
foreach ($arrayOfLists as $key => $value) {
  // echo '<pre>';
  // print_r($arrayOfLists[$i][0]);
  // echo '</pre>';
  // grab the community names (aka page paths)
  $page_path = $arrayOfLists[$i][0];
  // trim off the slash at the beginning
  $csvFile = ltrim($page_path, '/');
  // concat '.csv' to the string
  $csvFilename = $csvFile . '.csv';
  // echo $csvFilename;

  $fp = fopen('../../fileLocation/'.$csvFilename, 'w');
  //add BOM to fix UTF-8 in Excel
fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

  // foreach ($list as $fields) {
  // foreach ($arrayOfLists as $key => $value) {

  // !!!!!!!!!! THIS LINE HERE WORKS TO PUT THE CONTACTS INTO THE FILES, BUT IT PUTS THEM ALL ON
  // ONE LONG LINE SEPARATED BY "," BLAH BLAH BALHVLHALHSDFHDSKLHFLKDS DKLF HSDLDF KLDSFHDL HSD.
      // fputcsv($fp, $arrayOfLists[$i]['contacts']);

      // put just the contacts into an array?
      $contactsOnlyArr = array();
      $contactsOnlyArr[] = $arrayOfLists[$i]['contacts'];
      foreach ($contactsOnlyArr as $fields) {
        fputcsv($fp, $fields, ',');
      }

  // }




  fclose($fp);

  // plus one to the iterator
  $i++;
}
// $fp = fopen('../../fileLocation/file.csv', 'w');
//
// foreach ($list as $fields) {
//     fputcsv($fp, $fields);
// }
//
// fclose($fp);
