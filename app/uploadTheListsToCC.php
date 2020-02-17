<!DOCTYPE HTML>
<html>
<head>
    <title>zZz.Constant Contact API v2 Upload Contact File Example</title>
    <!-- <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet"> -->
    <link href="styles.css" rel="stylesheet">
</head>

<!--
README: Import Contacts from file example
This example flow illustrates how a Constant Contact account owner can upload a file to their contacts. In order for this example to function
properly, you must have a valid Constant Contact API Key as well as an access token. Both of these can be obtained from
http://constantcontact.mashery.com.
-->

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// require the autoloaders
require_once '../src/Ctct/autoload.php';
require_once '../vendor/autoload.php';

use Ctct\ConstantContact;

// Enter your Constant Contact APIKEY and ACCESS_TOKEN
define("APIKEY", "acxyj9gst9j3rwsjm4wx6vwn");
define("ACCESS_TOKEN", "cac50a04-85cb-4d34-ab2f-2a097fe29895");

$cc = new ConstantContact(APIKEY);

// This file queries the database and creates csv files for each cbc.
// Also it creates the $csvFileNamesArr used below.
require_once './db-csv/createCSVfromDB.php';

// GOALS
// -----------------------------------------------------------------------------
// for each .csv file in ../csvContacts upload those contacts to CC

// echo '<pre>';
//   print_r($csvFileNamesArr);
// echo '</pre>';
// echo '<hr style="border:2.4px solid black;">';

// A hard coded array of the contact list ID's from Constant Contact. To find
// these ID's you have to call to the API (
// use the API tester -> https://constantcontact.mashery.com/io-docs ) as the
// ID's are not visibile in the GUI (CC's website).
// Notice I have named the indexes to match the csv file names, which makes it
// easy to find a match.
$listIDsArray = array(
  // 'avamere-at-albany.csv' => '1548726530',
  // 'avamere-at-bethany.csv' => '1995041569',
  // 'avamere-at-cascadia-village.csv' => '1585174354',
  // 'avamere-at-chestnut-lane.csv' => '1530752912',
  // 'avamere-at-cheyenne.csv' => '1461093868',
  // 'avamere-at-englewood-heights.csv' => '1490802359'
  // ^^^^^^^^^ ABOVE ARE ALL THE TEST LIST ID'S
  // THE Actual Contact List ID's are below
  'avamere-at-albany.csv' => '1915856765',
  'avamere-at-bethany.csv' => '1654718594',
  'avamere-at-cascadia-village.csv' => '2063850485',
  'avamere-at-chestnut-lane.csv' => '1874978424',
  'avamere-at-cheyenne.csv' => '1098034043',
  'avamere-at-englewood-heights.csv' => '1397474243',
  'avamere-at-hermiston' => '2060751957',
  'avamere-at-hillsboro' => '2082235697',
  'avamere-at-lexington' => '2132841659',
  'avamere-at-moses-lake' => '1254127753',
  // 'avamere-at-mountain-ridge' => '',
  'avamere-at-newberg' => '1593570705',
  'avamere-at-oak-park' => '1625347543',
  'avamere-at-park-place' => '1493187826',
  'avamere-at-port-townsend' => '2009222702',
  'avamere-at-rio-rancho' => '1758681945',
  'avamere-at-roswell' => '1567867699',
  'avamere-at-sandy' => '1577313238',
  'avamere-at-seaside' => '2040156059',
  'avamere-at-sherwood' => '1674943066',
  'avamere-at-south-hill' => '1657041754',
  'avamere-at-st-helens' => '1813561808',
  'avamere-at-the-stratford' => '1442358372',
  'avamere-at-wenatchee' => '1747134508',
  'avamere-living-at-berry-park' => '1102345200',
  'suzanne-elise' => '1625361259',
  'the-arbor-at-bremerton' => '1227904174',
  'the-stafford' => '1116238271'
);
// uncomment below to see the array
// echo '<pre>';
// print_r($listIDsArray);
// echo '</pre>';

// Loop through the array of csv file names to do a batch upload to each list.
// Note the $csvFileNamesArr comes from createCSVfromDB.php
foreach ($csvFileNamesArr as $file) {
  echo 'The file to find is: '.$file;
  echo '<br><hr>';
  // Here we find a match of the csv file name and the named index in the
  // $listIDsArray array
  foreach ($listIDsArray as $key => $value) {
     // If the file names match
     if ($file == $key) {
       echo 'match found for: '.$file.' <br>';
       echo 'The list ID num is: '.$value;
       echo ' <hr><br>';

       // $fileName = 'testCsvFileToUpload.csv';
       $fileName = $file;
       // TEST CBC Exports list ID
       // $lists = '1661904574';
       $lists = $value;

       // $fileLocation = '../fileLocation/cbcContactsExport.csv';
       // $fileLocation = 'testCsvFileToUpload.csv';
       $fileLocation = '../csvContacts/'.$file;

       $fileUploadStatus = $cc->activityService->createAddContactsActivityFromFile(ACCESS_TOKEN, $fileName, $fileLocation, $lists);
       echo 'Contacts Uploaded... <hr><br>';
     } else {
       // echo ' No match found.';
     }
  }
}


// // This bit of code gets all the contact lists and then they are shown in the
// // select in the GUI below (if you want to use the GUI).
// $contactLists = array();
// $params = array();
// $listsResult = $cc->listService->getLists(ACCESS_TOKEN, $params);
// foreach ($listsResult as $list) {
//     array_push($contactLists, $list);
// }

?>


<!-- GUI For manually uploading -->
<!-- <body>
<div class="well">
    <h3>Import a spreadsheet of Contacts (.xls, .xlsx, .csv, .txt)</h3>

    <form class="form-horizontal" name="submitFile" id="submitFile" method="POST" action="importFromFile.php" enctype="multipart/form-data">
        <div class="control-group">
            <label class="control-label" for="file_name">File Name</label>

            <div class="controls">
                <input type="text" id="file_name" name="file_name" placeholder="File Name">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="file">File</label>

            <div class="controls">
                <input type="file" id="file" name="file" placeholder="Choose File">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="folder">Folder</label>

            <div class="controls">
                <select multiple name="lists" style="resize: both; overflow: auto;">
                  <  ?php
                    foreach ($contactLists as $list) {
                        echo '<option value="' . $list->id . '">' . $list->name . '</option>';
                    }
                    ?>
                </select>

            </div>
        </div>
        <div class="control-group">
            <label class="control-label">
                <div class="controls">
                    <input type="submit" value="Submit" class="btn btn-primary"/>
                </div>
        </div>
    </form>
</div> -->

<?php
// print the details of the contact upload status to screen
if (isset($fileUploadStatus)) {
  echo '<span class="label label-success">File Uploaded!</span>';
  echo '<div class="container alert-success"><pre class="success-pre">';

          print_r($fileUploadStatus);

      echo '</pre></div>';

      echo '<pre>';
        var_dump($_POST);
      echo '</pre>';
}
?>

</body>
</html>
