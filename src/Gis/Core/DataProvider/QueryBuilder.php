<?php

namespace Gis\Core\DataProvider;

use Gis\Core\DataProvider\Exception\IncorrectTypeException;
use Gis\Core\DataProvider\Exception\MissingParameterException;


/**
 * Example Usage:
 *
 * try {
 *		$_sql = 'SELECT * FROM xzy WHERE ID=:id';
 *		$_qb = new QueryBuilder($_sql);
 *		$_qb->bindInt(':id', 23);
 *		$_sql = $_qb->prepare();
 *		... execute the sql
 * }
 * catch(IncorrectTypeException $_exception)
 * {
 *
 * }
 * catch(MissingParameterException $_exception)
 * {
 *
 * }
 *
 * @author David Berliner <dsberliner@gmail.com>
 */
class QueryBuilder
{
	const LIKE_BEFORE = 0;
	const LIKE_AFTER = 1;
	const LIKE_BOTH = 2;

	/**
	 * Used to ensure that all of the replacements were made
	 * @var int
	 */
	private $totalPlaceholders;

	/**
	 * An internal reference to the current sql query
	 * @var string
	 */
	private $sql;

	/**
	 *
	 * @var char
	 */
	private $delimiterPrefix;

	/**
	 * This is where all of the replacement key=>values will be stored
	 * @var array
	 */
	private $replacements;

	/**
	 * @param type $_sql
	 * @param string
	 */
	public function __construct($_sql = '', $_delimiterPrefix = ':')
	{
		$this->sql = $_sql;
		$this->delimiterPrefix = $_delimiterPrefix;

		$this->totalPlaceholders = $this->countPlaceholders();
		$this->replacements = array();
	}

	/**
	 * Count how many placeholders are in the SQL so that we can ensure that
	 * all of them have been replace at the end
	 *
	 * @return int
	 */
	private function countPlaceholders()
	{
		return substr_count($this->sql, $this->delimiterPrefix);
	}

	/**
	 * Validate that the value is infact an int, this will only be bound to the
	 * query when prepare is called.
	 *
	 * The following will pass as an int:
	 * 1234
	 * '1234'
	 * null - Needs to be allowed for nullable fields
	 *
	 * The following will not pass as an int
	 * 1.2
	 * '1.2'
	 *
	 *
	 * Could use: $str_numbers_only = preg_replace("/[^\d]/", "", $str);
	 * to remove everything except numbers from the string.
	 *
	 * @param string $_key
	 * @param int $_value
	 * @throws IncorrectTypeException
	 */
	public function bindInt($_key, $_value)
	{
		if (!is_null($_value))
		{
			if (empty($_value) && !is_numeric($_value))
			{
				$_value = '""';
			}
			elseif (false == $this->testInt($_value))
			{
				throw new IncorrectTypeException("The parameter:'" . $_value . "' is not a valid Int");
			}
		}
		else
		{
			$_value = 'NULL';
		}

		$this->replacements[$this->delimiterPrefix . $_key] = $_value;
	}

	/**
	 * Validate that the value is infact an array containing int values, this will only be
	 * bound to the query when prepare is called.
	 *
	 * The following will pass as an int:
	 * array(123,234,345)
	 * array('1234','234','234')
	 *
	 * The following will not pass as an int
	 * 1.2
	 * '1.2'
	 *  null - Needs to be allowed for nullable fields
	 *
	 * @param string $_key
	 * @param array $_value
	 * @throws IncorrectTypeException
	 */
	public function bindInInt($_key, $_value)
	{
		if (is_null($_value))
		{
			throw new IncorrectTypeException("The parameter cannot be null");
		}
		else if (!is_array($_value))
		{
			throw new IncorrectTypeException("The parameter must be an array");
		}
		else {
			foreach ($_value as $k => $val) {

				if (!is_null($val))
				{
					if (empty($val) && !is_numeric($val))
					{
						$_value[$k] = '""';
					}
					elseif (false == $this->testInt($val))
					{
						throw new IncorrectTypeException("The parameter contains an invalid int '" . $val . "'");
					}
				}

			}
		}

		$this->replacements[$this->delimiterPrefix . $_key] = implode(',', $_value);
	}

	/**
	 * Used internally to test if a value is an int
	 * @param mixed $_value
	 * @return bool
	 */
	private function testInt($_value)
	{
		$_value = filter_var($_value, FILTER_VALIDATE_INT);

		return is_int($_value);
	}

	/**
	 * Validate that the value is infact a string, this will only be bound to the
	 * query when prepare is called.
	 *
	 * @param string $_key
	 * @param string $_value
	 * @throws IncorrectTypeException
	 */
	public function bindString($_key, $_value)
	{
		$_value = is_null($_value) ? 'NULL' : "'" . $this->escapeString($_value) . "'";

		$this->replacements[$this->delimiterPrefix . $_key] = $_value;
	}

	/**
	 * Used to bind the column name, this makes sure that its a string and that it
	 * is escaped correctly. We cannot use bindString as it places the value in quotes
	 * 
	 * @param string $_key
	 * @param string $_value
	 * @throws IncorrectTypeException
	 */
	public function bindColumnString($_key, $_value)
	{
		$_value = is_null($_value) ? '' : "`".$this->escapeString($_value)."`";

		$this->replacements[$this->delimiterPrefix . $_key] = $_value;
	}

