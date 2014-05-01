<?php
namespace Gis\Domain\Spatial\Model\Point;

use Gis\Core\DataProvider\IDataProvider;

interface IPointDataProvider extends IDataProvider
{
	/**
	 * Fetch all of the points that belong to this layer that fall within
	 * the bounds specified. 
	 * 
	 * @param  int $layerId
	 * @param  Coord $boundsMin holds the min x/y
	 * @param  Coord $boundsMax holds the max x/y
	 * @return PointStruct[]
	 */
	 function getByLayerInBounds($layerId, $boundsMin, $boundsMax);
}
