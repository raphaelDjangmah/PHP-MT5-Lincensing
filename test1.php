<?php

  if($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo "ACCESS DENIED";
  }else{
    if(isset($_GET['name'])){
       echo ($_GET['name']);
    }else{
        echo "POST -> not seen";
    }
  }