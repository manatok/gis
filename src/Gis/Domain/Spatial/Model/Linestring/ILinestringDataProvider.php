<?php
namespace Gis\Domain\Spatial\Model\Linestring;

use Gis\Core\DataProvider\IDataProvider;

interface ILinestringDataProvider extends IDataProvider
{
	/**
	 * Fetch all of the linestrings that belong to this layer that fall within
	 * the bounds specified. 
	 * 
	 * @param  int $layerId
	 * @param  Coord $boundsMin holds the min x/y
	 * @param  Coord $boundsMax holds the max x/y
	 * @return LinestringBinaryStruct[]
	 */
	function getByLayerInBounds($layerId, $boundsMin, $boundsMax);
}
