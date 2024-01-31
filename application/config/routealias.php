<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

// Define All Route URL
// $adminpath = 'admin/';

$config['news.index'] = 'admin/news';
$config['news.create'] = 'admin/news/create';
$config['news.view'] = 'admin/news/(:any)';



// Define Route Controller/Method

$config['news.index.controller'] = 'news';
$config['news.create.controller'] = 'news/create';
$config['news.view.controller'] = 'news/view/$1';