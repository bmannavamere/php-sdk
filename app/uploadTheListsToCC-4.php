<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// require the autoloaders
// require_once '../src/Ctct/autoload.php';
// above path did not work w/ cron job so trying absolute paths below
require_once '/home/avameremarketing/public_html/pushToConstantContact/src/Ctct/autoload.php';
// require_once '../vendor/autoload.php';
require_once '/home/avameremarketing/public_html/pushToConstantContact/vendor/autoload.php';

use Ctct\ConstantContact;

// Enter your Constant Contact APIKEY and ACCESS_TOKEN
define("APIKEY", "acxyj9gst9j3rwsjm4wx6vwn");
define("ACCESS_TOKEN", "cac50a04-85cb-4d34-ab2f-2a097fe29895");

$cc = new ConstantContact(APIKEY);

// This file, required below, queries the database and creates csv files for
// each cbc. Also it creates the $csvFileNamesArr array used below.
// require_once './db-csv/createCSVfromDB.php';
require_once '/home/avameremarketing/public_html/pushToConstantContact/app/db-csv/createCSVfromDB.php';

// Uncomment the below to see the $csvFileNamesArr
// echo '<pre>';
//   print_r($csvFileNamesArr);
// echo '</pre>';

// A hard coded array of the contact lists ID's from Constant Contact. To find
// these ID's you have to call to the API, ( use the API tester here ->
// https://constantcontact.mashery.com/io-docs ) as the ID's are not visibile in
// the GUI (CC's website). Notice I have named the indexes to match the csv file
// names, which makes it easy to find a match.
$listIDsArray = array(
  // 'avamere-at-albany.csv' => '1915856765',
  // 'avamere-at-bethany.csv' => '1654718594',
  // 'avamere-at-cascadia-village.csv' => '2063850485',
  // 'avamere-at-chestnut-lane.csv' => '1874978424',
  // 'avamere-at-cheyenne.csv' => '1098034043',
  // 'avamere-at-englewood-heights.csv' => '1397474243',
  // 'avamere-at-hermiston.csv' => '2060751957',
  // 'avamere-at-hillsboro.csv' => '2082235697',
  // 'avamere-at-lexington.csv' => '2132841659',
  // 'avamere-at-moses-lake.csv' => '1254127753',
  // 'avamere-at-newberg.csv' => '1593570705',
  // 'avamere-at-oak-park.csv' => '1625347543',
  // 'avamere-at-park-place.csv' => '1493187826',
  // 'avamere-at-port-townsend.csv' => '2009222702',
  // 'avamere-at-rio-rancho.csv' => '1758681945',
  'avamere-at-roswell.csv' => '1567867699',
  'avamere-at-sandy.csv' => '1577313238',
  'avamere-at-seaside.csv' => '2040156059',
  'avamere-at-sherwood.csv' => '1674943066',
  'avamere-at-south-hill.csv' => '1657041754'
  // 'avamere-at-st-helens.csv' => '1813561808',
  // 'avamere-at-the-stratford.csv' => '1442358372',
  // 'avamere-at-wenatchee.csv' => '1747134508',
  // 'avamere-living-at-berry-park.csv' => '1102345200',
  // 'suzanne-elise.csv' => '1625361259',
  // 'the-arbor-at-avamere-court.csv' => '1290808128',
  // 'the-arbor-at-bremerton.csv' => '1227904174',
  // 'the-stafford.csv' => '1116238271'
);

echo '<pre>';
  print_r($csvFileNamesArr);
echo '</pre>';
echo '<hr><br>';

// Loop through the array of csv file names.
foreach ($csvFileNamesArr as $file) {
  echo 'The file to find is: '.$file;
  echo '<br><hr>';
  // Here we find a match of the csv file name and the named index in the
  // $listIDsArray array.
  foreach ($listIDsArray as $key => $value) {
     // If the file names match, then the list ID to use is $value.
     if ($file == $key) {
       echo 'match found for: '.$file.' <br>';
       echo 'The list ID num is: '.$value;
       echo ' <hr><br>';

       // Define the filename to upload.
       $fileName = $file;
       // Define the list ID for the upload.
       $lists = $value;
       // Define the file location.
       // $fileLocation = '../csvContacts/'.$file;
       // Full path for cron job to work
       $fileLocation = '/home/avameremarketing/public_html/pushToConstantContact/csvContacts/'.$file;

       // Run this great code from Constant Contact's GitHub to upload the
       // .csv list of contacts to their website.
       $fileUploadStatus = $cc->activityService->createAddContactsActivityFromFile(ACCESS_TOKEN, $fileName, $fileLocation, $lists);

       // Show the POST data
       if (isset($fileUploadStatus)) {
         echo '<span class="label label-success">File Uploaded!</span>';
         echo '<div class="container alert-success"><pre class="success-pre">';

                 print_r($fileUploadStatus);

             echo '</pre></div>';

             echo '<pre>';
               var_dump($_POST);
             echo '</pre>';
       }
       echo '<hr><br>';
     }
  }
}
