<?php
namespace Gis\Core\DataProvider;

use Gis\Core\DataProvider\Exception\MissingDepedencyException;
use Gis\Core\Connection\IConnectionFactory;
use Gis\Core\Cache\ICacherFactory;
use Gis\Core\Cache\ICacher;

/**
 * Have some common functionality for our IDataProviders
 *
 * @author David Berliner <dsberliner@gmail.com>
 */
abstract class BaseDataProvider implements IRetrievableDataProvider, IRowMappable
{
	/**
	 * @var IConnectionFactory
	 * @access private
	 */
	private $connectionFactory;

	/**
	 * @var ICacherFactory
	 * @access private
	 */
	private $cacherFactory;

	/**
	 * @param IConnectionFactory $connectionFactory
	 * @param ICacherFactory $cacherFactory
	 *
	 * @return void
	 */
	public function __construct(IConnectionFactory $connectionFactory, ICacherFactory $cacherFactory = null)
	{
		$this->setConnectionFactory($connectionFactory);

		if (!empty($cacherFactory)) {
			$this->setCacherFactory($cacherFactory);
		}
	}

	/**
	 * @param mixed $id
	 *
	 * @return string The SQL string used for the getById() call. Used to fetch
	 *                1 row from the table whos primaryKey = $id
	 */
	abstract function getSelectSQL($id);

	/**
	 * @SuppressWarnings docBlocks,
	 *
	 * @return IDataStruct|null The common representation of the entity.
	 */
	public function getById($id)
	{
		$dataStruct = null;

		$sql = $this->getSelectSQL($id);
		$result = $this->getConnectionFactory()->getReadConnection()->query($sql);

		if (is_array($result) && count($result) == 1) {
			$dataStruct = $this->mapRow($result[0]);
		}

		return $dataStruct;
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
	 * This will convert an array of Mysqls response Objects
	 * into an array of @link{IDataStruct}s. This is just a helper function.
     *
	 * @param Object $structArray[]
	 * @return IDataStruct[]
	 */
	public function createDataStructs($structArray)
	{
		$structs = array();

		if(is_array($structArray) && count($structArray))
		{
			foreach($structArray as $struct)
			{
				$structs[] = $this->mapRow($struct);
			}
		}

		return $structs;
	}

}
