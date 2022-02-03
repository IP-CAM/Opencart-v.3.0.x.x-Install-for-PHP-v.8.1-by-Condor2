<?php
namespace Cache;
class Redis {
	private $expire;
	private $cache;

	public function __construct($expire = 3600) {
		$this->expire = $expire;

		$this->cache = new \Redis();
		$this->cache->pconnect(CACHE_HOSTNAME, CACHE_PORT);
	}

	public function get($key) {
		$data = $this->cache->get(CACHE_PREFIX . $key);

		return json_decode($data, true);
	}

	public function set($key, $value, $expire = 0) {
		if (!$expire) {
			$expire = $this->expire;
		}

		$status = $this->cache->set(CACHE_PREFIX . $key, json_encode($value));

		if ($status) {
			$this->cache->expire(CACHE_PREFIX . $key, $expire);
		}

		return $status;
	}

	public function delete($key) {
		$this->cache->del(CACHE_PREFIX . $key);
	}
}