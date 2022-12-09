<?php

class DBConnect{

  //variable declaration
  private $gethostname;
  private $dbname;
  private $user;
  private $pass;
  private $connection;
  private $error_text;
  private $error_code;

  public function connect(){


    //remote database details
    // $this->hostname = 'us-cdbr-east-06.cleardb.net';
    // $this->user     = 'b7e4d9fa3f95b6';             
    // $this->pass     = '9e4314ab';                   
    // $this->dbname   = 'heroku_481e0448f352958';     

      $this->hostname = "localhost";
      $this->user     = "root";
      $this->pass     = "";
      $this->dbname   = "mql5_licensing";

      $this->connection = new mysqli($this->hostname, $this->user,$this->pass,$this->dbname);

    //return code and text
      if($this->connection->connect_errno){
        $this->error_code = -1;
        $this->error_text = mysqli_error();
      }else{
        $this->error_code = 1;
        $this->error_text = "DB connected successfully";
      }


      return $this->connection;
  }

    public function get_conn_text(){
        return $this->error_text;
    }

    public function get_conn_id(){
        return $this->error_code;
    }
}