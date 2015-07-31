<?php

use Tygh\Settings;


class gruntroll{
	var $dob, $name, $ssn, $access_token, $mashape_key;
	
	function gruntroll($mil_dob, $mil_name, $mil_ssn){	
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
		//return true;
		$url = 'https://gruntroll-military-verification-v1.p.mashape.com/verify/active';		
		$fields = 'access_token=' . $this->access_token . '&name=' . $this->name;
		$paramCount = 3;

		if(preg_match('/([0-9]{3})-([0-9]{2})-([0-9]{4})/', $this->ssn)){
			$fields .= '&ssn=' . $this->ssn;
			$paramCount++;
		} elseif($this->valid_dob()) {
			$fields .= '&dob=' . $this->dob;
		} else {
			return false;
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
		
		if($result['is_active'] || $result['is_veteran']){
			return true;
		} else {
			return false;
		}
		
	}

	function gr_create_code(){
		return strtolower($this->name) . rand(10000,99999);
	}
	
	function gr_get_promo_id(){
		return 106;
	}

	function gr_modify_promo_code($mil_promo_id, $mil_code, $action = 'remove'){
		
		$promotion_data = fn_get_promotion_data($mil_promo_id);
		
		fn_promotion_update_condition($promotion_data['conditions']['conditions'], $action, 'auto_coupons', $mil_code);
		
		db_query("UPDATE ?:promotions SET conditions = ?s, conditions_hash = ?s, users_conditions_hash = ?s WHERE promotion_id = ?i", serialize($promotion_data['conditions']), fn_promotion_serialize($promotion_data['conditions']['conditions']), fn_promotion_serialize_users_conditions($promotion_data['conditions']['conditions']), $mil_promo_id);
		
	}
	
	function valid_dob(){
		if(preg_match('#([0-9]{2})/([0-9]{2})/([0-9]{4})#', $this->dob)){
			try{
				$date = new DateTime($this->dob);
			} catch (Exception $e) {
				return false;
			}
			return checkdate($date->format('m'), $date->format('d'), $date->format('Y'));
		}
		return false;
	}

}