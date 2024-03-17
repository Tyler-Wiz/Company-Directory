<?php

function handleResponse(int $code, string $name, string $desc, array | bool $data = null) {

    $executionStartTime = microtime(true);
    $output['status']['code'] = $code;
    $output['status']['name'] = $name;
    $output['status']['description'] = $desc;
    $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
    $output['data'] = $data;


    echo json_encode($output);
}
