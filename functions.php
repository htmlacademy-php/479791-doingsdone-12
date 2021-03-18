<?php
/**
 * Шаблонизатор
 * @param $name
 * @param $data
 * @return false|string
 */
function includeTemplate($name, array $data = [])
{
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
}

/**
 * Подсчёт задач в проекте
 * @param $arr
 * @param $project
 * @return integer
 */
function countTask($arr, $project)
{
    $count = 0;
    foreach ($arr as $task) {
        if ($task['project_id'] === $project || $project === '1') {
            $count++;
        }
    };
    return($count);
}

/**
 * Проверка задач на просроченный дедлайн
 * @param $date
 * @return integer
 */
function dateOverdue($date)
{
    if ($date !== null) {
        $diff = strtotime($date) - strtotime("now");
        $hours_count = floor($diff/3600);
        return $hours_count;
    };
}

/**
 * Проверяет правильность введённой даты
 * @param $date
 * @return bool
 */
function isDateValid(string $date) : bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Возвращает введённое значение в поле формы при ошибке в форме
 * @param $name
 * @return string
 */
function getPostVal($name)
{
    return $_POST[$name] ?? "";
}

/**
 * Подключение к базе данных
 * @return mysqli|false
 */
function connect()
{
    $connect = mysqli_connect('localhost', 'root', 'root', 'doingsdone');
    mysqli_set_charset($connect, "utf8");

    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    }
    return $connect;
}

/**
 * Узнаём данные юзера по мэйлу
 * @param $connect
 * @param $userMail
 * @return array
 */
