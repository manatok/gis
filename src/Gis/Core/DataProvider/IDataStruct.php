<?php
namespace Gis\Core\DataProvider;


/**
 * Marker interface denoting a <kbd>DataStruct</kbd>.
 * A <kbd>DataStruct</kbd> is a container for all attributes of a particular
 * entity, for example all fields in a row on a database table.  As such, it
 * holds the data representing an entity.<br>
 * <kbd>DataStruct</kbd>s should in general only contain only publically
 * accessible attributes - there should be no method implementations.
 */
interface IDataStruct
{
}