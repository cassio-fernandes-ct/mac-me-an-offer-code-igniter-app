<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
class Test extends CI_Controller
{

    protected $shipping_label_file_name = '';

    public function __construct()
    {
        parent::__construct();
        // $this->load->model("productmodel");
        $this->load->model('admin/settingmodel');
        $this->load->library('email');
        header('Access-Control-Allow-Origin: *');

    }
    public function index22()
    {

        $this->load->view('serialNumberSearchBar');

    }
    public function storeproductinfo()
    {

        $data = $this->input->post();
        if (isset($data['attribute']) && !empty($data['attribute'])) {
            $tokenupdate = $this->productmodel->quotesteponeinsert($data);
            $seraiproductlhtml['formhtml'] = $tokenupdate;
            echo json_encode($seraiproductlhtml);
        } else {

            $seraiproductlhtml['formhtml'] = '';
            echo json_encode($seraiproductlhtml);
        }
    }

    public function index()
    {

        $settingData = $this->settingmodel->getSettingData('1');

        $rate_api_url = $this->config->item('ups')['urls'][$settingData['ups_environment']]['rating'];
        $shipping_api_url = $this->config->item('ups')['urls'][$settingData['ups_environment']]['shipping'];

        /* CSC means Country State City */
        $this->load->model('Csc_model');
        $state_data = $this->Csc_model->get_state_data("MT");
        
        $country = $state_data['country_code'];
        echo 'Country: '.$country;
        exit;

        // $country = "US";
        $stateCode = "MT";
        $product_weight = "8.00 LBS";
        $pw = $this->get_product_weight_in_pound($product_weight);
        $product_weight = $pw;
        $ShipFromData = [
            'Name' => $settingData['shipto_name'],
            'AddressLine1' => $settingData['shipto_address_line_1'],
            'AddressLine2' => $settingData['shipto_address_line_2'],
            'City' => $settingData['shipto_city'],
            'StateProvinceCode' => $settingData['shipto_state'],
            'PostalCode' => $settingData['shipto_pincode'],
            'CountryCode' => $settingData['shipto_country'],
            'Number' => $settingData['shipto_number'],
        ];
        $ShipToData = [
            'Name' => "Avery",
            'AddressLine1' => '1711 S 11th ave',
            'AddressLine2' => 'Apt 515',
            'City' => 'Bozeman',
            'StateProvinceCode' => 'MT',
            'PostalCode' => '59715',
            'CountryCode' => $country,
            'Number' => '14065503228',
        ];

        $productData = [
            'Height' => '',
            'Width' => '',
            'Weight' => $product_weight,
            'Length' => '',
            'Qty' => '1',
        ];
        $data = [
                'tokensavedetils' => 'ILxBGhD1CQv87bMfHY9Tr5yPgNKZzds3pV6S4FtuenJXiRAEoq',

        ];
        if (isset($data['tokensavedetils']) && !empty($data['tokensavedetils'])) {

            // $tokenupdate = $this->productmodel->updatecustomerinfo($data);
            $tokenupdate = true;
            if ($tokenupdate) {
                if (!$settingData['is_shipping_module_enabled']) {
                    //knockout email not send code.
                    // $knockout = $this->productmodel->checkKnowoutNum($data['tokensavedetils']);
                    // if ($knockout['knockout'] == 0) {
                    //     $this->emailtem($data['tokensavedetils'], $this->shipping_label_file_name);
                    // }
                    //knockout email not send code.

                    // Send Email With out Shipping Lable
                    // $this->emailitemadmin($data['tokensavedetils'], $this->shipping_label_file_name);
                    // echo 'send mail without image';
                    // $tokenupdate = $this->productmodel->updatecustomerinfo($data);
                    $mes['suc'] = 'suc';
                    echo json_encode($mes);
                    exit;

                } else {
                    if ($productData['Weight'] <= 0 || $productData['Qty'] <= 0) {
                        $mes['error'] = 'This product has not added weight you can contact the website administrator.';
                        echo json_encode($mes);
                        exit;
                    } else {
                        $lowest_rate_service_code = $this->get_lowest_rate_service_code($productData, $ShipToData);
                        // echo $lowest_rate_service_code;
                        // exit;
                        // $lowest_rate_service_code = '03';
                        $this->generate_shipping_label($lowest_rate_service_code, $productData, $ShipToData, $ShipFromData, $tokenupdate, $data);

                        // echo 'send mail with image';
                        // Send Email With Shipping Label
                        // /var/www/html/application/uploads/ups/shipping file path
                        //knockout email not send code.
                        // $knockout = $this->productmodel->checkKnowoutNum($data['tokensavedetils']);

                        // if ($knockout['knockout'] == 0) {
                        //     $this->emailtem($data['tokensavedetils'], $this->shipping_label_file_name);
                        // }
                        //knockout email not send code.

                        // $this->emailitemadmin($data['tokensavedetils'], $this->shipping_label_file_name);
                        $mes['suc'] = $this->shipping_label_file_name;
                        // $mes['bc_id_mmo'] = @$knockout['customer_id'];
                        echo json_encode($mes);
                        //exit;

                    }

                }

            }
        } else {

            echo "null";
        }
    }

