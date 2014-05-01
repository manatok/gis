<?php
namespace Gis\Domain\Spatial\Model\Linestring;

use Gis\Core\Domain\IDomainObjectFactory;

/**
 * Force the implementing DomainFactory to use the functions defined below.
 *
 * Since this extends IRetrievableDomainObjectFactory the
 * factory will need to implement a:
 * 
 * function getById(int) 
 */
interface ILinestringFactory extends IDomainObjectFactory
{
	/**
	 * Fetch all of the Polygons that belong to this layer and fall within
	 * the specified bounds.
	 * 
	 * @param  int $layerId
	 * @param  Coord $boundsMin
	 * @param  Coord $boundsMax
	 * @return Polygon[]
	 */
	function getByLayerInBounds($layerId, $boundsMin, $boundsMax);
}
