<?php
include 'functions.php';
$connect = connect();
$users = getUsersInfo($connect);

$required_fields = ['email', 'password'];
$errors = [];

if (isset($_POST['submit'])) {
  
  foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $errors[$field] = 'Поле не заполнено';
    };
  };
  
  if (!empty($_POST['email'])) {
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
      $errors['email'] = 'Введите корректный Email';
    };

    foreach ($users as $user) {
      if ($user['e_mail'] == $_POST['email']) {
          
          if (password_verify($_POST['password'], $user['user_password'])) {
            session_start();
            $_SESSION['id'] = $user['id'];
            $_SESSION['user'] = $user['user_name'];
            header ('Location: index.php');
          } else {
            $errors['password'] = 'Введите верный пароль';
          };
        };
    };
  };
};

$pageContent = include_template('authForm.php', ['errors' => $errors]); 
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'title' => "Дела в порядке"]); 

print($layoutContent);

?>