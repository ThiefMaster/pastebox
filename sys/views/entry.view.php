<?php

$title = $entry->subject;
$menu_add = '<li><a href="'.url_generate('entry.txt', $entry->id).'">Plain text</a></li>';
require_once("views/header.php");
?>
<dl id="entry">
<dt>Subject</dt>
<dd><?php echo htmlspecialchars($entry->subject); ?></dd>
<dt>Author</dt>
<dd><?php echo htmlspecialchars($entry->name); ?></dd>
<dt>Description</dt>
<dd><?php echo nl2br(htmlspecialchars($entry->description)); ?></dd>
<dt>Posted on</dt>
<dd><?php echo date("r",$entry->date); ?></dd>
<dt>Content</dt>
<dd>
<?php
if (
	ctype_alnum($entry->type) &&
	$entry->type!=="text" &&
	$entry->type!=='plain' &&
	file_exists("geshi/geshi/".$entry->type.".php")
)
{
	include_once('geshi/geshi.php');
	$language = $entry->type;
	$geshi =& new GeSHi($entry->content, $language);
	echo $geshi->parse_code();
}
else
{
	echo htmlspecialchars($entry->content);
}
?>
</dd>
</dl>

<?php
require_once("views/footer.php");

?>
