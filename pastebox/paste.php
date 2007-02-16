<?php
if ( !isset($_SERVER['REMOTE_ADDR']) ) 
{
	$_SERVER['REMOTE_ADDR']='127.0.0.1';
}
if ( !isset($_SERVER['QUERY_STRING']) )
{
	$_SERVER['QUERY_STRING']="";
}

ini_set("display_errors","On");
define("FIELDS_ALL",0);

error_reporting(E_ALL);

setlocale(LC_TIME, 'en_GB');
mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

if ( get_magic_quotes_gpc() ) {
	function stripslashes_deep($value) {
		if ( is_array($value) )
		{
			return array_map('stripslashes_deep', $value);
		}
		else
		{
			return (isset($value) ? stripslashes($value) : null);
		}
	}
	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);
	$_COOKIE = stripslashes_deep($_COOKIE);

}
require_once("Paste.config.php");

$_POST['space']		= $config['space'];
$_REQUEST['space']	= $config['space'];

require_once("Paste.site.php");

$Site = new Site();

$Site->SetConfig($config);

$Site->Init();

if ( !isset($_GET['title']) )
{
	$_GET['title']="";
}

$Site->Req = explode("/",$_GET['title']);

foreach($Site->Req as $i => $v)
{
	if ( empty($v) && $i !== 0) 
	{
		unset($Site->Req[$i]);
	}
}
$Site->Req = array_values($Site->Req);

$_POST['ip'] = $_SERVER['REMOTE_ADDR'];

if ( isset($_POST['entry']) )
{
	$f='';
	$Entry = Entry::FromPost($_POST,$f);
	
	if ( isset($_POST['category']) )
	{
		setcookie("clang",$_POST['category'],time()+(3600*24*500));
	}
	
	if ( $Entry === false )
	{
		$Site->SetView("AddFailed");
	}
	else
	{
		$Result = $Site->AddEntry($Entry);
		if ( $Result === false )
		{
			$Site->SetView("AddFailed");
		}
		else
		{
			$Site->OutAssign("EntryID",$Result);
			$Site->SetView("AddRedirect");
		}
	}
}
elseif ( isset($_POST['comment']) )
{
	$f='';
	$Comment = Comment::FromPost($_POST,$f);
	if ( $Comment === false )
	{
		$Site->SetView("AddCFailed");
	}
	else
	{
		$Result = $Site->AddComment($Comment);
		if ( $Result === false )
		{
			$Site->SetView("AddCFailed");
		}
		else
		{
			$Site->OutAssign("EntryID", $_POST['entry_id']);
			$Site->SetView("AddCommentRedirect");
		}
	}
}
elseif ($Site->Req['0'] === "source" ) 
{
	$Site->SetView("Source");
}
elseif ( $Site->Req['0'] === "sp" )
{
	$Site->OutAssign("Spaces",$Site->GetSpaces());
	$Site->SetView("Spaces");
}
elseif ($Site->Req['0'] === "new" ) 
{
	$Site->SetView("New");
}
elseif (eregi('^([0-9]+)$',$Site->Req['0']))
{
	$ID = $Site->Req['0'];
	$Entry = $Site->GetEntry( array("id"=>$ID,"comments"=>true) );
	if ( $Entry !== false ) 
	{
		if ( isset($Site->Req['1'], $Site->Req['2']) )
		{
			$Site->OutAssign("HighlightFrom", $Site->Req['1']);
			$Site->OutAssign("HighlightTo", $Site->Req['2']);
		}
		$Site->OutAssign("Entry",$Entry);
		$Site->SetView("Entry");
	}
	else
	{
	//	print_r($Site);
		$Site->SetView("home");
	}
}
else
{
	if ( isset($_REQUEST['search']) )
	{
		$Entries = $Site->GetEntries( array("fulltext"=>$_REQUEST['search'], "count"=>100,"space"=>$config['space']) );
		$in_search = true;
		$Site->SetView("IndexResults");
	}
	else
	{
		$Entries = $Site->GetEntries( array("count"=>100,"space"=>$config['space']) );
		$Site->SetView("IndexResults");
	}
	if ( $Entries !== false )
	{
		$Site->OutAssign("Entries",$Entries);
	}
	else
	{
		$Site->SetView("home");
	}
}
$l = $Site->Req[count($Site->Req)-1];
if ( in_array($l,$config['ViewTypes']) )
{
	$Site->OutAssign("ViewType",$l);
}
else
{
	$Site->OutAssign("ViewType","html");
}

$Site->Close();
$Site->Display();

?>