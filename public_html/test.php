<?php
  include('Model/InstructorMapperMySQL.php');
  include('Model/Instructor.php');
  $instructor = new Instructor();
  $instructor->firstName = "Bob";
  $instructor->lastName = "Builder";
  echo("Before Add:");
  echo($instructor->firstName." ".$instructor->lastName." ".$instructor->id);

  $mapper = new InstructorMapperMySQL();
  $mapper->add($instructor);

  echo("After Add:");
  echo($instructor->lastName." ".$instructor->firstName." ".$instructor->id);

  $mapper->remove($instructor);


  // include('Model/InstructorService.php');
  // $instructorService = new InstructorService();
  // $status = $instructorService->deleteInstructor(25);
  // var_dump($instructorService->deleteInstructor(25));
?>
