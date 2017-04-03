<?php
include('../config.php');

//Check if post is sent from ajax call.
if(isset($_POST['type']) || isset($_POST['eventId'])) {
  $type = $_POST['type'];
  $eventId = $_POST['eventId'];
}
//Update course
if(isset($_POST['instructor']) || isset($_POST['course']) || isset($_POST['room'])) {
  $instructor = $_POST['instructor'];
  $course = $_POST['course'];
  $room = $_POST['room'];
}
//Update start and end time
if(isset($_POST['start']) || isset($_POST['end'])) {
  $start = $_POST['start'];
  $end = $_POST['end'];
}

//Select method based on type parameter.
switch($type) {
  case 'updateCourse':
    updateCourse($eventId, $instructor, $course, $room);
    break;
  case 'updateStartEnd' :
    updateStartEnd($eventId, $start, $end);
    break;
  case 'deleteCourse':
    deleteCourse($eventId);
    break;
  default:
    break;
}

function deleteCourse($eventId) {
  //Connect to database
  $dbh = dbConnect();
  $deleteCourse = '';

  //Build sql update query for instructor, course, room.

  $deleteCourse = 'DELETE FROM course_schedules WHERE schedule_id=:eventId';

  //Prepare the statement.
  $statement = $dbh->prepare($deleteCourse);
  //Bind the parameters
  $statement->bindParam(':eventId', $eventId, PDO::PARAM_STR);

  //Execute campus course.
  $delete = $statement->execute();

  if($delete) {
    //Return the id and campus in the success response.
    echo json_encode(array('status' => 'success'));
  }
  else {
    //Return the id and campus in the success response.
    echo json_encode(array('status' => 'failed'));
  }

  //Close the connection
  $dbh = null;
}

function updateCourse($scheduleId, $instructor, $course, $room) {
  //Connect to database
  $dbh = dbConnect();
  $updateCourse = '';
  // Retrieve unique ID for set of time
  $selectBlockOfTimes = 'SELECT block_id from course_schedules where schedule_id = '.$scheduleId;

  //Prepare the statement.
  $statement = $dbh->prepare($selectBlockOfTimes);

  $statement->execute();
  $result = $statement->fetch(PDO::FETCH_ASSOC);
  // Block ID represents a scheduled block of time
  $blockId = $result['block_id'];


  //Build sql update query for instructor, course, room.
  // $updateCourse = 'UPDATE course_schedules SET instructor_id=:instructor, room_id=:room, course_id=:course WHERE schedule_id=:eventId';
  $updateCourse = 'UPDATE course_schedules SET instructor_id=:instructor, room_id=:room, course_id=:course WHERE block_id=:blockId';

  //Prepare the statement.
  $statement = $dbh->prepare($updateCourse);
  //Bind the parameters
  $statement->bindParam(':blockId', $blockId, PDO::PARAM_INT);
  $statement->bindParam(':instructor', $instructor, PDO::PARAM_STR);
  $statement->bindParam(':room', $room, PDO::PARAM_STR);
  $statement->bindParam(':course', $course, PDO::PARAM_STR);

  //Execute campus course.
  $update = $statement->execute();

  if($update) {
    //Return the id and campus in the success response.
    echo json_encode(array('status' => 'success'));
  }
  else {
    //Return the id and campus in the success response.
    echo json_encode(array('status' => 'failed'));
  }

  //Close the connection
  $dbh = null;
}

function updateStartEnd($eventId, $start, $end) {
  //Connect to database
  $dbh = dbConnect();
  $updateStartDate = '';

  //Build sql update query for course start and end.

  $updateStartDate = 'UPDATE course_schedules SET start_time=:start, end_time=:end WHERE schedule_id=:eventId';

  //Prepare the statement.
  $statement = $dbh->prepare($updateStartDate);
  //Bind the parameters
  $statement->bindParam(':eventId', $eventId, PDO::PARAM_STR);
  $statement->bindParam(':start', $start, PDO::PARAM_STR);
  $statement->bindParam(':end', $end, PDO::PARAM_STR);

  //Execute campus course.
  $update = $statement->execute();

  if($update) {
    //Return the id and campus in the success response.
    echo json_encode(array('status' => 'success'));
  }
  else {
    //Return the id and campus in the success response.
    echo json_encode(array('status' => 'failed'));
  }

  //Close the connection
  $dbh = null;
}

?>
