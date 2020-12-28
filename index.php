<?php
// показывать или нет выполненные задачи
$showCompleteTasks = rand(0, 1);

//устанавливаем соединение

$connect = mysqli_connect ('localhost', 'root', 'root', 'doingsdone');
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    $error = mysqli_connect_error();
    print("Ошибка подключения к базе данных " . $error);
} 
//узнаём имя юзера
$sqlUserName = "SELECT user_name FROM users WHERE id = 2";
$result = mysqli_query($connect, $sqlUserName);

if($result) {
    $userName = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

//добавляем проекты

$sqlProjects = "SELECT * FROM projects WHERE user_id = 2";
$result = mysqli_query($connect, $sqlProjects);

if($result) {
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

//добавляем задачи

$id = $_GET['id'] ?? '';

$sqlAllTasks = "SELECT * FROM tasks WHERE user_id = 2";
$sqlTasks = "SELECT * FROM tasks WHERE user_id = 2 AND project_id = $id";
$result = mysqli_query($connect, ($id && $id !== '1') ? $sqlTasks : $sqlAllTasks);

if($result) {
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

//все задачи одного пользователя

$sqlTasks = "SELECT * FROM tasks WHERE user_id = 2";
$result = mysqli_query($connect, $sqlTasks);

if($result) {
    $allTasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

//все id проектов

$sqlProjectsIds = "SELECT id FROM projects WHERE user_id = 2";
$result = mysqli_query($connect, $sqlProjectsIds);

if($result) {
    $projectsIds = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

$idsArray = [];
foreach ($projectsIds as $projectId):
array_push($idsArray, $projectId['id']);
endforeach;
array_push($idsArray, null);

include 'functions.php';

if(in_array($id, $idsArray)) {
    $pageContent = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'allTasks' => $allTasks, 'showCompleteTasks' => $showCompleteTasks]);
    } else {
            $pageContent = include_template('404.php',); 
            http_response_code(404);
            };
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'title' => "Дела в порядке", 'userName' => $userName]); 

print($layoutContent);
?>