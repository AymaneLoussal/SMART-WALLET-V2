<?php 
 
 class Database {
    private string $host;
    private string $db_name;
    private string $user;
    private string $password;

    function __construct($host,$db_name,$user,$pass){
    $this->host = $host;
    $this->db_name = $db_name;
    $this->user = $user;
    $this->password = $pass;
  }
  
  public function connect(){
    try {
        return new PDO(
            "mysql:host={$this->host};dbname={$this->db_name}",
            "{$this->user}",
            "{$this->password}",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        error_log($e->getMessage());
        die("Connection to database failed !");
    }
  }
}



?>
 