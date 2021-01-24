<?php
// показывать или нет выполненные задачи
$showCompleteTasks = rand(0, 1);
include 'functions.php';

$connect = connect();
$userName = getUserName($connect, 2);
$projects = getProjects($connect, 2);
$allTasks = getTasks($connect, 2);

$id = $_GET['id'] ?? '';
$safeId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($safeId == '1' || $safeId == '') {
    $tasks = $allTasks;
} else {
    $tasks = getProjectTasks($connect, $safeId, $allTasks, 2);
};

$projectsIds = getProjectsID($connect, 2);

$idsArray = [];
foreach ($projectsIds as $projectId):
array_push($idsArray, $projectId['id']);
endforeach;
array_push($idsArray, null);

if(in_array($safeId, $idsArray)) {
    $pageContent = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'allTasks' => $allTasks, 'showCompleteTasks' => $showCompleteTasks]);
    } else {
            $pageContent = include_template('404.php',); 
            http_response_code(404);
            };
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'title' => "Дела в порядке", 'userName' => $userName]); 

print($layoutContent);
?>