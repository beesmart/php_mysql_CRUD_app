<?php

class WishDB extends mysqli {
    
    // single instance of self shared among all instances
    private static $instance = null;
    
    // db connection config vars
    private $user = "phpuser";
    private $pass = "phpuserpw";
    private $dbName = "tut_wishlist";
    private $dbHost = "localhost";
    
    //this method must be static, and must return an instance of the object if the object
    //does not already exist.
    public static function getInstance() {
        if (!self::$instance instanceof self){
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
      trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
    public function __wakeup() {
      trigger_error('Deserializing is not allowed.', E_USER_ERROR);
 }
 
 //private constructor
 private function __construct() {
     parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
     if (mysqli_connect_error()){
         exit('Connect Error (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
    }
    parent::set_charset('utf-8');
    }
    // requires the wishers name and returns the ID - only if the user exists
    public function get_wisher_id_by_name($name) {
        $name = $this->real_escape_string($name);
        $wisher = $this->query("SELECT id FROM wishers WHERE name = '"
                
                . $name . "'");
        if ($wisher->num_rows > 0){
            $row = $wisher->fetch_row();
            return $row[0];
        } else {
        return null;}
    }
    // takes the wisherID and and returns the assoc. wishes
    public function get_wishes_by_wisher_id($wisherID) {
    return $this->query("SELECT id, description, due_date FROM wishes WHERE wisher_id=" . $wisherID);
    }
    // creates a new record in the wishers table, requires username and pass of user
    public function create_wisher ($name, $password){
    $name = $this->real_escape_string($name);
    $password = $this->real_escape_string($password);
    $this->query("INSERT INTO wishers (name, password) VALUES ('" . $name . "', '" . $password . "')");
    }
    
    public function verify_wisher_credentials ($name, $password){
     $name = $this->real_escape_string($name);
     // the query returns the number of records that meet the query, returns true or false
     $password = $this->real_escape_string($password);
     $result = $this->query("SELECT 1 FROM wishers
                     WHERE name = '" . $name . "' AND password = '" . $password . "'");
     return $result->data_seek(0);
  }
    // convert the date into MYSQL friendly date, then insert into db
    function insert_wish($wisherID, $description, $duedate){
      $description = $this->real_escape_string($description);
      if ($this->format_date_for_sql($duedate)==null){
          $this->query("INSERT INTO wishes (wisher_id, description)" .
               " VALUES (" . $wisherID . ", '" . $description . "')");
      } else
      $this->query("INSERT INTO wishes (wisher_id, description, due_date)" . 
                         " VALUES (" . $wisherID . ", '" . $description . "', " 
                         . $this->format_date_for_sql($duedate) . ")");
    }
    
    // The function in this example uses the PHP date_parse function. This function works only with English-language dates, 
    // such as December 25, 2010, and only Arabic numerals. A professional web site would use a date picker.
    function format_date_for_sql($date){
    if ($date == "")
        return null;
    else {
        $dateParts = date_parse($date);
        return $dateParts["year"]*10000 + $dateParts["month"]*100 + $dateParts["day"];
        }

    }
   // updates or verifys if a wish is new 
    public function update_wish($wishID, $description, $duedate){
    $description = $this->real_escape_string($description);
    if ($duedate==''){
        $this->query("UPDATE wishes SET description = '" . $description . "',
             due_date = NULL WHERE id = " . $wishID);
    } else
        $this->query("UPDATE wishes SET description = '" . $description .
            "', due_date = " . $this->format_date_for_sql($duedate)
            . " WHERE id = " . $wishID);
    }  
    public function get_wish_by_wish_id ($wishID) {
    return $this->query("SELECT id, description, due_date FROM wishes WHERE id = " . $wishID);
    }
    
    function delete_wish ($wishID){
    $this->query("DELETE FROM wishes WHERE id = " . $wishID);
    }
     
}
    


