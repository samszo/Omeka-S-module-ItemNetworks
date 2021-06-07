<?php
namespace ItemNetworks\Service\ViewHelper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use ItemNetworks\View\Helper\ItemNetworksViewHelper;

class ItemNetworksFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $arrS = [
            'api'=>$services->get('Omeka\ApiManager')
            ,'conn' => $services->get('Omeka\Connection')
            ,'logger' => $services->get('Omeka\Logger')
            ,'entityManager' => $services->get('Omeka\EntityManager')
        ]; 

        return new ItemNetworksViewHelper($arrS);
    }
}