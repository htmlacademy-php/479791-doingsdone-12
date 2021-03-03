<?php
// показывать или нет выполненные задачи

include 'functions.php';
$userName = null;
session_start();
if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'] ?? '';
    $userName = $_SESSION['user'] ?? '';
    $showCompleteTasks = (int)$_SESSION['show_complete_tasks'] ?? '';
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
        switchTaskDone($connect, $safeTaskId);
        header('Location: index.php');
    };

    if (isset($_GET['submitSearch'])) {
        $searchTasks = filter_input(INPUT_GET, 'searchTasks', FILTER_SANITIZE_SPECIAL_CHARS);
        $tasks = getSearchTasks($connect, $searchTasks, $userID);
    } else {
        $safeId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if ($safeId === null) {
            $tasks = $allTasks;
        } else {
            $tasks = getProjectTasks($connect, $safeId, $userID);
        };
    };

    $safeFilter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $tasks = filterTasks($tasks, $safeFilter);

    $projectsIds = getProjectsID($connect, $userID);

    $idsArray = [];
    foreach ($projectsIds as $projectId) {
        array_push($idsArray, $projectId['id'] ?? '');
    };
    array_push($idsArray, null);
   
    if (in_array($safeId, $idsArray)) {
        $pageContent = includeTemplate('main.php', [
            'projects' => $projects,
            'tasks' => $tasks,
            'allTasks' => $allTasks,
            'showCompleteTasks' => $showCompleteTasks,
            'safeId' => $safeId,
            'safeFilter' => $safeFilter,
            ]);
    } else {
        $pageContent = includeTemplate('404.php');
        http_response_code(404);
    };
} else {
    $pageContent = includeTemplate('guest.php');
};

$layoutContent = includeTemplate('layout.php', [
    'content' => $pageContent,
    'title' => "Дела в порядке",
    'userName' => $userName,
    ]);

print($layoutContent);
