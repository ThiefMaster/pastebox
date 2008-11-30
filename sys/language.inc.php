<?php
$l['en'] = array(
	'Developed' => '<a href="http://pastebox.sf.net/">PasteBox</a>, developed by Drakas Tralas.',
	'New entry'=>'New entry',
	'Latest entries'=>'Latest entries',
	'Feeds'=>'Feeds',
	'Plain text'=>'Plain text',
	'Subject'=>'Subject',
	'Author'=>'Author',
	'Description'=>'Description',
	'Posted on'=>'Posted on',
	'Name'=>'Name',
	'Type'=>'Type',
	'Content'=>'Content'
);
$l['pt'] = array(
	'Developed' => '<a href="http://pastebox.sf.net/">PasteBox</a>, desenvolvido pela Drakas Tralas.',
	'New entry'=>'Novada entrada',
	'Latest entries'=>'&Uacute;ltimos envios',
	'Feeds'=>'Feeds',
	'Plain text'=>'Texto Plano',
	'Subject'=>'Assunto',
	'Author'=>'Autor',
	'Description'=>'Descrição',
	'Posted on'=>'Enviado por',
	'Content'=>'Conteúdo',
	'Name'=>'Nome',
	'Type'=>'Tipo'
);
$l['de'] = array(
	'Developed' => '<a href="http://pastebox.sf.net/">PasteBox</a> entwickelt von Drakas Tralas.',
	'New entry'=>'Neuer Eintrag',
	'Latest entries'=>'Letzte Eintr&auml;ge',
	'Feeds'=>'Feeds',
	'Plain text'=>'Nur Text',
	'Subject'=>'Titel',
	'Author'=>'Author',
	'Description'=>'Beschreibung',
	'Posted on'=>'Erstellt am',
	'Name'=>'Name',
	'Type'=>'Typ',
	'Content'=>'Inhalt'
);
function _l($text)
{
	static $lang;
	if ( !isset($lang) )
	{
		$lang = $GLOBALS['l'][config()->language];
	}
	if (isset($lang[$text]) )
	{
		return $lang[$text];
	}
	else
	{
		return '<span style="color:red">Undefined: '.$text.'</span>';
	}
}
