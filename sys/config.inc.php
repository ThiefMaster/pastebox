<?php
function config()
{
	static $co;
	if (!isset($co) )
	{
		$co = new stdClass();
$co->pdo = 'sqlite:xmain.db';
$co->path_absolute = '/pastebox';
$co->path_global = 'http://localhost'.$co->path_absolute;
$co->numentries = 10;
	}
	return $co;
}

