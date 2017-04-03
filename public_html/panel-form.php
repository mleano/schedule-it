<?php
include('../config.php');

//Check if post is sent from ajax call.
if(isset($_POST['type']) || isset($_POST['campus']) || isset($_POST['instructor']) ||
  isset($_POST['course']) || isset($_POST['room']) || isset($_POST['courseDays'])|| isset($_POST['year'])
) {
  $type = $_POST['type'];
  $campus = $_POST['campus'];
  $instructor = $_POST['instructor'];
  $course = $_POST['course'];
  $room = $_POST['room'];
  $courseDays = json_decode($_POST['courseDays']);
  $year = $_POST['year'];

  //Select method based on type parameter.
  switch($type) {
    case 'insertCourse' :
      insertCourse($campus, $instructor, $course, $room, $courseDays, $year);
      break;
    default:
      break;
  }
}

function insertCourse($campus, $instructor, $course, $room, $courseDays, $year) {
  //Connect to database
  $dbh = dbConnect();
  $insertCourse = '';

  //Iterate through courseDays array.
  for($row = 0, $size = count($courseDays); $row < $size; ++$row) {
    //Build sql insert query for campus course day.
    if($campus === 'auburn') {
      $insertCourse = 'INSERT INTO auburn_course(instructor_id, room_id, course_id, start_time, end_time,year)
                        VALUES (:instructor, :room, :course, :startTime, :endTime, :year)';
    }
    else {
      if($campus === 'kent') {
        $insertCourse = 'INSERT INTO kent_course(instructor_id, room_id, course_id, start_time, end_time,year)
                            VALUES (:instructor, :room, :course, :startTime, :endTime, :year)';
      }
    }

    //Prepare the statement.
    $statement = $dbh->prepare($insertCourse);

    //Bind the parameters.
    $statement->bindParam(':instructor', $instructor, PDO::PARAM_STR);
    $statement->bindParam(':room', $room, PDO::PARAM_STR);
    $statement->bindParam(':course', $course, PDO::PARAM_STR);
    $statement->bindParam(':startTime', $courseDays[$row][0], PDO::PARAM_STR);
    $statement->bindParam(':endTime', $courseDays[$row][1], PDO::PARAM_STR);
    $statement->bindParam(':year', $year, PDO::PARAM_INT);

    //Execute campus course.
    $statement->execute();

    //Return the id of the last row that was inserted.
    $lastId = $dbh->lastInsertId();
  }

  //Return the id and campus in the success response.
  echo json_encode(array('status' => 'success', 'id' => $lastId, 'campus' => $campus));

  //Close the connection
  $dbh = null;
}

?>
