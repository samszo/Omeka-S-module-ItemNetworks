<?php

/*

 */

namespace ResourceNetworks;

if (!class_exists(\Generic\AbstractModule::class)) {
    require file_exists(dirname(__DIR__) . '/Generic/AbstractModule.php')
        ? dirname(__DIR__) . '/Generic/AbstractModule.php'
        : __DIR__ . '/src/Generic/AbstractModule.php';
}

use Generic\AbstractModule;
use Omeka\Module\Exception\ModuleCannotInstallException;
use Omeka\Module\Manager as ModuleManager;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractController;
use ArchiveRepertory\Form\ConfigForm;
use Laminas\View\Renderer\PhpRenderer;

class Module extends AbstractModule
{
    const NAMESPACE = __NAMESPACE__;

    public function onBootstrap(MvcEvent $event): void
    {
        parent::onBootstrap($event);

        //require_once __DIR__ . '/vendor/autoload.php';

    }

    protected function postInstall(): void
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        $settings->set('resourcenetwork_name', $settings->get('installation_title'));
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');
        $settings = $services->get('Omeka\Settings');
        $data = $settings->get('ResourceNetworksConfigs');

        $fieldset = $services->get('FormElementManager')->get(\ResourceNetworks\Form\ResourceNetworksFieldset::class);
        if(!$data){
            $defaultSettings = $config['config'];
            foreach ($defaultSettings as $name => $value) {
                $data[$name] = $settings->get($name, $value);
            }    
        }
        $dataForm = [];
        foreach ($data as $key => $value) {
            // Add fields for repeatable fieldsets with multiple fields.
            if (is_array($value)) {
                $subFieldsetName = "o:block[__blockIndex__][o:data][$key]";
                /** @var \Laminas\Form\Fieldset $subFieldset */
                $subFieldset = $fieldset->get($subFieldsetName);
                $subFieldsetBaseName = $subFieldsetName . '[__' . substr($key, 0, -1) . 'Index__]';
                /** @var \Laminas\Form\Fieldset $subFieldsetBase */
                $subFieldsetBase = $subFieldset->get($subFieldsetBaseName);
                foreach (array_values($value) as $subKey => $subValue) {
                    $newSubFieldsetName = $subFieldsetName . "[$subKey]";
                    /** @var \Laminas\Form\Fieldset $newSubFieldset */
                    $newSubFieldset = clone $subFieldsetBase;
                    $newSubFieldset
                        ->setName($newSubFieldsetName)
                        ->setAttribute('data-index', $subKey);
                    $subFieldset->add($newSubFieldset);
                    foreach ($subValue as $subSubKey => $subSubValue) {
                        $elementBaseName = $subFieldsetBaseName . "[$subSubKey]";
                        $elementName = "o:block[__blockIndex__][o:data][$key][$subKey][$subSubKey]";
                        $newSubFieldset
                            ->get($elementBaseName)
                            ->setName($elementName)
                            ->setValue($subSubValue);
                        $dataForm[$elementName] = $subSubValue;
                    }
                    // $newSubFieldset->populateValues($dataForm);
                }
                $subFieldset
                    ->remove($subFieldsetBaseName)
                    ->setAttribute('data-next-index', count($value));
            } else {
                $dataForm['o:block[__blockIndex__][o:data][' . $key . ']'] = $value;
            }
        }

        /*
        $form->init();
        $form->setData($dataForm);
        $html = $renderer->render('ResourceNetwork/module/config', [
            'form' => $form,
        ]);
        */
        $fieldset->populateValues($dataForm);

        $assetUrl = $renderer->plugin('assetUrl');
        //$view->headLink()->appendStylesheet($assetUrl('css/asset-form.css', 'Omeka'));
        $renderer->headScript()
            ->appendFile($assetUrl('js/colors-form.js', 'ResourceNetworks'), 'text/javascript', ['defer' => 'defer']);
        $renderer->headScript()
            ->appendFile($assetUrl('js/itemsets-form.js', 'ResourceNetworks'), 'text/javascript', ['defer' => 'defer']);

        $html = $renderer->formCollection($fieldset);
        //

        return $html;
    }

    public function handleConfigForm(AbstractController $controller)
    {
        $result = parent::handleConfigForm($controller);
        if (!$result) {
            return false;
        }
        $data = $controller->params()->fromPost();
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        $settings->set('ResourceNetworksConfigs', $data['o:block']['__blockIndex__']['o:data']);
    }    


}
