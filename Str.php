<?php
class Str
{
    private $str;
    public function __construct($str)
    {
        $this->str = $str;
    }
    /**
        break a string by $location, it returns an array with 2 elements.
        array[0] contains chars from 0 to $location-1.
        array[0] contains chars from $location to end of str, or '' if $location 
        is bigger or equals to str's length
    ***/
    public function breakby($location)
    {
        $str = substr($this->str, 0, $location); 
		$str2 = substr($this->str, $location );
		
		if ( $str2 === FALSE) {
			$str2 = "";
		}
        return [$str, $str2];
    }
    
    /**
        applied to a predicate $pred to Str
        it return the first location that $pred failed.
        which also mean, last location the $pred was successfull applied.

        @param - $pred this is bool function which take 1 char as input.
        @return - bool
    ***/
    public function lastLocation($pred)
    {
        $i = 0;
		while ($pred($this->str[$i])) {
			$i++;
		}
		return $i;
    }
    /**
        @param $pred - a function which has one char as input
        @return bool

        applied to a predicate $pred to Str, 
        returns an array of 2 elements.
        array[0] satisfy $pred, array[1] is remainder of the Str
    ***/
    public function span($pred)
    {
        if ($this->str == '') return ['', ''];

		$i = $this->lastLocation($pred);
        return $this->breakby($i); 
    }
}