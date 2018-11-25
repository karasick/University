<?php

function GetLink() {

    $server = 'localhost';
    $username = 'admin';
    $password = '';
    $db = 'university';

    $link = mysqli_connect($server, $username, $password, $db);

    if(mysqli_connect_errno()) {
        echo "Error (" . mysqli_connect_errno() . "): " . mysqli_connect_error();
        exit();
    }

    return $link;
}
?>