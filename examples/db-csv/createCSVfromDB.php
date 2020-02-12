<?php
// Include the database connec class
include 'db-connec.php';
// DB Credentials
$dbhost = 'localhost';
$dbuser = 'avamerem_forms';
$dbpass = '23m2D09ZhkP4eWnnNr';
$dbname = 'avamerem_web_forms';
// Create new database connec class
$db = new db($dbhost, $dbuser, $dbpass, $dbname);
// Query the web_forms database, contact_form table
$data = $db->query('SELECT page, name, email, time_stamp FROM contact_form WHERE time_stamp IS NOT NULL ORDER BY time_stamp DESC')->fetchAll();
// Declare empty array to hold all the community names that have submissions
$commsArray = array();
// Loop through the response from the database
foreach ($data as $datas) {
  // echo '<pre>';
  //   print_r($datas);
  // echo '</pre>';
  // put all the community names that have contact submissions into an array
  array_push($commsArray, $datas[page]);
}
// create unique array of the community names, so there are no repeating names
$commsArray = array_unique($commsArray);
// Declare empty array
$arrayOfLists = array();
// Declare iterator
$i = 0;
// Loop through the $commsArray and make it into a nicely indexed array in $arrayOfLists
foreach ($commsArray as $data) {
  $arrayOfLists[$i]=[$data];
  $i++;
}
// echo '<pre>';
//   print_r($arrayOfLists);
// echo '</pre>';

// now backup and put the contact forms submission in the correct  $arrayOfLists[] (community name)
$data2 = $db->query('SELECT page, name, email, time_stamp FROM contact_form WHERE time_stamp IS NOT NULL ORDER BY time_stamp DESC')->fetchAll();
foreach ($data2 as $datas2) {

      foreach ($arrayOfLists as $key => $value) {
         // define the value (the page path) from $arrayOfLists to find a match
         // for in the database response
         $match = $value[0];

         if ($datas2[page] == $match) {
           // echo '$datas2[page] value is: '.$datas2[page];
           // echo '<br><hr>';
           // echo 'The match value is: '.$match;
           // echo '<br><hr>';
           // echo 'match found <br><hr>';
           // echo 'the key to be using is: '.$key;
           // echo '<br><hr>';
           // then do the magic owwwwwww
           $arrayOfLists[$key][contacts][] = $datas2[name].', '. $datas2[email];
         }
      }
}

  echo '<pre>';
    print_r($arrayOfLists);
  echo '</pre>';

//   echo '<pre>';
//     print_r($datas2);
//   echo '</pre>';
// echo $datas2[page];
//   // if ($datas2[page] == ) {
//   //
//   // }
//     $i = 0;
//     foreach ($arrayOfLists as $key => $value) {
//       // echo $key[$i];
//       if ($datas2[page] === $key[$i]) {
//         echo 'match foujnd';
//       }
//       $i++;
//     }

// $arrayOfLists



// foreach ($data as $datas) {
//   echo '<pre>';
//     print_r($datas);
//   echo '</pre>';
// }

// Query DB tables to create CSV files
// class createCSV {
//   public function __construct(){
//     $db = Db::getInstance();
//     $this->_dbh = $db->getConnection();
//   }
//   function contactFormCSV() {
//     // $sql = "TRUNCATE TABLE jobcategories; TRUNCATE TABLE jobs; TRUNCATE TABLE jobdetails";
//     $sql = "SELECT page, name, email, time_stamp FROM contact_form WHERE time_stamp IS NOT NULL ORDER BY time_stamp DESC";
//     foreach ($this->_dbh->query($sql) as $row) {
//                 echo '<pre>';
//                   // print_r($row['page']);
//                   print_r($row);
//                 echo '</pre>';
//             }
//   }
//   // do same but for tour
// }
// $tablesObj = new createCSV();
// $tablesObj->contactFormCSV();
