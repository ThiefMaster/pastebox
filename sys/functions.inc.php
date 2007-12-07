<?php
function db_get()
{
	static $link;
	if ( !isset($link) )
	{
		$link = new PDO(config()->pdo, config()->user, config()->pass);

		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	return $link;
}
function url_generate()
{
	$fa = func_get_args();
	$t = array_shift($fa);
	$glob = false;
	if ( is_array($t) ) { if ( isset($t[1]) ) { $glob = (bool)$t[1]; }$t = $t[0]; }
	if ( $glob)
	{
		$pp = config()->path_global;
	}
	else
	{
		$pp = config()->path_absolute;
	}
	switch($t)
	{
		case 'entry.view':
			return $pp.'/'.array_shift($fa);
		case 'index':
			return $pp.'/';
		case 'index.rss':
			return $pp.'/rss';
		case 'new':
			return $pp.'/new';
		case 'entry.txt':
			return $pp.'/'.array_shift($fa).'/txt';
		default:
			return $pp.'/'.array_shift($fa);
	}
}
function vars_inject_definition()
{
	$ar = func_get_args();
	$definition = array_shift($ar);
	$destination = new stdClass();
	array_unshift($ar, $destination);
	call_user_func_array('vars_inject', $ar);

	foreach((array)$destination as $key => $value)
	{
		$definition->get($key)->control->__value = $value;
	}
}
function vars_inject()
{
	$args = func_get_args();
	$count = count($args);
	if ( $count < 3 )
	{
		throw new Exception("Not enough arguments - ".$count.".");
		return false;
	}
	$dest = array_shift($args);
	$source = array_shift($args);

	if ( is_bool($args[0]) ) { $ignore = (bool)array_shift($args); }
	else { $ignore = true; }
	$names = $args;
	if ( is_array($names[0]) ) { $names = $names[0]; }
	foreach($names as $name)
	{
		$va = (string)ixget($source, $name);
		if ( !isset($va) && $ignore == false )
		{
			return false;
		}
		$dest->$name = $va;
	}
	return true;
}
function ixget($source, $name)
{
	if ( is_object($source) )
	{
		return $source->__get($name);
	}
	else
	{
		if ( isset($source[$name]) )
		{
			return $source[$name];
		}
		else
		{
			return NULL;
		}
	}
}
function ixset($source, $name)
{
	if ( is_object($source) )
	{
		return $source->__isset($name);
	}
	else
	{
		return isset($source[$name]);
	}
}
function date3339($timestamp=0)
{
if (!$timestamp)
{
$timestamp = time();
}
$date = date('Y-m-d\TH:i:s', $timestamp);
$matches = array();
if (preg_match('/^([\-+])(\d{2})(\d{2})$/', date('O', $timestamp), $matches))
{
$date .= $matches[1].$matches[2].':'.$matches[3];
}
else
{
$date .= 'Z';
}
return $date;
}

function vars_get()
{
	//debug_print_backtrace();
	$args = func_get_args();
	$count = count($args);
	if ( $count < 2 ) 
	{
		throw new Exception("Not enough arguments - ".$count.".");
		return false;
	}
	$source = (array)array_shift($args);
	if ( !( is_array($source) || is_object($source) ) )
	{
		throw new Exception("Source is not an array or an object.");
		return false;
	}
	if ( is_bool($args[0]) ) { $ignore = $args[0];array_shift($args[0]); }
	if ( is_array($args[0]) ) { $args = $args[0]; }
	else { $ignore = false; }
	$ret = array();
	//var_dump($args);
	foreach($args as $name)
	{
		if ( isset($source[$name]) )
		{
			$ret[$name] = $source[$name];
		}
		elseif ( $ignore == true )
		{
			$ret[$name] = null;
		}
		else
		{
			return false;
		}
	}
	return $ret;
}
