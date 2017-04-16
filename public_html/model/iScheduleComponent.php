<?php
  interface iScheduleComponent {
    public function insertSchedule($instructor, $course, $room, $courseDays, $year);
    public function updateSchedule($id, $instructor,$course, $room);
    public function updateStartEnd($eventId, $startTime, $endTime);
    public function deleteSchedule($id);
    public function selectByRoom($quarter, $year);
    public function selectByInstructor($quarter, $year);
  }
?>
