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

  if (strtotime($_POST['date']) + 86400 < time()) {
    $errors['date'] = 'Введите корректную дату';
  };
  
  if (in_array($_POST['project'], $idsArray)) {} else {
    $errors['project'] = 'Такого проекта не существует';
  };

  if(is_uploaded_file($_FILES['file']['tmp_name'])) {
  $fileName = $_FILES['file']['name'];
  $filePath = __DIR__ . '/';
  $fileUrl = '/' . $fileName;
  
  move_uploaded_file($_FILES['file']['tmp_name'], $filePath . $fileName);
  } else {
    $fileUrl = '';
  };
    
  if (empty($errors)) {
    addTask($connect, $_POST['project'], 2, $_POST['name'], $_POST['date'], $fileUrl);
    header ('Location: index.php');
    exit;
  };
}

$pageContent = include_template('addTask.php', ['errors' => $errors, 'projects' => $projects, 'allTasks' => $allTasks]); 
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'title' => "Дела в порядке", 'userName' => $userName]); 

print($layoutContent);

?>