<?php

$title = $entry->subject;
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
<dd><code><pre><?php echo htmlspecialchars($entry->content); ?></pre></code></dd>
</dl>

<?php
require_once("views/footer.php");

?>
