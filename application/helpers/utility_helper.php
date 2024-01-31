<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('asset_url()'))
{
	function asset_url()
	{
	  return base_url().'assets/';
	}
} 
if ( ! function_exists('get($name)'))
{
	function get($name)
	{
	    $CI = & get_instance();
	    return empty($CI->input->post($name))? $CI->input->get($name) : $CI->input->post($name) ;
	}
} 
