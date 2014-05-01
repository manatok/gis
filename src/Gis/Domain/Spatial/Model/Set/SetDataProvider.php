<?php
namespace Gis\Domain\Spatial\Model\Set;

use Gis\Core\DataProvider\BaseDataProvider;
use Gis\Core\DataProvider\QueryBuilder;
use Gis\Core\DataProvider\IDataStruct;
use Gis\Core\Connection\IConnectionFactory;

/**
 * The DataProvider used to run SQL queries against the sets table
 */
class SetDataProvider extends BaseDataProvider implements ISetDataProvider
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
					layer,
					type,
					X(min_lat_long) as minX,
					Y(min_lat_long) as minY,
					X(max_lat_long) as maxX,
					Y(max_lat_long) as maxY
				FROM sets where id = :id";

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
		$setStruct = new SetStruct();

		$setStruct->id = $row->id;
		$setStruct->name = $row->name;
		$setStruct->layer = $row->layer;
		$setStruct->type = $row->type;
		$setStruct->minX = $row->minX;
		$setStruct->minY = $row->minY;
		$setStruct->maxX = $row->maxX;
		$setStruct->maxY = $row->maxY;
		
		return $setStruct;
	}

	/**
	 * @param IDataStruct $struct
	 * @return int - The primary Key
	 */
	public function insert(IDataStruct $struct)
	{
		$query = "INSERT INTO sets
					(
					`name`,
					`layer`,
					`type`,
					`min_lat_long`,
					`max_lat_long`
					)
				VALUES
					(
					:name,
					:layer,
					:type,
					GeomFromText('POINT(:minx :miny)'),
					GeomFromText('POINT(:maxx :maxy)')
					)";

		$qb = new QueryBuilder($query);

		$qb->bindString('name', $struct->name);
		$qb->bindInt('layer', $struct->layer);
		$qb->bindInt('type', $struct->type);
		$qb->bindFloat('minx', $struct->minX);
		$qb->bindFloat('miny', $struct->minY);
		$qb->bindFloat('maxx', $struct->maxX);
		$qb->bindFloat('maxy', $struct->maxY);

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
		$query = "UPDATE sets
				SET
					name=:name,
					layer=:layer,
					type=:type,
					min_lat_long=GeomFromText('POINT(:minx :miny)'),
					max_lat_long=GeomFromText('POINT(:maxx :maxy)'),
				WHERE
					id=:id";

		$qb = new QueryBuilder($query);

		$qb->bindString('name', $struct->name);
		$qb->bindInt('layer', $struct->layer);
		$qb->bindInt('type', $struct->type);
		$qb->bindFloat('minx', $struct->minX);
		$qb->bindFloat('miny', $struct->minY);
		$qb->bindFloat('maxx', $struct->maxX);
		$qb->bindFloat('maxy', $struct->maxY);
		$qb->bindInt('id', $struct->id);


		$this->getConnectionFactory()
				->getWriteConnection()
				->query($qb->prepare());
	}


}
