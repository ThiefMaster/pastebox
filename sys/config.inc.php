<?php
function config()
{
	static $co;
	if (!isset($co) )
	{
		$co = new stdClass();
		$co->pdo = 'sqlite:xmain.db';
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

