<?php namespace php\api;
session_start();

include '../src/php/essentials.php';
require '../src/php/mysql.php';

$server = new JsonRpc(new class {
    public function getUserData($username) {
        global $MySQL_DB;
        $result = $MySQL_DB->query("SELECT email,first_name,last_name FROM users WHERE username='$username'");
        if ($MySQL_DB->errno) {
            error('MySQL: ' . $MySQL_DB->error);
            return null;
        }

        $return = array();
        while ($row = $result->fetch_assoc())
            foreach($row as $k => $v)
                $return[$k] = $v;
        $result->free();
        return $return;
    }

    public function update($username, $password, $data) {
        global $MySQL_DB;

        $sql = 'UPDATE users SET ';
        $arr = array();
        foreach ($data as $k => $v)
            if (!empty($v))
                $arr[] = "$k='$v'";
        $sql .= join(', ', $arr);

        $MySQL_DB->query($sql." WHERE username='$username' AND password='". encrypt($password) ."'");
        if ($MySQL_DB->errno) {
            error('MySQL: ' . $MySQL_DB->error);
            return $MySQL_DB->error;
        }
        return true;
    }

    public function register($username, $password) {
        global $MySQL_DB;
        $MySQL_DB->query("INSERT INTO users (username,password,privileges) VALUES ('$username','" . encrypt($password) . "',1)");
        if ($MySQL_DB->errno) {
            error('MySQL: ' . $MySQL_DB->error);
            return -1;
        }
        return 1;
    }

    public function login($username, $password) {
        global $MySQL_DB;
        $result = $MySQL_DB->query("SELECT password,privileges FROM users WHERE username = '$username'") or die($MySQL_DB->error);
        while ($row = $result->fetch_assoc()) {
            if (hash_equals($row['password'], encrypt($password))) {
                $result->free();
                return $row['privileges'];
            }
        }
        error('Failed to login!');
        return -1;
    }
});
$server->process();
