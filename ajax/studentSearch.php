<?php
/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/20/2016
 * Time: 9:49 PM
 */

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



foreach ($students as $student_data) {
    $student = new Student($student_data['uid']);

    if(!in_array($student_data['uid'], $selected_students))
    {
        $student_array[] = array("name" => $student->getFirstName() . " " . $student->getLastName(), "uid" => $student->getUid());
    }
}

if(empty($student_array)) {
    $student_array = array("name" => "");
}



echo json_encode($student_array);