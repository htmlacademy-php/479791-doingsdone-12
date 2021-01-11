<?php
include 'functions.php';
include 'sql.php';

$required_fields = ['name', 'project'];
$errors = [];

if (isset($_POST['submit'])) {
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

  if (empty($errors)) {
    header ('Location: index.php?success=true');
  }
}

$layoutContent = include_template('addTask.php', ['errors' => $errors, 'projects' => $projects, 'tasks' => $tasks, 'allTasks' => $allTasks, 'userName' => $userName]); 

print($layoutContent);

?>