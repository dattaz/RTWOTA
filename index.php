<!DOCTYPE html>
<html style="width:100%">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>twitter without twitter </title>
    <link id="stylecss" rel="stylesheet" href="style/style.css" type="text/css">
    <style type="text/css"> .selectable_theme:not(.theme_nin){display:none!important;}</style>
    <script>
      function httpGetAsync(theUrl, callback)
      {
          var xmlHttp = new XMLHttpRequest();
          xmlHttp.onreadystatechange = function() { 
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200)

                                  callback(xmlHttp.responseText);
                        }
          xmlHttp.open("GET", theUrl, true);
          xmlHttp.send(null);
      }
      function add_to_posts(text){
        if(text != ""){
          document.getElementById("posts").innerHTML += text;
        }else{
          document.getElementById("more").innerHTML = "<p> Sorry no more tweets </p>";
        }
      }
      function more(){
        var nb_of_tweet = document.getElementById("posts").getElementsByTagName("li").length;
        url = "more.php?offset=" + nb_of_tweet;
        console.log(url);
        nb_of_tweet += 100;
        var text = httpGetAsync(url, add_to_posts);

      }

    </script>
</head>
<body style="width:100%">
        <ol id="posts" class="postboard-posts" style="width:100%">

<?php
include_once "show_tweet.php";
try {
     $handle = new PDO('sqlite:tweets.sqlite');
     $handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
     die('Erreur : '.$e->getMessage());
}

$req = $handle->prepare("SELECT * FROM tweets WHERE isanswer = 0 ORDER BY timestamp DESC LIMIT 100 ");
$req->execute();


$result = $req->fetchAll();
foreach($result as $row){
    print_tweet($row);
}
?>
</ol>
<a id="more" onclick="more()"> Show more </a>
</body></html>