    public function emailitemadmin($token, $shipping_label_file_name)
    {
        $tokenupdate = $this->productmodel->getquote($token);
        $emailtemplete = $this->productmodel->getBcConfig();
        $option = '';

        if (isset($tokenupdate['customerinfo']['selectedoption']) && !empty($tokenupdate['customerinfo']['selectedoption'])) {
            /*foreach ($tokenupdate['selectedoption'] as $value) {
            # code...

            $option .= '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">'.$value['option_set_name'].': </b> '.$value['option_label'].'</p>';
            }*/
            $o = json_decode($tokenupdate['customerinfo']['selectedoption']);

            if (isset($o->option) && !empty($o->option)) {
                foreach ($o->option as $value) {
                    # code...

                    $option .= '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">' . $value->option_set_name . ': </b> ' . $value->option_label . '</p>';
                }
            }

            if (isset($o->qustionans) && !empty($o->qustionans)) {
                foreach ($o->qustionans as $val) {
                    # code...

                    $option .= '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">' . $val->option_set_name . ': </b> ' . $val->option_label . '</p>';
                }
            }
        }

        //$emailtemplete = $this->emailtemplete();

        $htmlConten = str_replace(array('{{name}}', '{{PO}}',
            '{{category_name}}',
            '{{product_name}}',
            '{{options}}',
            '{{label_image}}',
            'Your estimate is approved and is valid for 14 days. This estimate is for the following product.',
            'Use the attached packing instructions and prepaid label to send your product to us. We will update you once it has been received and inspected!'),
            array('Admin', $tokenupdate['customerinfo']['id'],
                $tokenupdate['product_category_name']['category_name'],
                $tokenupdate['product_category_name']['product']['product_title'],
                $option,
                '',
                '',
                '',
            )
            , $emailtemplete['email_template']);

        /* $config['protocol']    = 'smtp';
        $config['smtp_user']   = 'tradein@macofalltrades.com';
        $config['smtp_port']   = '465';
        $config['smtp_host']   = 'secure.emailsrvr.com';
        $config['smtp_pass']   = 'sell-collar-Decimal-cave-74';
        $config['smtp_crypto'] = 'ssl';
        $config['charset']     = 'iso-8859-1';
        $config['wordwrap']    = TRUE; */

        $config['protocol'] = $emailtemplete['protocol'];
        $config['smtp_user'] = $emailtemplete['smtp_user'];
        $config['smtp_port'] = $emailtemplete['smtp_port'];
        $config['smtp_host'] = $emailtemplete['smtp_host'];
        $config['smtp_pass'] = $emailtemplete['smtp_pass'];
        $config['smtp_crypto'] = 'ssl';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = true;

        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");
        // $this->email->from('tradein@macofalltrades.com',"Mac Me An Offer");
        $this->email->from($emailtemplete['smtp_user'], "Mac Me An Offer");
        $this->email->to($emailtemplete['admin_email']);
        $this->email->reply_to($tokenupdate['customerinfo']['form_email_address']);

        $this->email->subject('New Quote Received From ' . $tokenupdate['customerinfo']['form_first_name'] . ' ' . $tokenupdate['customerinfo']['form_last_name'] . ' ');
        $this->email->message($htmlConten);
        if (isset($shipping_label_file_name) && !empty($shipping_label_file_name)) {
            $url = base_url() . '/application/uploads/ups/shipping/' . $shipping_label_file_name;
            $this->email->attach($url);
        }
        //$this->email->send();

        if ($this->email->send()) {
            //echo 'Your Email has successfully been sent.';
        } else {
            show_error($this->email->print_debugger());
        }
    }

