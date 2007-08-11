<?php
require_once("init.inc.php");

$e = entry::main();
$entries = $e->getlatest(config()->numentries);

if ( isset($_GET['ot']) && $_GET['ot'] == 'rss' )
{
	include("views/entry.list.rss.php");
}
else
{
	require_once("views/entry.list.php");
}

