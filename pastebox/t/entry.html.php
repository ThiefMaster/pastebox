<?php

header("Content-type: text/html; charset=utf-8");
include_once("defaultheader.html.php");

//echo "<pre>";print_r($this);echo "</pre>";

	function qll($t) {
		error_reporting(E_ALL);
		$t = preg_replace(
			"/(?<![\\/\\d\\w])(http:\\/\\/)([\\w\\d\\-]+)((\\.([\\w\\d\\-])+){2,})([\\/\\?\\w\\d\\.\\-_&=+%]*)?/i",
			'<a href="\\0" rel="nofollow">\\0</a>',$t);
			
		return $t;
	}

$entry = $this->out['Entry'];
array_push($this->out['menu'],array("text"=>"Comment","link"=>"#commentform"));
array_push($this->out['menu'],array("text"=>"Comments","link"=>"#comments"));
array_push($this->out['menu'],array("text"=>"Comment RSS","link"=>$this->GenUrl("entry.view",$entry['id'],2)."/rss/"));
array_push($this->out['menu'],array("text"=>"Download","link"=>$this->GenUrl("entry.view",$entry['id'],2)."/txt/"));
?>
<div id="entry">

<h1><a href="<?php echo $this->GenUrl("entry.view",$entry['id']); ?>"><?php echo htmlspecialchars($this->out['Entry']['subject']); ?></a></h1>

<div id="entry_description">
<h2>Description</h2>
<p><?php echo nl2br(qll(htmlspecialchars($this->out['Entry']['description']))); ?>

<!--<span style="display:block;"><a href="#comments">Comments (<?php echo count($entry['comments']);?>)</a></span>--></p>
</div>
<div id="c">
<div id="entry_content">

<?php

if ( ctype_alnum($entry['category']) && $entry['category']!=="text" &&file_exists("geshi/geshi/".$entry['category'].".php") )
{
	include_once('geshi/geshi.php');
	$language = $entry['category'];
	$geshi =& new GeSHi($entry['content'], $language);
//	echo str_replace("<pre>","<code>",str_replace("</pre>","</code>",$geshi->parse_code()));
	echo $geshi->parse_code();
}
else
{
	echo "<pre>".qll(htmlspecialchars($entry['content']))."</pre>";
}



$d = $this->out['Entry']['content'];
$dd = explode("\n",$d);
if ( isset($this->out['HighlightFrom']) )
{
//	for ($ix = $this->out['HighlightFrom']; $ix <= $this->out['HighlightTo'];$ix++)
//	{
//		$cline=$ix+1;
//		$dd[$cline]="<div class=\"hl\">".$dd[$cline]."</div>";
//	}
}
$count = count(explode("\n",$this->out['Entry']['content']));
//echo implode("\n",$dd);
?>

<div id="lines">
<?php $r = range(0,$count-1); 
foreach($r as $xi => $i){?>
<div><a name="<?php echo $i+1; ?>" href="<?php
//echo $this->GenUrl("entry.view",$entry['id']);
?>#<?php echo $i+1; ?>"><?php echo $i+1; ?></a></div>
<?php }?>
</div>
</div>
</div>

<div id="entry_info">

<address>
 Posted by
  <span class="name"><?php echo htmlspecialchars($entry['name']); ?></span>
  <span class="date"><?php echo date("r",$entry['date']); ?></span>
  <span class="category"><?php echo htmlspecialchars($entry['category']); ?></span>
</address>

</div>

</div>


<div id="comments">
<h1><a href="<?php echo $this->GenUrl("entry.view",$entry['id']);?>#comments">Comments</a></h1>
<?php foreach($this->out['Entry']['comments'] as $i => $comment) { ?>
<div class="comment_item">
<p><?php echo nl2br(qll(htmlspecialchars($comment['content'])));?></p>
<address>Posted by
 <span class="name"><?php echo htmlspecialchars($comment['name']);?></span>
 on <span class="date"><?php echo date("H:i:s l, jS M Y",$comment['date'] + (int)date('Z')); ?></span>
 <span class="clink"><a name="co_<?php echo $comment['id']; ?>" href="#co_<?php echo $comment['id']; ?>"># <?php echo $comment['id']; ?></a></span>
</address>
</div>
<?php }?>

<div id="commentform">
<form action="<?php echo $this->GenUrl("newcomment"); ?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<h2><a href="#comment">Comment!</a></h2>
<input type="hidden" name="entry_id" value="<?php echo $this->out['Entry']['id']; ?>">
<div id="c_name"><label>Name:</label> <input type="text" name="name" value="Anonymous"></div>
<div id="c_content"><label>Message:</label> <textarea name="content" rows="15" cols="60"></textarea></div>
<div><input type="submit" class="button" name="comment" value="Submit"></div>
</form>

</div>

</div><?php

include_once("defaultfooter.html.php");

?>