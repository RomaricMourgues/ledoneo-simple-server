<?php
session_start();

require("../database.php");

require("./pages/common/structure_before.php");

if(@$_SESSION["user_id"]){
    require("./pages/client.php");
}else{
    require("./pages/login.php");
}

require("./pages/common/structure_after.php");