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

$config['color'] = array(
	'background'	=> array(76, 95, 115),
	'border'		=> array(150,150,150)
);