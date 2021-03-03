<?php
include 'functions.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
};
$userID = $_SESSION['id'] ?? '';
$userName = $_SESSION['user'] ?? '';

$connect = connect();
$projects = getProjects($connect, $userID);
$allTasks = getTasks($connect, $userID);
$projectsIds = getProjectsID($connect, $userID);
$idsArray = [];
foreach ($projectsIds as $projectId) {
    array_push($idsArray, $projectId['id']);
};

$required_fields = ['name', 'project'];
$errors = [];

if (isset($_POST['submit'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $project = filter_input(INPUT_POST, 'project', FILTER_SANITIZE_SPECIAL_CHARS);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_SPECIAL_CHARS);
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        };
    };
    
    if ($date !== "") {
        if (isDateValid($date) === false) {
            $errors['date'] = 'Неверный формат даты';
        };

        if (strtotime($date) + 86400 < time()) {
            $errors['date'] = 'Введите корректную дату';
        };
    } else $date = NULL;

    if (!in_array($project, $idsArray)) {
        $errors['project'] = 'Такого проекта не существует';
    };

    if (is_uploaded_file($_FILES['file']['tmp_name'] ?? '')) {
        $fileName = $_FILES['file']['name'] ?? '';
        $filePath = __DIR__ . '/';
        $fileUrl = '/' . $fileName;
  
        move_uploaded_file($_FILES['file']['tmp_name'] ?? '', $filePath . $fileName);
    } else {
        $fileUrl = '';
    };
    
    if (empty($errors)) {
        addTask($connect, $project, $userID, $name, $date, $fileUrl);
        header('Location: index.php');
        exit;
    };
}

$pageContent = includeTemplate('addTask.php', [
    'errors' => $errors,
    'projects' => $projects,
    'allTasks' => $allTasks,
    ]);
$layoutContent = includeTemplate('layout.php', [
    'content' => $pageContent,
    'title' => "Дела в порядке",
    'userName' => $userName,
    ]);

print($layoutContent);
