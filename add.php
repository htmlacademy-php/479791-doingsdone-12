<?php
include 'functions.php';
$connect = connect();
$userName = getUserName($connect, 2);
$projects = getProjects($connect, 2);
$allTasks = getTasks($connect, 2);
$projectsIds = getProjectsID($connect, 2);
$idsArray = [];
foreach ($projectsIds as $projectId):
array_push($idsArray, $projectId['id']);
endforeach;

$required_fields = ['name', 'project'];
$errors = [];

if (isset($_POST['submit'])) {
  
  foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $errors[$field] = 'Поле не заполнено';
    }
  };

  if (is_date_valid($_POST['date']) == false) {
    $errors['date'] = 'Неверный формат даты';
  };

  if (strtotime($_POST['date']) < strtotime(date("m.d.y"))) {
    $errors['date'] = 'Введите корректную дату';
  };

  if (in_array($_POST['project'], $idsArray)) {} else {
    $errors['project'] = 'Такого проекта не существует';
  };

  if (isset($_FILES['file'])) {
    $file_name = $_FILES['file']['name'];
    $file_path = __DIR__ . '/uploads/';
    $file_url = '/uploads/' . $file_name;
    
    move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
}

  if (empty($errors)) {
    addTask($connect, $_POST['project'], 2, $_POST['name'], $_POST['date'], false, $file_url);
    exit;
    header ('Location: index.php');
  };
}

$pageContent = include_template('addTask.php', ['errors' => $errors, 'projects' => $projects, 'allTasks' => $allTasks]); 
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'title' => "Дела в порядке", 'userName' => $userName]); 

print($layoutContent);

?>