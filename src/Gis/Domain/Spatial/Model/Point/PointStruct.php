<?php
namespace Gis\Domain\Spatial\Model\Point;

use Gis\Core\DataProvider\IDataStruct;

/**
 * A camelCasse mapping of the points table
 */
class PointStruct implements IDataStruct
{
	public $id;
	public $setId;
	// @var Gis\Domain\Spatial\TransferObject\SpatialObject
	public $spatialObject;
}
