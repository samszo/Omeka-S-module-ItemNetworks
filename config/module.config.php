<?php
namespace ItemNetworks;

return [

    'view_helpers' => [
        
        'invokables' => [
            'ItemNetworksViewHelper' => View\Helper\ItemNetworksViewHelper::class,
        ],                
        'factories'  => [
            'ItemNetworksFactory' => Service\ViewHelper\ItemNetworksFactory::class,
        ],

    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],    
    'block_layouts' => [
        'invokables' => [
            'ItemNetworks' => Site\BlockLayout\ItemNetworks::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            Form\ItemNetworksFieldset::class => Form\ItemNetworksFieldset::class,
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'ItemNetworks' => [
        'block_settings' => [
            'ItemNetworks' => [
                'heading' => '',
                'colors' => [
                    [
                        'class' => null,
                        'color' => null,
                    ],
                ],
            ],
        ],
    ],
];
