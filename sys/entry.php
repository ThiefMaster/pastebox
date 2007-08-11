<?php
require_once("init.inc.php");

if ( !isset($_GET['id']) )
{
	include("views/404.php");
}
else
{
	$e = entry::main();
	$entry = $e->get((int)$_GET['id']);
	if ( $entry !== false )
	{
		if ( isset($_GET['ot']) && $_GET['ot'] == 'txt' )
		{
			include("views/entry.view.txt.php");
		}
		else
		{
			include("views/entry.view.php");
		}
	}
	else
	{
		include("views/404.php");
	}
}
