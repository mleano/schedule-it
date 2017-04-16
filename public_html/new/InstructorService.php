<?php
  require('Instructor.php');
  require('InstructorMapperMySQL.php');

  class InstructorService {

    public function addInstructor($firstName, $lastName){
      $instructor = new Instructor();
      $instructor->firstName = $firstName;
      $instructor->lastName = $lastName;
      $mapper = new InstructorMapperMySQL();
      $mapper->add($instructor);
    }

    public function deleteInstructor($instructor_id){
      $instructor = new Instructor();
      $instructor->id = $instructor_id;
      $mapper = new InstructorMapperMySQL();
      return $mapper->remove($instructor);
    }

    public function fetchAll(){
      $mapper = new InstructorMapperMySQL();
      $mapper->fetchAll();
    }
  }
?>
