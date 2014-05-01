<?php
namespace Gis\Domain\Spatial\Model\Attribute;

use Gis\Core\DataProvider\BaseDataProvider;
use Gis\Core\DataProvider\QueryBuilder;
use Gis\Core\DataProvider\IDataStruct;
use Gis\Core\Connection\IConnectionFactory;

/**
 * The DataProvider used to run SQL queries against the attributes table
 */
class AttributeDataProvider extends BaseDataProvider implements IAttributeDataProvider
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
		$query = "SELECT * from attributes where id = :id";
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
		$attributeStruct = new AttributeStruct();

		$attributeStruct->id = $row->id;
		$attributeStruct->setId = $row->set_id;
		$attributeStruct->sequence = $row->sequence;
		$attributeStruct->name = $row->name;
		$attributeStruct->value = $row->value;
		
		return $attributeStruct;
	}

	/**
	 * @param IDataStruct $struct
	 * @return int - The primary Key
	 */
	public function insert(IDataStruct $struct)
	{
		$query = "INSERT INTO attributes
					(
					`set_id`,
					`sequence`,
					`name`,
					`value`)
				VALUES
					(
					:set_id,
					:sequence,
					:name,
					:value)";

		$qb = new QueryBuilder($query);

		$qb->bindInt('set_id', $struct->setId);
		$qb->bindInt('sequence', $struct->sequence);
		$qb->bindString('name', $struct->name);
		$qb->bindString('value', $struct->value);

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
		$query = "UPDATE attributes
				SET
					set_id=:set_id,
					sequence=:sequence,
					name=:name,
					value=:value
				WHERE
					id=:id";

		$qb = new QueryBuilder($query);

		$qb->bindInt('set_id', $struct->setId);
		$qb->bindInt('sequence', $struct->sequence);
		$qb->bindString('name', $struct->name);
		$qb->bindString('value', $struct->value);
		$qb->bindInt('id', $struct->id);


		$this->getConnectionFactory()
				->getWriteConnection()
				->query($qb->prepare());
	}


}