    public function emailtem($token, $shipping_label_file_name)
    {

        //$token = "JA3tdYIuGrC6sqSOLiN8";
        $tokenupdate = $this->productmodel->getquote($token);
        $emailtemplete = $this->productmodel->getBcConfig();
        $option = '';
        if (isset($tokenupdate['selectedoption']) && !empty($tokenupdate['selectedoption'])) {
            foreach ($tokenupdate['selectedoption'] as $value) {
                # code...

                $option .= '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">' . $value['option_set_name'] . ': </b> ' . $value['option_label'] . '</p>';
            }
        }

        if (isset($shipping_label_file_name) && !empty($shipping_label_file_name)) {
            //$label_image = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Shipping Label: </b> <a href = "https://app.macmeanoffer.com/application/uploads/ups/shipping/'.$shipping_label_file_name.'" download>'.$shipping_label_file_name.'</a></p>';
        }

        //$emailtemplete = $this->emailtemplete();

        /* $htmlConten = str_replace(array('{{name}}',
        '{{category_name}}',
        '{{product_name}}',
        '{{options}}',
        '{{label_image}}'),
        array( $tokenupdate['customerinfo']['form_first_name'].' '.$tokenupdate['customerinfo']['form_last_name'],
        $tokenupdate['product_category_name']['category_name'],
        $tokenupdate['product_category_name']['product']['product_title'],
        $option,
        ''
        )
        ,$emailtemplete['email_template']);*/

        $htmlConten = str_replace(array('{{name}}',
            '{{category_name}}',
            '{{product_name}}',
            '{{options}}',
            '{{label_image}}',
            'Category Name:', '{{PO}}'),
            array($tokenupdate['customerinfo']['form_first_name'] . ' ' . $tokenupdate['customerinfo']['form_last_name'],
                '',
                $tokenupdate['product_category_name']['product']['product_title'],
                '',
                '',
                '', $tokenupdate['customerinfo']['id'],
            )
            , $emailtemplete['email_template']);

        /*$config['protocol']    = 'smtp';
        $config['smtp_user']   = 'tradein@macofalltrades.com';
        $config['smtp_port']   = '465';
        $config['smtp_host']   = 'secure.emailsrvr.com';
        $config['smtp_pass']   = 'sell-collar-Decimal-cave-74';
        $config['smtp_crypto'] = 'ssl';
        $config['charset']     = 'iso-8859-1';
        $config['wordwrap']    = TRUE; */

        $config['protocol'] = $emailtemplete['protocol'];
        $config['smtp_user'] = $emailtemplete['smtp_user'];
        $config['smtp_port'] = $emailtemplete['smtp_port'];
        $config['smtp_host'] = $emailtemplete['smtp_host'];
        $config['smtp_pass'] = $emailtemplete['smtp_pass'];
        $config['smtp_crypto'] = 'ssl';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = true;

        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");
        // $this->email->from('tradein@macofalltrades.com',"Mac Me An Offer");
        $this->email->from($emailtemplete['smtp_user'], "Mac Me An Offer");
        $this->email->to($tokenupdate['customerinfo']['form_email_address']);
        //$this->email->reply_to($emailtemplete['admin_email']);
        $this->email->reply_to($emailtemplete['smtp_user']);
        $this->email->subject('Your Estimate is Approved!');
        $this->email->message($htmlConten);
        if (isset($shipping_label_file_name) && !empty($shipping_label_file_name)) {
            $url = base_url() . '/application/uploads/ups/shipping/' . $shipping_label_file_name;
            $this->email->attach($url);
            $pdfurl = base_url() . '/application/uploads/pdf/MMAO_Packing_Instructions.pdf';
            $this->email->attach($pdfurl);
        }
        //$this->email->send();

        if ($this->email->send()) {
            //echo 'Your Email has successfully been sent.';
        } else {
            show_error($this->email->print_debugger());
        }
    }

    public function tokengenerate()
    {

        $tokenupdate = $this->productmodel->random_strings();

        $seraiproductlhtml['tokengenerate'] = $tokenupdate;
        echo json_encode($seraiproductlhtml);
    }

