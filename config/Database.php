<?php 
// include 'config/App.php';

// use App\DotEnv;
// (new DotEnv(__DIR__ . '/.env'))->load();

class Database {

    private $dbhost = "127.0.0.1";
    private $dbport = "3306";
    private $dbusername = "root";
    private $dbpassword = "";
    private $dbname = "s_kmeans_nb"; 
    // private $dbhost = null;
    // private $dbusername = null;
    // private $dbpassword = null;
    // private $dbname = null;

    // protected $connection = null;

    // function __construct() {
    //     $this->dbhost = getenv('DB_HOST');
    //     $this->dbusername = getenv('DB_USER');
    //     $this->dbpassword = getenv('DB_PASSWORD');
    //     $this->dbname = getenv('DB_NAME');
    // }

    //create connection 
    public function connect() {
        $conn = new mysqli($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname, $this->dbport) or die("Database connection error." . $conn->connect_error);
        return $conn;           
    }
    
    // close connection
    public function close($conn) {        
        $conn->close();    
    }
}
