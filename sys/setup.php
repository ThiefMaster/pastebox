<?php 
require_once("init.inc.php");
$db = db_get();
try { $st = $db->query('SELECT id FROM entry LIMIT 1;'); $st->execute();echo"No need for new tables, seems to work."; }
catch(Exception $e)
{
	if ($db->getAttribute(PDO::ATTR_DRIVER_NAME) == 'sqlite')
	{
	//$d->query('CREATE TABLE comment (id INTEGER PRIMARY KEY, content TEXT, date INT, entry INT, space TEXT, ip TEXT);');
		$db->query('CREATE TABLE entry (id INTEGER PRIMARY KEY, type TEXT, subject TEXT, description TEXT, content TEXT, ip TEXT, name TEXT, comments INT, date INT, space TEXT);');
		echo "<p>Tables were created.</p>";
	}
	else
	{
		$db->query('CREATE TABLE entry (id INTEGER PRIMARY KEY AUTO_INCREMENT, type TEXT, subject TEXT, description TEXT, content TEXT, ip TEXT, name TEXT, comments INT, date INT, space TEXT);');
		echo "<p>Tables were created.</p>";
	}
}

