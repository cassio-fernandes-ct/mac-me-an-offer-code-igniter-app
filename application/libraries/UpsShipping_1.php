<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class UpsShipping {
	private $CI;
	private $fields = array();
	public function __construct() {
        $this->CI =& get_instance();
    }
	public function addField($field, $value) {
		$this->fields[$field] = $value;
	}
	public function processShipAccept() {
		try {
			$shipmentData = $this->getProcessShipAccept();
			$shipmentData = json_encode( $shipmentData );

			/* Curl start to call UPS shipping API */
			$this->CI->load->model('admin/settingmodel');
		 
			$settingData = $this->CI->settingmodel->getSettingData('1');
			   ;
			$ch = curl_init($this->CI->config->item('ups')['urls'][$settingData['ups_environment']]['shipping']);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $shipmentData);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			if ( !($res = curl_exec($ch)) ) {
				die(date('[Y-m-d H:i e] '). "Got " . curl_error($ch) . " when processing data");
				curl_close($ch);
				exit;
			}
			curl_close($ch);
			/* Curl End */
			
			if( is_string( $res ) ) {
				$resObject = json_decode( $res );
			}
			if( isset( $resObject->Fault ) && !empty( $resObject->Fault ) ) {
				return array( $res, 403 );
			} else if( isset( $resObject->ShipmentResponse ) && !empty( $resObject->ShipmentResponse ) ) {
				return array( $res, 200 );
			}
		}
		catch(Exception $ex) {
			return array( $ex, 403 );
		}
	}
	public function getProcessShipAccept() {
		$this->CI->load->model('admin/settingmodel');
		$settingData = $this->CI->settingmodel->getSettingData('1');

		$userNameToken['Username'] =  $settingData['ups_user_id'];
		$userNameToken['Password'] = $settingData['ups_password'];
		$UPSSecurity['UsernameToken'] = $userNameToken;
		$accessLicenseNumber['AccessLicenseNumber'] = $settingData['ups_access_key'];
		$UPSSecurity['ServiceAccessToken'] = $accessLicenseNumber;
		$request['UPSSecurity'] = $UPSSecurity;


		/* Important "validate", */
		$requestoption['RequestOption'] = 'validate';
		$request['ShipmentRequest']['Request'] = $requestoption;


		/* This is From Address (Set Customre Address Here) */
		$shipment['Description'] = '';
		$shipper['Name'] = $this->fields['ShipFrom_Name'];
		$shipper['AttentionName'] = $this->fields['ShipFrom_Name'];
		$shipper['ShipperNumber'] = '8A9Y19';
		$address['AddressLine'] = $this->fields['ShipFrom_AddressLine'];
		$address['City'] = $this->fields['ShipFrom_City'];
		$address['StateProvinceCode'] = $this->fields['ShipFrom_StateProvinceCode'];
		$address['PostalCode'] = $this->fields['ShipFrom_PostalCode'];
		$address['CountryCode'] = $this->fields['ShipFrom_CountryCode'];
		$shipper['Address'] = $address;
		$phone['Number'] =  $this->fields['ShipFrom_Number'];
		$shipper['Phone'] = $phone;
		$shipment['Shipper'] = $shipper;




		/* Ship To Address (Set MMAO Address Here) */
		$shipto['Name'] = $this->fields['ShipTo_Name'];
		$shipto['AttentionName'] = $this->fields['ShipTo_Name'];
		$addressTo['AddressLine'] = $this->fields['ShipTo_AddressLine'];
		$addressTo['City'] = $this->fields['ShipTo_City'];
		$addressTo['StateProvinceCode'] = $this->fields['ShipTo_StateProvinceCode'];
		$addressTo['PostalCode'] = $this->fields['ShipTo_PostalCode'];
		$addressTo['CountryCode'] = $this->fields['ShipTo_CountryCode'];
		$shipto['Address'] = $addressTo;
		$phone2['Number'] = $this->fields['ShipTo_Number'];
		$shipto['Phone'] = $phone2;
		$shipment['ShipTo'] = $shipto;
	 

		/* Ship To Address (Set MMAO Address Here) */
		$shipfrom['Name'] =  $this->fields['ShipFrom_Name'];
		$shipfrom['AttentionName'] = $this->fields['ShipFrom_Name'];
		$addressFrom['AddressLine'] = $this->fields['ShipFrom_AddressLine'];
		$addressFrom['City'] =$this->fields['ShipFrom_City'];
		$addressFrom['StateProvinceCode'] = $this->fields['ShipFrom_StateProvinceCode'];
		$addressFrom['PostalCode'] = $this->fields['ShipFrom_PostalCode'];
		$addressFrom['CountryCode'] =$this->fields['ShipFrom_CountryCode'];
		$shipfrom['Address'] = $addressFrom;
		$phone22['Number'] = $this->fields['ShipFrom_Number'];
		$shipfrom['Phone'] = $phone22;
		$shipment['ShipFrom'] = $shipfrom;
	  

		/* Important */
		$shipmentcharge['Type'] = '01';
		// $creditcard['Type'] = '06';

		// $creditcard['AccountNumber'] ='213';
	

		// $creditcard['Number'] = $this->CI->config->item('ups')['cc']['CC_Number'];

		// $creditcard['SecurityCode'] = $this->CI->config->item('ups')['cc']['CC_SecurityCode'];
		// $creditcard['ExpirationDate'] = $this->CI->config->item('ups')['cc']['CC_ExpirationDate'];
		// $creditCardAddress['AddressLine'] = $this->CI->config->item('ups')['cc']['CC_AddressLine'];
		// $creditCardAddress['City'] = $this->CI->config->item('ups')['cc']['CC_City'];
		// $creditCardAddress['StateProvinceCode'] = $this->CI->config->item('ups')['cc']['CC_StateProvinceCode'];
		// $creditCardAddress['PostalCode'] = $this->CI->config->item('ups')['cc']['CC_PostalCode'];
		// $creditCardAddress['CountryCode'] = $this->CI->config->item('ups')['cc']['CC_CountryCode'];
		// $creditcard['Address'] = $creditCardAddress;
		$billshipper['AccountNumber'] =  $settingData['shipper_number'];
		$shipmentcharge['BillShipper'] = $billshipper;
		$paymentinformation['ShipmentCharge'] = $shipmentcharge;
		$shipment['PaymentInformation'] = $paymentinformation;
		/* Important */
		$service['Code'] = $this->fields['selected_code'];
		$service['Description'] = 'Service Description';
		$shipment['Service'] = $service;

		/* Important */
		$packaging['Code'] = '02';
		$packaging['Description'] = 'Description';
		$package['Description'] = 'Description';
		$package['Packaging'] = $packaging;

		// $UnitOfMeasurement['Code'] = 'LBS';
		// $UnitOfMeasurement['Description'] = 'Pounds';
		// $pdimension['UnitOfMeasurement'] =  $UnitOfMeasurement;
		// $pdimension['Length'] =  '53';
		// $pdimension['Width'] =  '53';
		// $pdimension['Height'] =  '53';
		// $package['Dimensions'] = $pdimension;

		/* Important */
		$package_array = array();
		$weight = 0;
		foreach( $this->fields['dimensions'] as $dimension ) {
			$weight = $weight + $dimension['Weight']*$dimension['Qty'];
		}
		$punit['Code'] = 'LBS';
		$punit['Description'] = 'Pounds';
		$packageweight['Weight'] = "$weight";
		$packageweight['UnitOfMeasurement'] = $punit;
		$package['PackageWeight'] = $packageweight;

		$shipment['Package'] = array( $package );
		
		/* Important */
		$labelimageformat['Code'] = 'GIF';
		$labelimageformat['Description'] = 'GIF';
		$labelspecification['LabelImageFormat'] = $labelimageformat;
		$labelspecification['HTTPUserAgent'] = 'Mozilla/4.5';
		$shipment['LabelSpecification'] = $labelspecification;
		$request['ShipmentRequest']['Shipment'] = $shipment;
// echo '<pre>';
// print_r($request);
// echo '</pre>';
 

		return $request;
	}
}


