<?php
include('../config.php');

//Check if post is sent from ajax call.
if(isset($_POST['type']) || isset($_POST['filter'])
|| isset($_POST['quarter']) || isset($_POST['year'])) {
  $type = $_POST['type'];
  $filter = $_POST['filter'];
  $quarter = $_POST['quarter'];
  $year = $_POST['year'];



  //Select method based on type parameter.
  switch($type) {
    case 'selectCampusCourses' :
      selectCampusCourses();
      break;
    default:
      break;
  }
}

/**
 * Get all scheduled courses for Auburn or Kent courses.
 */
function selectCampusCourses() {
  global $filter;
  global $quarter;
  global $year;

  // Dynamically change the ORDER BY value based on what filter button was clicked.
  $filterVal = '';
  if($filter === 'room') {
    $filterVal = 'rooms.room_number';
  }
  elseif($filter === 'instructor') {
    $filterVal = 'instructors.last_name';
  }

  $startDay = '';
  if($quarter === '1'){
    $startDay = 4;
  }
  else if($quarter === '2'){
    $startDay = 11;
  }
  else if($quarter === '3'){
    $startDay = 18;
  }
  else if($quarter === '4'){
    $startDay = 25;
  }

  // Date for end of week quarter
  $endDay = $startDay + 4;

  // Create date and time range
  $startDate = mktime(0,0,0,07,(int)$startDay,2016);

  $endDate = mktime(24,0,0,07,(int)$endDay,2016);
  // Date and time format
  $startRange = date("Y-m-d\TH:i:s", $startDate);
  $endRange = date("Y-m-d\TH:i:s", $endDate);

  $stmt = "SELECT
    course_schedules.schedule_id,
    instructors.first_name, instructors.last_name,
    courses.course_number,
    rooms.room_number,
    course_schedules.start_time, course_schedules.end_time
    FROM course_schedules
    INNER JOIN instructors ON course_schedules.instructor_id = instructors.instructor_id
    INNER JOIN courses ON course_schedules.course_id = courses.course_id
    INNER JOIN rooms ON course_schedules.room_id = rooms.room_id
    WHERE course_schedules.start_time
    BETWEEN '$startRange'
    AND '$endRange'
    AND year = $year
    ORDER BY $filterVal ASC";

  // Connect to database.
  $dbh = dbConnect();
  $statement = $dbh->prepare($stmt);

  $statement->execute();

  // Process the results.
  $result = $statement->fetchAll(PDO::FETCH_ASSOC);

  if(!empty($result)){
    // Create object class
    $response = new stdClass();
    // Courses field hold schedules for found courses
    $response->courses = selectCampusCoursesResults($result);
    // Default date will set the calendar to the correct quarter
    $response->defaultDate = date("Y-m-d",$startDate);

    echo json_encode($response);
  }else{
    echo json_encode(false);
  }


  // Close the connection.
  $dbh = null;
}

function selectCampusCoursesResults($result) {
  global $filter;
  $filterTemp = '';
  $courses = array();
  $coursesTemp = array();

  foreach($result as $row) {
    $course = array();
    $course['id'] = $row['schedule_id'];
    $course['title'] = ($filter === 'room') ? $row['room_number'] : $row['first_name'] . ' ' . $row['last_name'];
    $course['courseNumber'] = $row['course_number'];
    $course['instructor'] = $row['first_name'] . ' ' . $row['last_name'];
    $course['roomNumber'] = $row['room_number'];
    $course['start'] = $row['start_time'];
    $course['end'] = $row['end_time'];

    // Set initial filterTemp if empty. Stores the previous value when grouping by instructor or room.
    if(!$filterTemp) {
      if($filter === 'room') {
        $filterTemp = $row['room_number'];
      }
      elseif($filter === 'instructor') {
        $filterTemp = $row['last_name'];
      }
    }

    // If the same room or instructor when comparing filterTemp to the current row.
    if($filterTemp === $row['room_number'] || $filterTemp === $row['last_name']) {
      // Add course array to coursesTemp array of the current row.
      $coursesTemp[] = $course;
    }
    // If not the same room or instructor when comparing filterTemp to the current row.
    else {
      // Add coursesTemp with the same room or instructor to the courses array.
      $courses[] = $coursesTemp;
      // Clear coursesTemp to store the next rooms or instructor that are the same.
      unset($coursesTemp);
      // Add course array to coursesTemp array of the current row.
      $coursesTemp[] = $course;

      // Reset filterTemp to the new room or instructor of the current row.
      if($filter === 'room') {
        $filterTemp = $row['room_number'];
      }
      elseif($filter === 'instructor') {
        $filterTemp = $row['last_name'];
      }
    }
  }

  // Adds the last group in coursesTemp array to courses array. When the foreach loop ends the last
  // coursesTemp group isn't added.
  $courses[] = $coursesTemp;
  unset($coursesTemp);

  return $courses;
}

?>
