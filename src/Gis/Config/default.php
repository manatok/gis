<?php
/**
 * This would be accesss as follows:
 *
 * $ConfigReader = new ConfigReader();
 * $port = $ConfigReader->get('memcached.port');
 */
$config['db'] = array(
	'host'		=> 'localhost',
	'user'		=> 'root',
	'pass' 		=> 'root',
	'dbname'	=> 'gis'
);