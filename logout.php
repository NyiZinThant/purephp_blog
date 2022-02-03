<?php
require "./config/common.php";
session_start();
session_destroy();
header('location: login.php');