<?php
$config = array();
$config['domain'] = 'mydomain.co.uk';
$config['site_url2']		= "http://".$config['domain']."/";

$config['site_url']		= "http://{$config['domain']}/paste.php?title=";
$config['absolute_url']	= "/paste.php?title=";
$config['db']['url']='mysql://user:password@host/database_name/';

$config['relative_url']	= "/";
$config['entry.view']	= "%s";
$config['entry.rss']		= "%s/rss";
$config['entry.txt']		= "%s/txt";
$config['rss']				= "rss";
$config['entry.new']		= "new";
$config['source']			= "source";
$config['main']			= "";
$config['newcomment']	= "";
$config['subdomains']	= true;
$config['space']			= "www";
if ( $config['subdomains'] == true && isset($_SERVER['HTTP_HOST']) )
{
	$sub = str_replace(".".$config['domain'],"",$_SERVER['HTTP_HOST']);
	if ( $sub !== "" && $sub !== "." && $sub !== "www" && ctype_alnum($sub) )
	{
		$config['space']		= $sub;
		$config['site_url']	= "http://".$sub.".".$config['domain'].$config['absolute_url'];
		$config['site_url2']	= "http://".$sub.".".$config['domain']."/";
	}
}

if ( isset($_GET['space']) && ctype_alnum($_GET['space']) )
{
	$config['space']	= $_GET['space'];
}
if ( $config['space'] == "all" )
{
$config['space']="%";
}

$config['db']['tables']['entries']='p_e';
$config['db']['tables']['comments']='p_c';
$config['ViewTypes'] = array(
	"html",
	"rss",
	"xml",
	"txt"
);
?>
