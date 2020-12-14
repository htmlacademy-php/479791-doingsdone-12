<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

//устанавливаем соединение

$connect = mysqli_connect ('localhost', 'root', 'root', 'doingsdone');
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    $error = mysqli_connect_error();
    print("Ошибка подключения к базе данных " . $error);
} else {
    print("Соединение установлено");
}

//добавляем проекты

$sql_projects = "SELECT * FROM projects";
$result = mysqli_query($connect, $sql_projects);

if($result) {
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    print ("Добавлены проекты");
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

//добавляем задачи

$sql_tasks = "SELECT * FROM tasks";
$result = mysqli_query($connect, $sql_tasks);

if($result) {
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    print ("Добавлены задачи");
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

include 'functions.php';

$page_content = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => "Дела в порядке"]); 

print($layout_content);
?>