	/**
	 * Validate that the value is infact a string, and then adds the % based
	 * on the value of fuzzySide.
	 *
	 *      fuzzySide = self::LIKE_BEFORE   -> search like '%string';
	 *      fuzzySide = self::LIKE_AFTER   -> search like 'string%';
	 *      fuzzySide = self::LIKE_BOTH   -> search like '%string%';
	 *
	 * @param string $_key
	 * @param string $_value
	 * @param int $_fuzzySide
	 * @throws IncorrectTypeException
	 */
	public function bindStringLike($_key, $_value, $_fuzzySide = 2)
	{
		$_value = is_null($_value) ? 'NULL' : $this->escapeString($_value) ;

		switch($_fuzzySide)
		{
			case self::LIKE_BEFORE : $_value = "'%" . $_value ."'"; break;
			case self::LIKE_AFTER : $_value = "'" . $_value ."%'"; break;
			case self::LIKE_BOTH : $_value = "'%" . $_value ."%'"; break;
		}

		$this->replacements[$this->delimiterPrefix . $_key] = $_value;
	}

	/**
	 * Validate that the value is infact a double, this will only be bound to the
	 * query when prepare is called.
	 *
	 * @param string $_key
	 * @param float $_value
	 * @throws IncorrectTypeException
	 */
	public function bindFloat($_key, $_value)
	{
		if (!is_null($_value))
		{
			/* add the +1 to do an implicit cast since the float could be a string */
			//if ((false === is_float($_value + 1)) && (false === $this->testInt($_value)))
			if (!is_numeric($_value))
			{
				throw new IncorrectTypeException("The parameter:'" . $_value . "' is not a valid Float");
			}
		}
		else
		{
			$_value = 'NULL';
		}

		$this->replacements[$this->delimiterPrefix . $_key] = $_value;
	}

	/**
	 * Validate that the value is infact a bool, this will only be bound to the
	 * query when prepare is called.
	 *
	 * @param string $_key
	 * @param bool $_value
	 * @throws IncorrectTypeException
	 */
	public function bindBool($_key, $_value)
	{
		if (!is_null($_value))
		{
			/* we want to allow the string 'false' or 'true' to work too. */
			if(strtolower($_value) === 'false' || $_value == '0')
			{
				$_value = FALSE;
			}
			elseif(strtolower($_value) === 'true' || $_value == '1')
			{
				$_value = TRUE;
			}

			if (false === is_bool($_value))
			{
				throw new IncorrectTypeException("The parameter:'" . $_value . "' is not a valid Boolean");
			}

			/* to convert 1s and 0s */
			$_value = $_value ? 'TRUE' : 'FALSE';
		}
		else
		{
			$_value = 'NULL';
		}

		$this->replacements[$this->delimiterPrefix . $_key] = (string) $_value;
	}

	/**
	 * When using a tiny int as a bool
	 *
	 * @param type $_key
	 * @param type $_value
	 * @throws IncorrectTypeException
	 */
	public function bindTinyInt($_key, $_value)
	{
		$_value = filter_var($_value, FILTER_VALIDATE_INT);

		if (!is_null($_value))
		{
			if (0 !== $_value && 1 !== $_value)
			{
				throw new IncorrectTypeException("The parameter:'" . $_value . "' is not a tinyInt");
			}
		}
		else
		{
			$_value = 'NULL';
		}

		$this->replacements[$this->delimiterPrefix . $_key] = $_value;
	}

	/**
	 * Will replace all of the placeholders in the sql query with their actual
	 * values.
	 *
	 * @return string
	 * @throws MissingParameterException
	 */
	public function prepare()
	{
		/* are there tokens in the string that need replacement */
		if ($this->totalPlaceholders)
		{
			/**
			 * Need to make sure that we have all of the replacement values needed
			 * for the query. If we are missing tokens we will throw an exception.
			 */
			preg_match_all($this->getTokenRegex(), $this->sql, $_matches);

			/**
			 * We may reuse the parameters in the query
			 */
			$_uniqueQueryTokens = array_unique($_matches[0]);

			/**
			 * Are we missing any
			 */
			if (array_diff($_uniqueQueryTokens, array_keys($this->replacements)))
			{
				throw new MissingParameterException('Expecting: ' . $_uniqueQueryTokens . 'tokens');
			}

			$_str = strtr($this->sql, $this->replacements);
		}
		else
		{
			$_str = $this->sql;
		}

		return $_str;
	}

	/**
	 * Get the search regex used to find tokens in the query based on the
	 * delimiter
	 * @return string
	 */
	private function getTokenRegex()
	{
		return '/\\' . $this->delimiterPrefix . '\w+/';
	}

	/**
	 * Used as a replacement for mysql_real_escape_string - I found it here:
	 * http://www.gamedev.net/topic/448909-php-alternative-to-mysql_real_escape_string/
	 *
	 * @param string $_string
	 * @return string
	 */
	private function escapeString($_string)
	{
		return strtr($_string, array(
					"\n" => '\n',
					"\r" => '\r',
					'\\' => '\\\\',
					"'" => "\'",
					'"' => '\"',
					"\x1a" => '\x1a'
				));
	}

}
