<?php
namespace Gis\Domain\Spatial\Model\Point;

use Gis\Core\DataProvider\BaseDataProvider;
use Gis\Core\DataProvider\QueryBuilder;
use Gis\Core\DataProvider\IDataStruct;
use Gis\Core\Connection\IConnectionFactory;
use Gis\Domain\Spatial\TransferObject\SpatialObject;
use Gis\Domain\Spatial\TransferObject\Coord;

/**
 * The DataProvider used to run SQL queries against the points table
 */
class PointDataProvider implements IPointDataProvider
{
	private $connectionFactory;

	/**
	 * @param IConnectionFactory $connectionFactory
	 */
	public function __construct(IConnectionFactory $connectionFactory)
	{
		$this->setConnectionFactory($connectionFactory);
	}

	/**
	 * @param IConnectionFactory $connectionFactory
	 *
	 * @return void
	 */
	protected function setConnectionFactory(IConnectionFactory $connectionFactory)
	{
		$this->connectionFactory = $connectionFactory;
	}

	/**
	 * @return IConnectionFactory
	 */
	protected function getConnectionFactory()
	{
		return $this->connectionFactory;
	}

	/**
	 * Used to map the table to the struct
	 *
	 * @param	object $row - The record from the database
	 * @return	IDataStruct
	 */
	public function mapRow($row, SpatialObject $spatialObject)
	{
		$pointStruct = new PointStruct();

		$pointStruct->id = $row->id;
		$pointStruct->setId = $row->set_id;
		$pointStruct->spatialObject = $spatialObject;
		
		return $pointStruct;
	}

	/**
	 * @param IDataStruct $struct
	 * @return int - The primary Key
	 */
	public function insert(IDataStruct $struct)
	{
		$query = "INSERT INTO points
						(
						`set_id`,
						`lat_long`
						)
					VALUES
						(
						:set_id,
						GeomFromText('POINT(:x :y)')
						)";

			$qb = new QueryBuilder($query);

			$qb->bindInt('set_id', $struct->setId);
			$qb->bindFloat('x', $struct->spatialObject->current()[0]->getX());
			$qb->bindFloat('y', $struct->spatialObject->current()[0]->getY());

			$this->getConnectionFactory()
					->getWriteConnection()
					->query($qb->prepare());
	}

	/**
	 * Fetch all of the points that belong to this layer that fall within
	 * the bounds specified. 
	 * 
	 * @param  int $layerId
	 * @param  Coord $boundsMin holds the min x/y
	 * @param  Coord $boundsMax holds the max x/y
	 * @return PointStruct[]
	 */
	public function getByLayerInBounds($layerId, $boundsMin, $boundsMax)
	{
		$query = "SELECT 
					p.id, 
					p.set_id, 
					X(p.lat_long) as x,
					Y(p.lat_long) as y
					FROM points p 
					LEFT JOIN sets s
						ON p.set_id=s.id
					WHERE 
						MBRContains(GeomFromText('POLYGON((
							:minx :miny,
							:maxx :miny,
							:maxx :maxy,
							:minx :maxy,
							:minx :miny))'),
						p.lat_long)
					AND s.layer=:layerId";

		$qb = new QueryBuilder($query);
		$qb->bindInt('layerId', $layerId);
		$qb->bindFloat('minx', $boundsMin->getX());
		$qb->bindFloat('miny', $boundsMin->getY());
		$qb->bindFloat('maxx', $boundsMax->getX());
		$qb->bindFloat('maxy', $boundsMax->getY());

		$res = $this->getConnectionFactory()
						->getWriteConnection()
						->query($qb->prepare());

		$structs = array();

		foreach($res as $row) {
			$spatialObject = new SpatialObject();
			$coord = new Coord();
			$coord->setX($row->x);
			$coord->setY($row->y);
			$spatialObject->addCoordToPart($coord);

			$structs[] = $this->mapRow($row, $spatialObject);
		}

		return $structs;
	}
}
