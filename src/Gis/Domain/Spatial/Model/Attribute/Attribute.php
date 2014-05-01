<?php
namespace Gis\Domain\Spatial\Model\Attribute;

use Gis\Core\DataProvider\IDataProvider;
use Gis\Core\Domain\BaseDomainObject;
use Gis\Core\Domain\IPersistableDomainObject;


/**
 * Maintains this business logic for working
 * with a Attribute
 */
class Attribute extends BaseDomainObject implements IPersistableDomainObject
{
	/**
	 * Construct this DomainObject with its backing DP
	 *
	 * @param IAttributeDataProvider $dataProvider
	 */
	public function __construct(IAttributeDataProvider $dataProvider)
	{
		$this->setDataProvider($dataProvider);
	}

	/**
	 * Used to create a new DataStruct ascosiated withi this DO
	 * @return  AttributeStruct
	 */
	public function createEmptyDataStruct()
	{
		return new AttributeStruct();
	}


	/**
	 * Used to insert/update the current
	 * DomainObject instance.
	 */
	public function save()
	{
		if (is_null($this->getDataStruct()->id)) {
			$this->getDataStruct()->id = $this->getDataProvider()->insert($this->getDataStruct());
		} else {
			$this->getDataProvider()->update($this->getDataStruct());
		}
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->getDataStruct()->id = $id;
	}

	/**
	 * @return int $id
	 */
	public function getId()
	{
		return $this->getDataStruct()->id;
	}

	/**
	 * @param int $setId
	 */
	public function setSetId($setId)
	{
		$this->getDataStruct()->setId = $setId;
	}

	/**
	 * @return int $setId
	 */
	public function getSetId()
	{
		return $this->getDataStruct()->setId;
	}

	/**
	 * @param int $sequence
	 */
	public function setSequence($sequence)
	{
		$this->getDataStruct()->sequence = $sequence;
	}

	/**
	 * @return int $sequence
	 */
	public function getSequence()
	{
		return $this->getDataStruct()->sequence;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->getDataStruct()->name = $name;
	}

	/**
	 * @return string $name
	 */
	public function getName()
	{
		return $this->getDataStruct()->name;
	}

	/**
	 * @param string $value
	 */
	public function setValue($value)
	{
		$this->getDataStruct()->value = $value;
	}

	/**
	 * @return string $value
	 */
	public function getValue()
	{
		return $this->getDataStruct()->value;
	}
}