    public function emailtemplete()
    {
        $emailtemplete = '<table bgcolor="#eeeeee" cellpadding="0" cellspacing="0" class="main-wrapper" dir="ltr" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fbfafa;" width="100%">
    <tbody>
        <tr>
            <td style="border-collapse: collapse; padding: 40px 10px 40px 10px;">
            <table align="center" cellpadding="0" cellspacing="0" class="content-wrapper" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; border: 1px solid #eeeeee;" width="600">
                <tbody>
                    <tr class="message-header" style="background-color:#e8e8e8">
                        <td style="border-collapse: collapse; padding: 10px 30px 20px 30px; color: #363636; font-family: \'Arial\',sans-serif,Helvetica,Arial,sans-serif; font-size: 13px;">
                            <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                <tbody>
                                    <tr>
                                        <td style="border-collapse: collapse; color: #363636; font-family: \'Arial\',sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; text-align: center;"><a href="https://bargain.haggleit.com/index.php" style="outline: none; color: #379424;"><img alt="HaggleIt" src="https://app.macmeanoffer.com/assets/logo/mmao-logo.png" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: none; width: 200px;" width="547" /> </a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr class="message-body">
                        <td style="border-collapse: collapse; padding: 30px;">
                            <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Hello&nbsp;{{name}},</b></p>
                            <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">An Estimate was generated With below information</b></p>
                            <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Category Name: </b> {{category_name}}</p>
                            <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Product Name: </b> {{product_name}}</p>
                            {{options}}


                        </td>
                    </tr>
                    <tr class="message-body" >
                        <td>
                            <p style="color: #00c4ff;padding: 0 0 30px 33; font-family: \'Arial\',sans-serif,Helvetica,Arial,sans-serif;font-size: 15px;">
                                Note: This is a system generated email, please don\'t reply to this mail.
                            </p>
                        </td>
                    </tr>

                    <tr class="message-copyright">
                        <td style="border-collapse: collapse; padding: 0px 30px 10px; color: #363636; font-family: \'Arial\',sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; background-color: #757f83;">
                            <table class="copyright" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                <tbody>
                                    <tr>
                                        <td style="border-collapse: collapse; color: #363636; font-family: \'Arial\',sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; padding: 10px 0 0 0; padding-bottom: 0 !important; color: white; ">Copyright &copy; mac me an offer, All rights reserved.</td>
                                        <td align="right" style="border-collapse: collapse; color: #363636; font-family: \'Arial\',sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; padding: 10px 0 0 0; padding-bottom: 0 !important; color: white;">

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
    </tbody>
</table>';

        return $emailtemplete;
    }

    public function get_lowest_rate_service_code($productData, $ShipToData)
    {
        $this->load->library('UpsRating');
        $this->upsrating->addField('ShipTo_Name', $ShipToData['Name']);
        $this->upsrating->addField('ShipTo_AddressLine',
            array($ShipToData['AddressLine1'], $ShipToData['AddressLine2']));
        $this->upsrating->addField('ShipTo_City', $ShipToData['City']);
        $this->upsrating->addField('ShipTo_StateProvinceCode', $ShipToData['StateProvinceCode']);
        $this->upsrating->addField('ShipTo_PostalCode', $ShipToData['PostalCode']);
        $this->upsrating->addField('ShipTo_CountryCode', $ShipToData['CountryCode']);

        $dimensions = array();
        $index = 0;
        $dimensions[$index]['Length'] = $productData['Length'];
        $dimensions[$index]['Width'] = $productData['Width'];
        $dimensions[$index]['Height'] = $productData['Height'];
        $dimensions[$index]['Weight'] = $productData['Weight'];
        $dimensions[$index]['Qty'] = $productData['Qty'];
        $this->upsrating->addField('dimensions', $dimensions);
        $ups_rate_list = json_decode($this->upsrating->processRate()[0]);
        // echo '<pre>';
        // var_dump($ups_rate_list);
        // echo '</pre>';
        $selected_service_code = -1;
        if (isset($ups_rate_list->RateResponse)) {
            $services_code_list = [];
            $service_charges_list = [];
            foreach ($ups_rate_list->RateResponse->RatedShipment as $key => $value) {
                $services_code_list[$value->RatedPackage->TotalCharges->MonetaryValue] = $value->Service->Code;
                $service_charges_list[] = $value->RatedPackage->TotalCharges->MonetaryValue;
                // echo 'Service Code: '.$value->Service->Code.'<br>';
                // echo 'Service Code: '.$value->RatedPackage->TotalCharges->MonetaryValue.'<br>';
                // print_r($value);
                // echo '<br><br>';

            }

            $selected_service_code = $services_code_list[min($service_charges_list)];

        } else {
            $mes['error'] = $ups_rate_list->Fault->detail->Errors->ErrorDetail->PrimaryErrorCode->Description;
            // $mes['error'] = $ups_rate_list;
            echo json_encode($mes);
            exit;
        }

        // '82';
        return $selected_service_code;

    }

