<?php
$db = mysqli_connect('localhost', 'root', '', 'pawsome');

if (!$db) {
    die("Connection Failed!" . mysqli_connect_errno());
}
