<?php
  require('Instructor.php');
  
  class InstructorMapperMySQL {
    protected $database;

    public function fechAll(){
      $this->database->connect();

      $select = "SELECT * FROM instructor ORDER BY last_name ASC";

      $statement = $this->database->prepare($sql);
      $statement->execute();

      $result = $statement->fetchAll(PDO::FETCH_ASSOC);
      $row = $result->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
    }

    public function add(Instructor $instructor){
      $this->database->connect();
      $sql = "INSERT INTO instructors ( `first_name`, `last_name`) VALUES (:firstName, :lastName);";
      try {
          $statement = $this->database->prepare($sql);

          $statement->bindParam(':first_name', $instructor->firstName, PDO::PARAM_STR);
          $statement->bindParam(':last_name', $instructor->lastName, PDO::PARAM_STR);

          $add = $statement->execute();

          if ($add) {
              //Return the id and campus in the success response.
              echo json_encode(array('status' => 'success'));
          } else {
              //Return the id and campus in the success response.
              echo json_encode(array('status' => 'failed'));
          }
      } catch (PDOException $e) {
          echo 'PDOException : ' . $e->getMessage();
      }

      $this->database->disconnect();

    }

    public function remove(Instructor $instructor){
      $this->database->connect();

      //delete instructor from foreign key table auburn_course
      $sql = "DELETE FROM instructors WHERE `instructor_id` = :id;";

      try {
          $statement = $this->database->prepare($sql);

          $statement->bindParam(':id', $id, PDO::PARAM_STR);

          $delete = $statement->execute();

          if ($delete) {
              //Return the id and campus in the success response.
              echo json_encode(array('status' => 'success'));
          } else {
              //Return the id and campus in the success response.
              echo json_encode(array('status' => 'failed'));
          }
      } catch (PDOException $e) {
          echo 'PDOException : ' . $e->getMessage();
      }

      $this->database->disconnect();
    }

    private function connect(){
      try {
        // Retrieves data needed to connect to data base via config.ini
        $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/../config/config.ini');

        // Attempts to connect to database
        $this->database = new PDO($config['dbname'], $config['username'], $config['password']);

      } catch (PDOException $e) {
        // Displays error message need to change when in production to clean error message
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
        return false;
      }
      return self::$database;
    }

    private function disconnect(){
      $this->database = null;
    }
  }
?>
