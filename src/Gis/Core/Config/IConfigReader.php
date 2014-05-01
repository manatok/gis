<?php

namespace Gis\Core\Config;

interface IConfigReader
{
	/**
	 * Fetch a config value for us by name.
	 * @param  string $index [description]
	 * @return mixed $response
	 */
	function get($index);
}