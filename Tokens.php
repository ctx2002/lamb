<?php
require_once "Token.php";

class LambToken implements Token {
	private $name;
	private $value;
	
	public function __construct($name, $value) {
	    $this->name = $name;
		$this->value = $value;
	}

    public function getName()
	{
		return $this->name;
	}
	public function getValue()
	{
		return $this->value();
	}
}

class StringToken extends LambToken
{
	
}

class DonotKnowToken extends LambToken
{
	
}

class QuoteToken extends LambToken
{
}

class NameToken extends LambToken
{
}

class LPToken extends LambToken
{
	public function __construct() {
	    parent::__construct("LP", "(" );
	}
}

class RPToken extends LambToken
{
    public function __construct() {
	    parent::__construct("RP", ")" );
	}
}

class SlashToken extends LambToken
{
    public function __construct() {
	    parent::__construct("SLASH", "\\" );
	}
}

class DotToken extends LambToken
{
    public function __construct() {
	    parent::__construct("DOT", "." );
	}
}

class  SpaceToken extends LambToken
{
    public function __construct() {
	    parent::__construct("SPACE", " " );
	}
}