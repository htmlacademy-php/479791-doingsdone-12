<?php
// показывать или нет выполненные задачи
$showCompleteTasks = rand(0, 1);
include 'functions.php';
$userName = NULL;
$notTasksFound = false;
session_start();
if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
    $userName = $_SESSION['user'];
    
    $connect = connect();
    $projects = getProjects($connect, $userID);
    $allTasks = getTasks($connect, $userID);
    if (isset($_GET['submitSearch'])) {
        $tasks = getSearchTasks($connect, $_GET['searchTasks'], $userID);
        if (empty($tasks)) {
            $notTasksFound = true;
        };
    } else {
        $safeId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if ($safeId == '1' || $safeId == '') {
            $tasks = $allTasks;
        } else {
            $tasks = getProjectTasks($connect, $safeId, $allTasks, $userID);
        };
    };

    $projectsIds = getProjectsID($connect, $userID);

    $idsArray = [];
    foreach ($projectsIds as $projectId):
    array_push($idsArray, $projectId['id']);
    endforeach;
    array_push($idsArray, null);

    if(in_array($id, $idsArray)) {
        $pageContent = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'allTasks' => $allTasks, 'showCompleteTasks' => $showCompleteTasks, 'noTasksFound' => $notTasksFound]);
        } else {
            $pageContent = include_template('404.php',); 
            http_response_code(404);
            };
} else {
    $pageContent = include_template('guest.php');
};

$layoutContent = include_template('layout.php', ['content' => $pageContent, 'title' => "Дела в порядке", 'userName' => $userName]);

print($layoutContent);
?>