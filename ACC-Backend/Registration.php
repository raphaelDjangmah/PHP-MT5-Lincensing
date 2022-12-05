<?php

    //-- database connection
    require('DbConnection.php');

    class USERS{
        public function create_user($email, $password,$phone,$country){
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
            $date     = date('m/d/Y h:i:s a', time());


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
            $stmt->bind_param('ssiss',$email,$password,$phone,$date,$country);

            if(!$stmt->execute()){
                return "An error occured in inserting data";
            }
            
            return "Data inserted successfully";
        }
    }


$users = new USERS();
echo $users->create_user('raphael@gmail.com','my-password',549022485,'dema');