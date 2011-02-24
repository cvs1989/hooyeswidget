<?php
define('UC_CONNECT', 'mysql');
define('UC_DBHOST', 'localhost');
define('UC_DBUSER', 'root');
define('UC_DBPW', 'sohu');
define('UC_DBNAME', 'dz');
define('UC_DBCHARSET', 'gbk');
define('UC_DBTABLEPRE', '`dz`.cdb_uc_');
define('UC_DBCONNECT', '0');
define('UC_KEY', '1234567');
define('UC_API', 'http://localhost/dz/uc_server');
define('UC_CHARSET', 'gbk');
define('UC_IP', '');
define('UC_APPID', '4');
define('UC_PPP', '20');



/*
define('UC_CONNECT', 'mysql');
define('UC_DBHOST', 'localhost');
define('UC_DBUSER', 'root');
define('UC_DBPW', 'sohu');
define('UC_DBNAME', 'dz7');
define('UC_DBCHARSET', 'gbk');
define('UC_DBTABLEPRE', '`dz7`.uc_');
define('UC_DBCONNECT', '0');
define('UC_KEY', 'fdafdsafds');
define('UC_API', 'http://localhost/dz/uc_server');
define('UC_CHARSET', 'gbk');
define('UC_IP', '');
define('UC_APPID', '5');
define('UC_PPP', '20');
*/


//以下这一行必须保留.不能删除
$_SC = array('dbhost'=>UC_DBHOST,'dbuser'=>UC_DBUSER,'dbpw'=>UC_DBPW);