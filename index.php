<?php
// показывать или нет выполненные задачи
$showCompleteTasks = rand(0, 1);

include 'sql.php';

$idsArray = [];
foreach ($projectsIds as $projectId):
array_push($idsArray, $projectId['id']);
endforeach;
array_push($idsArray, null);

include 'functions.php';
var_dump($_POST);

//добавляем задачу

if (isset($_GET['success'])) {
$connect = mysqli_connect ('localhost', 'root', 'root', 'doingsdone');
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    $error = mysqli_connect_error();
    print("Ошибка подключения к базе данных " . $error);
} 
$sqlAddTask = "INSERT INTO tasks VALUES ($_POST['project'], 2, ,$_POST['name'], $_POST['date'], false);";
$result = mysqli_query($connect, $sqlUserName);

if($result) {
    $userName = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print ("Ошибка MySQL" . $error);
}

}

if(in_array($id, $idsArray)) {
    $pageContent = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'allTasks' => $allTasks, 'showCompleteTasks' => $showCompleteTasks]);
    } else {
            $pageContent = include_template('404.php',); 
            http_response_code(404);
            };
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'title' => "Дела в порядке", 'userName' => $userName]); 

print($layoutContent);
?>