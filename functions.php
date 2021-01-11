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
?>