<?php
include 'functions.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
};
$userID = $_SESSION['id'];
$userName = $_SESSION['user'];

$connect = connect();
$projects = getProjects($connect, $userID);
$allTasks = getTasks($connect, $userID);

$projectsNames = [];
foreach ($projects as $project) {
    array_push($projectsNames, mb_strtolower($project['project_name']));
};

$errors = [];

if (isset($_POST['submitProject'])) {
    if (empty($_POST['name'])) {
        $errors['name'] = 'Поле не заполнено';
    };
  
    if (in_array(mb_strtolower($_POST['name']), $projectsNames, true)) {
        $errors['name'] = 'Такой проект уже существует';
    };

    if (empty($errors)) {
        addProject($connect, $userID, $_POST['name']);
        header('Location: index.php');
        exit;
    };
};

$pageContent = includeTemplate('addProject.php', [
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
