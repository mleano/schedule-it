<?php
  class InstructorMapperMySQL {
    protected $database;

    public function fetchAll(){
      $this->connect();

      $select = "SELECT * FROM instructors ORDER BY last_name ASC";

      $statement = $this->database->query($select);
      $statement->execute();

      $result = $statement->fetchAll(PDO::FETCH_ASSOC);

      $this->disconnect();

       return $result;
    }

    /* Passes Instructor model and uses the instructor's first and last name to add entry */
    public function add(Instructor $instructor){
      $this->connect();

      $insert = "INSERT INTO instructors (first_name, last_name) VALUES (:first_name, :last_name);";
      try {
          $statement = $this->database->prepare($insert);

          $statement->bindParam(':first_name', $instructor->firstName, PDO::PARAM_STR);
          $statement->bindParam(':last_name', $instructor->lastName, PDO::PARAM_STR);
          $add = $statement->execute();
          $last_id = $this->database->lastInsertId();
          $instructor->id = $last_id;
          return $instructor;

          // if ($add) {
          //   $last_id = $statement->lastInsertId();
          //   $instructor->id = $last_id;
          //   return $instructor;
          // } else {
          //     //Return the id and campus in the success response.
          //     echo json_encode(array('status' => 'failed'));
          // }
      } catch (PDOException $e) {
          echo 'PDOException : ' . $e->getMessage();
      }

      $this->disconnect();

    }

    /* Passes Instructor model and uses the instructor's id to remove entry */
    public function remove(Instructor $instructor){
      $this->connect();

      //delete instructor from foreign key table auburn_course
      $remove = "DELETE FROM instructors WHERE `instructor_id` = :id;";

      try {
          $statement = $this->database->prepare($remove);

          $statement->bindParam(':id', $instructor->id, PDO::PARAM_STR);

          $delete = $statement->execute();

          if ($delete) {
              //Return the id and campus in the success response.
              return json_encode(array('status' => 'success'));
          } else {
              //Return the id and campus in the success response.
              return json_encode(array('status' => 'failed'));
          }
      } catch (PDOException $e) {
          return 'PDOException : ' . $e->getMessage();
      }

      $this->disconnect();
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
      return $this->database;
    }

    private function disconnect(){
      $this->database = null;
    }
  }
?>
