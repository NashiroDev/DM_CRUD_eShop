<?php
session_start();

include_once('/app/conf/variables.php');

if (isset($_SESSION['CURRENT_USER'])) {
    session_destroy();
}

header("Location:$rootUrl");