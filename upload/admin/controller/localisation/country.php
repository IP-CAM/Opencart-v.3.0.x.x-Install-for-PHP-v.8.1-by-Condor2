<?php
class ControllerLocalisationCountry extends Controller {
	protected $error = array();

	public function index() {
		$this->load->language('localisation/country');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/country');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/country');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/country');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_country->addCountry($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_iso_code_2'])) {
				$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_iso_code_3'])) {
				$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/country');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/country');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_country->editCountry($this->request->get['country_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_iso_code_2'])) {
				$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_iso_code_3'])) {
				$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/country');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/country');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $country_id) {
				$this->model_localisation_country->deleteCountry($country_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_iso_code_2'])) {
				$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_iso_code_3'])) {
				$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	public function enable() {
		$this->load->language('localisation/country');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/country');

		if (isset($this->request->post['selected']) && $this->validateEnable()) {
			foreach ($this->request->post['selected'] as $country_id) {
				$this->model_localisation_country->enableCountry($country_id);
			}

			$this->session->data['success'] = $this->language->get('text_enable');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_iso_code_2'])) {
				$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_iso_code_3'])) {
				$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	public function disable() {
		$this->load->language('localisation/country');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/country');

		if (isset($this->request->post['selected']) && $this->validateDisable()) {
			foreach ($this->request->post['selected'] as $country_id) {
				$this->model_localisation_country->disableCountry($country_id);
			}

			$this->session->data['success'] = $this->language->get('text_disable');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_iso_code_2'])) {
				$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_iso_code_3'])) {
				$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = (string)$this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_iso_code_2'])) {
			$filter_iso_code_2 = (string)$this->request->get['filter_iso_code_2'];
		} else {
			$filter_iso_code_2 = '';
		}

		if (isset($this->request->get['filter_iso_code_3'])) {
			$filter_iso_code_3 = (string)$this->request->get['filter_iso_code_3'];
		} else {
			$filter_iso_code_3 = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_2'])) {
			$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_3'])) {
			$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('localisation/country/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('localisation/country/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['enable'] = $this->url->link('localisation/country/enable', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['disable'] = $this->url->link('localisation/country/disable', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['countries'] = array();

		$filter_data = array(
			'filter_name'       => $filter_name,
			'filter_iso_code_2' => $filter_iso_code_2,
			'filter_iso_code_3' => $filter_iso_code_3,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$country_total = $this->model_localisation_country->getTotalCountries($filter_data);

		$results = $this->model_localisation_country->getCountries($filter_data);

		foreach ($results as $result) {
			$data['countries'][] = array(
				'country_id' => $result['country_id'],
				'name'       => $result['name'] . (($result['country_id'] == $this->config->get('config_country_id')) ? $this->language->get('text_default') : null),
				'iso_code_2' => $result['iso_code_2'],
				'iso_code_3' => $result['iso_code_3'],
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'edit'       => $this->url->link('localisation/country/edit', 'user_token=' . $this->session->data['user_token'] . '&country_id=' . $result['country_id'] . $url, true)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_2'])) {
			$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_3'])) {
			$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
		$data['sort_iso_code_2'] = $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . '&sort=iso_code_2' . $url, true);
		$data['sort_iso_code_3'] = $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . '&sort=iso_code_3' . $url, true);
		$data['sort_status'] = $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_2'])) {
			$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_3'])) {
			$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $country_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($country_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($country_total - $this->config->get('config_limit_admin'))) ? $country_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $country_total, ceil($country_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_iso_code_2'] = $filter_iso_code_2;
		$data['filter_iso_code_3'] = $filter_iso_code_3;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/country_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = (!isset($this->request->get['country_id']) ? $this->language->get('text_add') : $this->language->get('text_edit'));

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_2'])) {
			$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_3'])) {
			$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['country_id'])) {
			$data['action'] = $this->url->link('localisation/country/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('localisation/country/edit', 'user_token=' . $this->session->data['user_token'] . '&country_id=' . $this->request->get['country_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['country_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($country_info)) {
			$data['name'] = $country_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['iso_code_2'])) {
			$data['iso_code_2'] = $this->request->post['iso_code_2'];
		} elseif (!empty($country_info)) {
			$data['iso_code_2'] = $country_info['iso_code_2'];
		} else {
			$data['iso_code_2'] = '';
		}

		if (isset($this->request->post['iso_code_3'])) {
			$data['iso_code_3'] = $this->request->post['iso_code_3'];
		} elseif (!empty($country_info)) {
			$data['iso_code_3'] = $country_info['iso_code_3'];
		} else {
			$data['iso_code_3'] = '';
		}

		if (isset($this->request->post['address_format'])) {
			$data['address_format'] = $this->request->post['address_format'];
		} elseif (!empty($country_info)) {
			$data['address_format'] = $country_info['address_format'];
		} else {
			$data['address_format'] = '';
		}

		if (isset($this->request->post['postcode_required'])) {
			$data['postcode_required'] = $this->request->post['postcode_required'];
		} elseif (!empty($country_info)) {
			$data['postcode_required'] = $country_info['postcode_required'];
		} else {
			$data['postcode_required'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($country_info)) {
			$data['status'] = $country_info['status'];
		} else {
			$data['status'] = '1';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/country_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/country')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 128)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/country')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		$this->load->model('customer/customer');
		$this->load->model('localisation/zone');
		$this->load->model('localisation/geo_zone');

		foreach ($this->request->post['selected'] as $country_id) {
			if ($this->config->get('config_country_id') == $country_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			$store_total = $this->model_setting_store->getTotalStoresByCountryId($country_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}

			$address_total = $this->model_customer_customer->getTotalAddressesByCountryId($country_id);

			if ($address_total) {
				$this->error['warning'] = sprintf($this->language->get('error_address'), $address_total);
			}

			$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId($country_id);

			if ($zone_total) {
				$this->error['warning'] = sprintf($this->language->get('error_zone'), $zone_total);
			}

			$zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByCountryId($country_id);

			if ($zone_to_geo_zone_total) {
				$this->error['warning'] = sprintf($this->language->get('error_zone_to_geo_zone'), $zone_to_geo_zone_total);
			}
		}

		return !$this->error;
	}

	protected function validateEnable() {
		if (!$this->user->hasPermission('modify', 'localisation/country')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateDisable() {
		if (!$this->user->hasPermission('modify', 'localisation/country')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}