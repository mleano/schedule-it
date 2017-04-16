<?php
    abstract class BaseModel{

        // Place to store the database connection
        protected static $connection;

        public function connect(){
          try {
            // Retrieves data needed to connect to data base via config.ini
            $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/../config/config.ini');

            // Attempts to connect to database
            $this->connection = new PDO($config['dbname'], $config['username'], $config['password']);

          } catch (PDOException $e) {
            // Displays error message need to change when in production to clean error message
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
            return false;
          }

          return self::$connection;
        }

        public function disconnect(){
          self::$connection = null;
        }
    }
?>
