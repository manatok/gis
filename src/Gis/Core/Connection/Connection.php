<?php

namespace Gis\Core\Connection;

use Gis\Core\Config\ConfigReader;

/**
 * Create a mysqli connection to the host sepcified in the config.
 *
 * @author David Berliner <dsberliner@gmail.com>
 */
class Connection implements IConnection
{
	/**
	 * @var Connection
	 */
	private static $self;
	private static $conn;

	/**
	 * Connects to the DB
	 *
	 * @throws ConnectionException If unable to connect
	 */
	private function __construct()
	{
		$configReader = new ConfigReader(dirname(dirname(__DIR__)).'/Config');

		self::$conn = mysqli_connect(
			$configReader->get('db.host'),
			$configReader->get('db.user'),
			$configReader->get('db.pass'),
			$configReader->get('db.dbname')
		);

		if (self::$conn->connect_errno) {
			throw new ConnectionException("Failed to connect to MySQL: (" . 
				self::$conn->connect_errno . ") " . 
				self::$conn->connect_error);
		}
	}

	/**
	 * Maintain a single reference to the conneciton
	 *
	 * @return Connection
	 */
	public static function singleton()
	{
		if(is_null(self::$self)) {
			self::$self = new Connection();
		}

		return self::$self;
	}

	/**
	 * Run the SQL query against the backing connection
	 *
	 * @param string $sql - The query to run
	 *
	 * @throws QueryException - on pear error
	 * @TODO - Create a connection Exception
	 *
	 * @return mixed - A collection of result objects
	 */
	public function query($sql)
	{
		$response = self::$conn->query($sql);
		
		if(is_object($response)) {
			$payload = array();
			while($data = $response->fetch_object()) {
				$payload[] = $data;
			}
		} else {
			$payload = $response;
		}
		
		if (self::$conn->error) {
			throw new QueryException(self::$conn->error);
		}
		return $payload;
	}

	/**
	 * Fetch the last inserted ID. If no table
	 * is specified then the last id from the last
	 * insert will be returned.
	 *
	 * @param string $table
	 * @return int
	 */
	public function lastInsertId($table = null)
	{
		$query = 'SELECT LAST_INSERT_ID() as id';

		if(!is_null($table))
		{
			$query .= ' FROM '.$table;
		}

		$res = $this->query($query);
		return $res[0]->id;
	}
}
