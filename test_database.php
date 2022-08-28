<?php
require("./database.php");

DB::upsert("users", [
    "id" => 1,
    "username" => "admin"
]);

DB::upsert("users", [
    "id" => 2,
    "username" => "admin",
    "password" => "somehash"
]);

DB::delete("users", ["id" => 3]);
DB::delete("users", ["id" => 1]);

error_log(json_encode(DB::select("users", ["id" => 1])));