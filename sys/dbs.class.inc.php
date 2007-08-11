<?php
class entry extends dbobject
{
function main() { static $e; if ( !isset($e) ) { $e = new entry(null, 'entry'); } return $e; }
function getlatest($count)
{
	try
	{
		$sql = 'SELECT subject,name,date,description,id FROM '.$this->__table.' ORDER BY date DESC LIMIT '.(int)$count;
		$stmt = db_get()->prepare($sql);
		$stmt->execute();
		return $stmt;
	}
	catch (Exception $e)
	{
		throw $e;
		return false;
	}
}
}
?>
