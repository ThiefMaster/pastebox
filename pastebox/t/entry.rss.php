<?php
header("Content-type: application/xml; charset=utf-8");
$entry = $this->out['Entry'];
echo "<".'?xml version="1.0" encoding="utf-8"?'.">\n";
?><rss version="2.0">
<channel>
	<title>#<?php echo $entry['id']; ?> comments</title>
	<link><?php echo $this->GenUrl('entry.view',$entry['id'],true); ?></link>
	<description>Post #<?php echo $entry['id']; ?>; Subject: <?php echo htmlspecialchars($entry['subject']); ?></description>
	<language>en-gb</language>
	<copyright>No license</copyright>
	<ttl>15</ttl>

<?php foreach($entry['comments'] as $i => $comment) { ?>	<item>
		<title>Message from <?php echo htmlspecialchars($comment['name']); ?></title>
		<description><?php echo htmlspecialchars(nl2br(htmlspecialchars($comment['content']))); ?></description>
		<link><?php echo $this->GenUrl("entry.view",$entry['id'],true); ?></link>
		<guid><?php echo $this->GenUrl("entry.view",$entry['id'],true); ?></guid>
		<pubDate><?php echo date("r",$comment['date']); ?></pubDate>
		<category><?php echo htmlspecialchars($entry['category']); ?></category>
	</item>
<?php } ?>

</channel>
</rss>