    public function generate_shipping_label($lowest_rate_service_code, $productData, $ShipToData, $ShipFromData, $id, $postdata)
    {
        /*
        | -------------------------------------------------------------------
        | UPS Shipping API
        | To Address Is Mac me an offer address and
        | From address is customers address
        | -------------------------------------------------------------------
         */

        $from['ShipTo_Name'] = $ShipFromData['Name'];
        $from['ShipTo_AddressLine'] = $ShipFromData['AddressLine1'];
        $from['ShipTo_City'] = $ShipFromData['City'];
        $from['ShipTo_StateProvinceCode'] = $ShipFromData['StateProvinceCode'];
        $from['ShipTo_PostalCode'] = $ShipFromData['PostalCode'];
        $from['ShipTo_CountryCode'] = $ShipFromData['CountryCode'];
        $from['ShipTo_Number'] = $ShipFromData['Number'];

        $from['Service_Code'] = '01';

        $from['ShipFrom_Name'] = $ShipToData['Name'];
        $from['ShipFrom_AddressLine'] = $ShipToData['AddressLine1'];
        $from['ShipFrom_City'] = $ShipToData['City'];
        $from['ShipFrom_StateProvinceCode'] = $ShipToData['StateProvinceCode'];
        $from['ShipFrom_PostalCode'] = $ShipToData['PostalCode'];
        $from['ShipFrom_CountryCode'] = $ShipToData['CountryCode'];
        $from['ShipFrom_Number'] = $ShipToData['Number'];
        $this->load->library('UpsShipping');
        $this->upsshipping->addField('selected_code', $lowest_rate_service_code);
        $this->upsshipping->addField('ShipTo_Name', $from['ShipTo_Name']);
        $this->upsshipping->addField('ShipTo_AddressLine', array(
            $from['ShipTo_AddressLine']));
        $this->upsshipping->addField('ShipTo_City', $from['ShipTo_City']);
        $this->upsshipping->addField('ShipTo_StateProvinceCode', $from['ShipTo_StateProvinceCode']);
        $this->upsshipping->addField('ShipTo_PostalCode', $from['ShipTo_PostalCode']);
        $this->upsshipping->addField('ShipTo_CountryCode', $from['ShipTo_CountryCode']);
        $this->upsshipping->addField('ShipTo_Number', $from['ShipTo_Number']);
        $this->upsshipping->addField('Service_Code', $from['Service_Code']);

        $this->upsshipping->addField('ShipFrom_Name', $from['ShipFrom_Name']);
        $this->upsshipping->addField('ShipFrom_AddressLine',
            array($from['ShipFrom_AddressLine']));
        $this->upsshipping->addField('ShipFrom_City', $from['ShipFrom_City']);
        $this->upsshipping->addField('ShipFrom_StateProvinceCode', $from['ShipFrom_StateProvinceCode']);
        $this->upsshipping->addField('ShipFrom_PostalCode', $from['ShipFrom_PostalCode']);
        $this->upsshipping->addField('ShipFrom_CountryCode', $from['ShipFrom_CountryCode']);
        $this->upsshipping->addField('ShipFrom_Number', $from['ShipFrom_Number']);
        $dimensions[0]['Length'] = $productData['Length'];
        $dimensions[0]['Width'] = $productData['Width'];
        $dimensions[0]['Height'] = $productData['Height'];
        $dimensions[0]['Weight'] = $productData['Weight'];
        $dimensions[0]['Qty'] = $productData['Qty'];
        $this->upsshipping->addField('dimensions', $dimensions);
        list($response, $status) = $this->upsshipping->processShipAccept();
        $ups_response = json_decode($response);
        echo '<pre>';
        print_r($ups_response);
        echo '</pre>';
        exit;

        if (isset($ups_response->ShipmentResponse->ShipmentResults->ShipmentIdentificationNumber) && isset($ups_response->ShipmentResponse->ShipmentResults->ShipmentCharges->TotalCharges->MonetaryValue) && isset($ups_response->ShipmentResponse->ShipmentResults->PackageResults->ShippingLabel->GraphicImage)) {

            $this->load->helper('string');
            $this->load->helper('text');
            $this->load->helper('file');
            $time = time();
            $path = APPPATH . 'uploads/ups/shipping/';
            // $graphic_image_file = $path.'graphic';
            // $html_image_file = $path.'html';
            // Response:
            $track_number = $ups_response->ShipmentResponse->ShipmentResults->ShipmentIdentificationNumber;
            $total_charges = $ups_response->ShipmentResponse->ShipmentResults->ShipmentCharges->TotalCharges->MonetaryValue;
            $graphic_image = $ups_response->ShipmentResponse->ShipmentResults->PackageResults->ShippingLabel->GraphicImage;
            // $html_image = $ups_response->ShipmentResponse->ShipmentResults->PackageResults->ShippingLabel->HTMLImage;
            $string = $graphic_image;
            $im = base64_decode($string);
            $size = getImageSizeFromString($im);
            $ext = substr($size['mime'], 6);
            if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
                die('Base64 value is not a valid image');
            }
            if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
                die('Unsupported image type');
            }
            $img_file = $path . $time . ".$ext";
            $image_name = $time . ".$ext";
            $rotate_image_name = $time . "mmao.$ext";
            $this->shipping_label_file_name = $rotate_image_name;
          //  file_put_contents($img_file, $im);

