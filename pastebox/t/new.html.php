<?php

header("Content-type: text/html; charset=utf-8");

include_once("t/defaultheader.html.php");

$categories['asp']			= "ASP";
$categories['c++']			= "C++";
$categories['css']			= "CSS";
$categories['javascript']	= "JavaScript";
$categories['delphi']		= "Delphi";
$categories['html']			= "HTML";
$categories['pascal']		= "Pascal";
$categories['php']			= "PHP";
$categories['text']			= "Plain Text";
$categories['xml']			= "XML";

$default="php";

if (isset($_COOKIE['clang']) )
{
	if (in_array($_COOKIE['clang'],$categories) )
	{
		$default=$_COOKIE['clang'];
	}
}

?>
<div id="newentry">
<form action="" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<div id="f_name"><label>Name:</label> <input type="text" name="name" value=""></div>
<div id="f_subject"><label>Subject:</label> <input type="text" name="subject" value=""></div>
<div id="f_category"><label>Category:</label> 
<select name="category"><?php echo "\n";
foreach($categories as $id => $name) {
if ( $default == $id ) { $def = " selected"; } else { $def = ""; }
echo "<option value=\"{$id}\" {$def}>{$name}</option>\n";
}
?></select>
</div>
<div id="f_description">
<label>Description:</label> 
<textarea name="description" rows="5" cols="90"></textarea>
</div>
<div id="f_content">
<label>Content:</label> 
<textarea name="content" rows="15" cols="90"></textarea>
</div>
<input type="submit" value="Submit" class="button" name="entry">

</form>
</div>
<?php

include_once("t/defaultfooter.html.php");

?>