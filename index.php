<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

//устанавливаем соединение

$connect = mysqli_connect ('localhost', 'root', 'root', 'doingsdone');
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    $error = mysqli_connect_error();
    print("Ошибка подключения к базе данных " . $error);
} 
//узнаём имя юзера
$sql_user_name = "SELECT user_name FROM users WHERE id = 2";
$result = mysqli_query($connect, $sql_user_name);

if($result) {
    $user_name = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

//добавляем проекты

$sql_projects = "SELECT * FROM projects WHERE user_id = 2";
$result = mysqli_query($connect, $sql_projects);

if($result) {
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

//добавляем задачи

$id = $_GET['id'] ?? '';

$sql_all_tasks = "SELECT * FROM tasks WHERE user_id = 2";
$sql_tasks = "SELECT * FROM tasks WHERE user_id = 2 AND project_id = $id";
$result = mysqli_query($connect, ($id && $id !== '1') ? $sql_tasks : $sql_all_tasks);

if($result) {
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

//все задачи одного пользователя

$sql_tasks = "SELECT * FROM tasks WHERE user_id = 2";
$result = mysqli_query($connect, $sql_tasks);

if($result) {
    $all_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

include 'functions.php';

if(isset($tasks[0])) {
    $page_content = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'all_tasks' => $all_tasks, 'show_complete_tasks' => $show_complete_tasks]);
    } else {$page_content = include_template('404.php',); http_response_code(404);
    };
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => "Дела в порядке", 'user_name' => $user_name]); 

print($layout_content);
?>