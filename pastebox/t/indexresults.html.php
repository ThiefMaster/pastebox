<?php

header("Content-type: text/html");

include_once("t/defaultheader.html.php");

function truncate($string, $length = 80, $etc = '...',$break_words = false, $middle = false)
{
    if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= strlen($etc);
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length).$etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}


global $in_search;
if ( isset($in_search) ) 
{
?>
<form action="paste.php" method="get" enctype="multipart/form-data">
<input type="text" name="search" value="<?php if ( isset($_REQUEST['search']) ) { echo $_REQUEST['search']; } ?>">
<input type="submit" value="Search" class="button">
</form>
<?php
}
?>
<div id="search_list">
<?php

foreach($this->out['Entries'] as $i => $entry) {
?>
<div class="search_item">
<h2><a href="<?php echo $this->GenUrl("entry.view",$entry['id']); ?>"><?php
if ( !empty($entry['subject']) )
{
	echo $entry['subject'];
}
else
{
	echo "No subject";
}
?></a></h2>
<p><?php echo nl2br(htmlspecialchars(truncate($entry['description'],200)));?></p>
<address>
 Posted by
  <span class="name"><?php echo htmlspecialchars($entry['name']); ?></span>
  <span class="date"><?php echo date("r",$entry['date']); ?></span>
  <span class="comments"><a href="<?php echo $this->GenUrl("entry.view",$entry['id']); ?>"><?php echo $entry['comments']; ?> comments</a></span>
  <span class="category"><?php echo htmlspecialchars($entry['category']); ?></span>
</address>
</div>
<?php
}

echo "</div>";

array_unshift($this->out['menu'],array("text"=>"RSS Feeds","link"=>$this->GenUrl("rss")));

include_once("t/defaultfooter.html.php");

?>