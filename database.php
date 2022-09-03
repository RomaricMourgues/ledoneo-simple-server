<?php

class DB {

    static function autoIncrement($table, $key = "id") {
        $data = DB::load($table);
        $id = 0;
        foreach($data as $line){
            $id = max($id, $line[$key]);
        }
        return $id + 1;
    }

    static function select($table, $query = []) {
        $data = DB::load($table);
        return array_values(array_filter($data, function($v, $k) use ($query) {
            return DB::testQuery($v, $query);
        }, ARRAY_FILTER_USE_BOTH));
    }

    static function get($table, $query) {
        $data = DB::load($table);
        return @array_values(array_filter($data, function($v, $k) use ($query) {
            return DB::testQuery($v, $query);
        }, ARRAY_FILTER_USE_BOTH))[0];
    }

    static function upsert($table, $value, $key = ["id"]) {
        $data = DB::load($table);
        $query = array_filter($value,
            function($v, $k) use ($key) {
                return in_array($k, $key);
            }, ARRAY_FILTER_USE_BOTH);
        $data = array_values(array_filter($data, function($v, $k) use ($query) {
            return !DB::testQuery($v, $query);
        }, ARRAY_FILTER_USE_BOTH));
        $data[] = $value;
        DB::save($table, $data);
    }

    static function delete($table, $query) {
        $data = DB::load($table);
        $data = array_values(array_filter($data, function($v, $k) use ($query) {
            return !DB::testQuery($v, $query);
        }, ARRAY_FILTER_USE_BOTH));
        DB::save($table, $data);
    }

    static function load($table) {
        try{
            if(!file_exists(__DIR__ . "/database/" . $table . ".json")){
                file_put_contents(__DIR__ . "/database/" . $table . ".json", "[]");
            }
            return json_decode(file_get_contents(__DIR__ . "/database/" . $table . ".json"), true);
        }catch(Exception $err){
            return [];
        }
    }

    static function save($table, $data) {
        return file_put_contents(__DIR__ . "/database/" . $table . ".json", json_encode($data));
    }

    static function testQuery($candidate, $query) {
        foreach($query as $key => $value){
            if($candidate[$key] !== $value){
                return false;
            }
        }
        return true;
    }

}