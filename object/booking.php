<?php
class Booking {
    private $conn;
    private $table_name = "bookings";

    // Object Properties
    public $id;
    public $booked_date;
    public $job;
    public $slot;
    public $booked_on;
    public $user;
    public $cancelled;
    public $job_served;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CRUD [Create, Read, Update, Delete] Operations
    // BEGIN
        
    // Create booking slot
    function create() {
        // sanitize data
        $this->booked_date = htmlspecialchars(strip_tags($this->booked_date));
        $this->job = htmlspecialchars(strip_tags($this->job));
        $this->slot = $this->slot;
        // $this->booked_on = htmlspecialchars(strip_tags($this->booked_on));
        $this->user = $this->user;
        // $this->cancelled = $this->cancelled;
        // $this->job_served = $this->job_served;
        // query to insert data into table
        $query = "INSERT INTO " . $this->table_name . "(`booked_date`, `job`, `slot`, `user`)"
                                                    ." VALUES('" . $this->booked_date . "', '" 
                                                                 . $this->job . "', " 
                                                                 . $this->slot . ", " 
                                                                 . $this->user . ")" ;

/* $query = "INSERT INTO " . $this->table_name . "(`booked_date`, `job`, `slot`, `user`)"
                                                    ." VALUES('" . $this->booked_date . "', '" 
                                                                 . $this->job . "', " 
                                                                 . $this->slot . ", '" 
                                                                //  . $this->booked_on . "', '" 
                                                                 . $this->user . "')" ;
                                                                //  . $this->cancelled . ", " 
                                                                //  . $this->job_served . ")";

 */        
        // echo $query;
        $stmt = $this->conn->prepare($query);
        // print_r($stmt);
        if($stmt->execute())
            return true;
        
        return false;
    }

    // Read data
    function read($user) {
        $hq = "SELECT b.id, b.booked_date, b.job, b.slot, b.booked_on, u.name, b.cancelled, b.job_served FROM " . $this->table_name 
                . " as b JOIN user as u WHERE b.user = u.id AND b.booked_date = '". date('Y-m-d') ."'";
        if ($user == "my_salon_owner")
            $query = $hq;//"SELECT * FROM " . $this->table_name; // . " WHERE user = " . $this->user;
        else
            $query = $hq . " AND b.user = '" . $user . "'";
        

	    // echo $query ."\n";
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    // Read databookings_data
    function read_old($user) {
        $hq = "SELECT b.id, b.booked_date, b.job, b.slot, b.booked_on, u.name, b.cancelled, b.job_served FROM " . $this->table_name 
                . " as b JOIN user as u WHERE b.user = u.id AND b.booked_date < '". date('Y-m-d') ."'";
        if ($user == "my_salon_owner")
            $query = $hq;//"SELECT * FROM " . $this->table_name; // . " WHERE user = " . $this->user;
        else
            $query = $hq . " AND b.user = '" . $user . "'";
        

	    // echo $query ."\n";
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    // Update
    function update_job_served() {
        
        // update query
        $query = "UPDATE " . $this->table_name . " SET job_served = " . $this->job_served
                                                . " WHERE id = " . $this->id;

        $stmt = $this->conn->prepare($query);
        if($stmt->execute())
            return true;
        return false;
    }
    // Update
    function update_cencel() {
        
        // update query
        $query = "UPDATE " . $this->table_name . " SET cancelled = " . $this->cancelled 
                                                . " WHERE id = " . $this->id;

        $stmt = $this->conn->prepare($query);
        if($stmt->execute())
            return true;
        return false;
    }

    // Delete
    function delete() {

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = " . $this->id;

        $stmt = $this->conn->prepare($query);
        
        if($stmt->execute())
            return true;
        return false;
    }
    // END

    // count today's bookings
    function count() {
        // $query = "SELECT COUNT(*) as total_bookings FROM " .$this->table_name. " WHERE booked_date = '" .$this->booked_date. "'";
        $query = "SELECT COUNT(*) as total_bookings FROM " . $this->table_name . " WHERE booked_date = '" . date('Y-m-d') . "'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_bookings'];
    }
}
?>
