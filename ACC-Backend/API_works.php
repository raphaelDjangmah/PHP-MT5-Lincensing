<?php

    //-- database connection
    require_once('DbConnection.php');

    class API {
        public static function generateAPIToken(){
                //Generate a random string.
                $token = openssl_random_pseudo_bytes(32);
                
                //Convert the binary data into hexadecimal representation.
                $token = bin2hex($token);
                

                //to be sure a token does not already exists, we continously check if the generated token against the db
                $db = new DbConnect(); 
                $connection = $db->connect();
                $table_name = "user_tokens";
                $dup_query = sprintf("SELECT TOKEN FROM %s",$table_name);
                $obj = $connection->prepare($dup_query);
                
                if(!$obj->execute()){
                    return "-1";
                }
                $result = $obj->get_result();

                $already_exists = false;
                while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                    foreach ($row as $r) {
                        if($r == $token){
                            $already_exists = true;
                            break;
                        }
                    }
                }


                if($already_exists){
                    $this->generateAPIToken();
                }
                
            return (string)$token;
        }

        public function saveToken($email, $save_or_update=True){
            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_tokens";
            
            if($db->get_conn_id() != 1){
                return "Database Connection Failed\n".$db->get_conn_text();
            }

            //-- sanitizing strings
            $email    = htmlspecialchars(strip_tags($email));

            if(empty($email)){
                return "NULL values not allowed";
            }



            //making the email exists
            $e_query = sprintf("SELECT * FROM %s WHERE EMAIL=?","user_registration");
            $obj_ = $connection->prepare($e_query);
            $obj_->bind_param("s",$email);

            if(!$obj_->execute()){
                return "An unknown error occured!";
            }

            $result_ = $obj_->get_result();
            $result_number_ = mysqli_num_rows($result_);

            if($result_number_ <= 0 ){
                return "Invalid account email";
            }

            //-- check to prevent duplicates
            $dup_query = sprintf("SELECT * FROM %s WHERE EMAIL=?",$table_name);
            $obj = $connection->prepare($dup_query);
            $obj->bind_param("s",$email);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);

            if($save_or_update && $result_number > 0 ){
                return "Token already exists this account";
            }

            if(!$save_or_update && $result_number <= 0 ){
                return "No Token exists";
            }

            // we either update or save new to database
            $query = ($save_or_update)?sprintf("INSERT INTO %s SET EMAIL =?, TOKEN=?",$table_name):sprintf("UPDATE  %s SET TOKEN=? WHERE EMAIL=?",$table_name);
            $stmt = $connection->prepare($query);

            //============ in the future.. we will explore security tight procedures to save and retrieve api tokens================
            $gen_token = $this->generateAPIToken();

            if($save_or_update){
                $stmt->bind_param("ss",$email,$gen_token);
            }else{
                $stmt->bind_param("ss",$gen_token,$email);
            }

            return ($stmt->execute())?(($save_or_update)?"Token generated successfully":"Token updated successfully"):"api token operation failed!";
        }

        public function verifyToken($token){

            //--we return the email of the user if the token is done
            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_tokens";
            
            if($db->get_conn_id() != 1){
                return "Database Connection Failed\n".$db->get_conn_text();
            }

            //-- sanitizing strings
            $token    = htmlspecialchars(strip_tags($token));

            if(empty($token)){
                return "NULL values not allowed";
            }

            //-- check to prevent duplicates
            $dup_query = sprintf("SELECT * FROM %s WHERE TOKEN=?",$table_name);
            $obj = $connection->prepare($dup_query);
            $obj->bind_param("s",$token);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);

            if($result_number < 0 ){
                return NULL; //meaning there is no data
            }
            
            //-- looping through the result.. but since it is a specific query, we will get only 1
            $mail = "";
            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                foreach ($row as $r) {
                    $mail = $r;
                    break;
                }
            }

            return $mail;   
        }
    }

    $api = new API();

    //echo $api->saveToken('raphael@gmail.com',true);
    // //echo "<br/>";
    // echo $api->verifyToken('f33ed08a7dc42fc71f93c5c892810a3b98fd415a9018184b6641bbf07b538ec7');