<?php
namespace ResourceNetworks\Service\ViewHelper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use ResourceNetworks\View\Helper\ResourceNetworksViewHelper;

class ResourceNetworksFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $arrS = [
            'api'=>$services->get('Omeka\ApiManager')
            ,'logger' => $services->get('Omeka\Logger')
            ,'settings' => $services->get('Omeka\Settings')
        ]; 
        
        return new ResourceNetworksViewHelper($arrS);
    }
}