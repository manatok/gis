<?php
namespace Gis\Domain\Spatial\Model\Linestring;

use Gis\Core\DataProvider\BaseDataProvider;
use Gis\Core\DataProvider\QueryBuilder;
use Gis\Core\DataProvider\IDataStruct;
use Gis\Core\Connection\IConnectionFactory;

/**
 * The DataProvider used to run SQL queries against the linestrings table
 */
class LinestringDataProvider implements ILinestringDataProvider
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
	public function mapRow($row)
	{
		$linestringStruct = new LinestringStruct();

		$linestringStruct->id = $row->id;
		$linestringStruct->setId = $row->set_id;
		$linestringStruct->latLong = $row->lat_long;
		
		return $linestringStruct;
	}

	/**
	 * @param IDataStruct $struct
	 * @return int - The primary Key
	 */
	public function insert(IDataStruct $struct)
	{
		foreach($struct->spatialObject as $key => $part) {
			$coordStr = "";
			foreach($part as $coord) {
				$coordStr .= $coord->getX()." ".$coord->getY().",";
			}

			$coordStr = rtrim($coordStr, ',');

			$query = "INSERT INTO linestrings
						(
						`set_id`,
						`part`,
						`lat_long`
						)
					VALUES
						(
						:set_id,
						:part,
						GeomFromText('LINESTRING($coordStr)')
						)";

			$qb = new QueryBuilder($query);

			$qb->bindInt('set_id', $struct->setId);
			$qb->bindInt('part', $key);

			$this->getConnectionFactory()
					->getWriteConnection()
					->query($qb->prepare());
		}
	}

	/**
	 * Fetch all of the linestrings that belong to this layer that fall within
	 * the bounds specified. 
	 * 
	 * @param  int $layerId
	 * @param  Coord $boundsMin holds the min x/y
	 * @param  Coord $boundsMax holds the max x/y
	 * @return LinestringBinaryStruct[]
	 */
	public function getByLayerInBounds($layerId, $boundsMin, $boundsMax)
	{
		$query = "SELECT 
					p.id, 
					p.set_id, 
					p.part, 
					asBinary(p.lat_long) as xy
				FROM linestrings p LEFT JOIN
				(
					select distinct p.set_id as id
						FROM linestrings p
						LEFT JOIN sets s
							ON p.set_id=s.id
						WHERE 
							Intersects(GeomFromText('POLYGON((
								:minx :miny,
								:maxx :miny,
								:maxx :maxy,
								:minx :maxy,
								:minx :miny))'),
							p.lat_long)
						AND s.layer=:layerId
				) as innerSets
				ON p.set_id=innerSets.id
				WHERE innerSets.id IS NOT NULL
				ORDER BY p.set_id, p.part";

		$qb = new QueryBuilder($query);
		$qb->bindInt('layerId', $layerId);
		$qb->bindFloat('minx', $boundsMin->getX());
		$qb->bindFloat('miny', $boundsMin->getY());
		$qb->bindFloat('maxx', $boundsMax->getX());
		$qb->bindFloat('maxy', $boundsMax->getY());
		//echo $qb->prepare();die;
		$res = $this->getConnectionFactory()
						->getWriteConnection()
						->query($qb->prepare());

		$structs = array();

		foreach($res as $row) {
			$structs[] = $this->mapBinaryStruct($row);
		}

		return $structs;
	}

	/**
	 * Unpack a db row onto a LinestringBinaryStruct. This struct is different to
	 * the LinestringStruct in that this does not contain a SpatialObject, rather
	 * a latLongBin field which is a binary representation of all of the points
	 * of the linestring.
	 *
	 * These points still need to be unpacked and a SpatialObject created.
	 * 
	 * @param  mysqli_fetch_row $row  A linestring row
	 * @return LinestringBinaryStruct
	 */
	private function mapBinaryStruct($row)
	{
		$linestringStruct = new LinestringBinaryStruct();

		$linestringStruct->id = $row->id;
		$linestringStruct->setId = $row->set_id;
		$linestringStruct->part = $row->part;
		$linestringStruct->latLongBin = $row->xy;
		$linestringStruct->centreX = $row->x_centre;
		$linestringStruct->centreY = $row->y_centre;
		
		return $linestringStruct;
	}


}
