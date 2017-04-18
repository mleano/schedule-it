<?php
/**
 * User: gurmandhaliwal and Casey Morris
 * Date: 8/8/16
 * Time: 4:50 PM
 */
include('../config.php');

if (isset($_POST['id']) || isset($_POST['type'])) {
    $id = (int) $_POST['id'];
    $type = $_POST['type'];
}
switch ($type) {
    case 'deleteCourse':
        deleteCourse($id);
        break;
    case 'deleteInstructor' :
        deleteInstructor($id);
        break;
    case 'deleteRoom':
        deleteRoom($id);
        break;
    default:
        break;
}

function dbQuery($sql, $id) {

    $dbh = dbConnect();


    try {
        $statement = $dbh->prepare($sql);

        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        $delete = $statement->execute();
        if ($delete) {
            //Return the id and campus in the success response.
            echo json_encode(array('status' => 'success'));
        } else {
            //Return the id and campus in the success response.
            echo json_encode(array('status' => 'failed'));
        }
    } catch (PDOException $e) {
        echo 'PDOException : ' . $e->getMessage();
    }

    $dbh = null;
}

function deleteInstructor($id) {
    // Change all existing schedules where instructor_id is being deleted to TBD.

    //delete instructor from instructor table
    $sql = "DELETE FROM `instructors` WHERE `instructor_id` = :id;";

    dbQuery($sql, $id);

}

function deleteCourse($id){
    // Change all existing courses to default of Unknown

    //delete instructor from course table
    $sql = "DELETE FROM `courses` WHERE `course_id` = :id;";

    dbQuery($sql, $id);
}

function deleteRoom($id){
    // Change all room_ids from delete room name to the name Not Assigned
    //delete instructor from course table
    $sql = "DELETE FROM `rooms` WHERE `room_id` = :id;";

    dbQuery($sql, $id);
}
?>
