<?php
require_once("init.inc.php");
$iz = array('subject', 'type', 'description', 'content', 'name');
$v = vars_get($_POST, $iz);
if ( $v === false )
{
	require_once("views/new.php");
}
else
{
	$e = entry::main();
	$entry = $e->cnew();
	vars_inject($entry, $v, $iz);
	if ( empty($entry->subject) ) { $entry->subject = 'Untitled'; }
	if ( empty($entry->description) ) { $entry->description = 'No description given'; }
	if ( empty($entry->name) ) { $entry->name = 'Unnamed'; }
	$entry->date = time();
	$id = $entry->save();
	header('Location: '.url_generate('entry.view', $id));
}

