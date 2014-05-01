<?php

namespace Gis\Core\Domain;

interface IPersistableDomainObject extends IDomainObject
{
	function save();
}
