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