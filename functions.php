<?php
define("DOMAIN", "http://localhost:63342/todoapp/");
define("STATUS_OPENED", "0");
define("STATUS_CLOSED", "1");
define("TODO_LIST_CSV", "todo_list.csv");
define("TASK_MAX_LENGTH", 140);

define("MESSAGE_TASK_EMPTY", "タスクが未入力です。");
define("MESSAGE_TASK_MAX_LENGTH", "タスクが140文字を超えています。");
define("MESSAGE_ID_INVALID", "選択されたタスクは不正です。");

function read_todo_list($include_closed = true, $reverse = false)
{
    $handle = fopen(TODO_LIST_CSV, "r");
    $todo_list = [];
    while ($todo = fgetcsv($handle)) {
        if ($todo[3] === STATUS_CLOSED && $include_closed === false) {
            continue;
        }
        $todo_list[] = $todo;
    }
    fclose($handle);

    if ($reverse === true) {
        $todo_list = array_reverse($todo_list);
    }
    return $todo_list;
}

function write_todo_list($todo_list)
{
    $handle = fopen(TODO_LIST_CSV, "w");
    foreach ($todo_list as $todo) {
        fputcsv($handle, $todo);
    }
    fclose($handle);
}

function get_todo_id()
{
    return count(read_todo_list()) + 1;
}

function add_todo_list($todo)
{
    $handle = fopen(TODO_LIST_CSV, "a");
    fputcsv($handle, $todo);
    fclose($handle);
}

function redirect($page)
{
    header("Location: " . DOMAIN . $page);
    exit();
}

function redirect_with_message($page, $message)
{
    if (empty($message)) {
        redirect($page);
    }
    $message = urlencode($message);
    header("Location: " . DOMAIN . $page . "?message=${message}");
    exit();
}
