<?php //defined('SYSPATH') or die('No direct script access.');

if ( ! extension_loaded('curl') || ! extension_loaded('json'))
{
	throw new AirshipException('There is missing dependent extensions - please ensure both cURL and JSON modules are installed');
}