<?php

namespace Arlo;

class Utilities 
{
	public static function get_first_para($string)
	{
		$string = substr($string,0, strpos($string, "</p>")+4);
		$string = str_replace("<p>", "", str_replace("<p/>", "", $string));
		return $string;
	}

	public static function limit_text($text, $limit)
	{
		if (str_word_count($text, 0) > $limit) {
			$words = str_word_count($text, 2);
			$pos = array_keys($words);
			$text = substr($text, 0, $pos[$limit]) . '...';
		}
		return $text;
	}
}