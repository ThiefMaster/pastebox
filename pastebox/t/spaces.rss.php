<?php
header("Content-type: application/xml; charset=utf-8");
echo "<".'?xml version="1.0" encoding="ISO-8859-1" '."?".">\n";
?>
<rss version="2.0">
<channel>
	<title>My pastebin!</title>
	<description>Pastebin</description>
	<language>en-gb</language>
	<lastBuildDate>Unknown!</lastBuildDate>
	<copyright>No license</copyright>
	<ttl>15</ttl>
<?php foreach($this->out['Spaces'] as $i => $space) { ?>	<item>
		<title>Space <?php echo htmlspecialchars($space['space_name']); ?> updated</title>
		<description>Space <?php echo htmlspecialchars($space['space_name']); ?> updated Updated and <?php echo $space['space_count']; ?> items exist</description>
		<link>http://<?php echo $space['space_name'];?>.<?php echo Config::get('domain'); ?>/</link>
	</item>
<?php } ?>

</channel>
</rss>
