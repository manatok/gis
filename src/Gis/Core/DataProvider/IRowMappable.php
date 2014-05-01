<?php

namespace Gis\Core\DataProvider;

interface IRowMappable
{
	/**
	 * Maps a result set row to a corresponding
	 * <kbd>IDataStruct</kbd>.
	 *
	 * @param object The object with string properties corresponding
	 * 			to the fetched row.
	 *
	 * @return IDataStruct The common representation of the entity.
	 */
	function mapRow($row);
}
