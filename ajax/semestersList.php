<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$info = array("data" => array());

$Semesters = new Semesters();

$semesters = $Semesters->getSemesters();

/**
 * @var $Semester Semester
 */
foreach ($semesters as $Semester)
{
    $info['data'][] = array(
        "sid"       => $Semester->getId(),
        "startDate" => $Semester->getSemesterStartDate(),
        "endDate"   => $Semester->getSemseterEndDate()
    );
}

echo json_encode($info);