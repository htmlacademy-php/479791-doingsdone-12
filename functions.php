<?php
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

function count_task($arr, $project) {
    $count = 0;
    foreach($arr as $task) {
        if ($task['project_id'] === $project || $project === '1') {
            $count++;
        }
    };
    return($count);
};

function date_overdue($date) {
    if ($date !== null) {
        $diff = strtotime($date) - strtotime("now");
        $hours_count = floor($diff/3600);
        return $hours_count;
    };
};

function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
};

function getPostVal($name) {
    return $_POST[$name] ?? "";
};

function connect() {
$connect = mysqli_connect ('localhost', 'root', 'root', 'doingsdone');
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    $error = mysqli_connect_error();
    print("Ошибка подключения к базе данных " . $error);
} 
return $connect;
};

//узнаём имя юзера
function getUserName($connect, $userId) {
    $userName;
    $sqlUserName = "SELECT user_name FROM users WHERE id = $userId";
    $result = mysqli_query($connect, $sqlUserName);

    if($result) {
        $userName = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    } 
    return $userName;
};

//добавляем проекты
function getProjects($connect, $userId) {
    $projects;
    $sqlProjects = "SELECT * FROM projects WHERE user_id = $userId";
    $result = mysqli_query($connect, $sqlProjects);

    if($result) {
        $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    } 
    return $projects;
};

//все задачи одного пользователя
function getTasks($connect, $userId) {
    $allTasks;
    $sqlTasks = "SELECT * FROM tasks WHERE user_id = $userId ORDER BY id DESC";
    $result = mysqli_query($connect, $sqlTasks);

    if($result) {
        $allTasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    } 
    return $allTasks;
};

//показываем задачи из проекта 

function getProjectTasks($connect, $id, $allTasks, $userId) {
    $tasks;

    if ($id == '') {
        $id = '1';
    }

    $sqlTasks = "SELECT * FROM tasks WHERE user_id = $userId AND project_id = $id ORDER BY id DESC";
    $result = mysqli_query($connect, $sqlTasks);

    if($result) {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if ($id == '1') {
            $tasks = $allTasks;
        }
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    } 
    return $tasks;
};

//все id проектов
function getProjectsID($connect, $userId) {
    $projectsIds;
    $sqlProjectsIds = "SELECT id FROM projects WHERE user_id = $userId";
    $result = mysqli_query($connect, $sqlProjectsIds);

    if($result) {
        $projectsIds = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    } 
    return $projectsIds;
};

//добавляем задачу
function addTask($connect, $projectId, $userId, $taskName, $date, $fileUrl) {

    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } 
    $sqlAddTask = "INSERT INTO tasks (project_id, user_id, task_name, task_deadline, file) VALUES ($projectId, $userId, '$taskName', '$date', '$fileUrl')";
    $result = mysqli_query($connect, $sqlAddTask);

    if(!$result) {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
};

?>