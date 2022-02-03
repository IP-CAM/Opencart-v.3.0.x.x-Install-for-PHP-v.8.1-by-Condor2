<?php
class ControllerMailTransaction extends Controller {
	// catalog/model/account/customer/addTransaction/after
	public function index(&$route, &$args, &$output) {
		$this->load->language('mail/transaction');

		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getCustomer($args[0]);

		if ($customer_info) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');
				$store_url = $store_info['url'];
			} else {
				$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
				$store_url = HTTP_SERVER;
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($customer_info['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}

			// Load the language for any mails using a different country code and prefixing it so it does not pollute the main data pool.
			$this->language->load($language_code, 'mail', $language_code);
			$this->language->load('mail/transaction', 'mail', $language_code);

			// Add language vars to the template folder
			$results = $this->language->all('mail');

			foreach ($results as $key => $value) {
				$data[$key] = $value;
			}

			$this->load->model('tool/image');

			if (is_file(DIR_IMAGE . html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8'))) {
				$data['logo'] = $this->model_tool_image->resize(html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8'), $this->config->get('theme_default_image_location_width'), $this->config->get('theme_default_image_cart_height'));
			} else {
				$data['logo'] = '';
			}

			$data['text_received'] = sprintf($this->language->get('text_received'), $this->config->get('config_name'));
			$data['text_amount'] = $this->language->get('text_amount');
			$data['text_total'] = $this->language->get('text_total');
			
			$data['amount'] = $this->currency->format($args[2], $this->config->get('config_currency'));
			$data['total'] = $this->currency->format($this->model_account_customer->getTransactionTotal($args[0]), $this->config->get('config_currency'));

			$data['store'] = $store_name;
			$data['store_url'] = $store_url;

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject($subject);
			$mail->setHtml($this->load->view('mail/transaction', $data));
			$mail->send();
		}
	}
}