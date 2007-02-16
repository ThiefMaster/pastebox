<?php
header("Location: ".$this->GenUrl("entry.view",$this->out['EntryID']));
echo $this->out['EntryID'];
?>