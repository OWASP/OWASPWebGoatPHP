<?php
/**
 * Converts PHP code to javascript code
 */
class jPhpjs {
	static function Convert($code)
	{
		$t=new jPhpjs($code);
		return $t->GetJS();
	}
	
	/** @var array holds tokens of the php code being converted */
	private $_tokens;
	/** @var int number of tokens */
	private $count;
	/** @var int the current token */
	private $current = 0;
	/** @var javascript gets collected here */
	private $js;

	/** @var array these token keys will be converted to their values */
	private $_convert = array (
			'T_IS_EQUAL'=>'==',
			'T_IS_GREATER_OR_EQUAL'=>'>=',
			'T_IS_SMALLER_OR_EQUAL'=>'<=',
			'T_IS_IDENTICAL'=>'===',
			'T_IS_NOT_EQUAL'=>'!=',
			'T_IS_NOT_IDENTICAL'=>'!==',
			'T_IS_SMALLER_OR_EQUA'=>'<=',
			'T_BOOLEAN_AND'=>'&&',
			'T_BOOLEAN_OR'=>'||',
			'T_CONCAT_EQUAL'=>'+= ',
			'T_DIV_EQUAL'=>'/=',
			'T_DOUBLE_COLON'=>'.',
			'T_INC'=>'++',
			'T_MINUS_EQUAL'=>'-=',
			'T_MOD_EQUAL'=>'%=',
			'T_MUL_EQUAL'=>'*=',
			'T_OBJECT_OPERATOR'=>'.',
			'T_OR_EQUAL'=>'|=',
			'T_PLUS_EQUAL'=>'+=',
			'T_SL'=>'<<',
			'T_SL_EQUAL'=>'<<=',
			'T_SR'=>'>>',
			'T_SR_EQUAL'=>'>>=',
			'T_START_HEREDOC'=>'<<<',
			'T_XOR_EQUAL'=>'^=',
			'T_NEW'=>'new',
			'T_ELSE'=>'else',
			'.'=>'+',
			'T_IF'=>'if',
			'T_RETURN'=>'return',
			'T_AS'=>'in',
			'T_WHILE'=>'while',
			'T_LOGICAL_AND' => 'AND',
			'T_LOGICAL_OR' => 'OR',
			'T_LOGICAL_XOR' => 'XOR',
			'T_EVAL' => 'eval',
			'T_ELSEIF' => 'else if',
			'T_BREAK' => 'break',
			'T_DOUBLE_ARROW' => ':',
	);

	/** @var array these tokens stays the same */
	private $_keep = array(
			'=', ',', '}', '{', ';', '(', ')', '*', '/', '+', '-', '>', '<', '[', ']',
	);

	/** @var array these tokens keeps their value */
	private $_keepValue = array (
			'T_CONSTANT_ENCAPSED_STRING', 'T_STRING', 'T_COMMENT', 'T_ML_COMMENT', 'T_DOC_COMMENT', 'T_LNUMBER',
			'T_WHITESPACE',
	);

	/**
	 * constructor, runs the show
	 *
	 * @param string $code PHP code
	*/
	public function __construct ($code) {
		$code='<'.'?php'."\n".$code;
		$this->_tokens = $this->getTokens($code);
		$this->count = count($this->_tokens)-1;
		$this->compileJs();

	}

	/**
	 * gets tokens from code. Remove the meta PHP2js stuff.
	 *
	 * @param string $code 
	 * @return array
	 */
	private function getTokens($code) {
		$this->src = $code;
		return token_get_all($code);
	}

	/**
	 * loops through tokens and convert to js
	 *
	 */
	private function compileJs() {
		foreach ($this->_tokens as $_) {
			$this->next ($name, $value);
			$this->parseToken($name, $value, $this->js);
		}
	}


	/**
	 * changed referenced args to name and value of next token
	 *
	 * @param string $name
	 * @param string $value
	 * @param unknown_type $i, the amount of nexts to skip
	 */
	private function next(& $name, & $value, $i=1) {
		for ($j=0; $j<$i; $j++) {
			$this->current++;
			if ($this->current > $this->count) return;
			$_token = $this->_tokens[$this->current];
			$this->getToken ($name, $value, $_token);
		}
	}

