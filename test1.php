<?php

date_default_timezone_set('Africa/Accra');
$date_a = date('m/d/Y h:i:s a',1670336093);
$date_b = date('m/d/Y h:i:s a',time());

echo time() - 1670336093;

return; 


  if($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo "ACCESS DENIED";
  }else{
    if(isset($_GET['name'])){
       echo ($_GET['name']);
    }else{
        echo "POST -> not seen";
    }
  }

