<?php
require_once "Tokens.php";
require_once "Str.php";
class TokenizerNormal
{
	private $tokens;
	public function __construct()
	{
		$this->tokens = [];
	}

	public function tokenize($str)
	{
		if ($str == '') return [];
		
		$first = $str[0];
		$rest  = $this->restOfString($str);
		$tokenValue = null;
		
		while ($rest != '' || $first != '') {
			
			if (ctype_alpha( $first)) {
				list($tokenValue, $rest) = $this->name($first, $rest );
			} else if ($first == "(") {
				list($tokenValue, $rest) = $this->LP($first, $rest);
			} else if ($first == ")") {
				list($tokenValue, $rest) = $this->RP($first, $rest);
			} else if ($first == "\\") { 
				list($tokenValue, $rest) = $this->slash($first, $rest);
			} else if ($first == '.') {
				list($tokenValue, $rest) = $this->dot($first,$rest);
			} else if (ctype_space($first)) {
				list($tokenValue, $rest)= $this->space($first,$rest);
			} else if ($first == '"' || $first == "'") {
				
		        list($tokenValue, $rest)= $this->str($first,$rest);
			} else {
			  	list($tokenValue, $rest)= $this->donotknow($first,$rest);
				$rest = '';
			}
			
			$first = '';
			if (isset($rest[0])) {
				$first = $rest[0];
				$rest = $this->restOfString($rest);
			}
			
			$this->tokens[] = $tokenValue;
		}
		
		return $this->tokens;
	}
	
	public function restOfString($str)
	{
		if (isset($str[1]) ) return substr($str, 1);
		
		return "";
	}
	
	protected function str($first, $rest)
	{
		
		$pred = function ($c) use ($first) {
			if ($c == $first) return false;
			else return true;
		};
		
		list($tokenValue, $str) = $this->span($pred, $rest);
		
		if (!isset( $str[0]) ) {
			throw new \Exception($first.$rest . " is not a valid string.");
		} else {
			$c = $str[0];
			if ($c != $first) {
			    throw new \Exception($first.$rest . " end with $c instead of a (\"|').");
			}
		}
		$t = new StringToken("STRING", $tokenValue);
		return [$t, $this->restOfString($str) ];
	}
	
	protected function donotknow($first, $str)
	{
		$token = new QuoteToken("DONOTKNOW", $first.$rest);
		return [$token, $rest];
	}
	
	protected function quote($first, $rest)
	{
		$token = new QuoteToken("QUOTE", $first);
		return [$token, $rest];
	}
	
	protected function space($char,$str)
	{
		list($tokenValue, $restString) = $this->span('ctype_space', $str);
		$token = new SpaceToken();
		return [$token, $restString];
	}
	
	function dot($char,$str)
	{
		list($tokenValue, $restString) = [".", $str];
		$token = new DotToken();
		return [$token, $restString];
	}
	
	function slash($char, $str)
	{
		list($tokenValue, $restString) = ["\\", $str];
		$token = new SlashToken();
		return [$token, $restString];
	}
	
	protected function RP($char, $str) 
	{
		list($tokenValue, $restString) = [")", $str];
		$token = new RPToken();
		return [$token, $restString];
	}
	
	protected function LP($char, $str)
	{
		list($tokenValue, $restString) = ["(", $str];
		$token = new RPToken();
		return [$token, $restString];
	}
	
	protected function name($char,  $rest)
	{
		list($tokenValue, $restString) = $this->span('ctype_alpha', $rest);
		$token = new NameToken('name', $char. $tokenValue);
		
		return [$token, $restString];
	}

	protected function span($pred, $string)
	{
		$str = new Str($string);
		return $str->span($pred);
	}
}