	/**
	 * find and return first name matching argument
	 *
	 * @param mixed $_tokenNames
	 * @return string
	 */
	private function findFirst ($_needles) {
		$name = $value = '';
		for ($i=$this->current+1; $i<$this->count; $i++) {
			$this->getToken($name, $value, $this->_tokens[$i]);
			if (in_array($name, (array)$_needles)) {
				return $name;
			}
		}
	}

	/**
	 * return javascript until match, match not included
	 *
	 * @param array $_needles
	 * @return string
	 */
	private function parseUntil ($_needles, $_schema=array(), $includeMatch = false) {
		$name = $value = $js = $tmp = '';
		while (true) {
			$this->next ($name, $value);
			$this->parseToken($name, $value, $tmp, $_schema);
			if (in_array($name, (array)$_needles)) {
				if ($includeMatch === true) {
					return $tmp;
				} else {
					return $js;
				}
			}
			$js = $tmp;
		}
	}

	/**
	 * tries to find the token in $this->_convert, $this->_keep and $this->_keepValue
	 * if it fails it tries to find a method named as the token. If fails here also it throws away the token.
	 *
	 * @param string $name
	 * @param string $value
	 * @param string $js store js here by reference
	 */
	private function parseToken ($name, $value, & $js, $_schema=array()) {
		//custom changes
		if (in_array($name, array_keys ((array)$_schema))) {
			$js .= $_schema[$name];
			//change name to other value
		} else if (in_array($name, array_keys ($this->_convert))) {
			$js .= (!empty($this->_convert[$name])) ? $this->_convert[$name]: $name;
			//keep key
		} elseif (in_array($name, $this->_keep)) {
			$js .= $name;
			//keep value
		} elseif (in_array($name, $this->_keepValue)) {
			$js .= $value;
			//call method
		} else {
			if (method_exists($this, $name)) {
				$js .= $this->$name($value);
			}
		}
		//ignore
	}

	/**
	 * converters
	 *
	 * These guys are equivalents to tokens.
	 */

	/**
	 * class definition
	 *
	 * @param sting $value
	 * @return string
	 */
	private function T_CLASS($value) {
		$this->next ($name, $value, 2);
		return "function $value() ";
	}

	/**
	 * define function
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_FUNCTION($value) {
		$this->next ($name, $value, 2);
		return "this.$value = function";
	}

	/**
	 * echo is replaced with document.write
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_ECHO($value) {
		return 'document.write('.$this->parseUntil(';').');';
	}

	/**
	 * array. Supports both single and associative
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_ARRAY($value) {
		$_convert = array('('=>'{',	')'=>'}',);
		$js = $this->parseUntil(array(';'), $_convert, true);
		if (strpos($js, ':') === false) {
			$this->tmp = -1;
			$js = preg_replace_callback ('/([{, \t\n])(\'.*\')(|.*:(.*))([,} \t\n])/Uis', array($this, 'cb_T_ARRAY'), $js);
		}
		return $js;
	}

	private function cb_T_ARRAY($_matches) {
		$this->tmp++;
		if (strpos($_matches[0], ':') === false) {
			return ($_matches[1].$this->tmp.':'.$_matches[2].$_matches[3].$_matches[4].$_matches[5]);
		} else {
			return $_matches[0];
		}
	}
	/**
	 * foreach. Gets converted to for (var blah in blih). Supports as $key=>$value
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_FOREACH($value) {
		$_vars = array();
		while (true) {
			$this->next ($name, $value);
			if ($name == 'T_VARIABLE') $_vars[] = $this->cVar($value);
			$this->parseToken($name, $value, $js);
			if ($name == '{') {
				if (count($_vars) == 2) {
					$array = $_vars[0];
					$val = $_vars[1];
					$this->js .=
					"for (var {$val}Val in $array) {".
					"\n                        $val = $array"."[{$val}Val];";
				}
				if (count($_vars) == 3) {
					$array = $_vars[0];
					$key = $_vars[1];
					$val = $_vars[2];
					$this->js .=
					"for (var $key in $array) {".
					"\n                        $val = $array"."[$key];";
				}
				return '';
			}
			$jsTmp = $js;
		}
	}

	/**
	 * declare a public class var
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_PUBLIC ($value) {
		$type = $this->findFirst(array('T_VARIABLE', 'T_FUNCTION'));
		if ($type == 'T_FUNCTION') return '';
		$js = '';
		while (true) {
			$this->next ($name, $value);
			$this->parseToken($name, $value, $js);
			if ($name == ';') {
				$js = str_replace(array(' '), '', $js);
				return 'this.'.$js;
			} else if ($name == '=') {
				$js = str_replace(array(' ','='), '', $js);
				return 'this.'.$js.' =';
			}
		}
	}

	/**
	 * variable. Remove the $
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_VARIABLE($value) {
		return str_replace('$', '', $value);
	}

	/* helpers */

