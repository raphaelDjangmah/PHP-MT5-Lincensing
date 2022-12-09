<?php
    require "../ACC-Backend/Registration.php";

    function signup_checker(){
        //- request method must be pose
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            return "Access Denied!";
        }
    
        //-- making sure a button has been clicked
        if(!isset($_POST['signup_btn_clicked'])){
            return "Can Only create account from website";
        }
    
        //-- get details from user
        $email    = (isset($_POST['user_email']))?$_POST['user_email']:"";
        $password = (isset($_POST['user_pass']))?$_POST['user_pass']:"";
        $confirm  = (isset($_POST['user_pass_confirm']))?$_POST['user_pass_confirm']:"";
        $phone    = (isset($_POST['user_phone']))?$_POST['user_phone']:"";
        $country  = "Ghana";

        //--sanitizing passwords and checking match
        $password = htmlspecialchars(strip_tags($password));
        $confirm  = htmlspecialchars(strip_tags($confirm));
        
        if($password!==$confirm){
            return "Passwords do not match";
        }


        //-- login
        $user = new USERS();
        $login = $user->create_user($email,$password,$phone,$country);

        if($login==1){
            session_start();
            $_SESSION['user_logged_in'] = $email;
        }
    
        return $login;

    }


//--redirecting back to page with details
header('location:../FrontENDS/signinsignup/signup.php?signup_message_check='.signup_checker());
