<?php
namespace Gis\Domain\Spatial\Model\Point;

use Gis\Core\Connection\GisConnectionFactory;
use Gis\Core\DataProvider\IDataStruct;
use Gis\Core\DataProvider\IDataProvider;

/**
 *  PointFactory
 *  Create instances of Point by injecting
 *  in all of its dependencies.
 */
class PointFactory implements IPointFactory
{
	private $domainDataProvider;

	/**
	 * Used to construct the DataProvider needed by this factory
	 */
	public function __construct()
	{
		$dataProvider = new PointDataProvider(new GisConnectionFactory());
		$this->setDomainDataProvider($dataProvider);
	}

	/**
	 * @return IDataProvider The provider to use for this domain object.
	 */
	private function getDomainDataProvider()
	{
		return $this->domainDataProvider;
	}

	/**
	 * @param IDataProvider $domainDataProvider
	 */
	private function setDomainDataProvider(IDataProvider $domainDataProvider)
	{
		$this->domainDataProvider = $domainDataProvider;
	}

	/**
	 * Will create a constructed instance of an Point from
     * a PointStruct
	 * @param	IDataStruct $struct
	 * @return	Point
	 */
	public function create(IDataStruct $struct = null)
	{
		$point = new Point($this->getDomainDataProvider());
		$struct = is_null($struct) ? new PointStruct() : $struct;
		$point->setDataStruct($struct);
		return $point;
	}

	/**
	 * Fetch all of the points that belong to this layer that fall within
	 * the bounds specified. 
	 * 
	 * @param  int $layerId
	 * @param  Coord $boundsMin holds the min x/y
	 * @param  Coord $boundsMax holds the max x/y
	 * @return Point[]
	 */
	public function getByLayerInBounds($layerId, $boundsMin, $boundsMax)
	{
		$structs = $this->getDomainDataProvider()->getByLayerInBounds($layerId, $boundsMin, $boundsMax);
		$response = array();

		foreach($structs as $struct) {
			$response[] = $this->create($struct);
		}
		return $response;
	}
}
