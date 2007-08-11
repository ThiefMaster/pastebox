<?php
$title = 'New paste entry';
require_once("views/header.php");

?>
<form action="" method="post" enctype="multipart/form-data">
<dl>
<?php
echo $defe->form_render();
?>
<dd><input type="submit" value="Submit"></dd>
</dl>
</form>
<?php
require_once("views/footer.php");
?>
