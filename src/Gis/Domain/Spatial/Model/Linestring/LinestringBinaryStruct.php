<?php
namespace Gis\Domain\Spatial\Model\Linestring;

use Gis\Core\DataProvider\IDataStruct;

/**
 * When we read the linestring from the db we leave the coordinates
 * in binary. This struct allows us to pass this record up to the
 * factory to unpack and create the regular LinestringStruct which
 * contains a SpatialObject with all of the coordinates.
 */
class LinestringBinaryStruct implements IDataStruct
{
	public $id;
	public $setId;
	public $part;
	public $latLongBin;
}
