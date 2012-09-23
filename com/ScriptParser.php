<?php
namespace com;


// Method for checking if weekend.
function isWeekend() {
	return (date('N') >= 6);
}

function clockIs($hour){
	return date('H') == $hour;
}



/**
 * A custom script parser, we have implemented a better way to script behavior.
 *
 * // Sample of short scripting devices.
 * // This turns off a1 and dims b4 to 50 when midnight is reached.
 * if (date('H') == 00){
 * 	[a1].off() [b4].dim(50)
 * }
 *
 *
 *
 *
 * @author Petter Kjelkenes <kjelkenes@gmail.com>
 *
 */
class ScriptParser{

	private $raw;
	private $devices;

	public function __construct($script, $devices){
		$this->raw = $script;
		$this->devices = $devices;
	}



	public function run(\X10 $x10, array $config, $reks, \reks\Log $log){
		$script = $this->raw;
		$check = $this->syntaxCheck($script, $x10, $config, $reks, $log);
		if ($check==false){
			eval('namespace com; ' . $script);
		}else{
			echo "Error IN SCRIPT:";
			print_r($check);
			$log->error('There is a error in script ... got messages ' . var_dump($check, true));
		}
	}


	/**
	 * Check the syntax of some PHP code.
	 * @param string $code PHP code to check.
	 * @return boolean|array If false, then check was successful, otherwise an array(message,line) of errors is returned.
	 */
	public function syntaxCheck($code, $x10, $config, $reks, $log){
		error_reporting(E_ALL);
		$braces=0;
		$inString=0;
		foreach (token_get_all('<?php ' . $code) as $token) {
			if (is_array($token)) {
				switch ($token[0]) {
					case T_CURLY_OPEN:
					case T_DOLLAR_OPEN_CURLY_BRACES:
					case T_START_HEREDOC: ++$inString; break;
					case T_END_HEREDOC:   --$inString; break;
				}
			} else if ($inString & 1) {
				switch ($token) {
					case '`': case '\'':
					case '"': --$inString; break;
				}
			} else {
				switch ($token) {
					case '`': case '\'':
					case '"': ++$inString; break;
					case '{': ++$braces; break;
					case '}':
						if ($inString) {
							--$inString;
						} else {
							--$braces;
							if ($braces < 0) break 2;
						}
						break;
				}
			}
		}
		$inString = @ini_set('log_errors', false);
		$token = @ini_set('display_errors', true);
		ob_start();
		$braces || $code = "if(0){{$code}\n}";
		if (eval($code) === false) {
			if ($braces) {
				$braces = PHP_INT_MAX;
			} else {
				false !== strpos($code,CR) && $code = strtr(str_replace(CRLF,LF,$code),CR,LF);
				$braces = substr_count($code,LF);
			}
			$code = ob_get_clean();
			$code = strip_tags($code);
			if (@preg_match("'syntax error, (.+) in .+ on line \d+)$'s", $code, $code)) {
				$code[2] = (int) $code[2];
				$code = $code[2] <= $braces
				? array($code[1], $code[2])
				: array('unexpected $end' . substr($code[1], 14), $braces);
			} else $code = array('syntax error', 0);
		} else {
			ob_end_clean();
			$code = false;
		}
		@ini_set('display_errors', $token);
		@ini_set('log_errors', $inString);
		return $code;
	}

}



class ScriptParseError extends \Exception{

}