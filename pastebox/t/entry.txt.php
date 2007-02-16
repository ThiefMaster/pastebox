<?php
header("Content-type: text/plain; charset=utf-8");
$entry = $this->out['Entry'];
echo $entry['content'];
?>