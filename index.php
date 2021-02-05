<?php
// показывать или нет выполненные задачи

include 'functions.php';
$userName = NULL;
session_start();
if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
    $userName = $_SESSION['user'];
    $showCompleteTasks = (int)$_SESSION['show_complete_tasks'];
   
    $connect = connect();
    $projects = getProjects($connect, $userID);
    $allTasks = getTasks($connect, $userID);
 
    if (isset($_GET['show_completed'])) {
        $safeShowCompleteTask = filter_input(INPUT_GET, 'show_completed', FILTER_VALIDATE_INT);
        $showCompleteTasks = $safeShowCompleteTask;
        $_SESSION['show_complete_tasks'] = $safeShowCompleteTask;
    };
   
    if (isset($_GET['task_id'])) {
        $safeTaskId = filter_input(INPUT_GET, 'task_id', FILTER_SANITIZE_NUMBER_INT);
        switchTaskDone($connect ,$safeTaskId);
        header ('Location: index.php');
    };

    if (isset($_GET['submitSearch'])) {
        $tasks = getSearchTasks($connect, $_GET['searchTasks'], $userID);
    } else {
        $safeId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if ($safeId == '') {
            $tasks = $allTasks;
        } else {
            $tasks = getProjectTasks($connect, $safeId, $allTasks, $userID);
        };
    };

    $safeFilter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $tasks = filterTasks($tasks, $safeFilter);

    $projectsIds = getProjectsID($connect, $userID);

    $idsArray = [];
    foreach ($projectsIds as $projectId):
    array_push($idsArray, $projectId['id']);
    endforeach;
    array_push($idsArray, null);

    if(in_array($id, $idsArray)) {
        $pageContent = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'allTasks' => $allTasks, 'showCompleteTasks' => $showCompleteTasks, 'safeId' => $safeId, 'safeFilter' => $safeFilter]);
        } else {
            $pageContent = include_template('404.php',); 
            http_response_code(404);
            };
} else {
    $pageContent = include_template('guest.php');
};

$layoutContent = include_template('layout.php', ['content' => $pageContent, 'title' => "Дела в порядке", 'userName' => $userName]);

print($layoutContent);
