<?php
function config()
{
	static $co;
	if (!isset($co) )
	{
		$co = new stdClass();
		$co->user = NULL;
		$co->pass = NULL;
		$co->pdo = 'sqlite:xmain.db';
	/**
		// MySQL users: uncomment this:
		$co->user = 'user';
		$co->pass = 'password';
		$co->pdo = 'mysql:host=localhost;dbname=name';
		You will need to go to sys/setup.php too
		(through your web browser) to set up the tables.
	*/
		$parts = explode('/',$_SERVER['SCRIPT_NAME']);
		$n = array_search('sys', $parts);
		$pp = array_slice($parts, 0, $n);
		$co->path_absolute = implode("/",$pp);
		if (substr($co->path_absolute, 0, 1) == '/' )
		{
			$co->path_global = 'http://'.$_SERVER['HTTP_HOST'].$co->path_absolute;
		}
		else
		{
			$co->path_global = 'http://'.$_SERVER['HTTP_HOST'].'/'.$co->path_absolute;
		}
		$co->numentries = 10;
	}
	return $co;
}

