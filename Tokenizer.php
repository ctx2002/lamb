<?php
require_once "Tokens.php";
class Tokenizer
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
		//var_dump($first, $rest);
		if (ctype_alpha( $first)) {
			$this->tokens = $this->name($first, $rest );
		} else if ($first == "(") {
			$this->tokens = $this->LP($first, $rest);
		} else if ($first == ")") {
			$this->tokens = $this->RP($first, $rest);
		} else if ($first == "\\") { 
			$this->tokens = $this->slash($first, $rest);
			
		} else if (ctype_punct($first)) {
			$this->tokens = $this->dot($first,$rest);
		} else if (ctype_space($first)) {
			$this->tokens = $this->space($first,$rest);
		}
		
		return $this->tokens;
	}
	
	public function restOfString($str)
	{
		if (isset($str[1]) ) return substr($str, 1);
		
		return "";
	}
	
	protected function space($char,$str)
	{
		list($tokenValue, $restString) = $this->span('ctype_space', $str);
		
		$token = new SpaceToken();
		return ( array_merge( [$token] , $this->tokenize($restString)) ) ;
	}
	
	function dot($char,$str)
	{
		list($tokenValue, $restString) = [".", $str];
		$token = new DotToken();
		return ( array_merge( [$token] , $this->tokenize($restString)) ) ;
	}
	
	function slash($char, $str)
	{
		list($tokenValue, $restString) = ["\\", $str];
		$token = new SlashToken();
		return array_merge( [$token] , $this->tokenize($restString));
	}
	
	protected function RP($char, $str) 
	{
		list($tokenValue, $restString) = [")", $str];
		$token = new RPToken();
		return ( array_merge( [$token] , $this->tokenize($restString)) ) ;
	}
	
	protected function LP($char, $str)
	{
		list($tokenValue, $restString) = ["(", $str];
		$token = new RPToken();
		return ( array_merge( [$token] ,  $this->tokenize($restString)) ) ;
	}
	
	protected function name($char,  $rest)
	{
		list($tokenValue, $restString) = $this->span('ctype_alpha', $rest);
		$token = new NameToken('name', $char. $tokenValue);
		
		return array_merge( [$token] , $this->tokenize($restString)) ;
	}
	
	protected function span($pred, $string)
	{
		$i = 0;
		$str = "";
		$str2 = "";
		while (true) {
			if (isset( $string[$i])) {
				if ($pred($string[$i]) ) {
					$str .= $string[$i];
				} else {
					break;
				}
			} else {
				break;
			}
			++$i;
		}
		
		$str2 = substr($string, $i );
		if ( $str2 === FALSE) {
			$str2 = "";
		}

		return [$str, $str2];
	}
}