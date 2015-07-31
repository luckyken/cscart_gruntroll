<?php
use Tygh\Registry;


if (!defined('BOOTSTRAP')) { die('Access denied'); }

// Cart is empty, create it
if (empty($_SESSION['cart'])) {
	fn_clear_cart($_SESSION['cart']);
}
	
$cart = & $_SESSION['cart'];

if ($mode == 'check_mil_id') {
	if (defined('AJAX_REQUEST')) {
		$mil_dob = $_REQUEST['mil_dob'];
		$mil_name = $_REQUEST['mil_name'];
		$mil_ssn = (isset($_REQUEST['mil_ssn']) ? $_REQUEST['mil_ssn'] : '');
		$gruntroll = new gruntroll($mil_dob, $mil_name, $mil_ssn);
		
		$result = $gruntroll->gr_mil_id_request();
		
		if($result){
			//give discount
			//create coupon code
			$mil_code = $gruntroll->gr_create_code();

			$mil_promo_id = $gruntroll->gr_get_promo_id();
			
			//add code to promotion in db
			$gruntroll->gr_modify_promo_code($mil_promo_id, $mil_code, 'add');
			
  			unset($_SESSION['promotion_notices']);
			$cart['pending_coupon'] = $mil_code;
			$cart['recalculate'] = true;
			
			if (!empty($cart['chosen_shipping'])) {
				$cart['calculate_shipping'] = true;
			}
			
		} else {
			//set error and return to checkout
			fn_set_notification('W', __('mil_stat_warn_title'), __('mil_stat_warn_message'));

		}

	}
}
