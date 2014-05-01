<?php

namespace Gis\Core\DataProvider;

interface IRetrievableDataProvider extends IDataProvider
{
	/**
	 * @param mixed $id
	 * @return IDataStruct
	 */
	function getById($id);
}
