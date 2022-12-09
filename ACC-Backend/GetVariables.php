<?php

    //-- database connection
    require_once('DbConnection.php');

    class VARIABLES{
        
        public function total_users(){

            //-- return 1 for successfully creating user else error text

            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_registration";
            

            if($db->get_conn_id() != 1){
                echo "Database Connection Failed\n".$db->get_conn_text();
                return;
            }

            //-- check to prevent duplicates
            $dup_query = sprintf("SELECT * FROM %s",$table_name);
            $obj = $connection->prepare($dup_query);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);
            
            return $result_number;
        }

        public function subscribed_users(){

            //-- return 1 for successfully creating user else error text

            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_subscriptions";
            

            if($db->get_conn_id() != 1){
                echo "Database Connection Failed\n".$db->get_conn_text();
                return;
            }

            //-- check to prevent duplicates
            $dup_query = sprintf("SELECT * FROM %s",$table_name);
            $obj = $connection->prepare($dup_query);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);
            
            return $result_number;
        }

        
        public function all_user_mails(){
            
            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_registration";
            

            if($db->get_conn_id() != 1){
                echo "Database Connection Failed\n".$db->get_conn_text();
                return;
            }

            //-- check to prevent duplicates
            $dup_query = sprintf("SELECT EMAIL FROM %s",$table_name);
            $obj = $connection->prepare($dup_query);
            
            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);

            if($result_number==0){
                return "Invalid Email";
            }
            
            $emails = array();
            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                array_push($emails,$row[0]);
            }
            return $emails;
        }
        

        public function user_details($email,$data_type=1){
            /*
                 DATA TYPES
                 1 = phone
                 2 = date registered
                 3 = country
                 4 = status
                 5 = package
                 6 = expiry date
            */

            //-- return 1 for successfully creating user else error text


            if($data_type<=0){
                return "index can only start from 1";
            }

            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_registration";
            

            if($db->get_conn_id() != 1){
                echo "Database Connection Failed\n".$db->get_conn_text();
                return;
            }

            //-- check to prevent duplicates
            $dup_query = sprintf("SELECT PHONE,DATE_CREATED,COUNTRY FROM %s  WHERE EMAIL=?",$table_name);
            $obj = $connection->prepare($dup_query);
            $obj->bind_param('s',$email);
            
            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);

            if($result_number==0){
                return "Invalid Email";
            }

            $counter = 0;
            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                if(count($row)>=$data_type){
                    return $row[$data_type-1];
                }
                
                $counter =  count($row);;
            }

            $dup_query = sprintf("SELECT PACKAGE FROM %s WHERE EMAIL=?",'user_subscriptions');
            $obj = $connection->prepare($dup_query);
            $obj->bind_param('s',$email);
            
            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result_ = $obj->get_result();
            $result_number_ = mysqli_num_rows($result_);

            if($result_number_==0){
                return "None";   //incase the user registers but isnt subscribed yet
            }

            while($row_ = mysqli_fetch_array($result_, MYSQLI_NUM)){
                switch($data_type){
                    case 5: 
                        return $row_[0];
                    case 4:
                        require_once('Subscription.php');
                            $subs    = new Subscriptions();
                            $return  = $subs->subscription_status($email);
                            return $return;
                    case 6:
                        require_once('Subscription.php');
                             $subs    = new Subscriptions();
                             $return  = $subs->subscription_status($email);
                            return abs($return);

            }
        }
            return -1;
        }

        public function users_active(){

            //-- return 1 for successfully creating user else error text

            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_subscriptions";
            

            if($db->get_conn_id() != 1){
                echo "Database Connection Failed\n".$db->get_conn_text();
                return;
            }

            //-- check to prevent duplicates
            $dup_query = sprintf("SELECT EMAIL FROM %s",$table_name);
            $obj = $connection->prepare($dup_query);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);
            $active_count = 0;

            //--
            require_once('Subscription.php');
            $subs = new Subscriptions();

            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                $mail  = $row[0];
                if($subs->subscription_status($mail) >0){
                    $active_count++;
                }
            }
            
            return $active_count;
        }

        public function users_expired(){
            //-- return 1 for successfully creating user else error text

            $db = new DbConnect(); 
            $connection = $db->connect();
            $table_name = "user_subscriptions";
            

            if($db->get_conn_id() != 1){
                echo "Database Connection Failed\n".$db->get_conn_text();
                return;
            }

            //-- check to prevent duplicates
            $dup_query = sprintf("SELECT EMAIL FROM %s",$table_name);
            $obj = $connection->prepare($dup_query);

            if(!$obj->execute()){
                return "An unknown error occured!";
            }

            $result = $obj->get_result();
            $result_number = mysqli_num_rows($result);
            $expired_count = 0;

            //--
            require_once('Subscription.php');
            $subs = new Subscriptions();

            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                $mail  = $row[0];
                if($subs->subscription_status($mail) < 0){
                    $expired_count++;
                }
            }
            
            return $expired_count;
        }
    }


// $test = new VARIABLES();
// echo  $test->user_details('lallotey@st.ug.edu.gh',1);


// while($counter<count($arr)){
//     echo $arr[$counter]." - ".$test->user_details($arr[$counter],5)."<br>";
//     $counter++;
// }