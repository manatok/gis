<?php
namespace Gis\Domain\Spatial\Model\Polygon;

use Gis\Core\DataProvider\IDataStruct;

/**
 * When we read the polygon from the db we leave the coordinates
 * in binary. This struct allows us to pass this record up to the
 * factory to unpack and create the regular PolygonStruct which
 * contains a SpatialObject with all of the coordinates.
 */
class PolygonBinaryStruct implements IDataStruct
{
	public $id;
	public $setId;
	public $part;
	public $latLongBin;
}
