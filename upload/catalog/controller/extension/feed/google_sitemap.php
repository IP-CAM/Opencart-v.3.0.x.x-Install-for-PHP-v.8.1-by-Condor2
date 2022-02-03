<?php
class ControllerExtensionFeedGoogleSitemap extends Controller {
	public function index() {
		if ($this->config->get('feed_google_sitemap_status')) {
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="https://www.google.com/schemas/sitemap-image/1.1">';

			$this->load->model('catalog/product');
			$this->load->model('tool/image');

			$products = $this->model_catalog_product->getProducts();

			foreach ($products as $product) {
				$output .= '<url>';
				$output .= '  <loc>' . htmlspecialchars($this->url->link('product/product', 'product_id=' . $product['product_id']), ENT_COMPAT | ENT_XML1) . '</loc>';
				$output .= '  <changefreq>weekly</changefreq>';
				$output .= '  <lastmod>' . date('Y-m-d\TH:i:sP', strtotime($product['date_modified'])) . '</lastmod>';
				$output .= '  <priority>1.0</priority>';

				if ($product['image']) {
					$output .= '  <image:image>';
					$output .= '  <image:loc>' . htmlspecialchars($this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')), ENT_COMPAT | ENT_XML1) . '</image:loc>';
					$output .= '  <image:caption>' . htmlspecialchars($product['name'], ENT_COMPAT | ENT_XML1) . '</image:caption>';
					$output .= '  <image:title>' . htmlspecialchars($product['name'], ENT_COMPAT | ENT_XML1) . '</image:title>';
					$output .= '  </image:image>';
				}

				$output .= '</url>';
			}

			$this->load->model('catalog/category');

			$output .= $this->getCategories(0);

			$this->load->model('catalog/manufacturer');

			$manufacturers = $this->model_catalog_manufacturer->getManufacturers();

			foreach ($manufacturers as $manufacturer) {
				$output .= '<url>';
				$output .= '  <loc>' . htmlspecialchars($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']), ENT_COMPAT | ENT_XML1) . '</loc>';
				$output .= '  <changefreq>weekly</changefreq>';
				$output .= '  <priority>0.7</priority>';
				$output .= '</url>';
			}

			$this->load->model('catalog/information');

			$informations = $this->model_catalog_information->getInformations();

			foreach ($informations as $information) {
				$output .= '<url>';
				$output .= '  <loc>' . htmlspecialchars($this->url->link('information/information', 'information_id=' . $information['information_id']), ENT_COMPAT | ENT_XML1) . '</loc>';
				$output .= '  <changefreq>weekly</changefreq>';
				$output .= '  <priority>0.5</priority>';
				$output .= '</url>';
			}

			$output .= '</urlset>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		}
	}

	protected function getCategories($parent_id) {
		$output = '';

		$results = $this->model_catalog_category->getCategories($parent_id);

		foreach ($results as $result) {
			$output .= '<url>';
			$output .= '  <loc>' . htmlspecialchars($this->url->link('product/category', 'path=' . $result['category_id']), ENT_COMPAT | ENT_XML1) . '</loc>';
			$output .= '  <changefreq>weekly</changefreq>';
			$output .= '  <priority>0.7</priority>';
			$output .= '</url>';

			$output .= $this->getCategories($result['category_id']);
		}

		return $output;
	}
}