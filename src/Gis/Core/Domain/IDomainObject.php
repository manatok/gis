<?php

namespace Gis\Core\Domain;

use Gis\Core\DataProvider\IDataStruct;

interface IDomainObject
{
	/**
	 * @return IDataStruct The data for this entity.
	 */
	function getDataStruct();

	/**
	 * @param $dataStruct The data for this entity.
	 */
	function setDataStruct(IDataStruct $dataStruct);
}

