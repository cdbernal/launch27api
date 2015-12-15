<?php
/*
	* A simple class to help you manage the Launch27 API v.2 *
	Version     1.0
	License     This code is released under the MIT Open Source License. Feel free to do whatever you want with it.
	Author      cesar7designs@gmail.com
	LastUpdate  2015/12/09
*/

	if (!function_exists('curl_init')) {
	  throw new Exception('Launch27 needs the CURL PHP extension.');
	}
	if (!function_exists('json_decode')) {
	  throw new Exception('Launch27 needs the JSON PHP extension.');
	}

	require(dirname(__FILE__) . '/launch27/launch27.php');
	require(dirname(__FILE__) . '/launch27/utils.php');
	require(dirname(__FILE__) . '/launch27/request.php');
	require(dirname(__FILE__) . '/launch27/customer.php');
	require(dirname(__FILE__) . '/launch27/booking.php');
	require(dirname(__FILE__) . '/launch27/frequencies.php');
	require(dirname(__FILE__) . '/launch27/services.php');
	require(dirname(__FILE__) . '/launch27/custom_fields.php');
	require(dirname(__FILE__) . '/launch27/states.php');
	require(dirname(__FILE__) . '/launch27/spots.php');
	require(dirname(__FILE__) . '/launch27/error.php');

?>