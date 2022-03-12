<?php

$conn = mysqli_connect('localhost', 'root', 'root', 'test_app');

if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}



?>