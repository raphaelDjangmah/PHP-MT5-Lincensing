<?php
    require "../ACC-Backend/API_works.php";
    require "../ACC-Backend/Subscription.php";

    
    
  if($_SERVER['REQUEST_METHOD'] != 'POST'){
    echo "ACCESS DENIED";
  }else{
    if(!isset($_GET['api_key'])){
       echo "Incomplete request";
    }else{
        $token = $_GET['api_key'];

        //verify the token.
        $api = new API();
        $result = $api->verifyToken($token);

        if(strlen($result)<=0){
            echo "Invalid API key";
        }else{
            $subs = new Subscriptions();
            $result = $subs->subscription_status($result);

            switch($result){
                case 1:
                    echo "Subscription active";break;
                case -1:
                    echo "Subscription expired!";break;
                case 0:
                    echo "you don't have an active subscription";break;
            }
        }
    }
  }

    