	private function getToken(& $name, & $value, $_token) {
		if (is_array($_token)) {
			$name = trim(token_name($_token[0]));
			$value = $_token[1];
		} else {
			$name = trim($_token);
			$value = '';
		}
	}

	private function cVar($var) {
		return str_replace('$', '', $var);
	}

	/** debugging stuff. Ugly and deprecated. */

	/** deprecated and sucks */
	private $_openTags = array(
			'T_OPEN_TAG', 'T_CLASS', 'T_PUBLIC', 'T_FOREACH', 'T_ARRAY', '{', 'T_VARIABLE', '('
	);

	/** deprecated and sucks */

	/** deprecated and sucks */
	private $indent = 0;
	/** deprecated and sucks */
	private $debug;


	private $_closeTags = array(
			'}', 'T_CLOSE_TAG', ';', ')',
	);

	public function __destruct() {
		/**
		 $js = htmlentities ($this->js);
		 echo ("<pre>$js</pre>");
		 $this->write();
		 echo $this->debug;
		 //*/
	}
	public function GetJS()
	{
		return $this->js;
	}


	private function write() {
		$_tokens = token_get_all($this->src);
		foreach ($_tokens as $key=>$_token) {
			if (is_array($_token)) {
				$name = trim(token_name($_token[0]));
				$value = $_token[1];
			} else {
				$name = trim($_token);
				$value = '';
			}
			$this->printToken($name, $value, $_token);
		}
	}

	private function printToken ($name, $value, $_token) {
		$value = htmlentities($value);

		if (in_array($name, $this->_closeTags)) $this->indent--;
		$indent = str_repeat('.&nbsp;&nbsp;&nbsp;&nbsp;', $this->indent);
		if (in_array($name, $this->_openTags)) $this->indent++;
		if (!empty($value))
			$this->debug .= "
			<br />$indent
			<b>$name&nbsp;&nbsp;=&nbsp;&nbsp;'$value'</b>

			";
		else
		$this->debug .= "
		<br />$indent
		<b>$name</b>

		";
	}
	static 	/**
	 * Converts a closure to PHP code and returns.
	 * @param Closure $closure
	 */
	protected function Closure2Code($closure)
	{
		$reflection = new ReflectionFunction($closure);
		$tmp = $reflection->getParameters();
		$args = array();
		foreach ($tmp as $a) array_push($args, '$'.$a->getName() . ($a->isDefaultValueAvailable() ? '=\''.$a->getDefaultValue().'\'' : ''));
		$file = new SplFileObject($reflection->getFileName());
		$file->seek($reflection->getStartLine()-1);
		$code = '';
		while ($file->key() < $reflection->getEndLine())
		{
			$code .= $file->current();
			$file->next();
		}
		$start = strpos($code, '{')+1;
		$end = strrpos($code, '}');
		return "function (".implode(', ', $args)."){". substr($code, $start, $end - $start)."}" ;
	}		
}
?>