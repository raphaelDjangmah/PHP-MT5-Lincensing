<?php
    require "../ACC-Backend/registration.php";

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
        $email = (isset($_POST['user_email']))?$email:"";
        $password = (isset($_POST['user_pass']))?$password:"";
    
        return "Details-> ".$email."  ".$password;
    }

    
echo login_checker();
