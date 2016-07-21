<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT s.uid FROM Students s, Users u 
                        WHERE u.uid = s.uid AND (u.firstName LIKE :firstName OR u.lastName LIKE :lastName)");
$query->bindValue(":firstName", $_REQUEST['studentName']."%");
$query->bindValue(":lastName", $_REQUEST['studentName']."%");
$query->execute();

$students = $query->fetchAll();


$selected_students = (!empty($_REQUEST['selectedStudents']) ? $_REQUEST['selectedStudents'] : array());

// Section ID to look in
$sectionID = $_REQUEST['section'];

foreach ($students as $student_data) {
    $student = new Student($student_data['uid']);

    if(!in_array($student_data['uid'], $selected_students))
    {

        $Section = new Section($student->getSid());

        if($student->getSid() == $sectionID || $sectionID == "all")
        {

            $student_array['data'][] = array("name" => $student->getFirstName() . " " . $student->getLastName(), "uid" => $student->getUid(), "sName" => $Section->getSectionName());
        }

    }
}

if(empty($student_array)) {
    $student_array['data'][] = array();
}



echo json_encode($student_array);