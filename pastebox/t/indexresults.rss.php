<?php
header("Content-type: application/xml; charset=utf-8");
echo "<".'?xml version="1.0" encoding="ISO-8859-1" '."?".">\n";
?>
<rss version="2.0">
<channel>
<title>My pastebin!</title>
	<link><?php echo $this->config['site_url']; ?></link>
	<description>Pastebin</description>
	<language>en-gb</language>
	<lastBuildDate>Unknown!</lastBuildDate>
	<copyright>No license</copyright>
	<ttl>15</ttl>

<?php foreach($this->out['Entries'] as $i => $entry) { ?>	<item>
		<title><?php echo htmlspecialchars($entry['subject']); ?></title>
		<description><?php echo htmlspecialchars(nl2br(htmlspecialchars($entry['description']))); ?></description>
		<link>http://<?php echo $this->config['space'];?>.<?php echo Config::get('domain'); ?>/<?php echo $entry['id']; ?>/</link>
		<pubDate><?php echo date("r",$entry['date']); ?></pubDate>
		<category><?php echo htmlspecialchars($entry['category']); ?></category>
	</item>
<?php } ?>

</channel>
</rss>
