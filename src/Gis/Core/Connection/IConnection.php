<?php

namespace Gis\Core\Connection;

/**
 * All connections passed to the DataProviders should be of this type.
 */
interface IConnection
{
	/**
	 * Send through a SQL query to the underlying connection
	 *
	 * @param string $sql
	 */
	function query($sql);
	
	/**
     * Fetch the last inserted ID. If no table
     * is specified then the last id from the last
     * insert will be returned.
     *
     * @param string $table
     * @return int
     */
    function lastInsertId($table = null);
}
