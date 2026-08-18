<?php

$conn = mysqli_connect('localhost', 'root', '', 'db_hardware');

if ($conn) {
    // echo "Connection successful";
} else {
    echo "Connection failed";
}
