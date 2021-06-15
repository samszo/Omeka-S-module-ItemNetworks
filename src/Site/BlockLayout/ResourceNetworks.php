<?php 
namespace ResourceNetworks\Site\BlockLayout;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\SitePageBlockRepresentation;
use Omeka\Api\Representation\SitePageRepresentation;
use Omeka\Api\Representation\SiteRepresentation;
use Omeka\Site\BlockLayout\AbstractBlockLayout;
use Omeka\Entity\SitePageBlock;
use Omeka\Stdlib\ErrorStore;

class ResourceNetworks extends AbstractBlockLayout
{
    /**
     * The default partial view script.
     */
    const PARTIAL_NAME = 'common/block-layout/ResourceNetworks';

    public function getLabel()
    {
        return 'ResourceNetworks'; // @translate
    }

    public function onHydrate(SitePageBlock $block, ErrorStore $errorStore): void
    {
        $data = $block->getData();

        if (!isset($data['colors'])) {
            $data['colors'] = [];
        }

        // Normalize values and purify html.
        $data['colors'] = array_map(function ($v) {
            $v += ['color' => null, 'class' => null];
            return $v;
        }, $data['colors']);

        // Trim all values, then remove empty color arrays: array without color
        $data['colors'] = array_values(array_filter(
            array_map(function ($v) {
                return array_map('trim', $v);
            }, $data['colors']),
            function ($color) {
                return !empty($color['color']);
            }
        ));

        $data = $block->setData($data);
    }

    public function form(
        PhpRenderer $view,
        SiteRepresentation $site,
        SitePageRepresentation $page = null,
        SitePageBlockRepresentation $block = null
    ) {
        // Factory is not used to make rendering simpler.
        $services = $site->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $defaultSettings = $services->get('Config')['ResourceNetworks']['block_settings']['ResourceNetworks'];
        $fieldset = $formElementManager->get(\ResourceNetworks\Form\ResourceNetworksFieldset::class);

        $data = $block ? $block->data() + $defaultSettings : $defaultSettings;

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

        $fieldset->populateValues($dataForm);

        $html = '<p>'
            . $view->translate('Un block qui affiche la cartographie des relations sous forme de réseau.') // @translate
            . '</p>';
        $html .= $view->formCollection($fieldset);
        return $html;
    }

    public function render(PhpRenderer $view, SitePageBlockRepresentation $block)
    {
        $vars = [
            'heading' => $block->dataValue('heading', ''),
            'colors' => $block->dataValue('colors', ''),
            'itemsets' => $block->dataValue('itemsets', ''),
        ];
        return $view->partial(self::PARTIAL_NAME, $vars);
    }

    public function getFulltextText(PhpRenderer $view, SitePageBlockRepresentation $block)
    {
        return strip_tags($this->render($view, $block));
    }

    
    public function prepareRender(PhpRenderer $view)
    {

        $view->headScript()->appendFile($view->assetUrl('js/d3.min.js','ResourceNetworks'));
        $view->headScript()->appendFile($view->assetUrl('js/reseau.js','ResourceNetworks'));
        $view->headLink()->appendStylesheet($view->assetUrl('css/w3.css','ResourceNetworks'));

    }

    public function prepareForm(PhpRenderer $view): void
    {
        $assetUrl = $view->plugin('assetUrl');
        //$view->headLink()->appendStylesheet($assetUrl('css/asset-form.css', 'Omeka'));
        $view->headScript()
            ->appendFile($assetUrl('js/colors-form.js', 'ResourceNetworks'), 'text/javascript', ['defer' => 'defer']);
        $view->headScript()
            ->appendFile($assetUrl('js/itemsets-form.js', 'ResourceNetworks'), 'text/javascript', ['defer' => 'defer']);
    }

}