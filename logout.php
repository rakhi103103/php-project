<?php
require "functions.php";
session_unset();
session_regenerate_id();

header("location:login.php");
die;