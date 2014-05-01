<?php
namespace Gis\Core\Connection;

/**
 * DB transaction manager
 */
class TransactionManager implements ITransactionManager 
{
	
	private $connectionFactory;
	
	/**
	 * Construct our transaction manager and inject in a connection factory
	 * to run the transactions against.
	 * 
	 */
	function __construct(IConnectionFactory $connectionFactory) 
	{
		$this->connectionFactory = $connectionFactory;
	}
	
	/**
	 * Start a DB transaction. Caution, this does not keep track of the isolation
	 * level. If you start a transaction and an existing transaction is already 
	 * running it will stop the previous transaction.
	 * 
	 * @return void
	 */
	public function start() 
	{
		$this->connectionFactory->getWriteConnection()->query('START TRANSACTION');
	}

	/**
	 * Commit the DB transaction.
	 * @return void
	 */
 	public function commit() 
 	{
 		$this->connectionFactory->getWriteConnection()->query('COMMIT');
 	}

 	/**
 	 * Rollback all the changes on the DB that were a part of the transaction.
 	 * @return void
 	 */
 	public function rollback() 
 	{
 		$this->connectionFactory->getWriteConnection()->query('ROLLBACK');
 	}
}
