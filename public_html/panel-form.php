<?php
include('../config.php');

//Check if post is sent from ajax call.
if(isset($_POST['type'])  || isset($_POST['instructor']) ||
  isset($_POST['course']) || isset($_POST['room']) || isset($_POST['courseDays'])|| isset($_POST['year'])
) {
  $type = $_POST['type'];
  $instructor = $_POST['instructor'];
  $course = $_POST['course'];
  $room = $_POST['room'];
  $courseDays = json_decode($_POST['courseDays']);
  $year = $_POST['year'];

  //Select method based on type parameter.
  switch($type) {
    case 'insertCourse' :
      insertCourse($instructor, $course, $room, $courseDays, $year);
      break;
    default:
      break;
  }
}

function insertCourse($instructor, $course, $room, $courseDays, $year) {
  //Connect to database
  $dbh = dbConnect();
  $insertCourse = '';

  // Insert an entry into block_schedule table
  $insertBlockOfTime = 'INSERT INTO block_schedules(block_id) values (null)';
  //Prepare the statement.
  $statement = $dbh->prepare($insertBlockOfTime);
  //Execute campus course.
  $statement->execute();

  // Retrieve unique ID used and insert into course_schedules table
  $lastId = $dbh->lastInsertId();

  //Iterate through courseDays array.
  for($row = 0, $size = count($courseDays); $row < $size; ++$row) {
    //Build sql insert query for campus course day.

    $insertCourse = 'INSERT INTO course_schedules(instructor_id, room_id, course_id, start_time, end_time,year, block_id)
                        VALUES (:instructor, :room, :course, :startTime, :endTime, :year, :blockId)';

    //Prepare the statement.
    $statement = $dbh->prepare($insertCourse);
    //Bind the parameters.
    $statement->bindParam(':instructor', $instructor, PDO::PARAM_STR);
    $statement->bindParam(':room', $room, PDO::PARAM_STR);
    $statement->bindParam(':course', $course, PDO::PARAM_STR);
    $statement->bindParam(':startTime', $courseDays[$row][0], PDO::PARAM_STR);
    $statement->bindParam(':endTime', $courseDays[$row][1], PDO::PARAM_STR);
    $statement->bindParam(':year', $year, PDO::PARAM_INT);
    $statement->bindParam(':blockId', $lastId, PDO::PARAM_INT);

    //Execute campus course.
    $statement->execute();

  }

  //Return the id and campus in the success response.
  // echo json_encode(array('status' => 'success', 'id' => $lastId);

  //Close the connection
  $dbh = null;
}

?>
