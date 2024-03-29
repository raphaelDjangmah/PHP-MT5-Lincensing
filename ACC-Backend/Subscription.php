<?php

    //-- database connection
    require_once('DbConnection.php');
    date_default_timezone_set('Africa/Accra');

    class Subscriptions{

        //-- return 1 for successfull subscription, else error text

        public function subscribe($email, $amount_paid, $package, $save_or_update=TRUE){
            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_subscriptions";
            

            if($db->get_conn_id() != 1){
                echo "Database Connection Failed\n".$db->get_conn_text();
                return;
            }

            //-- sanitizing strings
            $email         = htmlspecialchars(strip_tags($email));
            $amount_paid   = htmlspecialchars(strip_tags($amount_paid));
            $package       = htmlspecialchars(strip_tags($package));
            $date_paid     = time();


            if(empty($email) || empty($amount_paid) || empty($package)){
                return "NULL values not allowed";
            }

            //-- making sure account is valid
            $dup_query = sprintf("SELECT * FROM %s WHERE EMAIL=?","user_registration");
            $obj = $connection->prepare($dup_query);
            $obj->bind_param("s",$email);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);
            if($result_number <= 0 ){
                return "This user is unrecognized"; 
            }

            //preventing duplicate payment
            $dup_query = sprintf("SELECT * FROM %s WHERE EMAIL=?",$table_name);
            $obj = $connection->prepare($dup_query);
            $obj->bind_param("s",$email);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);

            $status = $this->subscription_status($email);

            //-- if the subscription is active, return else update
            if($status > 0){
                return "You already have an active subscription"; 
            }else if ($status <0){
                $save_or_update = false;
            }



            //-- inserting or updating 
            $ins_query = ($save_or_update)?sprintf("INSERT INTO %s SET EMAIL=?, AMOUNT_PAID=?, DATE_PAID=?, PACKAGE=?",$table_name):sprintf("UPDATE %s  SET AMOUNT_PAID=?, DATE_PAID=?, PACKAGE=? WHERE EMAIL=?",$table_name);
            $stmt  = $connection->prepare($ins_query);
            
            if($save_or_update){
                $stmt->bind_param('sdii',$email,$amount_paid,$date_paid,$package);
            }else{
                $stmt->bind_param('diis',$amount_paid,$date_paid,$package,$email);
            }

            if(!$stmt->execute()){
                echo $stmt->mysqli_error();
                return "Subscription Failed!";
            }
            
            return 1;
        }


        public function subscription_status($email){

            //-- return 0=not subscribed, neg expiry=expired, pos expiry=active user else error return text                

            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_subscriptions";
            

            if($db->get_conn_id() != 1){
                echo "Database Connection Failed\n".$db->get_conn_text();
                return;
            }

            //-- sanitizing strings
            $email    = htmlspecialchars(strip_tags($email));


            if(empty($email)){
                return "NULL values not allowed";
            }

            //-- making sure the email exists
            $dup_query = sprintf("SELECT * FROM %s WHERE EMAIL=?",'user_registration');
            $obj = $connection->prepare($dup_query);
            $obj->bind_param("s",$email);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);

            if($result_number<=0){
                return "This account is not recognized";
            }

            //-- check status
            $dup_query = sprintf("SELECT PACKAGE,DATE_PAID FROM %s WHERE EMAIL=?",$table_name);
            $obj = $connection->prepare($dup_query);
            $obj->bind_param("s",$email);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);

            if($result_number<=0){
                return 0; //user not subscribed
            }

            //-- subscription type is in days so convert it into seconds
            $sub_dates;
            $sub_days;
            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                $sub_dates = $row[1];
                $sub_days  = $row[0];
            }

            //get the amount of seconds from the number of days. return -1 for expired subscriptions
            $expiry = $sub_dates + $sub_days * 86400;
            if(time()>$expiry){ 
                return -1 * $expiry;
            }

            return $expiry;
        }


        public function terminate_subscription($email){
            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_subscriptions";
            

            if($db->get_conn_id() != 1){
                echo "Database Connection Failed\n".$db->get_conn_text();
                return;
            }

            //-- sanitizing strings
            $email         = htmlspecialchars(strip_tags($email));
            $date_paid     = time();


            if(empty($email)){
                return "NULL values not allowed";
            }

            //-- making sure account is valid
            $dup_query = sprintf("SELECT * FROM %s WHERE EMAIL=?","user_registration");
            $obj = $connection->prepare($dup_query);
            $obj->bind_param("s",$email);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);
            if($result_number <= 0 ){
                return "This user is unrecognized"; 
            }

            //preventing duplicate payment
            $dup_query = sprintf("SELECT * FROM %s WHERE EMAIL=?",$table_name);
            $obj = $connection->prepare($dup_query);
            $obj->bind_param("s",$email);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);

            if($result_number == 0 || $this->subscription_status($email)==0){
                return "No active subscription"; 
            }

            if($this->subscription_status($email)<0){
                return "Subscription expired already";
            }

            //-- inserting or updating 
            $ins_query = sprintf("UPDATE %s  SET DATE_PAID=?, PACKAGE=? WHERE EMAIL=?",$table_name);
            $stmt  = $connection->prepare($ins_query);
            $zero = 0;
            $stmt->bind_param('iis',$date_paid,$zero,$email);

            if(!$stmt->execute()){
                return "Terminating Subscription failed";
            }
            
            return 1;
        }
    }

    //$pay = new Subscriptions();
    //echo $pay->subscribe('raphael@gmail.com',12.90,45);
//    echo $pay->terminate_subscription('raphael@gmail.com');

    //echo date('d-m-y h:i:s',1671349848);