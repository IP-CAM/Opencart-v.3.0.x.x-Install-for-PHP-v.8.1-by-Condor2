<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Log class
*/
class Log {
	private $file;
	private $message = '';

	/**
	 * Constructor
	 *
	 * @param	string	$filename
 	*/
	public function __construct($filename) {
		$this->file = DIR_LOGS . $filename;
	}
	
	/**
     * 
     *
     * @param	string	$message
     */
	public function write($message) {
		$this->message .= date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n";
	}
	
	/**
     * 
     *
     */
	public function __destruct() {
		file_put_contents($this->file, $this->message, FILE_APPEND);
	}
}