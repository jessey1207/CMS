<?php
include_once'connect_db.php';
$p=md5('abc');
$result=sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','data_verification',"password=$p",'account',"user_name=HR");

echo $result;
print_r($result);