function getUserInfo($connect, $userMail)
{
    $usersInfo = [];
    $safeUserMail = mysqli_real_escape_string($connect, $userMail);
    $sqlUsersInfo = "SELECT * FROM users WHERE e_mail = '$safeUserMail'";
    $result = mysqli_query($connect, $sqlUsersInfo);

    if ($result) {
        $usersInfo = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
    return $usersInfo;
}

/**
 * Узнаём проекты пользователю
 * @param $connect
 * @param $userId
 * @return array
 */
function getProjects($connect, $userId)
{
    $projects = [];
    $safeUserId = mysqli_real_escape_string($connect, $userId);
    $sqlProjects = "SELECT * FROM projects WHERE user_id = $safeUserId";
    $result = mysqli_query($connect, $sqlProjects);

    if ($result) {
        $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
    return $projects;
}

/**
 * Узнаём все задачи одного пользователя
 * @param $connect
 * @param $userId
 * @return array
 */
function getTasks($connect, $userId)
{
    $allTasks = [];
    $safeUserId = mysqli_real_escape_string($connect, $userId);
    $sqlTasks = "SELECT * FROM tasks WHERE user_id = $safeUserId ORDER BY id DESC";
    $result = mysqli_query($connect, $sqlTasks);

    if ($result) {
        $allTasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
    return $allTasks;
}

/**
 * Узнаём все задачи проекта
 * @param $connect
 * @param $id
 * @param $allTasks
 * @param $userId
 * @return array
 */
function getProjectTasks($connect, $id, $userId)
{
    $tasks = [];
    $safeId = mysqli_real_escape_string($connect, $id);
    $safeUserId = mysqli_real_escape_string($connect, $userId);
    $sqlTasks = "SELECT * FROM tasks WHERE user_id = $safeUserId AND project_id = $safeId ORDER BY id DESC";
    $result = mysqli_query($connect, $sqlTasks);

    if ($result) {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
    return $tasks;
}

/**
 * Узнаём все ID проектов
 * @param $connect
 * @param $userId
 * @return array
 */
function getProjectsID($connect, $userId)
{
    $projectsIds = [];
    $safeUserId = mysqli_real_escape_string($connect, $userId);
    $sqlProjectsIds = "SELECT id FROM projects WHERE user_id = $safeUserId";
    $result = mysqli_query($connect, $sqlProjectsIds);

    if ($result) {
        $projectsIds = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
    return $projectsIds;
}

/**
 * Добавляем задачу в проект
 * @param $connect
 * @param $projectId
 * @param $userId
 * @param $taskName
 * @param $date
 * @param $fileUrl
 * @return void
 */
function addTask($connect, $projectId, $userId, $taskName, $date, $fileUrl)
{

    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    }
    $safeTaskName = mysqli_real_escape_string($connect, $taskName);
    $safeFileUrl = mysqli_real_escape_string($connect, $fileUrl);
    if ($date === null) {
        $sqlAddTask = "INSERT INTO tasks (project_id, user_id, task_name, task_deadline, file) VALUES ($projectId, $userId, '$safeTaskName', null, '$safeFileUrl')";
    } else {
        $sqlAddTask = "INSERT INTO tasks (project_id, user_id, task_name, task_deadline, file) VALUES ($projectId, $userId, '$safeTaskName', '$date', '$safeFileUrl')";
    };
    $result = mysqli_query($connect, $sqlAddTask);

    if (!$result) {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
}

/**
 * Добавляем пользователя
 * @param $connect
 * @param $name
 * @param $email
 * @param $password
 * @return void
 */
function addUser($connect, $name, $email, $password)
{

    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    }
    $safeName = mysqli_real_escape_string($connect, $name);
    $safeEmail = mysqli_real_escape_string($connect, $email);
    $sqlAddUser = "INSERT INTO users (user_name, e_mail, user_password) VALUES ('$safeName', '$safeEmail', '$password')";
    $result = mysqli_query($connect, $sqlAddUser);

    if (!$result) {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
}

/**
 * Ищем задачи по поиску
 * @param $connect
 * @param $searchWord
 * @param $userId
 * @return array
 */
function getSearchTasks($connect, $searchWord, $userId)
{
    $tasks = [];

    $safeSearchWord = mysqli_real_escape_string($connect, $searchWord);
    $sqlTasks = "SELECT * FROM tasks WHERE MATCH(task_name) AGAINST('$safeSearchWord') AND user_id = $userId";
    $result = mysqli_query($connect, $sqlTasks);

    if ($result) {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
    return $tasks;
}


/**
 * Добавляем проект пользователю
 * @param $connect
 * @param $userId
 * @param $projectName
 * @return void
 */
function addProject($connect, $userId, $projectName)
{

    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    }
    $safeProjectName = mysqli_real_escape_string($connect, $projectName);
    $sqlAddProject = "INSERT INTO projects (project_name, user_id) VALUES ('$safeProjectName', $userId)";
    $result = mysqli_query($connect, $sqlAddProject);

    if (!$result) {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
}


/**
 * Фильтрация задач на сегодня
 * @param $tasks
 * @return array
 */
function filterToday($tasks)
{
    $filterTasks = [];
    foreach ($tasks as $task) {
        if (strtotime($task['task_deadline'] ?? '') === strtotime(date('Y-m-d'))) {
            array_push($filterTasks, $task);
        };
    };
    return $filterTasks;
}


/**
 * Фильтрация задач на завтра
 * @param $tasks
 * @return array
 */
function filterTommorow($tasks)
{
    $filterTasks = [];
    foreach ($tasks as $task) {
        if (strtotime($task['task_deadline'] ?? '') === strtotime(date('Y-m-d')) + 86400) {
            array_push($filterTasks, $task);
        };
    };
    return $filterTasks;
}

/**
 * Фильтрация просроченных задач
 * @param $tasks
 * @return array
 */
function filterExpired($tasks)
{
    $filterTasks = [];
    foreach ($tasks as $task) {
        if (strtotime($task['task_deadline'] ?? '') < strtotime(date('Y-m-d')) && $task['task_deadline'] !== null) {
            array_push($filterTasks, $task);
        };
    };
    return $filterTasks;
}

/**
 * Фильтрация задач по фильтрам
 * @param $tasks
 * @param $filter
 * @return array
 */
function filterTasks($tasks, $filter)
{
    $filterTasks = [];

    if ($filter === 'today') {
        $filterTasks = filterToday($tasks);
    };

    if ($filter === 'tommorow') {
        $filterTasks = filterTommorow($tasks);
    };

    if ($filter === 'expired') {
        $filterTasks = filterExpired($tasks);
    };

    if ($filter === 'all' || $filter == '') {
        $filterTasks = $tasks;
    };
    return $filterTasks;
}

/**
 * Устанавливает статус задачи - выполненная  
 * @param $connect
 * @param $taskId
 * @return void
 */
function GetTaskDone($connect, $taskId)
{
    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    };
    $safeTaskId = mysqli_real_escape_string($connect, $taskId);
    $sqlTakDone = "UPDATE tasks SET task_done = 1 WHERE id = $safeTaskId";
    $result = mysqli_query($connect, $sqlTakDone);

    if (!$result) {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    };
}

/**
 * Устанавливает статус задачи - невыполненная 
 * @param $connect
 * @param $taskId
 * @return void
 */
function GetTaskUndone($connect, $taskId)
{
    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    };
    $safeTaskId = mysqli_real_escape_string($connect, $taskId);
    $sqlTakUndone = "UPDATE tasks SET task_done = 0 WHERE id = $safeTaskId";
    $result = mysqli_query($connect, $sqlTakUndone);

    if (!$result) {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    };
}

/**
 * Переключает задачу на выполненную и обратно 
 * @param $connect
 * @param $taskId
 * @return void
 */
function switchTaskDone($connect, $taskId)
{
    if (!$connect) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    }
    $safeTaskId = mysqli_real_escape_string($connect, $taskId);
    $checkTaskDone = "SELECT task_done FROM tasks WHERE id = $safeTaskId";
    $result = mysqli_query($connect, $checkTaskDone);

    if ($result) {
        $taskDone = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    };
    if (($taskDone[0]['task_done'] ?? null) === '0') {
        GetTaskDone($connect, $taskId);
    } else {
        GetTaskUndone($connect, $taskId);
    };
}

/**
 * Получаем задачи запланированные на сегодня и список пользователей 
 * @param $connect
 * @return array
 */
function getUsersTasksToday($connect)
{
    $usersTasksToday = [];
    $sqlTasksToday = "SELECT users.id, users.user_name, users.e_mail, tasks.task_name , tasks.task_deadline
    FROM users JOIN tasks ON tasks.user_id = users.id 
    WHERE tasks.task_done = '0' AND tasks.task_deadline = CURRENT_DATE()";
    $result = mysqli_query($connect, $sqlTasksToday);

    if ($result) {
        $usersTasksToday = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print ("Ошибка MySQL" . $error);
    }
    return $usersTasksToday;
}
