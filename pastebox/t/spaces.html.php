<?php

header("Content-type: text/html");

include_once("t/defaultheader.html.php");

?>
<ol style="margin-left:70px;">
<?php

foreach($this->out['Spaces'] as $i => $space) {
?>
<li><a href="http://<?php echo $space['space_name']; ?>.<?php echo Config::get('domain'); ?>"><?php echo $space['space_name']; ?></a> (<?php echo $space['space_count']; ?>)</li>
<?php
}

echo "</ol>";

array_unshift($this->out['menu'],array("text"=>"RSS Feeds","link"=>"/sp/rss/"));

include_once("t/defaultfooter.html.php");

?>
