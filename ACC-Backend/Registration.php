<?php

    //-- database connection
    require('DbConnection.php');

    class USERS{
        public function create_user($email, $password,$phone,$date,$country){
            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_registration";

            if($db->get_conn_id() != 1){
                echo "Database Connection Failed\n".$db->get_conn_text();
                return;
            }

            //-- sanitizing strings
            $email    = htmlspecialchars(strip_tags($email));
            $password = htmlspecialchars(strip_tags($password));
            $phone    = htmlspecialchars(strip_tags($phone));
            $date     = htmlspecialchars(strip_tags($date));
            $country  = htmlspecialchars(strip_tags($country));


            //-- check to prevent duplicates
            $dup_query = sprintf("SELECT * FROM %s  WHERE email=?",$table_name);
            $obj = $connection->prepare($query);
            //$stmt = $obj->bind_param("sss",$this->name, $this->email, $this->mobile);

            echo $obj;


        }
    }


$users = new USERS();
$users->create_user('raphael@gmail.com','my-password',549022485,'27-90-97','Ghana');