<?php
  class InstructorMapperMySQL {
    protected $database;

    // Returns all instructors
    public function fetchAll(){
      $this->connect();

      $select = "SELECT * FROM instructors ORDER BY last_name ASC";

      $statement = $this->database->query($select);
      $statement->execute();

      $result = $statement->fetchAll(PDO::FETCH_ASSOC);
      // TODO: JCM Loop through returned data and add to Instructor Model
      foreach ($result as $row) {

          $instructor = new Instructor();
          //add each element from the database into the array
          $instructorArray[] = array(
              $instructor->id = $row['instructor_id'],
              $instructor->firstName = $row['first_name'],
              $instructor->lastName = $row['last_name']
          );
      }

      $this->disconnect();

       return $result;
    }

    /* Passes Instructor model, updates database, and finishes building Instructor object */
    public function addByName(Instructor $instructor, $firstName, $lastName = " "){
      $this->connect();

      $insert = "INSERT INTO instructors (first_name, last_name) VALUES (:first_name, :last_name);";
      try {
          $statement = $this->database->prepare($insert);

          $statement->bindParam(':first_name', $firstName, PDO::PARAM_STR);
          $statement->bindParam(':last_name', $lastName, PDO::PARAM_STR);
          $add = $statement->execute();
          $last_id = $this->database->lastInsertId();
          // Add to instructor object before returning
          $instructor->firstName = $firstName;
          $instructor->lastName= $lastName;
          $instructor->id = $last_id;
          return $instructor;

      } catch (PDOException $e) {
          echo 'PDOException : ' . $e->getMessage();
      }

      $this->disconnect();

    }

    /* Uses id to remove instructor from database*/
    public function removeById($id){
      $this->connect();

      //delete instructor from foreign key table auburn_course
      $remove = "DELETE FROM instructors WHERE `instructor_id` = :id;";

      try {
          $statement = $this->database->prepare($remove);

          $statement->bindParam(':id', $id, PDO::PARAM_STR);

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
