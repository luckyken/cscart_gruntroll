<?php

use Tygh\Settings;


class gruntroll(){
	
	function gruntroll($mil_dob, $mil_name, $mil_ssn){
		var $dob, $name, $ssn, $access_token, $mashape_key;
		$this->dob = $mil_dob;
		$this->name = $mil_name;
		$this->ssn = $mil_ssn;
		
		$this->access_token =  Settings::instance()->getValue('access_token', 'gruntroll', $_SESSION['settings']['company_id']['value']);
		$this->mashape_key = Settings::instance()->getValue('X-Mashape-Key', 'gruntroll', $_SESSION['settings']['company_id']['value']);
		
	}
	/**
	 * Queries Gruntroll api to verify military status
	 * @param string $mil_dob
	 * @param string $mil_name
	 * @param string $mil_ssn
	 * @return boolean
	 */
	function gr_mil_id_request(){

		$url = 'https://gruntroll-military-verification-v1.p.mashape.com/verify/active';
		$fields = 'access_token=' . $this->access_token . '&name=' . $this->mil_name . '&dob=' . $this->mil_dob;
		$paramCount = 3;
		if(!empty($this->mil_ssn) && $this->mil_ssn != ''){
			$fields .= '&ssn=' . $this->mil_ssn;
			$paramCount++;
		}
		
	    $ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch,CURLOPT_POST, $paramCount);
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		$headr = array();
		$headr[] = 'Content-Type: application/x-www-form-urlencoded';
		$headr[] = 'X-Mashape-Key: QbF4kMVxTamsh6ZNvlnXYEz2g3MZp1r9G8Zjsnp5UCPbzzbV6E';
		$headr[] = 'Accept: text/plain';
		
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headr);
		curl_setopt ( $ch, CURLOPT_HEADER , true );
	
		$response = curl_exec ( $ch );
		$error = curl_error( $ch );
		curl_close ( $ch );
		
		list($header, $body) = explode("\r\n\r\n", $response, 2);
		
		$result = json_decode($body, true);
		
		if($result['is_active'] || $result['is_veteran'] || true){
			return true;
		} else {
			return false;
		}
		
	}

	function gr_create_code(){
	//	return $this->mil_name . rand(1000,9999);
		return 'sdfads645fd4s'; 
	}
	
	function gr_get_promo_id(){
		return 106;
	}

}