<?php
namespace Gis\Core\DataProvider;

/**
 * Denotes a Data Provider which is able to insert and/or update
 * its associated {@link IDataStruct}.
 */
interface IPersistableDataProvider
{
	/**
	 * @param IDataStruct $struct
	 */
	function insert(IDataStruct $struct);

	/**
	 * @param IDataStruct $struct
	 */
	function update(IDataStruct $struct);
}
