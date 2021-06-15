<?php
namespace ResourceNetworks;

return [

    'view_helpers' => [
        
        'invokables' => [
            'ResourceNetworksViewHelper' => View\Helper\ResourceNetworksViewHelper::class,
        ],                
        'factories'  => [
            'ResourceNetworksFactory' => Service\ViewHelper\ResourceNetworksFactory::class,
        ],

    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],    
    'block_layouts' => [
        'invokables' => [
            'ResourceNetworks' => Site\BlockLayout\ResourceNetworks::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            Form\ResourceNetworksFieldset::class => Form\ResourceNetworksFieldset::class,
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
    'ResourceNetworks' => [
        'block_settings' => [
            'ResourceNetworks' => [
                'heading' => '',
                'colors' => [
                    [
                        'class' => null,
                        'color' => null,
                    ],
                ],
                'itemsets' => [
                    [
                        'itemset' => null,
                    ],
                ],
            ],
        ],
    ],
];
