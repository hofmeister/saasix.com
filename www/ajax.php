<?
require_once 'bootstrap.php';

$data = $ctrl->$methodName();
if (!is_string($data)) {
    echo json_encode($data);
} else {
    echo $data;
}