
<?php
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

//все задачи одного пользователя

$sqlTasks = "SELECT * FROM tasks WHERE user_id = 2";
$result = mysqli_query($connect, $sqlTasks);

if($result) {
    $allTasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

//добавляем задачи

$id = $_GET['id'] ?? '';

if ($id == '') {
    $id = '1';
}

$sqlTasks = "SELECT * FROM tasks WHERE user_id = 2 AND project_id = $id";
$result = mysqli_query($connect, $sqlTasks);

if($result) {
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if ($id == '1') {
        $tasks = $allTasks;
    }
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

?>