<?php
namespace Gis\Domain\Spatial\Model\Layer;

use Gis\Core\DataProvider\BaseDataProvider;
use Gis\Core\DataProvider\QueryBuilder;
use Gis\Core\DataProvider\IDataStruct;
use Gis\Core\Connection\IConnectionFactory;

/**
 * The DataProvider used to run SQL queries against the layers table
 */
class LayerDataProvider extends BaseDataProvider implements ILayerDataProvider
{

	/**
	 * @param IConnectionFactory $connectionFactory
	 */
	public function __construct(IConnectionFactory $connectionFactory)
	{
		parent::__construct($connectionFactory);
	}

	/**
	 * This method is required if we want to use the boilerplate getById method that is provided
	 *
	 * @param	int $id
	 * @return	string
	 */
	public function getSelectSQL($id)
	{
		$query = "SELECT 
					id, 
					name, 
					X(min_lat_long) as minX,
					Y(min_lat_long) as minY,
					X(max_lat_long) as maxX,
					Y(max_lat_long) as maxY,
					limit_scale
				FROM layers 
				WHERE id = :id";

		$qb = new QueryBuilder($query);
		$qb->bindInt('id', $id);

		return $qb->prepare();
	}

	/**
	 * Used to map the table to the struct
	 *
	 * @param	object $row - The record from the database
	 * @return	IDataStruct
	 */
	public function mapRow($row)
	{
		$layerStruct = new LayerStruct();

		$layerStruct->id = $row->id;
		$layerStruct->description = $row->description;
		$layerStruct->minX = $row->minX;
		$layerStruct->minY = $row->minY;
		$layerStruct->maxX = $row->maxX;
		$layerStruct->maxY = $row->maxY;
		$layerStruct->minZone = $row->min_zone;
		$layerStruct->maxZone = $row->max_zone;
		$layerStruct->limitScale = $row->limit_scale;
		$layerStruct->colour = $row->colour;
		
		return $layerStruct;
	}

	/**
	 * @param IDataStruct $struct
	 * @return int - The primary Key
	 */
	public function insert(IDataStruct $struct)
	{
		$query = "INSERT INTO layers
					(
					`name`,
					`min_lat_long`,
					`max_lat_long`,
					`limit_scale`)
				VALUES
					(
					:name,
					GeomFromText('POINT(:minx :miny)'),
					GeomFromText('POINT(:maxx :maxy)'),
					:limit_scale)";

		$qb = new QueryBuilder($query);

		$qb->bindString('name', $struct->name);
		$qb->bindFloat('minx', $struct->minX);
		$qb->bindFloat('miny', $struct->minY);
		$qb->bindFloat('maxx', $struct->maxX);
		$qb->bindFloat('maxy', $struct->maxY);
		$qb->bindInt('limit_scale', $struct->limitScale);

		$this->getConnectionFactory()
				->getWriteConnection()
				->query($qb->prepare());

		return $this->getConnectionFactory()
					->getWriteConnection()
					->lastInsertId();
	}

	/**
	 * @param IDataStruct $struct
	 */
	public function update(IDataStruct $struct)
	{
		$query = "UPDATE layers
				SET
					name=:name,
					min_lat_long=GeomFromText('POINT(:minx :miny)'),
					max_lat_long=GeomFromText('POINT(:maxx :maxy)'),
					limit_scale=:limit_scale,
				WHERE
					id=:id";

		$qb = new QueryBuilder($query);

		$qb->bindString('name', $struct->description);
		$qb->bindFloat('minx', $struct->minX);
		$qb->bindFloat('miny', $struct->minY);
		$qb->bindFloat('maxx', $struct->maxX);
		$qb->bindFloat('maxy', $struct->maxY);
		$qb->bindInt('limit_scale', $struct->limitScale);
		$qb->bindInt('id', $struct->id);


		$this->getConnectionFactory()
				->getWriteConnection()
				->query($qb->prepare());
	}

	/**
	 * @TODO - This is horrible. Need to add the type to the layer table
	 * @param  [type] $layerId [description]
	 * @return [type]          [description]
	 */
	public function getLayerType($layerId)
	{
		$query = "SELECT type FROM sets WHERE layer=:layerId LIMIT 1";

		$qb = new QueryBuilder($query);
		$qb->bindInt('layerId', $layerId);

		$res = $this->getConnectionFactory()
				->getReadConnection()
				->query($qb->prepare());

		return $res[0]->type;
	}


}
