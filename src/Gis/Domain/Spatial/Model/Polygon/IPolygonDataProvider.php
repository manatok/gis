<?php
namespace Gis\Domain\Spatial\Model\Polygon;

use Gis\Core\DataProvider\IDataProvider;

interface IPolygonDataProvider extends IDataProvider
{
	/**
	 * Fetch all of the polygons that belong to this layer that fall within
	 * the bounds specified. 
	 * 
	 * @param  int $layerId
	 * @param  Coord $boundsMin holds the min x/y
	 * @param  Coord $boundsMax holds the max x/y
	 * @return PolygonBinaryStruct[]
	 */
	function getByLayerInBounds($layerId, $boundsMin, $boundsMax);
}
