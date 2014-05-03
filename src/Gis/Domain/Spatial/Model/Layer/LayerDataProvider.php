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
					color,
					enabled,
					position,
					zoom_min,
					zoom_max
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
		$layerStruct->color = $row->color;
		$layerStruct->enabled = $row->enabled;
		$layerStruct->position = $row->position;
		$layerStruct->zoomMin = $row->zoom_min;
		$layerStruct->zoomMax = $row->zoom_max;
		
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
					`color`,
					`enabled`,
					`position`,
					`zoom_min`,
					`zoom_max`)
				VALUES
					(
					:name,
					GeomFromText('POINT(:minx :miny)'),
					GeomFromText('POINT(:maxx :maxy)'),
					:color,
					:enabled,
					:position,
					:zoom_min,
					:zoom_max)";

		$qb = new QueryBuilder($query);

		$qb->bindString('name', $struct->name);
		$qb->bindFloat('minx', $struct->minX);
		$qb->bindFloat('miny', $struct->minY);
		$qb->bindFloat('maxx', $struct->maxX);
		$qb->bindFloat('maxy', $struct->maxY);
		$qb->bindString('color', $struct->color);
		$qb->bindInt('enabled', $struct->enabled);
		$qb->bindInt('position', $struct->position);
		$qb->bindInt('zoom_min', $struct->zoomMin);
		$qb->bindInt('zoom_max', $struct->zoomMax);

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
					color=:color,
					enabled=:enabled,
					position=:position,
					zoom_min=:zoom_min,
					zoom_max=:zoom_max
				WHERE
					id=:id";

		$qb = new QueryBuilder($query);

		$qb->bindString('name', $struct->description);
		$qb->bindFloat('minx', $struct->minX);
		$qb->bindFloat('miny', $struct->minY);
		$qb->bindFloat('maxx', $struct->maxX);
		$qb->bindFloat('maxy', $struct->maxY);
		$qb->bindString('color', $struct->color);
		$qb->bindInt('enabled', $struct->enabled);
		$qb->bindInt('position', $struct->position);
		$qb->bindInt('zoom_min', $struct->zoomMin);
		$qb->bindInt('zoom_max', $struct->zoomMax);
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

	/**
	 * Fetch all of the active layers to be drawn ordered by
	 * position, id
	 *
	 * @param int $zoom If passed in this will filter the result based on zoom_min & zoom_max
	 * @return LayerStruct
	 */
	public function getActive($zoom = null)
	{
		$query = "SELECT * FROM layers WHERE enabled=1 ";

		if(!is_null($zoom)) {
			$query .= " AND zoom_min<=:zoom AND zoom_max >=:zoom ";
		}

		$query .= "order by position, id";

		$qb = new QueryBuilder($query);
		$qb->bindInt('zoom', $zoom);

		$res = $this->getConnectionFactory()
				->getReadConnection()
				->query($qb->prepare());

		return $this->createDataStructs($res);
	}


}
