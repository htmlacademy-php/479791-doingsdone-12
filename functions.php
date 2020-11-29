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

function count_task($arr, $projectname) {
    $count = 0;
    foreach($arr as $task) {
        if ($task['Category'] === $projectname) {
            $count++;
        }
    };
    return($count);
};

function date_overdue($date) {
    if ($date !== null) {
    $diff = date_timestamp_get($date) - strtotime("now");
    $hours_count = $diff/3600;
    return $hours_count;
    };
};
?>