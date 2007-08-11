<?php

$title = 'PasteBox';
require_once("views/header.php");
$ee = entry::main();
$i = 0;
?>
<dl id="entries">


<?php
while ($entry = $ee->fetch($entries))
{
?>
<dt><a href="<?php echo url_generate('entry.view', $entry->id); ?>"><?php echo htmlspecialchars($entry->subject); ?></a></dt>
<dd class="desc"><?php echo nl2br(htmlspecialchars($entry->description)); ?></dd>
<dd class="ii"><?php echo date("r", $entry->date); ?> by <?php echo htmlspecialchars($entry->name); ?></dd>
<?php
}
?>
</dl>
<?php
require_once("views/footer.php");
?>
