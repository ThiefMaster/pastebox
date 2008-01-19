<?php
require_once("definition.class.inc.php");

$defe = new Definition();
$defe->add(array('description'=>'Subject', 'name'=>'subject', 'title'=>_l('Subject'), 'default'=>'', 'control'=>new Definition_Text()));
$defe->add(array('description'=>'Name', 'name'=>'name', 'title'=>_l('Name'), 'default'=>'', 'control'=>new Definition_Text()));
$defe->add(array('description'=>'Type', 'name'=>'type', 'title'=>_l('Type'), 'default'=>'plain', 'control'=>new Definition_Select(
array('plain'=>_l('Plain text'), 'asp'=>'ASP', 'cpp' => 'C++', 'css'=>'CSS', 'javascript'=>'JavaScript', 'delphi'=>'Delphi', 'html'=>'HTML', 'pascal'=>'Pascal', 'php'=>'PHP', 'xml'=>'XML')
)));
$defe->add(array('description'=>'Description', 'name'=>'description', 'title'=>_l('Description'), 'default'=>'', 'control'=>new Definition_Textarea()));
$defe->add(array('description'=>'Content', 'name'=>'content', 'title'=>_l('Content'), 'default'=>'', 'control'=>new Definition_Textarea(array('rows'=>20, 'cols'=>150))));
?>
