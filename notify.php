<?php
require('vendor/autoload.php');
include 'functions.php';
$connect = connect();

$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);

$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$users = getUsersTasksToday($connect);
$dataToSend = [];

foreach ($users as $user) {
    $dataToSend[$user['id']]['name'] = $user['user_name'];
    $dataToSend[$user['id']]['email'] = $user['e_mail'];
    $dataToSend[$user['id']]['tasks'][] = [
        'title' => $user['task_name'],
        'deadline' => $user['task_deadline']
    ];
};

foreach ($dataToSend as $userData) {
    $message = new Swift_Message();
    $message->setSubject("Уведомление от сервиса «Дела в порядке»");
    $message->setFrom('keks@phpdemo.ru');
    $message->setTo($userData['email']);

    $message_content = 'Уважаемый, ' . $userData['name'] .'<br>';

    foreach ($userData['tasks'] as $task) {
        $message_content .= 'У вас запланирована задача: ';
        $message_content .=  $task['title'];
        $message_content .= ' на ' . date('d.m.Y', strtotime($task['deadline']));
        $message_content .= '<br>';
    }

    $message->addPart($message_content . '<br>', 'text/html');
}

$result = $mailer->send($message);

if ($result) {
    print("Рассылка успешно отправлена");
} else {
    print("Не удалось отправить рассылку: " . $logger->dump());
}
