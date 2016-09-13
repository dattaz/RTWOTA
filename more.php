<?php
include_once "show_tweet.php";
try {
     $handle = new PDO('sqlite:tweets.sqlite');
     $handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
     die('Erreur : '.$e->getMessage());
}
if (isset($_GET['offset'])){
  $offset = SQLite3::escapeString($_GET['offset']);
  if (isset($_GET['user'])){ // we want more of a user
    $account = SQLite3::escapeString($_GET['user']);
    if ($_GET['show_answers'] != "True"  ){ // and without answers
      $req = $handle->prepare("SELECT * FROM tweets WHERE timelineof = :timelineof and isanswer = 0 ORDER BY timestamp DESC LIMIT 100 OFFSET :offset");
    }else{
      $req = $handle->prepare("SELECT * FROM tweets WHERE timelineof = :timelineof ORDER BY timestamp DESC LIMIT 100 OFFSET :offset");
    }
    $req->bindValue(':timelineof', $account);
    $req->bindValue(':offset', $offset);
    $req->execute();
  }else { //we want more of a imeline
    $req = $handle->prepare("SELECT * FROM tweets WHERE isanswer = 0 ORDER BY timestamp DESC LIMIT 100 OFFSET :offset"); 
    $req->bindValue(':offset', $offset);
    $req->execute();
  }
  
  $result = $req->fetchAll();
  foreach($result as $row){
    print_tweet($row);
  }
}
?>
