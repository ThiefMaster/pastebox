<?php

error_reporting(E_ALL);
$ver = (int)implode('',explode('.', PHP_VERSION ));
if ( $ver < 520 )
{
	die('This script is dependent on PHP >= 5.2. If you are willing to test this anyway, please comment the line '.__LINE__.' in file '.__FILE__.'.');
}
if ( get_magic_quotes_gpc() == 1 )
{
	die('Magic quotes are on. Please add \'php_flag magic_quotes_gpc off\' to your .htaccess configuration or update your php.ini.');
}
if ( ini_get("register_globals") )
{
	die('register_globals is on. Please add \'php_flag register_globals off\' to your .htaccess configuration or update your php.ini.');
}
if ( !isset($_SERVER['SCRIPT_NAME']) )
{
	die('$'.'_SERVER[\'SCRIPT_NAME\'] was not set. Assuming that not called from a web server - diying.');
}
require_once("config.inc.php");
require_once("functions.inc.php");
require_once("dbobject.class.inc.php");
require_once("dbs.class.inc.php");
require_once("definition.inc.php");

