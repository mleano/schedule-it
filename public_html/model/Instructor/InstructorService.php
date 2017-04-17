<?php
  require('Instructor.php');

  class InstructorService {
    // Stores mapper being used
    $private storageType;

    // Intializes the type of mapper used for storing data
    function __construct($newStorageType){
      $this->storageType = $newStorageType;
    }

    /* Takes first and last name  adds data to instructor table and creates an object based on data table */
    public function addInstructor($firstName, $lastName){
      $instructor = new Instructor();
      $mapper = $this->storageType;
      $instructor = $mapper->addByName($instructor, $firstName, $lastName);
      return $instructor;
    }

    public function deleteInstructor($id){
      $mapper = new InstructorMapperMySQL();
      $mapper->removeById($id);
    }

    public function fetchAll(){
      $mapper = new InstructorMapperMySQL();
      $mapper->fetchAll();

    }
  }
?>
