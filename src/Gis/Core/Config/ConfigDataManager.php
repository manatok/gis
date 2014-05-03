<?php
namespace Gis\Core\Config;

/**
 * Since our configuration data needs to stay in memory once loaded and not reloaded
 * from disk on each call a static member was used. This singleton is decoupled from
 * classes by the wrapper ConfigReader so we can throw this implementation away at a
 * later point if we want to handle config data in a different manner.
 *
 * @author David Berliner <dsberliner@gmail.com>
 */
class ConfigDataManager
{
	private static $instance = null;
	private static $data;
	private static $folderPath;

	/**
	 * The singleton
	 * @return ConfigDataManager
	 */
	public static function instance($folderPath)
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
			self::$folderPath = $folderPath;
			self::$instance->loadData();
		}

		return self::$instance;
	}

	/**
	 * This is used to autoload all of the config files from the
	 * /Gis/Config/ directory. The default.php files is used to
	 * set default values and will/can be overwritten by specific 
	 * config files.
	 */
	private function loadData() 
	{
		if (!self::$data) {
			require_once(self::$folderPath . "/default.php");
			
			self::$data = $config;
			
			foreach (glob(self::$folderPath."/*.php") as $filename) {
				$name = explode('/',$filename);
				if(end($name) != "default.php") {
					include_once $filename;
				}
			}
			
			self::$data = self::$instance->array_merge_recursive_distinct(self::$data, $config);
		}
		return self::$data;
	}

	/**
	 * This will split the key into the 'namespace' and the
	 * index value and then do the lookup on the value.
	 *
	 * e.g a key of memcached.port would be split and a lookup performed on
	 * $config['memcached']['port']
	 * 
	 * @param  string $key - e.g memcached.port
	 * @return mixed $response
	 */
	public function get($key) {
		$key1 = $key2 = $response = null;
		list($key1, $key2) = @explode('.', $key);
		
		if (isset(self::$data[$key1][$key2])) {
			$response = self::$data[$key1][$key2];
		}
		
		return $response;
	}
	

	/**
	 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
	 * keys to arrays rather than overwriting the value in the first array with the duplicate
	 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
	 * this happens (documented behavior):
	 *
	 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('org value', 'new value'));
	 *
	 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
	 * Matching keys' values in the second array overwrite those in the first array, as is the
	 * case with array_merge, i.e.:
	 *
	 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('new value'));
	 *
	 * Parameters are passed by reference, though only for performance reasons. They're not
	 * altered by this function.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
	 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
	 */
	public function array_merge_recursive_distinct(array &$array1, array &$array2)
	{
		$merged = $array1;

		foreach ($array2 as $key => &$value) {
			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
				$merged[$key] = self::array_merge_recursive_distinct ($merged[$key], $value);
			}
			else {
				$merged[$key] = $value;
			}
		}

		return $merged;
	}

}
