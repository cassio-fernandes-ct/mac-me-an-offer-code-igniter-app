<?php

class Settings extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $session_data = $this->session->userdata('admin_session');
        if (!isset($session_data) || empty($session_data)) {
            redirect('admin/login');
        }else if ($session_data['role'] !='admin') {
            redirect('admin/login');
        }

        $this->load->library('upload');
        $this->load->library('image_lib');
        $this->load->model('admin/settingmodel');
        $this->lang->load('settings', 'english');
    }

    public function index()
    {
        $this->data['title'] = '';
        $success = 0;
        if ($this->form_validation->run('setting') === TRUE) {
            $update_record = $this->settingmodel->update_record();
            $success = 1;
        }
        $this->data['title'] = 'Setting';
        $this->data['page'] = 'admin/setting';
        $this->data['sub_title'] = 'Settings';
        $this->data['settingdata'] = $this->settingmodel->getSettingData('1');
        
        $admin_session = $this->session->userdata('admin_session');
        
        /**
         * Only users with admin role should be allowed to see registered user accounts. 
         * 
         * Class constructor should redirect all non-admins away from settings page so below is a redundant, but 
         * nice-to-have, check.
         */
        $this->data['user_accounts'] = [];
        if( 'admin' === $admin_session['role'] ) {
            $this->data['user_accounts'] = $this->settingmodel->get_user_accounts();
        }

        $this->data['success'] = $success;

        $this->load->view('admin/common/leftmenu',$this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/settings/settings',$this->data);
        $this->load->view('admin/common/footer');
    }

    public function ajaxdelete()
    {
        $images = $this->input->get('imgname');
        $this->settingmodel->delete_images($images);
        exit;
    }

    public function ajaxupload()
    {
        $uploaddir = FCPATH.'application/uploads/sitelogo/';
        $upload_conf = array(
            'upload_path' => $uploaddir.'original/',
            'allowed_types' => 'gif|jpg|png|jpeg',
            'max_size' => '0',
            'overwrite' => false,
            'remove_spaces' => true,
            'encrypt_name' => true,
            'file_name' => time(),
            );
        $this->upload->initialize($upload_conf);
        foreach ($_FILES['uploadfile'] as $key => $val) {
            $i = 1;
            foreach ($val as $v) {
                $field_name = 'file_'.$i;
                $_FILES[$field_name][$key] = $v;
                ++$i;
            }
        }
        unset($_FILES['uploadfile']);
        $error = array();
        $success = array();
        foreach ($_FILES as $field_name => $file) {
            if (!$this->upload->do_upload($field_name)) {
                $error['upload'][] = $this->upload->display_errors();
            } else {
                $config = array(
                    'file_name' => time().$field_name,
                );
                $upload_data = $this->upload->data($config);
                $success['original'][] = $upload_data;
                $upload_name = $upload_data['file_name'];
                $image_sizes = array(
                    'thumb400' => array(400, 400),
                    'thumb300' => array(300, 300),
                    'thumb200' => array(200, 200),
                    'thumb100' => array(100, 100),
                    'thumb50' => array(50, 50),
                );
                foreach ($image_sizes as $key => $resize) {
                    $config = array(
                        'source_image' => $upload_data['full_path'],
                        'new_image' => $uploaddir.$key.'/'.$upload_name,
                        'maintain_ration' => true,
                        'overwrite' => false,
                        'width' => $resize[0],
                        'remove_spaces' => true,
                        'encrypt_name' => true,
                        'height' => $resize[1],
                    );
                    $this->image_lib->initialize($config);
                    if (!$this->image_lib->resize()) {
                        $error['resize'][$key][] = $this->image_lib->display_errors();
                    }
                    $this->image_lib->clear();
                }
            }
        }
        if (count($error) > 0) {
            $this->data['status'] = 'error';
            $this->data['error_data'] = $error;
        } else {
            $this->data['status'] = 'success';
            $this->data['success_data'] = $success;
        }
        echo json_encode($this->data);
    }

    public function update_shipping_settings()
    {
          $this->data['success'] = 0;
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
             
            if ($this->form_validation->run('setting_shipping') == TRUE)
            {
                $enable = isset($_POST['enable'])? true: false;
                $data = [
                    'shipto_name' => get('name'),
                    'shipto_address_line_1'    => get('address1'),
                    'shipto_address_line_2' => get('address2'),
                    'shipto_city'  => get('city'),
                    'shipto_state'     => get('state'),
                    'shipto_country' => get('country'),
                    'shipto_pincode'   => get('pincode'),
                    'shipto_number' => get('number'),
                    'shipper_number' => get('snumber'),
                    'is_shipping_module_enabled' => $enable,
                    'fedex_api_key' => get('fedex_api_key'),
                    'fedex_api_secret' => get('fedex_api_secret'),
                    'ups_user_id' => get('ups_name'),
                    'ups_shipper_id' => get('ups_shipper_id'),
                    'ups_access_key' => get('ups_access_key'),
                    'ups_password' => get('ups_password'),
                    'ups_environment' => get('ups_environment'),
                ];
                

                $this->settingmodel->update_shipping_settings($data);
                          $this->data['success'] = 1;

            }
            else
            {
                return $this->index();
            }
        }

         $this->data['title'] = 'Setting';
        $this->data['page'] = 'admin/setting';
        $this->data['sub_title'] = 'Settings';
        $this->data['settingdata'] = $this->settingmodel->getSettingData('1');
        $admin_session = $this->session->userdata('admin_session');
      
        $this->load->view('admin/common/leftmenu',$this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/settings/settings',$this->data);
        $this->load->view('admin/common/footer');
    }


    public function tradesapi(){
        $this->settingmodel->update_trands_api();
        $this->session->set_userdata('updatedata','1'); 
        redirect('admin/settings');
    }

    public function generalsetting(){
        $this->settingmodel->update_generalsetting();
        $this->session->set_userdata('updatedata','1'); 
        redirect('admin/settings');
    }


    /**
     * Update password for specific user
     *
     * @todo Add response codes
     * 
     * @return void
     */
    public function updatePassword()
    {
        // super simple response
        $res = [
            'success' => null,
            'data' => null,
        ];

        // confirm current user has capability to change password
        $admin_session = $this->session->userdata( 'admin_session' );
        if( 'admin' !== $admin_session['role'] ) {
            $res['success'] = false;
            $res['data'] = sprintf(
                '<ul class="errors errors--user-create">%s</ul>',
                '<li class="error">You are not authorized to change a user\'s password.</li>'
            );
        
        } else {
            $this->load->library( 'form_validation' );
            $this->load->helper( 'security' );

            $this->form_validation->set_rules(
                'userId',
                'User ID',
                'required|trim|intval|callback_id_matches_existing_user',
                [
                    'id_matches_existing_user' => 'User ID does not match an existing user.'
                ]
            );

            // md5 used as hashing algo in application/controllers/Login.php
            $this->form_validation->set_rules( 
                'password', 
                'Password', 
                'required|trim|min_length[12]|xss_clean|md5'
            );
    
            if( false === $this->form_validation->run() ) {
                $res['success'] = false;
                $res['data'] = sprintf( 
                    '<ul class="errors errors--user-create">%s</ul>', 
                    validation_errors( '<li class="error">', '</li>' )
                );

            } else {
                $user_id = $this->input->post( 'userId' );
                $password = $this->input->post( 'password' );

                // change password
                $this->db->where( 'id', $user_id );
                $this->db->update( 
                    'users',
                    [
                        'password' => $password
                    ]
                );

                $res['success'] = true;
                $res['data'] = 'Password successfully updated!';
            }
        }

        echo json_encode( $res );
        exit;
    }


    /**
     * Delete specific user
     * 
     * @todo Add response codes
     * @todo Deactivate user (change status from "yes" to "no") instead of outright deleting user?
     *
     * @return void
     */
    public function deleteUser()
    {
        // super simple response
        $res = [
            'success' => null,
            'data' => null,
        ];

        // confirm current user has capability to delete user
        $admin_session = $this->session->userdata( 'admin_session' );
        if( 'admin' !== $admin_session['role'] ) {
            $res['success'] = false;
            $res['data'] = sprintf(
                '<ul class="errors errors--user-create">%s</ul>',
                '<li class="error">You are not authorized to delete a user account.</li>'
            );  

        // form validation probably overkill, but here to keep consistent
        } else {
            $this->load->library( 'form_validation' );
            $this->load->helper( 'security' );

            $this->form_validation->set_rules(
                'userId',
                'User ID',
                'required|trim|intval|callback_id_matches_existing_user|callback_user_is_not_admin',
                [
                    'id_matches_existing_user' => 'User ID does not match an existing user.',
                    'user_is_not_admin' => 'User is an admin and cannot be deleted.',
                ]
            );
    
            if( false === $this->form_validation->run() ) {
                $res['success'] = false;
                $res['data'] = sprintf( 
                    '<ul class="errors errors--user-create">%s</ul>', 
                    validation_errors( '<li class="error">', '</li>' )
                );

            } else {
                $user_id = $this->input->post( 'userId' );

                // delete user
                $this->db->delete( 
                    'users',
                    [
                        'id' => $user_id
                    ]
                );                

                $res['success'] = true;
                $res['data'] = 'User successfully deleted!';
            }
        }    
        
        echo json_encode( $res );
        exit;
    }


    /**
     * Create new user
     * 
     * @todo Check what "r_password" should represent
     * @todo Add response codes
     *
     * @return void
     */
    public function createUser()
    {
        // super simple response
        $res = [
            'success' => null,
            'data' => null,
        ];

        // confirm current user has capability to create user
        $admin_session = $this->session->userdata( 'admin_session' );
        if( 'admin' !== $admin_session['role'] ) {
            $res['success'] = false;
            $res['data'] = sprintf(
                '<ul class="errors errors--user-create">%s</ul>',
                '<li class="error">You are not authorized to create a new user account.</li>'
            );
            
        } else {
            $this->load->library( 'form_validation' );
            $this->load->helper( 'security' );
    
            // required fields
            $this->form_validation->set_rules( 
                'username', 
                'Username', 
                'required|trim|min_length[5]|xss_clean|callback_username_does_not_match_existing_user', 
                [
                    'callback_' => 'User already exists with that username.' 
                ]
            );
    
            // md5 used as hashing algo in application/controllers/Login.php
            $this->form_validation->set_rules( 
                'password', 
                'Password', 
                'required|trim|min_length[12]|xss_clean|md5'
            );
            
            $this->form_validation->set_rules( 
                'role', 
                'Role', 
                'required|trim|callback_valid_user_role',
                [
                    'valid_user_role' => 'User role must be either "admin" or "user".'
                ]
            );
    
            $this->form_validation->set_rules( 
                'email', 
                'Email', 
                'required|trim|valid_email|callback_email_does_not_match_existing_user',
                [
                    'email_does_not_match_existing_user' => 'User already exists with that email address.'
                ]
            );
    
            // optional fields
            $this->form_validation->set_rules( 'firstname', 'First Name', 'trim|xss_clean' );
            $this->form_validation->set_rules( 'lastname', 'Last Name', 'trim|xss_clean' );
    
            if( false === $this->form_validation->run() ) {
                $res['success'] = false;
                $res['data'] = sprintf( 
                    '<ul class="errors errors--user-create">%s</ul>', 
                    validation_errors( '<li class="error">', '</li>' )
                );
    
            } else {
                $username = $this->input->post( 'username' );
                $password = $this->input->post( 'password' );
                $email = $this->input->post( 'email' );
                $role = $this->input->post( 'role' );
    
                $firstname = $this->input->post( 'firstname' );
                $lastname = $this->input->post( 'lastname' );
    
                $data = [
                    'username' => $username,
                    'firstname' => $firstname ?: '',
                    'lastname' => $lastname ?: '',
                    'email' => $email,
                    'password' => $password, 
                    'framework_id' => 0,
                    'status' => 'yes',
                    'role' => $role,
                ];
    
                // all inputs are automatically escaped (@see https://www.codeigniter.com/userguide3/database/query_builder.html?highlight=update#inserting-data)
                $this->db->insert( 'users', $data );
    
                $res['success'] = true;
                $res['data'] = 'User successfully added!';            
            }
        }

        echo json_encode( $res );
        exit;
    }


    /**
     * Check that a user exists with specific user ID
     *
     * @param string $user_id
     * @return bool True, if user exists
     */
    public function id_matches_existing_user( string $user_id ): bool
    {
        $user_id = abs( intval( $user_id ) );

        $match = $this->db->get_where( 'users', [ 'id' => $user_id ] )->row_array();

        return is_array( $match ) && isset( $match['id'] ) && $user_id === intval( $match['id'] );
    }


    /**
     * Check that no user with username already exists
     *
     * @param string $username
     * @return bool True, if no user with username already exists
     */
    public function username_does_not_match_existing_user( string $username ): bool
    {
        return null === $this->db->get_where( 'users', [ 'username' => $username ] )->row_array();
    }


    /**
     * Check that user with email doesn't already exist
     *
     * @param string $email
     * @return bool True, if no user with email already exists
     */
    public function email_does_not_match_existing_user( string $email ): bool
    {
        return null === $this->db->get_where( 'users', [ 'email' => $email ] )->row_array();
    }  


    /**
     * Check that passed user role is a valid role
     *
     * @param string $role
     * @return bool True, if role is avalid
     */
    public function valid_user_role( string $role ): bool
    {
        return 'admin' === $role || 'user' === $role;
    }      


    /**
     * Check if user is NOT an admin
     *
     * @param string $user_id
     * @return bool True, if user is NOT an admin
     */
    public function user_is_not_admin( string $user_id ): bool
    {
        $user_id = abs( intval( $user_id ) );

        $match = $this->db->get_where( 'users', [ 'id' => $user_id ] )->row_array();

        return is_array( $match ) && isset( $match['role'] ) && 'admin' !== $match['role'];
    }

}
