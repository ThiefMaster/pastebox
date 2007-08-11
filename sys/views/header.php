<?php if ( !isset($title) ) { $title = 'PasteBox'; }?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<title><?php echo htmlspecialchars($title); ?></title>
<link rel="stylesheet" rev="stylesheet" type="text/css" href="<?php echo config()->url_global.'/style.css'; ?>">
<link rel="alternate" rev="alternate" type="application/rss+xml" href="<?php echo url_generate('index.rss'); ?>">
</head>

<body>
<div id="main">

<h1><?php echo htmlspecialchars($title); ?></h1>
<ul id="nav">

<li><a href="<?php echo url_generate('new'); ?>">New entry</a></li>
<li><a href="<?php echo url_generate('index'); ?>">Latest entries</a></li>
<li><a href="<?php echo url_generate('index.rss'); ?>">Feeds</a></li>
<?php if ( isset($menu_add) ) { echo $menu_add; } ?>
</ul>
<div id="content">
