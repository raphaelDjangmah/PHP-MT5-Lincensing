<?php

    //-- database connection
    include('DbConnection.php');

    class API {
        public function generateAPI(){
                //Generate a random string.
                $token = openssl_random_pseudo_bytes(32);
                
                //Convert the binary data into hexadecimal representation.
                $token = bin2hex($token);
                
                //Print it out for example purposes.
                return $token;
        }

        public function saveToken($email, $phone, $save_or_update=True){
            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_tokens";
            

            if($db->get_conn_id() != 1){
                echo "Database Connection Failed\n".$db->get_conn_text();
                return;
            }

            //-- sanitizing strings
            $email    = htmlspecialchars(strip_tags($email));
            $phone    = htmlspecialchars(strip_tags($phone));

            if(empty($email) || empty($phone)){
                return "NULL values not allowed";
            }

            //-- check to prevent duplicates
            $dup_query = sprintf("SELECT * FROM %s WHERE EMAIL=? OR PHONE=?",$table_name);
            $obj = $connection->prepare($dup_query);
            $obj->bind_param("si",$email,$phone);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);

            if($save_or_update && $result_number > 0 ){
                return "Token already exists";
            }

            if(!$save_or_update && $result_number <= 0 ){
                return "Does not have a token";
            }

            echo "good to go";

        }
    }


    $api = new API();
    echo $api->saveToken('raphael@gmail.com',549022485);