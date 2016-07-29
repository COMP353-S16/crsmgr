<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$pdo = Registry::getConnection();
$SQL = "SELECT * FROM Students s, Users u, StudentSemester st
WHERE u.uid = s.uid
AND s.uid = st.uid
  AND st.sid = :sid
AND (u.firstName LIKE :firstName OR u.lastName LIKE :lastName OR u.uid LIKE :uid)
      AND (st.uid, st.sid) NOT IN
          (SELECT gm.uid, gm.sid FROM GroupMembers gm)";

$query = $pdo->prepare($SQL);


$SEMESTER = $_REQUEST['semester'];


$query->bindValue(":firstName", "%".$_REQUEST['studentName']."%");
$query->bindValue(":lastName", "%".$_REQUEST['studentName']."%");
$query->bindValue(":uid", "%".$_REQUEST['studentName']."%");
$query->bindValue(":sid", $SEMESTER);  //SEMESTER SHOULD GO HERE
$query->execute();

$students = $query->fetchAll();

//print_r($students);

$selected_students = (!empty($_REQUEST['selectedStudents']) ? $_REQUEST['selectedStudents'] : array());

// Section ID to look in
$sectionName = $_REQUEST['section'];

foreach ($students as $student_data) {
    $student = new Student($student_data['uid'], $SEMESTER);

    if(!in_array($student_data['uid'], $selected_students))
    {


        if($student->getSectionName() == $sectionName || $sectionName == "all")
        {

            $student_array['data'][] = array(
                "name" => $student->getFirstName() . " " . $student->getLastName(),
                "uid" => $student->getUid(),
                "sName" => $student->getSectionName(),
                "sid" => $SEMESTER
            );
        }

    }
}

if(empty($student_array)) {
    $student_array['data'][] = array();
}



echo json_encode($student_array);