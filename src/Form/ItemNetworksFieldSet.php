<?php 
namespace ItemNetworks\Form;

use Laminas\Form\Element;
use Laminas\Form\Fieldset;

class ItemNetworksFieldset extends Fieldset
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
    
    }
}