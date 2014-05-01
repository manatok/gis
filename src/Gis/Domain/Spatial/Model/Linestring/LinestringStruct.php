<?php
namespace Gis\Domain\Spatial\Model\Linestring;

use Gis\Core\DataProvider\IDataStruct;

/**
 * A camelCasse mapping of the linestrings table
 */
class LinestringStruct implements IDataStruct
{
	public $id;
	public $setId;
	// @var Gis\Domain\Spatial\TransferObject\SpatialObject
	public $spatialObject;
}
