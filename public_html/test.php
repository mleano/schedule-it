<?php
  // Test Mapper add and remove
  // include('Model/InstructorMapperMySQL.php');
  // include('Model/Instructor.php');
  // $instructor = new Instructor();
  //
  // echo("Before Add:");
  // echo($instructor->firstName." ".$instructor->lastName." ".$instructor->id);
  //
  // $mapper = new InstructorMapperMySQL();
  // $instructor = $mapper->addByName($instructor, "Bob", "Builder");
  //
  // echo("After Add:");
  // echo($instructor->lastName." ".$instructor->firstName." ".$instructor->id);
  //
  // $mapper->removeById($instructor->id);

  // Test Service add and remove
  include('Model/InstructorService.php');
  $instructorService = new InstructorService();
  $instructor = $instructorService->addInstructor("Bob","Builder");

  $instructorService = new InstructorService(new InstructorMapperMySQL());
  $status = $InstructorService->deleteInstructor($id);
  $instructorService = new InstructorService();
  $instructor = $instructorService->addInstructor("Bob","Builder");
  $instructorService->deleteInstructor($instructor);

  // Test Mapper to displayAll //
  // include('Model/InstructorMapperMySQL.php');


?>
