<?php 
namespace ResourceNetworks\Form;

use Laminas\Form\Element;
use Laminas\Form\Fieldset;
use Omeka\Form\Element\ItemSetSelect;

class ResourceNetworksFieldset extends Fieldset
{
    public function init(): void
    {
        $this
            ->add([
                'name' => 'o:block[__blockIndex__][o:data][heading]',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'Block title', // @translate
                    'info' => 'Heading for the block, if any.', // @translate
                ],
            ])
            ->add([
                'name' => 'o:block[__blockIndex__][o:data][itemsets]',
                'type' => Fieldset::class,
                'options' => [
                    'label' => 'Visibilité des Collections', // @translate
                ],
                'attributes' => [
                    'class' => 'itemsets-list',
                    'data-next-index' => '0',
                ],
            ])
            ->add([
                'name' => 'o:block[__blockIndex__][o:data][colors]',
                'type' => Fieldset::class,
                'options' => [
                    'label' => 'Colors', // @translate
                ],
                'attributes' => [
                    'class' => 'colors-list',
                    'data-next-index' => '0',
                ],
            ]);


            $fieldsetBase = $this->get('o:block[__blockIndex__][o:data][colors]');
            $fieldsetBase
                ->add([
                    'name' => 'o:block[__blockIndex__][o:data][colors][__colorIndex__]',
                    'type' => Fieldset::class,
                    'options' => [
                        'label' => 'Color for class or properties', // @translate
                        'use_as_base_fieldset' => true,
                    ],
                    'attributes' => [
                        'class' => 'color-data',
                        'data-index' => '__colorIndex__',
                    ],
                ]);
            $fieldsetRepeat = $fieldsetBase->get('o:block[__blockIndex__][o:data][colors][__colorIndex__]');
            $fieldsetRepeat
                ->add([
                    'name' => 'o:block[__blockIndex__][o:data][colors][__colorIndex__][class]',
                    'type' => Element\Text::class,
                    'options' => [
                        'label' => 'Class or property', // @translate
                    ],
                ])
                ->add([
                    'name' => 'o:block[__blockIndex__][o:data][colors][__colorIndex__][color]',
                    'type' => Element\Color::class,
                    'options' => [
                        'label' => 'Color',
                    ],
                ])
                // TODO Move remove / creation of new fieldset to js?
                ->add([
                    'name' => 'add_color',
                    'type' => Element\Button::class,
                    'options' => [
                        'label' => 'Add another', // @translate
                    ],
                    'attributes' => [
                        'class' => 'color-form-add button',
                    ],
                ])
                ->add([
                    'name' => 'remove_color',
                    'type' => Element\Button::class,
                    'options' => [
                        'label' => 'Remove', // @translate
                    ],
                    'attributes' => [
                        'class' => 'color-form-remove button red',
                    ],
                ]);


                $fieldsetBaseIS = $this->get('o:block[__blockIndex__][o:data][itemsets]');
                $fieldsetBaseIS
                    ->add([
                        'name' => 'o:block[__blockIndex__][o:data][itemsets][__itemsetIndex__]',
                        'type' => Fieldset::class,
                        'options' => [
                            'label' => 'Choisir une collection à afficher', // @translate
                            'use_as_base_fieldset' => true,
                        ],
                        'attributes' => [
                            'class' => 'itemset-data',
                            'data-index' => '__itemsetIndex__',
                        ],
                    ]);
                $fieldsetRepeatIS = $fieldsetBaseIS->get('o:block[__blockIndex__][o:data][itemsets][__itemsetIndex__]');
                $fieldsetRepeatIS
                    ->add([
                        'name' => 'o:block[__blockIndex__][o:data][itemsets][__itemsetIndex__][itemset]',
                        'type' => ItemSetSelect::class,
                        'options' => [
                            'label' => 'Collection visible', // @translate
                            'empty_option' => 'Choisir une collection…', // @translate
                            'query' => ['is_open' => true],
                        ],
                        'attributes' => [
                            'required' => true,
                            'class' => 'chosen-select',
                            'id' => 'library-item-set',
                        ],
                    ])
                    // TODO Move remove / creation of new fieldset to js?
                    ->add([
                        'name' => 'add_itemset',
                        'type' => Element\Button::class,
                        'options' => [
                            'label' => 'Add another', // @translate
                        ],
                        'attributes' => [
                            'class' => 'itemset-form-add button',
                        ],
                    ])
                    ->add([
                        'name' => 'remove_itemset',
                        'type' => Element\Button::class,
                        'options' => [
                            'label' => 'Remove', // @translate
                        ],
                        'attributes' => [
                            'class' => 'itemset-form-remove button red',
                        ],
                    ]);

    
    }
}