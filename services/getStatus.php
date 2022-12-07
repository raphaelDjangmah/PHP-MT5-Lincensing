<?php
    require "../ACC-Backend/API_works.php";
    require "../ACC-Backend/Subscription.php";

    /*
        RETURN CODES
        1 = ACCESS DENIED
        2 = INCOMPLETE REQUEST
        3 = INVALID API KEY
        4 = SUBSCRIPTION ACTIVE
        5 = SUBSCRIPTION EXPIRED
        6 = NO ACTIVE SUBSCRIPTION
    */


    
  if($_SERVER['REQUEST_METHOD'] != 'GET'){
        http_response_code(404);
        echo json_encode(array(
            'status' => -1,
            'message' => "Access Denied"
        ));
  }else{
    if(!isset($_GET['api_key'])){
        http_response_code(404);
        echo json_encode(array(
            'status' => -2,
            'message' => "No API key specified"
        ));
    }else{
        $token = $_GET['api_key'];
        //verify the token.
        $api = new API();
        $result = $api->verifyToken($token);

        if(strlen($result)<=0){
            http_response_code(200);
            echo json_encode(array(
                'status' => -3,
                'message' => "Invalid API Key!"
            ));
        }else{
            $subs = new Subscriptions();
            $result = $subs->subscription_status($result);
            $message = "";
            $status = 0;

            switch($result){
                case 1:
                    $message = "Subscription Active";
                    $status=1;break;
                case -1:
                    $message = "Subscription Expired";
                    $status=2;break;
                case 0:
                    $message =  "No Active Subscription";
                    $status=3;break;
            }

            http_response_code(200);
            echo json_encode(array(
                'status' => $status,
                'message' => $message
            ));
        }
    }
  }

    