<?php

namespace Openpesa\SDK;

// API Context that contain info for the API endpoint
class APIContext {

	var $api_key = '';
	var $public_key = '';
	var $ssl = false;
	var $method_type = APIMethodType::GET;
	var $address = '';
	var $port = 80;
	var $path = '';
	var $headers = array();
	var $parameters = array();

	// Constructor with optional prepopulated variables
	function __construct($dictionary=null)
	{
		if ($dictionary != null && gettype($dictionary) != 'array') {
			throw new \Exception('Input must be an array');
		}

		if ($dictionary != null) {
			foreach($dictionary as $i => $item) {
				switch (strtolower($i)) {
					case 'api_key':
						$this->set_api_key($dictionary[$i]);
						break;
					case 'public_key':
						$this->set_public_key($dictionary[$i]);
						break;
					case 'ssl':
						$this->set_ssl($dictionary[$i]);
						break;
					case 'method_type':
						$this->set_method_type($dictionary[$i]);
						break;
					case 'address':
						$this->set_address($dictionary[$i]);
						break;
					case 'port':
						$this->set_port($dictionary[$i]);
						break;
					case 'path':
						$this->set_path($dictionary[$i]);
						break;
					case 'headers':
						if (gettype($dictionary[$i]) != 'array') {
							throw new \Exception('headers must be an array');
						}
						foreach($dictionary[$i] as $key => $value) {
							$this->add_header($key, $dictionary[$i][$key]);
						}
						break;
					case 'parameters':
					if (gettype($dictionary[$i]) != 'array') {
							throw new \Exception('parameters must be an array');
						}
						foreach($dictionary[$i] as $key => $value) {
							$this->add_parameter($key, $dictionary[$i][$key]);
						}
						break;
					default:
						echo 'Unknown parameter type';
				}
			}
		}
	}

	// Build the URL from context parameters
	function get_url() {
		if ($this->ssl == true) {
			return 'https://' . $this->address . ':' . $this->port . $this->path;
		} else {
			return 'http://' . $this->address . ':' . $this->port . $this->path;
		}
	}

	// Add/update headers
	function add_header($header, $value) {
		$this->headers[$header] = $value;
	}

	// Get headers as an array
	function get_headers() {
		$headers = array();
		foreach($this->headers as $key => $value) {
			array_push($headers, $key . ": " . $value);
		}

		return $headers;
	}

	// Add parameter
	function add_parameter($key, $value) {
		$this->parameters[$key] = $value;
	}

	// Get parameters
	function get_parameters() {
		return $this->parameters;
	}

	function get_api_key() {
		return $this->api_key;
	}

	function set_api_key($api_key) {
		if (gettype($api_key) != 'string') {
			throw new \Exception('api_key must be a string');
		} else {
			$this->api_key = $api_key;
		}
	}

	function get_public_key() {
		return $this->public_key;
	}

	function set_public_key($public_key) {
		if (gettype($public_key) != 'string') {
			throw new \Exception('public_key must be a string');
		} else {
			$this->public_key = $public_key;
		}
	}

	function get_ssl() {
		return $this->ssl;
	}

	function set_ssl($ssl) {
		if (gettype($ssl) != 'boolean') {
			throw new \Exception('ssl must be a boolean');
		} else {
			$this->ssl = $ssl;
		}
	}

	function get_method_type() {
		return $this->method_type;
	}

	function set_method_type($method_type) {
		if (gettype($method_type) != 'integer') {
			throw new \Exception('method_type must be a integer');
		} else {
			$this->method_type = $method_type;
		}
	}

	function get_address() {
		return $this->address;
	}

	function set_address($address) {
		if (gettype($address) != 'string') {
			throw new \Exception('address must be a string');
		} else {
			$this->address = $address;
		}
	}

	function get_port() {
		return $this->port;
	}

	function set_port($port) {
		if ($port != null && gettype($port) != 'integer') {
			throw new \Exception('port must be a integer');
		} else {
			$this->port = $port;
		}
	}

	function get_path() {
		return $this->path;
	}

	function set_path($path) {
		if (gettype($path) != 'string') {
			throw new \Exception('path must be a string');
		} else {
			$this->path = $path;
		}
	}

}
