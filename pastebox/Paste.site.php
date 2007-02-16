<?php
class BaseObject {
	/*
	 * Creates an object using $data variables and verified them using object's $o fields
	 *
	 */
	public static function FromPost(&$o,$data,&$failed='')
	{
		if (!isset($o->fields))
		{
			return false;
		}
		foreach($o->fields as $i => $field)
		{
			if ( isset($field['require']) && !isset($data[$field['name']]) )
			{
				if ( isset($field['default']) )
				{
					$o->$field['name']=$field['default'];
				}
				else
				{
					$failed = $field['name'];
					return false;
				}
			}

			if ( isset($data[$field['name']]) ) 
			{
						
				$v = $data[$field['name']];
				if ( !isset($field['validate']) )
				{
					if ( empty($v) )
					{
						if ( isset($field['default']) )
						{
							$o->$field['name'] = $field['default'];
						}
					}
					else
					{
						$o->$field['name']=$v;
					}
				}
				else 
				{
					switch ($field['validate']) {
						case "int": if ( !ctype_digit($v) ) { echo "Fail Me: $v"; $failed=$field['name']; return false; } break;
						case "anum": if( !ctype_alnum($v) ) { $failed=$field['name']; return false; } break;
					}
					$o->$field['name']=$v;
				}
			}
		}
		return $o;
	}
	/*
	 * Creates itself from list data
	 */
	public static function FromAssoc($data,$class=NULL)
	{
		if ( $class == NULL ) 
		{
			$class=__CLASS__;
		}
		$e = new $class();
		$k = array_keys($data);
		
		$prefix=strtolower($class."_");
		foreach($k as $i => $key)
		{
			$kk = str_replace($prefix,"",$key);
			$e->$kk=$data[$key];
		}
		return $e;
	}
}
/*
 * Basic PasteBin entry
 */
class Entry extends BaseObject {
	public $fields=array(
		array("name"=>"subject","require"=>true,"default"=>"No subject"),
		array("name"=>"description","default"=>"Author was too lazy to write the description"),
		array("name"=>"name","default"=>"Anonymous"),
		array("name"=>"content","require"=>true),
		array("name"=>"category"),
		array("name"=>"space"),
		array("name"=>"date"),
		array("name"=>"ip")
	);
	public static function FromAssoc($data) 
	{
		return parent::FromAssoc($data,__CLASS__);
	}	
	public static function FromPost($data,&$failed='')
	{
		$obj = __CLASS__;
		$o = new $obj();
		return BaseObject::FromPost($o,$data,$failed);
	}
}
class Comment extends BaseObject {
	public $fields=array(
		array("name"=>"name","require"=>true,"defautl"=>"anonymous"),
		array("name"=>"content","require"=>true),
		array("name"=>"entry_id","require"=>true,"validate"=>"int"),
		array("name"=>"date"),
		array("name"=>"space"),
		array("name"=>"ip")
	);
	public static function FromAssoc($data) 
	{
		return parent::FromAssoc($data,__CLASS__);
	}
	public static function FromPost($data,&$failed='')
	{
		$obj = __CLASS__;
		$o = new $obj();
		return BaseObject::FromPost($o,$data,$failed);
	}
}
class DB {
	protected $link = NULL;
	public $error;
	public $sql = array();
	
