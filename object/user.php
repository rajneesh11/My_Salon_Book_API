<?php
class User {
    private $conn;
    private $table_name = "user";

    // object prooperties
    public $id;
    public $name;
    public $age;
    public $address;
    public $email;
    public $phone;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }


    //user signup method
    function signup(){
    
        if($this->isAlreadyExist()){
            return 1;
        }
        // query to insert record of new user signup
        // $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->age = $this->age;
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // query to insert into users table
        $query = "INSERT INTO " . $this->table_name . "(`name`, `age`, `address`, `email`, `phone`, `password`) VALUES('" . $this->name . "', '"
                                                                  . $this->age . "', '"
                                                                  . $this->address . "', '"
                                                                  . $this->email . "', '"
                                                                  . $this->phone . "', '"
                                                                  . $this->password . "')";

        // echo $query;
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return 3;
        }
    
        return 2;
        
    }

    // login user method
    function login(){
        // select all query with user inputed username and password
        $query = "SELECT `id`, `name`, `age`, `address`, `email`, `phone` FROM " . $this->table_name . 
                    " WHERE email = '" .$this->email. "' AND password = '" .$this->password. "'";

        // echo $query;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }

    //Notify if User with given username Already exists during SignUp
    function isAlreadyExist(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE email='".$this->email."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}
?>