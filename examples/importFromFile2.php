<!DOCTYPE HTML>
<html>
<head>
    <title>Constant Contact API v2 Upload Contact File Example</title>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
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

// NOTE* I originally did CURL To get the file but that is not needed

// GOALS:
// 1st - GET CSV EXPORT FROM CBC DB TABLE W/ USERNAME, EMAIL, & PAGE PATH
// LOOP THROUGH THE CSV AND FOR EACH

// if ( some condition then run ) {

    $fileName = 'cbcContactsExport.csv';
    // TEST CBC Exports list ID
    $lists = '1661904574';

    // $fileLocation = '../fileLocation/cbcContactsExport.csv';
    $fileLocation = 'cbcContactsExport.csv';

    $fileUploadStatus = $cc->activityService->createAddContactsActivityFromFile(ACCESS_TOKEN, $fileName, $fileLocation, $lists);

// }

$contactLists = array();
$params = array();
$listsResult = $cc->listService->getLists(ACCESS_TOKEN, $params);
foreach ($listsResult as $list) {
    array_push($contactLists, $list);
}
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