	/*
	 * MySQL database class
	 * Connects to the server and returns false if failed
	 * Else, returns the new object
	 */
	public static function Connect($url)
	{
		$url=parse_url($url);
		$db = new DB();
		$db->link = mysql_connect($url['host'],$url['user'],$url['pass']);
		if ( $db->link === false )
		{
			unset($db);
			return false;
		}
		else
		{
			$selectResult = mysql_select_db(str_replace("/","",$url['path']),$db->link);
			if ( $selectResult === false )
			{
				$db->Close();
				return false;
			}
			else
			{
				// Enable utf-8
				mysql_query("SET NAMES 'utf8'", $db->link);
				mysql_query("SET CHARACTER SET utf8",$db->link);
				return $db;
			}
		}
	}
	/*
	 * The function routes specific queries
	 */
	function Query($queryData)
	{
		if ( $queryData['type'] == 'get' )
		{
			return $this->QueryGet($queryData);
		}
		elseif ( $queryData['type'] == 'put' )
		{
			return $this->QueryPut($queryData);
		}
		else
		{
			return false;
		}
	}
	/*
	 * MySQL Insert query
	 */
	function QueryPut($queryData)
	{
		$sql="";
		$sql.="INSERT INTO `".$queryData['to']."` ";
		$k = array_keys($queryData['data']);
		$kk = array();
		$vv = array();
		foreach($k as $i => $v)
		{
			if ( is_object($queryData['data'][$v]) )
			{
				$vv[]=$queryData['data'][$v]->value;
			}
			else
			{
				$vv[]="'".mysql_real_escape_string($queryData['data'][$v])."'";
			}
			$kk[]="`".$v."`";
		}
		$sql.=" (".implode(",",$kk).") ";
		$sql.=" VALUES (".implode(",",$vv).");";
		return $this->SqlQuery($sql);
//		print_r($queryData);
		return false;
	}
	/*
	 *	['fields']
	 * ['from']
	 * ['clauses']	->	array of clauses, written in:
	 *						['0'] -	Field
	 *						['1']	-	Operator
	 *						['2']	-	Comparison
	 *					 ORRR
	 *						Operator like AND OR ( ) etc.
	 *	['limit'] -> Either array("from"=>"<from>","limit"=>"<limit>") or "<limit>"
	 */
	function QueryGet($queryData)
	{
		$rv = array(
			"fields"=>array()
		);
		if ( !is_array($queryData['fields']) )
		{
			$rv['fields'][]="*";
		}
		else
		{
			foreach($queryData['fields'] as $i => $field)
			{
				$rv['fields'][]='`'.$field.'`';
			}
		}
		$sql="";
		$sql.="SELECT ".implode(",",$rv['fields'])." ";
		$sql.=" FROM `".$queryData['from']."` ";
		
		$added_where = false;
		
		if ( isset($queryData['clauses']) && count($queryData['clauses']) !== 0 )
		{
			$added_where = true;
			$sql.=" WHERE ";
			foreach($queryData['clauses'] as $i => $clause)
			{
				if ( is_int($clause['0']) )
				{
					$sql .= " AND ";
				}
				else
				{
					if ( $i !== 0 )
					{
						$sql.=" AND ";
					}
					$sql.="`".mysql_real_escape_string($clause['0'])."` ".$clause['1']." '".mysql_real_escape_string($clause['2'])."' ";
				}
			}
		}
		
		if ( isset($queryData['fulltext']) )
		{
			if ( $added_where == false )
			{
				$sql.=" WHERE ";
			}
			else
			{
				$sql.=" AND ";
			}
				$ff = array();
				foreach($queryData['fulltext']['fields'] as $i => $field)
				{
					$ff[]="`".$field."`";
				}
				$f = implode(",",$ff);
				$sql.="MATCH({$f}) ";
				$st = mysql_real_escape_string($queryData['fulltext']['searchquery']);
				$sql.=" AG"."AINST('".$st."'";
				if ( isset($queryData['fulltext']['boolmode']) )
				{
					$sql.=" IN BOOLEAN MODE";
				}
				$sql.=") ";
		}
		if ( isset($queryData['order']) )
		{
			if (isset($queryData['order']['by'],$queryData['order']['what']) )
			{
				$sql.=" ORDER BY `".$queryData['order']['by']."` ".$queryData['order']['what']." ";
			}
			elseif ( is_array($queryData['order']) && count($queryData['order']) !== 0 )
			{
				$sql.=" ORDER BY ";
				foreach($queryData['order'] as $i => $queryItem)
				{
					$sql.=" `".$queryItem['by']."` ".$queryItem['what']." ";
					if ( $i !== count($queryData['order']) -1 )
					{
						$sql.=", ";
					}
				}
			}
		}
		
		if ( isset($queryData['limit']) )
		{
			if ( is_array($queryData['limit']) )
			{
				$sql.="LIMIT ".$queryData['limit']['from'].",".$queryData['limit']['limit'];
			}
			else
			{
				$sql.="LIMIT ".$queryData['limit'];
			}
		}
		$sql.=" ;";
		
		return $this->SqlQuery($sql);
	}
	/*
	 * Queries the database with $sql query
	 * Returns false if query fails as well as set $this->error to the corresponding mysql error
	 */
	function SqlQuery($sql)
	{
		$this->sql[]=$sql;
//		echo $sql;
		$r = mysql_query($sql,$this->link);
		if ( $r === false )
		{
			$this->error = mysql_error($this->link);
			return false;
		}
		else
		{
			return $r;
		}
	}
	/*
	 * Gets the last insert's id
	 */
	public function InsertID()
	{
		return mysql_insert_id($this->link);
	}
	/*
	 * Fetches rows from the result.
	 * Returns false if the result is invalid
	 */
	function FetchRows($result)
	{
		if ( $result === false || !is_resource($result) )
		{
			return false;
		}
		$ret = array();
		while ($row = mysql_fetch_assoc($result))
		{
			$ret[]=$row;
		}
		return $ret;
	}
	/*
	 * Closes the MySQL connection
	 */
	function Close()
	{
		mysql_close($this->link);
	}
}
class Site {
	public $db = NULL;
	public $view = "";
	protected $config = array();
	protected $out = array("menu"=>array());
	
