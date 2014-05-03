<?php
namespace Gis\Core\Config;

/**
 * Used to access config data from the /class/config/ directory
 *
 * An example config file is as follows:
 *
 * 	$config['memcached'] = array(
 * 		'server' => 'localhost',
 *   	'port'	 => 11211,
 *    	'prefix' =>	'__site__'
 *  );
 *
 * This would be accesss as follows:
 *
 * $ConfigReader = new ConfigReader('/src/Config/');
 * $port = $ConfigReader->get('memcached.port');
 *
 * @author David Berliner <dsberliner@gmail.com>
 */
class ConfigReader implements IConfigReader
{
	private $configFolderPath;

	public function __construct($configFolderPath = null)
	{
		if(!is_null($configFolderPath)) {
			$this->configFolderPath = $configFolderPath;	
		} else {
			$this->configFolderPath = dirname(dirname(dirname(__FILE__))).'/Config';
		}
	}

	/**
	 * Fetch a config value for us by name.
	 * 
	 * @param  string $index - e.g memcached.host
	 * 
	 * @return mixed $response - e.g localhost
	 */
	public function get($index)
	{
		return ConfigDataManager::instance($this->configFolderPath)->get($index);
	}
}