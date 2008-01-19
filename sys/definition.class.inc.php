<?php
class definition_text
{
	public $__value = NULL;
	public $__name = NULL;
	function setvalue($v)
	{
		$this->__value = (string)$v;
	}
	function render($me)
	{
		if ( $this->__name == NULL ) { $this->__name = $me->name; }
		if ( $this->__value == NULL ) { $this->__value = $me->default; }
		$ret = '<input type="text" name="'.htmlspecialchars($this->__name).'" value="'.htmlspecialchars($this->__value).'"';
		if ( isset($this->__class) ) { $ret .=' class="'.htmlspecialchars($this->__class).'"'; }
		if ( isset($this->__id) ) { $ret .=' id="'.htmlspecialchars($this->__id).'"'; }
		$ret .= '>';
		return $ret;
	}
	function type() { return 'text'; }
}
class definition_textarea
{
	public $__value = NULL;
	public $__name = NULL;
	function __construct($options = array())
	{
		foreach($options as $k => $v)
		{
			$this->{'__'.$k} = $v;
		}
	}
	function setvalue($v)
	{
		$this->__value = (string)$v;
	}
	function render($me)
	{
		if ( $this->__name == NULL ) { $this->__name = $me->name; }
		if ( $this->__value == NULL ) { $this->__value = $me->default; }
		$ret = '<textarea name="'.htmlspecialchars($this->__name).'"';
		if ( isset($this->__class) ) { $ret .=' class="'.htmlspecialchars($this->__class).'"'; }
		if ( isset($this->__id) ) { $ret .=' id="'.htmlspecialchars($this->__id).'"'; }
		if ( isset($this->__rows) ) { $ret .=' rows="'.(int)$this->__rows.'"'; }
		if ( isset($this->__cols) ) { $ret .=' cols="'.(int)$this->__cols.'"'; }
		$ret .= '>';
		$ret .= htmlspecialchars($this->__value);
		$ret .='</textarea>';
		return $ret;
	}
	function type() { return 'text'; }
}
class definition_select
{
	public $__value = NULL;
	public $__name = NULL;
	public $items = array();
	function type() { return 'select'; }
	function __construct($data=array())
	{
		$this->items = $data;
	}
	function setvalue($v)
	{
		$this->__value = (string)$v;
	}
	function render($me)
	{
		if ( $this->__name == NULL ) { $this->__name = $me->name; }
		if ( $this->__value == NULL ) { $this->__value = $me->default; }
		$ret = '<select';
		if ( isset($this->__class) ) { $ret .=' class="'.htmlspecialchars($this->__class).'"'; }
		if ( isset($this->__id) ) { $ret .=' id="'.htmlspecialchars($this->__id).'"'; }
		$ret .= ' name="'.htmlspecialchars($this->__name).'"';
		$ret .= '>';
		$ret .= "\n";
		foreach($this->items as $value => $text)
		{
			$ret .= '<option value="'.htmlspecialchars($value).'"';
			if ( $value == $this->__value)
			{
				$ret .=' selected';
			}
			$ret .= '>';
			$ret .= htmlspecialchars($text);
			$ret .= '</option>';
			$ret .= "\n";
		}
		$ret .= '</select>';
		return $ret;
	}
}
class definition
{
	public $__items = array();
	function clear_defaults()
	{
		foreach($this->__items as $n => $item)
		{
			$item->default = NULL;
		}
	}
	function add($desc)
	{
		$def = new stdClass();
		if ( !isset($desc['name'], $desc['control']) )
		{
			throw new Exception("Name or control were not set");
			return false;
		}
		$def->name = $desc['name'];
		$def->control = $desc['control'];
		if ( isset($desc['default']) )
		{
			$def->default = $desc['default'];
		}
		if ( isset($desc['title']) )
		{
			$def->title = $desc['title'];
		}
		if ( isset($desc['description']) )
		{
			$def->description = $desc['description'];
		}
		$this->__items[$def->name] = $def;
		return $def;
	}
	function get($name)
	{
		//var_dump($this->__items);
		//echo "Search for ".$name;
		if ( isset($this->__items[$name]) )
		{
			$r =  $this->__items[$name];
			return $r;
		}
		else
		{
			return null;
		}
	}
	function form_render()
	{
		$ret = '';
		foreach($this->__items as $item)
		{
			$item->control->__id = 'c_'.$item->name;
			$me = '<dt';
			$me .= ' id="'.htmlspecialchars('i_'.$item->control->__id).'"';
			$me .= ' class="i_'.htmlspecialchars($item->control->type()).'"';
			if ( isset($item->description) )
			{
				$me .= ' title="'.htmlspecialchars($item->description).'"';
			}
			$me .= '><label for="'.htmlspecialchars($item->control->__id).'">'.htmlspecialchars($item->title).'</label></dt>';
			$me .= "\n";
			$me .= '<dd>';
			$me .= $item->control->render($item);
			$me .= '</dd>';
			
			$me .= "\n";
			$ret .= $me;
		}
		return $ret;
	}
	public function copy()
	{
	    $serialized_contents = serialize($this);
	    return unserialize($serialized_contents);
	}
}
