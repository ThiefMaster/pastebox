<?php

header("Content-type: application/rss+xml");

echo '<'.'?xml version="1.0"?'.">\n"; ?>
<rss version="2.0">
<channel>
<title>PasteBox</title>
<link><?php echo url_generate(array('index', true)) ; ?></link>
<?php
$ee = entry::main();

while ($entry = $ee->fetch($entries))
{
?>
<item>
<title><?php echo htmlspecialchars($entry->subject); ?></title>
<link><?php echo url_generate(array('entry.view', true), $entry->id); ?></link>
<author><?php echo htmlspecialchars($entry->name); ?></author>
<description><?php echo htmlspecialchars(nl2br(htmlspecialchars($entry->description))); ?></description>
</item>
<?php
}
?>
</channel>
</rss>
