<?php

$database = new mysqli("localhost", "root", "", "sistema-citas",3307);
if ($database->connect_error) {
    die("Connection failed:  " . $database->connect_error);
}
