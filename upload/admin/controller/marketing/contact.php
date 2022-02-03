<?php
class ControllerMarketingContact extends Controller {
	protected $error = array();

	public function index() {
		$this->load->language('marketing/contact');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->config->get('config_editor_default') == 'ckeditor') {
			$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
			$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');
		} else {
			$this->document->addScript('view/javascript/summernote/summernote.min.js');
			if (file_exists('view/javascript/summernote/lang/summernote-'. $this->language->get('summernote') .'.min.js')) {
			$this->document->addScript('view/javascript/summernote/lang/summernote-'. $this->language->get('summernote') .'.min.js');
			}
			$this->document->addScript('view/javascript/summernote/summernote-image-attributes.js');
			if (file_exists('view/javascript/summernote/img-lang/'. $this->language->get('summernote') . '.js')) {
			$this->document->addScript('view/javascript/summernote/img-lang/'. $this->language->get('summernote') . '.js');
            }
			$this->document->addScript('view/javascript/summernote/opencart.js');
			$this->document->addStyle('view/javascript/summernote/summernote.min.css');

			$this->document->addStyle('view/javascript/codemirror/lib/codemirror.css');
			$this->document->addStyle('view/javascript/codemirror/theme/monokai.css');

			$this->document->addScript('view/javascript/codemirror/lib/codemirror.js');
			$this->document->addScript('view/javascript/codemirror/lib/xml.js');
			$this->document->addScript('view/javascript/codemirror/lib/formatting.js');
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['editor'] = $this->config->get('config_editor_default');

		$data['lang'] = $this->language->get('summernote');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketing/contact', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['cancel'] = $this->url->link('marketing/contact', 'user_token=' . $this->session->data['user_token'], true);

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/contact', $data));
	}

	public function send() {
		$this->load->language('marketing/contact');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'marketing/contact')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}

			if (!$this->request->post['subject']) {
				$json['error']['subject'] = $this->language->get('error_subject');
			}

			if (!$this->request->post['message']) {
				$json['error']['message'] = $this->language->get('error_message');
			}

			if (!$json) {
				$this->load->model('setting/store');
				$this->load->model('setting/setting');
				$this->load->model('customer/customer');
				$this->load->model('sale/order');

				$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);

				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = $this->config->get('config_name');
				}

				$setting = $this->model_setting_setting->getSetting('config', $this->request->post['store_id']);

				$store_email = isset($setting['config_email']) ? $setting['config_email'] : $this->config->get('config_email');

				if (isset($this->request->get['page'])) {
					$page = (int)$this->request->get['page'];
				} else {
					$page = 1;
				}

				$email_total = 0;

				$emails = array();

				switch ($this->request->post['to']) {
					case 'newsletter':
						$customer_data = array(
							'filter_newsletter' => 1,
							'start'             => ($page - 1) * 10,
							'limit'             => 10
						);

						$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);

						$results = $this->model_customer_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						break;
					case 'customer_all':
						$customer_data = array(
							'start' => ($page - 1) * 10,
							'limit' => 10
						);

						$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);

						$results = $this->model_customer_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						break;
					case 'customer_group':
						$customer_data = array(
							'filter_customer_group_id' => $this->request->post['customer_group_id'],
							'start'                    => ($page - 1) * 10,
							'limit'                    => 10
						);

						$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);

						$results = $this->model_customer_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[$result['customer_id']] = $result['email'];
						}
						break;
					case 'customer':
						if (!empty($this->request->post['customer'])) {
							$email_total = count($this->request->post['customer']);

							$customers = array_slice($this->request->post['customer'], ($page - 1) * 10, 10);

							foreach ($customers as $customer_id) {
								$customer_info = $this->model_customer_customer->getCustomer($customer_id);

								if ($customer_info) {
									$emails[] = $customer_info['email'];
								}
							}
						}
						break;
					case 'affiliate_all':
						$affiliate_data = array(
							'filter_affiliate' => 1,
							'start'            => ($page - 1) * 10,
							'limit'            => 10
						);

						$email_total = $this->model_customer_customer->getTotalCustomers($affiliate_data);

						$results = $this->model_customer_customer->getCustomers($affiliate_data);

						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						break;
					case 'affiliate':
						if (!empty($this->request->post['affiliate'])) {
							$affiliates = array_slice($this->request->post['affiliate'], ($page - 1) * 10, 10);

                            foreach ($affiliates as $affiliate_id) {
                                $affiliate_info = $this->model_customer_customer->getCustomer($affiliate_id);

                                if ($affiliate_info) {
                                    $emails[] = $affiliate_info['email'];
                                }
                            }

						$email_total = count($this->request->post['affiliate']);
						}
						break;
					case 'product':
						if (isset($this->request->post['product'])) {
							$email_total = $this->model_sale_order->getTotalEmailsByProductsOrdered($this->request->post['product']);

							$results = $this->model_sale_order->getEmailsByProductsOrdered($this->request->post['product'], ($page - 1) * 10, 10);

							foreach ($results as $result) {
								$emails[] = $result['email'];
							}
						}
						break;
				}

				if ($emails) {
					$json['success'] = $this->language->get('text_success');

					$start = ($page - 1) * 10;
					$end = $start + 10;

						$json['success'] = sprintf($this->language->get('text_sent'), $start, $email_total);

					if ($end < $email_total) {
						$json['next'] = str_replace('&amp;', '&', $this->url->link('marketing/contact/send', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true));
					} else {
						$json['next'] = '';
					}

					$message  = '<html dir="ltr" lang="' . $this->language->get('code') . '">' . "\n";
					$message .= '  <head>' . "\n";
					$message .= '    <title>' . $this->request->post['subject'] . '</title>' . "\n";
					$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
					$message .= '  </head>' . "\n";
					$message .= '  <body>' . html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
					$message .= '</html>' . "\n";

					$mail = new Mail($this->config->get('config_mail_engine'));
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

					foreach ($emails as $email) {
						if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$mail->setTo(trim($email));
							$mail->setFrom($store_email);
							$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($message);
							$mail->send();
						}
					}
				} else {
					$json['error']['email'] = $this->language->get('error_email');
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}