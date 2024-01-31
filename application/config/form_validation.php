<?php
$config = array(
		'error_prefix' => '<li>',
		'error_suffix' => '</li>',

     'setting_shipping' => [
            [
               'field' => 'ups_name',
               'label' => 'ups_name',
               'rules' => 'required',
               'errors'=>[
                       'required' => 'Please Provide UPS Email or User ID.',
               ]
            ],

            [
               'field' => 'ups_password',
               'label' => 'ups_password',
               'rules' => 'required',
               'errors'=>[
                       'required' => 'Please Provide UPS Password.',
               ]
            ],
            [
               'field' => 'ups_shipper_id',
               'label' => 'ups_shipper_id',
               'rules' => 'required',
               'errors'=>[
                       'required' => 'Please Provide UPS Shipper number.',
               ]
            ],
            
            [
               'field' => 'ups_access_key',
               'label' => 'ups_access_key',
               'rules' => 'required',
               'errors'=>[
                       'required' => 'Please Provide UPS Access Key.',
               ]
            ],
            [
               'field' => 'name',
               'label' => 'name',
               'rules' => 'required',
               'errors'=>[
                       'required' => 'Please Provide Name.',
               ]
            ],
            [
               'field' => 'address1',
               'label' => 'address1',
               'rules' => 'required',
               'errors'=>[
                       'required' => 'Please Provide Address Line 1.',
               ]
            ],
            
            [
               'field' => 'city',
               'label' => 'city',
               'rules' => 'required',
               'errors'=>[
                       'required' => 'Please Provide City.',
               ]
            ],
            [
               'field' => 'state',
               'label' => 'state',
               'rules' => 'required',
               'errors'=>[
                       'required' => 'Please Provide State.',
               ]
            ],
            
            [
               'field' => 'country',
               'label' => 'country',
               'rules' => 'required',
               'errors'=>[
                       'required' => 'Please Provide Country.',
               ]
            ],
            [
               'field' => 'pincode',
               'label' => 'pincode',
               'rules' => 'required',
               'errors'=>[
                       'required' => 'Please Provide Pincode.',
               ]
            ],
        ],


        
        'serial_insert' =>  array(
               array(
                       'field' => 'serial',
                       'label' => 'serial',
                       'rules' => 'required',
                       'errors'=>[
                               'required' => 'Please add serial number.',
                       ]

               ),
               array(
                       'field' => 'product_ids[]',
                       'label' => 'product_ids',
                       'rules' => 'required',
                       'errors'=>[
                               'required' => 'Please select product.',
                       ]

               ),
       ),
       'serial_update' =>  array(
               array(
                       'field' => 'serial',
                       'label' => 'serial',
                       'rules' => 'required',
                       'errors'=>[
                               'required' => 'Please add serial number.',
                       ]

               ),
               array(
                       'field' => 'product_ids[]',
                       'label' => 'product_ids',
                       'rules' => 'required',
                       'errors'=>[
                               'required' => 'Please Select product.',
                       ]

               ),
               
       ),
        'customer_register' => [
                [
                        'field' =>   'email_id',
                        'label' => 'email_id',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'You must enter a valid email.',
                        ],
                ],
                [
                        'field' =>   'password',
                        'label' => 'password',
                        'rules' => 'required|min_length[7]',
                        'errors'=>[
                                'required' => 'You must enter a password.',
                                'min_length' => "Passwords must be at least 7 characters and contain both alphabetic and numeric characters.",
                        ],
                ],
                [
                        'field' =>   'cpassword',
                        'label' => 'cpassword',
                        'rules' => 'matches[password]',
                        'errors'=>[
                                'required' => 'You must enter a password.',
                        ],
                ],
                [
                        'field' => 'fname',
                        'label' => 'fname',
                        'rules' => 'required',
                        'errors' => [
                                'required' => "The 'First Name' field cannot be blank.",
                        ],
                ],
                [
                        'field' => 'lname',
                        'label' => 'lname',
                        'rules' => 'required',
                        'errors' => [
                                'required' => "The 'Last Name' field cannot be blank.",
                        ],
                ],
                [
                        'field' => 'address_line_1',
                        'label' => 'address_line_1',
                        'rules' => 'required',
                        'errors' => [
                                'required' => "The 'Address Line 1' field cannot be blank.",
                        ],
                ],
                [
                        'field' => 'city',
                        'label' => 'city',
                        'rules' => 'required',
                        'errors' => [
                                'required' => "The 'Suburb/City' field cannot be blank.",
                        ],
                ],
                [
                        'field' => 'state',
                        'label' => 'state',
                        'rules' => 'required',
                        'errors' => [
                                'required' => "The 'State/Province' field cannot be blank.",
                        ],
                ],
                [
                        'field' => 'pincode',
                        'label' => 'pincode',
                        'rules' => 'required',
                        'errors' => [
                                'required' => "The 'Zip/Postcode' field cannot be blank.",
                        ],
                ],
                 
                
        ],       
        'serise_insert' => [
                [
                        'field' => 'category_id',
                        'label' => 'category_id',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please select category.',
                        ],
                ],
                [
                        'field' => 'title',
                        'label' => 'Serise Title',
                        'rules' => 'required|min_length[2]|serise_title_should_be_unique[serise.category_id.title]',
                        'errors'=>[
                                'required' => 'Please select series title.',
                                'min_length' => 'Please select series title.',
                                'serise_title_should_be_unique' => 'Series title is already assigned to that category '
                                
                        ],
                ], 
                [
                        'field' => 'product_ids[]',
                        'label' => 'product_ids[]',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please select product.',
                        ],
                ],
               
        ],


        'serise_update' => [
                [
                        'field' => 'category_id',
                        'label' => 'category_id',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please select category.',
                        ],
                ],
                [
                        'field' => 'title',
                        'label' => 'Serise Title',
                        'rules' => 'required|min_length[2]|serise_title_should_be_unique_update[serise.category_id.title]',
                        'errors'=>[
                                'required' => 'Please select series title.',
                                'min_length' => 'The Series Title must be at least 2 characters in length.',
                                'serise_title_should_be_unique_update' => 'Series Title is already assigned to that category '
                                
                        ],
                ],
                [
                        'field' => 'product_ids[]',
                        'label' => 'product_ids[]',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please select product.',
                        ],
                ],
                

        ],

        

        'news_create' => array(
                array(
                        'field' => 'title',
                        'label' => 'News Title',
                        'rules' => 'required|min_length[10]',
                        'errors'=>[
                        	'required' => 'Please Provide News Title.',
                        	'min_length' => 'The News Title must be at least 10 characters in length.',
                        ]
                ),
                array(
                        'field' => 'text',
                        'label' => 'News Text',
                        'rules' => 'required|min_length[30]',
                        'errors'=>[
                        	'required' => 'Please Provide News Text.',
                        	'min_length' => 'The News Text must be at least 30 characters in length.',
                        ]
                )
        ),
        'admin_login' => array(
                array(
                        'field' => 'username',
                        'label' => 'User Name',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please add User Name.',
                        ]
                ),
                array(
                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please add Password.',
                        ]
                ),
                
        ),
        'setting' => [
                [
                        'field' => 'storeurl',
                        'label' => 'Store URL',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please Provide Store URL.',
                        ]

                ],

                [
                        'field' => 'apiusername',
                        'label' => 'apiusername',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please Provide Api User Name.',
                        ]

                ],
                [
                        'field' => 'apipath',
                        'label' => 'apipath',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please Provide Api Path.',
                        ]

                ],
                [
                        'field' => 'apitoken',
                        'label' => 'apitoken',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please Provide Api Path.',
                        ]

                ],
                [
                        'field' => 'storehas',
                        'label' => 'storehas',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please Provide Store Has.',
                        ]

                ],
                [
                        'field' => 'client_id',
                        'label' => 'client_id',
                        'rules' => 'required',
                        'errors'=>[
                                'required' => 'Please Provide Client Id.',
                        ]

                ],
                
        ]
);
