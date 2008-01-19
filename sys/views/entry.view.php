<?php

$title = $entry->subject;
$menu_add = '<li><a href="'.url_generate('entry.txt', $entry->id).'">'._l('Plain text').'</a></li>';
require_once("views/header.php");
?>
<dl id="entry">
<dt><?php echo _l("Subject"); ?></dt>
<dd><?php echo htmlspecialchars($entry->subject); ?></dd>
<dt><?php echo _l("Author"); ?></dt>
<dd><?php echo htmlspecialchars($entry->name); ?></dd>
<dt><?php echo _l("Description"); ?></dt>
<dd><?php echo nl2br(htmlspecialchars($entry->description)); ?></dd>
<dt><?php echo _l("Posted on"); ?></dt>
<dd><?php echo date("r",$entry->date); ?></dd>
<dt><?php echo _l("Content"); ?></dt>
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
	$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
	echo $geshi->parse_code();
}
else
{
	echo '<pre><code>'.htmlspecialchars($entry->content).'</code></pre>';
}
?>
</dd>
</dl>

<?php
require_once("views/footer.php");

?>
