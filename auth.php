<?php
include 'functions.php';
$connect = connect();

$required_fields = ['email', 'password'];
$errors = [];

if (isset($_POST['submit'])) {

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        };
    };
  
    if (!empty($_POST['email'])) {
        if (filter_var(($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Введите корректный Email';
        };
        $user = getUserInfo($connect, $_POST['email'] ?? '');
        if (password_verify(($_POST['password'] ?? ''), ($user[0]['user_password'] ?? ''))) {
            session_start();
            $_SESSION['id'] = $user[0]['id'] ?? '';
            $_SESSION['user'] = $user[0]['user_name'] ?? '';
            $_SESSION['show_complete_tasks'] = $user[0]['show_complete_tasks'] ?? '';
            header('Location: index.php');
        } else {
            $errors['password'] = 'Введите верный пароль';
        };
    };
};

$pageContent = includeTemplate('authForm.php', [
    'errors' => $errors,
    ]);
$layoutContent = includeTemplate('layout.php', [
    'content' => $pageContent,
    'title' => "Дела в порядке",
    ]);

print($layoutContent);
