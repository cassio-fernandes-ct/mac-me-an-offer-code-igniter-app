<?php
class MY_Form_validation extends CI_Form_validation{    
     function __construct($config = array()){
          parent::__construct($config);
     }
     function serise_title_should_be_unique($title,$table){
     	return $this->CI->serise_model->check_should_be_unique_two_field($title,$table);
     }

     function serise_title_should_be_unique_update($title,$table){
     	return $this->CI->serise_model->check_should_be_unique_two_field_update($title,$table);
     }
}