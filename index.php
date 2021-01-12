<?php
// показывать или нет выполненные задачи
$showCompleteTasks = rand(0, 1);
include 'functions.php';

$connect = connect();
$userName = getUserName($connect);
$projects = getProjects($connect);
$allTasks = getTasks($connect);

$id = $_GET['id'] ?? '';
$tasks = getProjectTasks($connect, $id, $allTasks);

$projectsIds = getProjectsID($connect);

$idsArray = [];
foreach ($projectsIds as $projectId):
array_push($idsArray, $projectId['id']);
endforeach;
array_push($idsArray, null);

if(in_array($id, $idsArray)) {
    $pageContent = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'allTasks' => $allTasks, 'showCompleteTasks' => $showCompleteTasks]);
    } else {
            $pageContent = include_template('404.php',); 
            http_response_code(404);
            };
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'title' => "Дела в порядке", 'userName' => $userName]); 

print($layoutContent);
?>