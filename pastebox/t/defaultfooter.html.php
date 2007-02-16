</div>
<div id="menu">
<?php

array_unshift($this->out['menu'],array("text"=>"New entry","link"=>$this->GenUrl("entry.new")));
array_unshift($this->out['menu'],array("text"=>"All","link"=>"/sp"));
array_unshift($this->out['menu'],array("text"=>"Home","link"=>$this->GenUrl("main")));

?><ul><?php foreach($this->out['menu'] as $i => $item) { echo "\n";
?><li><a href="<?php echo $item['link']; ?>"><?php echo $item['text']; ?></a></li><?php }?>
</ul>
</div>
</div>
</body>
</html>
