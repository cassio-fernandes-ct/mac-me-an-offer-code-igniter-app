<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('route($name)'))
{
	function route($name)
	{
	  return 'hello'.$name;
	}
}