<?php
require("../database.php");

$data = json_decode(file_get_contents('php://input'), true);

$user = @DB::select("users", ["username" => $data["username"]])[0];
if(!$user || !password_verify($data["password"], $user["password"])){
    die("Unauthorized");
}

$variables_values = DB::get("variables_values", ["id" => intval(@$data["group"])]);

if(!$variables_values){
    die("No such variable group");
}

$values = @$variables_values["values"] ?: [];

foreach($data["data"] as $key => $value) {
    $values[$key] = $value;
}


$variables_values["values"] = $values;
DB::upsert("variables_values", $variables_values);
