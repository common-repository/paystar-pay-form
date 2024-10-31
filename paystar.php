<?php

/*
Plugin Name: paystar-pay-form
Plugin URI: https://paystar.ir
Description: paystar-pay-form-desc
Version: 1.0
Author: ماژول بانک
Author URI: https://www.modulebank.ir
Text Domain: paystar-pay-form
Domain Path: /languages
 */


__('paystar-pay-form', 'paystar-pay-form');
__('paystar-pay-form-desc', 'paystar-pay-form');
load_plugin_textdomain('paystar-pay-form', false, dirname(plugin_basename(__FILE__)).'/languages/');

add_action('admin_menu', 'paystar_menu');
add_shortcode('paystar', 'paystar_form');

function paystar_menu()
{
	add_menu_page(__('PayStar Payment Gateway', 'paystar-pay-form'), __('PayStar Payment Gateway', 'paystar-pay-form'), 'manage_options', 'paystar/setting.php', 'paystar_menupage', esc_url(plugin_dir_url( __FILE__ )).'images/logo.png');
	add_submenu_page('paystar/setting.php', __('PayStar Setting', 'paystar-pay-form'), __('PayStar Setting', 'paystar-pay-form'), 'manage_options', 'paystar/setting.php', 'paystar_menupage');
}

function paystar_menupage()
{
	if (current_user_can('manage_options'))
	{
		include_once(dirname(__FILE__).'/templates/setting.php');
	}
	else
	{
		wp_die(__('You do not have sufficient permissions to access this page.', 'paystar-pay-form'));
	}
}

function paystar_form()
{
	ob_start();
	$current_user = wp_get_current_user();
	if (isset($_POST['submit_payment_paystar']) && $_POST['submit_payment_paystar'])
	{
		$payer_name = sanitize_text_field($_POST['payer_name']);
		$payer_email = sanitize_text_field($_POST['payer_email']);
		$payer_mobile = sanitize_text_field($_POST['payer_mobile']);
		$payer_price = sanitize_text_field($_POST['payer_price']);
		$description_payment = sanitize_text_field($_POST['description_payment']);
		$callback_url = esc_url_raw('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
		if($payer_name && $payer_email && $payer_mobile && $payer_price && $description_payment)
		{
			require_once(dirname(__FILE__) . '/paystar_payment_helper.class.php');
			$p = new PayStar_Payment_Helper(get_option('terminal'));
			$r = $p->paymentRequest(array(
					'amount'      => intval(ceil($payer_price)),
					'order_id'    => time(),
					'name'        => $payer_name,
					'mail'        => $payer_email,
					'phone'       => $payer_mobile,
					'description' => $description_payment,
					'callback'    => $callback_url,
				));
			if ($r)
			{
				add_option('paystar_price_' . $r, $payer_price);
				update_option('paystar_price_' . $r, $payer_price);
				session_write_close();
				echo '<form name="frmPayStarPayment" method="post" action="https://core.paystar.ir/api/pardakht/payment"><input type="hidden" name="token" value="'.esc_html($p->data->token).'" />';
				echo '<input class="paystar_btn btn button" type="submit" value="'.__('Pay', 'paystar-pay-form').'" /></form>';
				echo '<script>document.frmPayStarPayment.submit();</script>';
			}
			else
			{
				echo '<p class="error-payment">' . esc_html($p->error) . '</p>';
			}
		}
		else
		{
			echo '<p class="error-payment">' . __('Error! Please Complate all field.', 'paystar-pay-form') . '</p>';
		}
	}
	elseif (isset($_POST['status'],$_POST['order_id'],$_POST['ref_num']))
	{
		$post_status = sanitize_text_field($_POST['status']);
		$post_order_id = sanitize_text_field($_POST['order_id']);
		$post_ref_num = sanitize_text_field($_POST['ref_num']);
		$post_tracking_code = sanitize_text_field($_POST['tracking_code']);
		$amount = get_option('paystar_price_' . $post_ref_num);
		require_once(dirname(__FILE__) . '/paystar_payment_helper.class.php');
		$p = new PayStar_Payment_Helper(get_option('terminal'));
				$r = $p->paymentVerify($x = array(
						'status' => $post_status,
						'order_id' => $post_order_id,
						'ref_num' => $post_ref_num,
						'tracking_code' => $post_tracking_code,
						'amount' => $amount
					));
		if ($r)
		{
			delete_option('paystar_price_' . $post_ref_num);
			echo '<p class="success-payment">' . sprintf(__('Transaction was successful. Your Tracking Number: %s', 'paystar-pay-form'), esc_html($p->txn_id)) . '</p>';
		}
		else
		{
			echo '<p class="error-payment">' . esc_html($p->error) . '</p>';
		}
	}
	wp_enqueue_style('paystar-pay-form-style', esc_url(plugin_dir_url(__FILE__)) . '/styles/style.css');
	include_once(dirname(__FILE__).'/templates/form.php');
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

?>