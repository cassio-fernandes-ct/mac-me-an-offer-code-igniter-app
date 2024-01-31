<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class UpsRating {
	private $CI;
	private $fields = array();
	public function __construct() {
        $this->CI =& get_instance();
    }
	public function addField($field, $value) {
		$this->fields[$field] = $value;
	}
	public function processRate() {
		try {
			$rateData = $this->getProcessRate();
			$rateData = json_encode( $rateData );
			
			/* Curl start to call UPS rating API */
			$this->CI->load->model('admin/settingmodel');
		 
			$settingData = $this->CI->settingmodel->getSettingData('1');
			
			// $version = "v1";
			// $requestoption = "Shop";
			$ch = curl_init($this->CI->config->item('ups')['urls'][$settingData['ups_environment']]['rating']);
			// $ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POST, 1);

			curl_setopt($ch, CURLOPT_POSTFIELDS, $rateData);
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
			log_message( 'debug', 'FX UpsRatingRequest: ' . print_r($res, true) );
			
			if( is_string( $res ) ) {
				$resObject = json_decode( $res );
			}
			if( isset( $resObject->Fault ) && !empty( $resObject->Fault ) ) {
				log_message( 'debug', 'FX UPS Rating 403: ' . print_r($res, true) );
				return array( $res, 403 );
			} else if( isset( $resObject->RateResponse ) && !empty( $resObject->RateResponse ) ) {
				log_message( 'debug', 'FX UPS Rating 200: ' . print_r($res, true) );
				return array( $res, 200 );
			}
		}
		catch(Exception $ex) {
			return array( $ex, 403 );
		}
	}
	private function getProcessRate() {
		$this->CI->load->model('admin/settingmodel');
		 
		$settingData = $this->CI->settingmodel->getSettingData('1');
		$userNameToken['Username'] =  $settingData['ups_user_id'];
		$userNameToken['Password'] = $settingData['ups_password'];
		$UPSSecurity['UsernameToken'] = $userNameToken;
		$accessLicenseNumber['AccessLicenseNumber'] = $settingData['ups_access_key'];
		$UPSSecurity['ServiceAccessToken'] = $accessLicenseNumber;
		$request['UPSSecurity'] = $UPSSecurity;
		
		$option['RequestOption'] = 'Shop';
		$request['RateRequest']['Request'] = $option;

		$pickuptype['Code'] = '01';
		$pickuptype['Description'] = 'Daily Pickup';
		$request['PickupType'] = $pickuptype;

		$customerclassification['Code'] = '01';
		$customerclassification['Description'] = 'Classfication';
		$request['CustomerClassification'] = $customerclassification;
		
		$shipper['Name'] =  $settingData['shipto_name'];
		$shipper['ShipperNumber'] =  $settingData['shipper_number'];

		
		$address['AddressLine'] = $this->CI->config->item('ups')['shipper']['AddressLine'];
		$address['City'] =  $settingData['shipto_city'];
		$address['StateProvinceCode'] =  $settingData['shipto_state'];
		$address['PostalCode'] =  $settingData['shipto_pincode'];
		$address['CountryCode'] = $settingData['shipto_country'];
		$shipper['Address'] = $address;
		$shipment['Shipper'] = $shipper;

		$shipto['Name'] = $this->fields['ShipTo_Name'];
		$addressTo['AddressLine'] = $this->fields['ShipTo_AddressLine'];
		$addressTo['City'] = $this->fields['ShipTo_City'];
		$addressTo['StateProvinceCode'] = $this->fields['ShipTo_StateProvinceCode'];
		$addressTo['PostalCode'] = $this->fields['ShipTo_PostalCode'];



		$addressTo['CountryCode'] = $this->fields['ShipTo_CountryCode'];
		$shipto['Address'] = $addressTo;
		$shipment['ShipTo'] = $shipto;

		$service['Code'] = '03';
		$service['Description'] = 'Service Code';
		$shipment['Service'] = $service;
		$package = array();
		$packaging['Code'] = '02';
		$packaging['Description'] = 'Rate';
		$package['PackagingType'] = $packaging;
		$weight = 0;
		foreach( $this->fields['dimensions'] as $dimension ) {
			$weight = $weight + ($dimension['Weight']*$dimension['Qty']);
		}
		$punit['Code'] = 'LBS';
		$punit['Description'] = 'Pounds';
		$packageweight['Weight'] = "$weight";
		$packageweight['UnitOfMeasurement'] = $punit;
		$package['PackageWeight'] = $packageweight;

		$shipment['Package'] = array( $package );
		$request['RateRequest']['Shipment'] = $shipment;
		// echo '<pre>';
		// print_r($request);
		// echo '</pre>';
		log_message( 'debug', 'FX UpsRating: ' . json_encode( $request ) );


		return $request;
	}
}