<?php
class ControllerEventStatistics extends Controller {
	// catalog/model/catalog/review/addReview/after
	public function addReview(&$route, &$args, &$output) {
		$this->load->model('report/statistics');

		$this->model_report_statistics->addValue('review', 1);
	}

	// catalog/model/account/return/addReturn/after
	public function addReturn(&$route, &$args, &$output) {
		$this->load->model('report/statistics');

		$this->model_report_statistics->addValue('return', 1);
	}

	// catalog/model/checkout/order/addOrderHistory/before
	public function addOrderHistory(&$route, &$args) {
		$this->load->model('checkout/order');
				
		$order_info = $this->model_checkout_order->getOrder($args[0]);

		if ($order_info) {
			$this->load->model('report/statistics');

			$old_status_id = $order_info['order_status_id'];
			$new_status_id = $args[1];

			$processing_status = (array)$this->config->get('config_processing_status');
			$complete_status = (array)$this->config->get('config_complete_status');

			$active_status = array_merge($processing_status, $complete_status);

			// If order status in complete or processing add value to sale total
			if (in_array($new_status_id, $active_status) && !in_array($old_status_id, $active_status)) {
				$this->model_report_statistics->addValue('order_sale', $order_info['total']);
			}
			
			// If order status not in complete or processing remove value to sale total
			if (!in_array($new_status_id, $active_status) && in_array($old_status_id, $active_status)) {
				$this->model_report_statistics->removeValue('order_sale', $order_info['total']);
			}

			// Add to processing status if new status is in the array
			if (in_array($new_status_id, $processing_status) && !in_array($old_status_id, $processing_status)) {
				$this->model_report_statistics->addValue('order_processing', 1);
			}

			// Remove from processing status if new status is not array and old status is
			if (!in_array($new_status_id, $processing_status) && in_array($old_status_id, $processing_status)) {
				$this->model_report_statistics->removeValue('order_processing', 1);
			}

			// Add to complete status if new status is not array
			if (in_array($new_status_id, $complete_status) && !in_array($old_status_id, $complete_status)) {
				$this->model_report_statistics->addValue('order_complete', 1);
			}

			// Remove from complete status if new status is not array
			if (!in_array($new_status_id, $complete_status) && in_array($old_status_id, $complete_status)) {
				$this->model_report_statistics->removeValue('order_complete', 1);
			}
		}
	}
}