<?php
class ControllerMailReturn extends Controller {
	// admin/model/sale/return/addReturnHistory/after
	public function index($route, $args, $output) {
		if (isset($args[0])) {
			$return_id = $args[0];
		} else {
			$return_id = '';
		}
		
		if (isset($args[1])) {
			$return_status_id = $args[1];
		} else {
			$return_status_id = '';
		}		
		
		if (isset($args[2])) {
			$comment = $args[2];
		} else {
			$comment = '';
		}
		
		if (isset($args[3])) {
			$notify = $args[3];
		} else {
			$notify = '';
		}		
		
		if ($notify) {
			$this->load->model('sale/return');
			
			$return_info = $this->model_sale_return->getReturn($return_id);
			
			if ($return_info) {
				$this->load->model('sale/order');
				$this->load->language('mail/return');

				$order_info = $this->model_sale_order->getOrder($return_info['order_id']);

				if ($order_info) {
					$store_name = html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8');
					$store_url = $order_info['store_url'];
				} else {
					$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
					$store_url = HTTP_CATALOG;
				}

				$subject = sprintf($this->language->get('text_subject'), $store_name, $return_id);

				$data['return_id'] = $return_id;
				$data['date_added'] = date($this->language->get('date_format_short'), strtotime($return_info['date_modified']));
				$data['return_status'] = $return_info['return_status'];
				$data['comment'] = strip_tags(html_entity_decode($comment, ENT_QUOTES, 'UTF-8'));

				$data['store'] = $store_name;
				$data['store_url'] = $store_url;

				$mail = new Mail($this->config->get('config_mail_engine'));
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($return_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($store_name);
				$mail->setSubject($subject);
				$mail->setHtml($this->load->view('mail/return', $data));
				$mail->send();
			}
		}
	}
}