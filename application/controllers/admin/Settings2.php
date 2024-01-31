<?php

class Settings extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->library('image_lib');
        $this->load->model('admin/settingmodel');
        $this->lang->load('settings', 'english');
    }

    public function index()
    {
        $this->data['title'] = 
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
}
