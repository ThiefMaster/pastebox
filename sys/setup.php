<?php 
require_once("init.inc.php");
$d = db_get();
try { $st = $d->query('SELECT id FROM entry LIMIT 1;'); $st->execute();echo"No need for new tables, seems to work."; }
catch(Exception $e)
{
	//$d->query('CREATE TABLE comment (id INTEGER PRIMARY KEY, content TEXT, date INT, entry INT, space TEXT, ip TEXT);');
	$d->query('CREATE TABLE entry (id INTEGER PRIMARY KEY, type TEXT, subject TEXT, description TEXT, content TEXT, ip TEXT, name TEXT, comments INT, date INT, space TEXT);');
	echo "<p>Tables were created.</p>";
}