	/*
	 * Uses the configuration from $data
	 */
	function SetConfig($data)
	{
		$this->config = $data;
	}
	/*
	 * Initializes the side, such as Database connection
	 */
	function Init()
	{
		$this->db = DB::Connect($this->config['db']['url']);
		unset($this->config['db']['url']);
		if ( $this->db === false )
		{
			return false;
		}
	}
	/*
	 * Output/control templates
	 */
	function Display()
	{
	//	echo "Display...";

		$this->out['ViewList'] = array();
		$this->out['ViewList'] = $this->config['ViewTypes'];
		$r = array_search($this->out['ViewType'],$this->out['ViewList']);
		
		if ( $r !== false )
		{
			unset($this->out['ViewList'][$r]);
			array_unshift($this->out['ViewList'],$this->out['ViewType']);
			$this->out['ViewList'] = array_values($this->out['ViewList']);
		}
		
		
		$files = array();
		foreach($this->out['ViewList'] as $i => $vname)
		{
//			$files[]=strtolower($this->view.".".$vname.".php");
			$files[]="./t/".strtolower($this->view.".".$vname.".php");
		}
		
		$found = false;
		foreach($files as $i => $file)
		{
			if ( $found === false )
			{
				//$fname = strtolower("t/".$file);
					$fname=$file;
				if ( file_exists($fname) )
				{
					$include=$fname;
					$found = true;
				}
			}
		}
		
		if ( $found === true )
		{
			
			set_include_path(get_include_path() . PATH_SEPARATOR . "./t");
			
			include_once($include);
			return true;
		}
		else {
	//		echo "Not found: ".$file;
			header("Content-type: text/html");
			echo "<pre>";
			print_r($files);
			echo htmlspecialchars(print_r($this,true));
			echo "</pre>";
			echo "<form action=\"\" method=\"post\">
			<input type=\"text\" name=\"subject\" value=\"\">
			<textarea name=\"content\"></textarea>
			<input type=\"submit\" value=\"GoGo\" name=\"entry\">
			</form>
		
		<hr>
		
		<form action=\"\" method=\"post\">
		<input type=\"text\" name=\"name\" value=\"\">
		<input type=\"hidden\" name=\"entry_id\" value=\"5\">
		<textarea name=\"content\"></textarea>
		<input type=\"submit\" value=\"GoGo\" name=\"comment\">
		</form>
		
		";
		}
		
	}
	/*
	 * Changes the view
	 */
	function SetView($view)
	{
		$this->view = $view;
	}
	/*
	 * Assigns a variable to the output
	 */
	function OutAssign($key,$value)
	{
		if ( is_array($value) || is_object($value) )
		{
			$this->out[$key]=$this->ObjToArray($value);
		}
		else
		{
			$this->out[$key]=$value;
		}
	}
	function ObjToArray(&$value)
	{
		if ( is_object($value) )
		{
			$vv = (array)$value;
			return $this->ObjToArray($vv);
		}
		else
		{
			$ret = array();
			foreach($value as $i => $vv)
			{
				if ( is_array($vv) || is_object($vv) )
				{
					$ret[$i]=$this->ObjToArray($vv);
				}
				else
				{
					$ret[$i]=$vv;
				}
			}
			return $ret;
		}
	}
	function GenUrl($id,$data='',$full=false,$surl=NULL)
	{
		if ( $surl === NULL )
		{
			$surl = $this->config['site_url'];
		}
		if ( isset($this->config[$id]) )
		{
			$format="";
			if ( $full === 2 )
			{
				$format.=$this->config['site_url2'];
				$format.=$this->config[$id];
				return sprintf($format,$data);
			}
			elseif ( $full === true ) 
			{
				$format.=$surl;
			}
			else {
				$format.=$this->config['relative_url'];
			}
			$format .= $this->config[$id];
//			echo ":::: ".$format." ::::";
			return sprintf($format,$data);
		}
		else
		{
			return false;
		}
	}
	/*
	 * Closes the database connection(-s)
	 */
	function Close()
	{
		$this->db->Close();
	}
	function GetEntry($queryData)
	{
		$dbQueryData1 = array(
			"type"=>"get",
			"from"=>$this->config['db']['tables']['entries'],
			"clauses"=>array( array("entry_id","=",$queryData['id']), array("entry_space","LIKE",$this->config['space']) ),
			"fields"=>FIELDS_ALL,
			"limit"=>"1"
		);
		$dbQueryData2 = array(
			"type"=>"get",
			"from"=>$this->config['db']['tables']['comments'],
			"clauses"=>array(array("comment_entry_id","=",$queryData['id']), array("comment_space","LIKE",$this->config['space']) ),
			"order"=>array("by"=>"comment_date","what"=>"desc"),
			"fields"=>FIELDS_ALL
		);
		$Entry = $this->db->FetchRows($this->db->Query($dbQueryData1));
		if ( $Entry === false || count($Entry) === 0 )
		{
			return false;
		}
		else
		{
			$Comments = $this->db->FetchRows($this->db->Query($dbQueryData2));
			$c = $this->CommentsFromAssoc($Comments);
			$e = $this->EntryFromAssoc($Entry['0']);
			$e->comments = $c;
			return $e;
		}
	}
	function GetSpaces()
	{
		$sql = "SELECT entry_space as space_name, count( * ) as space_count FROM `{$this->config['db']['tables']['entries']}` GROUP BY entry_space ORDER BY entry_space ASC";
		return $this->db->FetchRows($this->db->SqlQuery($sql));
	}
	function GetEntries($queryData)
	{
		$dbQueryData = array(
			"type"=>"get",
			"from"=>$this->config['db']['tables']['entries'],
			"fields"=>array(
				"entry_id",
				"entry_subject",
				"entry_comments",
				"entry_date",
				"entry_name",
				"entry_description",
				"entry_category"
			),
			"order"=>array("by"=>"entry_date","what"=>"desc"),

			"limit"=>$queryData['count']
		);
		if ( isset($queryData['space']) )
		{
			$dbQueryData['clauses']=array(
				array("entry_space","LIKE",$this->config['space'])
			);
		}
		if ( isset($queryData['fulltext']) )
		{
			$dbQueryData['fulltext']=array(
				"fields"=>array(
					"entry_subject",
					"entry_description",
					"entry_category"
				),
				"boolmode"=>true,
				"searchquery"=>$queryData['fulltext']
			);
		}
		return $this->EntriesFromAssoc( $this->db->FetchRows($this->db->Query($dbQueryData)) );
	}