            $tokenupdate = $this->productmodel->updatecustomerinfo($postdata);

            $string = 'PO: ' . $tokenupdate;

            $original_file = APPPATH . 'uploads/ups/shipping/' . $image_name;
            $destination_file = APPPATH . 'uploads/ups/shipping/' . $rotate_image_name;
            $original_image = imagecreatefromgif($original_file);
            $rotated_image = imagerotate($original_image, -90, 0);
            $textColor = imagecolorallocate($original_image, 0, 0, 0);
            $font = '/var/www/html/assets/font/ArialMT.ttf';
            $black = imagecolorallocate($rotated_image, 0, 0, 0);
            //imagestring($rotated_image, 12, 10, 1050, $string, $textColor);
            imagettftext($rotated_image, 20, 0, 10, 1080, $black, $font, $string);
            imagegif($rotated_image, $destination_file);

            // imagegif($rotated_image, $destination_file);
            imagedestroy($original_image);
            imagedestroy($rotated_image);
            //$tokenupdate = $this->productmodel->updatecustomerinfo($postdata);

            $data = [
                'quote_id' => $tokenupdate,
                'tracking_number' => $track_number,
                'total_charges' => $total_charges,
                'shipping_image' => $rotate_image_name,
                'service_options_charges' => '',
                'transportation_charges' => '',
                'unit_of_measurement' => '',
                'weight' => '',
                'country_code' => 'US',
                'shipping_method' => '01',
            ];
            $this->load->model("Shippinglabel_model");
            $shipping_label_id = $this->Shippinglabel_model->add_new_shipping($data);
            // echo $shipping_label_id;
            //echo 'success';

        } else {
            $mes['error'] = $ups_response->Fault->detail->Errors->ErrorDetail->PrimaryErrorCode->Description;
            // $mes['error'] = $ups_response;
            echo json_encode($mes);
            exit;
        }
    }

    public function get_product_weight_in_pound($product_weight)
    {
        if (empty($product_weight)) {
            $mes['error'] = 'This product has not added weight you can contact the website administrator.';
            echo json_encode($mes);
            exit;

        }
        $pweight = explode(" ", $product_weight);
        $weight = $pweight[0];
        $unit = $pweight[1];
        if ($unit == 'LBS') {
            return $weight;
        } else if ($unit == 'Ounces') {
            return $weight / 16;
        } else if ($unit == 'KGS') {
            return $weight * 2.205;
        } else if ($unit == 'Grams') {
            return $weight / 454;
        } else if ($unit == 'Tonnes') {
            return $weight * 2205;
        }
    }

    public function thankyoupage()
    {
        $token = $this->input->post('token');
        $customer_id = $this->input->post('customer_id');

        // $token = 'rKseNPYQt4wmRWjE0D9AZ5fJ7OUyIH2qx3hzoTSuXdVlnBa6b8';
        // $customer_id = '190314';
        // $token = 'uYtzgpolE7GFhJBXRVw4jy2P5QdkaILbUAWfemHMKs6q0SNiZx';
        $tokenupdate = $this->productmodel->thankyoupagedisplay($token, $customer_id);
        $seraiproductlhtml['formhtml'] = $tokenupdate;
        echo json_encode($seraiproductlhtml);
    }

    public function logintommo()
    {
        $customer_id = $this->input->post('customer_id');
        $url = $this->input->post('url');

        $tokenupdate = $this->productmodel->logintommo($customer_id, $url);
        //  $seraiproductlhtml['formhtml'] = $tokenupdate;
        echo json_encode($tokenupdate);
    }
}

