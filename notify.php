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
    $dataToSend[$user['id']]['name'] = $user['user_name'] ?? '';
    $dataToSend[$user['id']]['email'] = $user['e_mail'] ?? '';
    $dataToSend[$user['id']]['tasks'][] = [
        'title' => $user['task_name'] ?? '',
        'deadline' => $user['task_deadline'] ?? '',
    ];
};

foreach ($dataToSend as $taskData) {
    $message = new Swift_Message();
    $message->setSubject("Уведомление от сервиса «Дела в порядке»");
    $message->setFrom('keks@phpdemo.ru');
    $message->setTo($taskData['email']);

    $messageСontent = 'Уважаемый, ' . ($taskData['name'] ?? '') .'<br>';

    foreach ($taskData['tasks'] as $task) {
        $messageСontent .= 'У вас запланирована задача: ';
        $messageСontent .=  $task['title'] ?? '';
        $messageСontent .= ' на ' . date('d.m.Y', strtotime($task['deadline'] ?? ''));
        $messageСontent .= '<br>';
    }

    $message->addPart($messageСontent . '<br>', 'text/html');
    $result = $mailer->send($message);

    if ($result) {
        print("Рассылка для " . ($taskData['name'] ?? '') . " успешно отправлена");
    } else {
        print("Не удалось отправить рассылку для " . ($taskData['name'] ?? '') . " : " . $logger->dump());
    }
}
