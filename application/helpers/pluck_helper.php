<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('array_pluck($array, $select_text, $select_value,$default)'))
{
	function array_pluck($array, $select_text, $select_value,$default)
	{
		//array_unshift($array, [ $select_value=> '' ,$select_text => $default ]);
		$array = array_column($array,$select_text,$select_value);
		return $array;
	}

	function array_pluck_category($array, $select_text, $select_value,$default)
	{
		array_unshift($array, [ $select_value=> '' ,$select_text => $default ]);
		$array = array_column($array,$select_text,$select_value);
		return $array;
	}
}