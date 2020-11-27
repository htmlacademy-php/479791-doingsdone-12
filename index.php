<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$projects = ['Все', 'Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'completion_date' => '01.12.2019',
        'Category' => 'Работа',
        'Done' => false
    ],
    [
        'task' => 'Выполнить тестовое задание',
        'completion_date' => '25.12.2019',
        'Category' => 'Работа',
        'Done' => false
    ],
    [
        'task' => 'Сделать задание первого раздела',
        'completion_date' => '21.12.2019',
        'Category' => 'Учеба',
        'Done' => true
    ],
    [
        'task' => 'Встреча с другом',
        'completion_date' => '22.12.2019',
        'Category' => 'Входящие',
        'Done' => false
    ],
    [
        'task' => 'Купить корм для кота',
        'completion_date' => null,
        'Category' => 'Домашние дела',
        'Done' => false
    ],
    [
        'task' => 'Заказать пиццу',
        'completion_date' => null,
        'Category' => 'Домашние дела',
        'Done' => false
    ]
];

function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
};

$page_content = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => "Дела в порядке"]); 

print($layout_content);
?>