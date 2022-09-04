<?php
session_start();

require("../database.php");

if(!@DB::select("users", [])[0]){
    echo "Creating missing user 'admin'<br/>";
    DB::upsert("users", [
        "id" => 1,
        "username" => "admin",
        "password" => password_hash("admin", PASSWORD_BCRYPT),
        "is_admin" => true,
        "variables_groups" => []
    ]);
}else{
    echo "Unable to install: some users already exists.";
}

echo "Install done.";