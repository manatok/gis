<?php
namespace Gis\Domain\Spatial\Model\Layer;

use Gis\Core\Domain\IRetrievableDomainObjectFactory;

/**
 * Force the implementing DomainFactory to use the functions defined below.
 *
 * Since this extends IRetrievableDomainObjectFactory the
 * factory will need to implement a:
 * 
 * function getById(int) 
 */
interface ILayerFactory extends IRetrievableDomainObjectFactory
{

}
