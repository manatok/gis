<?php

namespace Gis\Core\Connection;

/**
 * Used to fetch DB connections.
 */
class GisConnectionFactory implements IConnectionFactory
{
	/**
	 * Return a ReadConnection instance.
	 * @return IConnection
	 */
	public function getReadConnection()
	{
        return Connection::singleton();
	}

	/**
	 * Return a WriteConnection instance.
	 * @return IConnection
	 */
	public function getWriteConnection()
	{
        return Connection::singleton();
	}
}
