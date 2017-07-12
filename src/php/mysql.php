<?php
$MySQL_DB = new mysqli('localhost', 'root', '!@#$%', 'app');
if ($MySQL_DB->errno)
    die('Sorry, some problems occured...');

