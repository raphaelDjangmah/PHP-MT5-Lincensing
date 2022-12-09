<?php
    require "../ACC-Backend/Registration.php";

    function login_checker(){
        //- request method must be pose
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            return "Access Denied!";
        }
    
        //-- making sure a button has been clicked
        if(!isset($_POST['submit_btn_clicked'])){
            return "Can Only create account from website";
        }
    
        //-- get details from user
        $email = (isset($_POST['user_email']))?$_POST['user_email']:"";
        $password = (isset($_POST['user_pass']))?$_POST['user_pass']:"";

        //-- login
        $user = new USERS();
        $login = $user->verify_user($email,$password);

        if($login=="1"){
            session_start();
            $_SESSION['user_logged_in'] = $email;
        }
    
        return $login;

    }


//--redirecting back to page with details
header('location:../FrontENDS/signinsignup/login.php?login_check_message='.login_checker());