	function EntriesFromAssoc($data)
	{
		if ( !is_array($data) )
		{
			return false;
		}
		$ret = array();
		foreach($data as $i => $row)
		{
			$ret[]=$this->EntryFromAssoc($row);
		}
		return $ret;
	}
	function EntryFromAssoc($data)
	{
		$c = Entry::FromAssoc($data);
		unset($c->fields);
		return $c;
	}
	function CommentsFromAssoc($data)
	{
		if ( !is_array($data) )
		{
			return false;
		}
		$ret = array();
		foreach($data as $i => $row)
		{
			$ret[]=$this->CommentFromAssoc($row);
		}
		return $ret;
	}
	function CommentFromAssoc($data)
	{
		$c = Comment::FromAssoc($data);
		unset($c->fields);
		return $c;
	}
	function GenericAddEntry(&$e,$table,$prefix)
	{
		$dbQueryData = array(
			"type"=>"put",
			"to"=>$table,
			"data"=>array(
			),
		);
		foreach($e->fields as $i => $field)
		{
			$n = $field['name'];
			if ( isset($e->$n) )
			{
				$dbQueryData['data'][$prefix.$n] = $e->$n;
			}
		}
		$r = $this->db->Query($dbQueryData);
		if ( $r === false )
		{
			return false;
		}
		else
		{
			return $this->db->InsertID();
		}	
	}
	function AddEntry(&$e) {
		$e->date = time() -(int)date('Z');
		return $this->GenericAddEntry($e,$this->config['db']['tables']['entries'],"entry_");
	}
	function AddComment(&$e) {
		$e->date = time() - (int)date('Z');
		$r = $this->GenericAddEntry($e,$this->config['db']['tables']['comments'],"comment_");
		if ( $r === false )
		{
			return false;
		}
		$sql = "UPDATE `".$this->config['db']['tables']['entries']."` SET entry_comments = entry_comments + '1' WHERE entry_id = '".mysql_real_escape_string($e->entry_id)."' LIMIT 1";
		$this->db->SqlQuery($sql);
		return $r;
	}
}
?>