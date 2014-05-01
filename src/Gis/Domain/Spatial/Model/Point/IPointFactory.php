<?php
namespace Gis\Domain\Spatial\Model\Point;

use Gis\Core\Domain\IDomainObjectFactory;

/**
 * Force the implementing DomainFactory to use the functions defined below.
 *
 * Since this extends IRetrievableDomainObjectFactory the
 * factory will need to implement a:
 * 
 * function getById(int) 
 */
interface IPointFactory extends IDomainObjectFactory
{
	/**
	 * Fetch all of the points that belong to this layer that fall within
	 * the bounds specified. 
	 * 
	 * @param  int $layerId
	 * @param  Coord $boundsMin holds the min x/y
	 * @param  Coord $boundsMax holds the max x/y
	 * @return Point[]
	 */
	function getByLayerInBounds($layerId, $boundsMin, $boundsMax);
}
