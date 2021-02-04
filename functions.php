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
    $userName = '';
    $sqlUserInfo = "SELECT user_name FROM users WHERE id = $userId";
    $result = mysqli_query($connect, $sqlUserInfo);

    if($result) {
        $userInfo = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $userName = $userInfo[0]['user_name'];
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    } 
    return $userName;
};

//узнаём данные юзеров
function getUsersInfo($connect) {
    $usersInfo = [];
    $sqlUsersInfo = "SELECT * FROM users";
    $result = mysqli_query($connect, $sqlUsersInfo);

    if($result) {
        $usersInfo = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    } 
    return $usersInfo;
};

//добавляем проекты
function getProjects($connect, $userId) {
    $projects = [];
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
    $allTasks = [];
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
    $tasks = [];

    $sqlTasks = "SELECT * FROM tasks WHERE user_id = $userId AND project_id = $id ORDER BY id DESC";
    $result = mysqli_query($connect, $sqlTasks);

    if($result) {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
      
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    } 
    return $tasks;
};

//все id проектов
function getProjectsID($connect, $userId) {
    $projectsIds = [];
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
    $safeTaskName = mysqli_real_escape_string($connect,$taskName);
    $safeFileUrl = mysqli_real_escape_string($connect,$fileUrl);
    $sqlAddTask = "INSERT INTO tasks (project_id, user_id, task_name, task_deadline, file) VALUES ($projectId, $userId, '$safeTaskName', '$date', '$safeFileUrl')";
    $result = mysqli_query($connect, $sqlAddTask);

    if(!$result) {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
};

//добавляем пользователя

function addUser($connect, $name, $email, $password) {

    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } 
    $safeName = mysqli_real_escape_string($connect,$name);
    $safeEmail = mysqli_real_escape_string($connect,$email);
    $sqlAddUser = "INSERT INTO users (user_name, e_mail, user_password) VALUES ('$safeName', '$safeEmail', '$password')";
    $result = mysqli_query($connect, $sqlAddUser);

    if(!$result) {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
};

//ищем задачи по поиску

function getSearchTasks($connect, $searchWord, $userId){
    $tasks = [];

    $safeSearchWord = mysqli_real_escape_string($connect,$searchWord);
    $sqlTasks = "SELECT * FROM tasks WHERE MATCH(task_name) AGAINST('$safeSearchWord') AND user_id = $userId";
    $result = mysqli_query($connect, $sqlTasks);

    if($result) {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
      
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    } 
    return $tasks;
};

//добавляем проект

function addProject($connect, $userId, $projectName) {

    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } 
    $safeProjectName = mysqli_real_escape_string($connect,$projectName);
    $sqlAddProject = "INSERT INTO projects (project_name, user_id) VALUES ('$safeProjectName', $userId)";
    $result = mysqli_query($connect, $sqlAddProject);

    if(!$result) {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
};

//фильтрация задач

function filterToday($tasks) {
    $filterTasks = [];
    foreach ($tasks as $task):
        if(strtotime($task['task_deadline']) == strtotime(date('Y-m-d'))) {
            array_push($filterTasks, $task);
        };
    endforeach;
    return $filterTasks;
};

function filterTommorow($tasks) {
    $filterTasks = [];
    foreach ($tasks as $task):
        if(strtotime($task['task_deadline']) == strtotime(date('Y-m-d')) + 86400) {
            array_push($filterTasks, $task);
        };
    endforeach;
    return $filterTasks;
};

function filterExpired($tasks) {
    $filterTasks = [];
    foreach ($tasks as $task):
        if(strtotime($task['task_deadline']) < strtotime(date('Y-m-d'))) {
            array_push($filterTasks, $task);
        };
    endforeach;
    return $filterTasks;
};

function filterTasks($tasks, $filter){
    $filterTasks = [];

    if ($filter == 'today') {
        $filterTasks = filterToday($tasks);
    };

    if ($filter == 'tommorow') {
        $filterTasks = filterTommorow($tasks);
    };

    if ($filter == 'expired') {
        $filterTasks = filterExpired($tasks);
    };

    if ($filter == 'all' || $filter == '') {
        $filterTasks = $tasks;
    };
    return $filterTasks;
};

//переключает задачу на выполненную и обратно

function GetTaskDone($connect, $taskId) {
    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
        };
    $sqlTakDone = "UPDATE tasks SET task_done = 1 WHERE id = $taskId";;
    $result = mysqli_query($connect, $sqlTakDone);

    if(!$result) {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    };
};

function GetTaskUndone($connect, $taskId) {
    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
        };
    $sqlTakUndone = "UPDATE tasks SET task_done = 0 WHERE id = $taskId";;
    $result = mysqli_query($connect, $sqlTakUndone);

    if(!$result) {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    };
};


function switchTaskDone($connect ,$taskId) {
    if (!$connect) {
    $error = mysqli_connect_error();
    print("Ошибка подключения к базе данных " . $error);
    } 
    $checkTaskDone = "SELECT task_done FROM tasks WHERE id = $taskId";
    $result = mysqli_query($connect, $checkTaskDone);

    if($result) {
        $taskDone = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }; 
    if ($taskDone[0]['task_done'] == '0') {
        GetTaskDone($connect, $taskId);
    } else {
        GetTaskUndone($connect, $taskId);
        };
};
?>
