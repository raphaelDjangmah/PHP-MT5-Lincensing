<?php

    //-- database connection
    require_once('DbConnection.php');
    date_default_timezone_set('Africa/Accra');

    class USERS{
        
        public function create_user($email, $password,$phone,$country){

            //-- return 1 for successfully creating user else error text

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
            $country  = htmlspecialchars(strip_tags($country));
            $date     = time();


            if(empty($email) || empty($password) || empty($phone) || empty($country)){
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
            if($result_number >0 ){
                return "User already exists";
            }

            //-- add user to database - hash password
            $password   = password_hash($password, PASSWORD_BCRYPT);
            $ins_query = sprintf("INSERT INTO %s SET EMAIL=?, PASSWORD=?, PHONE=?, DATE_CREATED=?, COUNTRY=?",$table_name);
            $stmt  = $connection->prepare($ins_query);
            $stmt->bind_param('ssiis',$email,$password,$phone,$date,$country);

            if(!$stmt->execute()){
                return "An error occured in inserting data";
            }
            
            return 1;
        }


        public function verify_user($email,$password){
            
            //-- return 1 for successfully creating user else error text
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

            if(empty($email) || empty($password)){
                return "NULL values not allowed";
            }

            //-- get email and password
            $dup_query = sprintf("SELECT PASSWORD,USER_TYPE FROM %s WHERE EMAIL=?",$table_name);
            $obj = $connection->prepare($dup_query);
            $obj->bind_param("s",$email);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);
            if($result_number == 0 ){
                return "Email does not exist";
            }

            $hashed = "";
            $acc_type = 0;
            //-- verify hashed password
            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                $hashed = $row[0];
                $acc_type = $row[1];
            }
            
            if(!password_verify($password,$hashed)){
                return "Password Incorrect";
            }

            //-- if it is an admin account type, return 11;
            if($acc_type==1){
                return 11;
            }

            return 1;


        }
    }


//$users = new USERS();
//echo $users->verify_user('raphael@gmail.com','my-password');
// echo $users->create_user('raphael@gmail.com','my-password',549022485,'Ghana');