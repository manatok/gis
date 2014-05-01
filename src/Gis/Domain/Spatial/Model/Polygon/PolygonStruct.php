<?php
namespace Gis\Domain\Spatial\Model\Polygon;

use Gis\Core\DataProvider\IDataStruct;

/**
 * A camelCasse mapping of the polygons table
 */
class PolygonStruct implements IDataStruct
{
	public $id;
	public $setId;
	// @var Gis\Domain\Spatial\TransferObject\SpatialObject
	public $spatialObject;
}
