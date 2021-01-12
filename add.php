<?php
include 'functions.php';
$connect = connect();
$userName = getUserName($connect);
$projects = getProjects($connect);
$allTasks = getTasks($connect);
$projectsIds = getProjectsID($connect);
$idsArray = [];
foreach ($projectsIds as $projectId):
array_push($idsArray, $projectId['id']);
endforeach;

$required_fields = ['name', 'project'];
$errors = [];

if (isset($_POST['submit'])) {
  var_dump($_POST);
  foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $errors[$field] = 'Поле не заполнено';
    }
  };

  if (is_date_valid($_POST['date']) == false) {
    $errors[$date] = 'Неверный формат даты';
  };

  if (strtotime($_POST['date']) < strtotime(date("m.d.y"))) {
    $errors[$date] = 'Вы ввели дату из прошлого';
  };

  if (in_array($_POST['project'], $idsArray)) {} else {
    $errors[$project] = 'Такого проекта не существует';
  }

  if (empty($errors)) {
    addTask($connect, {$_POST['project']}, 2, {$_POST['name']}, {$_POST['date']}, false);
    exit;
    header ('Location: index.php');
  };
}

$pageContent = include_template('addTask.php', ['errors' => $errors, 'projects' => $projects, 'allTasks' => $allTasks]); 
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'title' => "Дела в порядке", 'userName' => $userName]); 

print($layoutContent);

?>