<?php
include 'functions.php';
$connect = connect();

$required_fields = ['email', 'password', 'name'];
$errors = [];

if (isset($_POST['submit'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $user = getUserInfo($connect, $email);
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        };
    };
  
    if (!empty($email)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Введите корректный Email';
        };

        if (!empty($user)) {
             $errors['email'] = 'Пользователь с этим Email уже зарегистрирован';
            };
        };

    if (!empty($_POST['password'])) {
        $hashPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    };
    
    if (empty($errors)) {
        addUser($connect, $name, $email, $hashPassword);
        header('Location: index.php');
        exit;
    };
};

$pageContent = includeTemplate('registerForm.php', [
    'errors' => $errors,
    ]);
$layoutContent = includeTemplate('layout.php', [
    'content' => $pageContent,
    'title' => "Дела в порядке",
    ]);

print($layoutContent);
