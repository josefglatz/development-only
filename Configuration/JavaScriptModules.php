<?php

return [
    'dependencies' => ['core', 'backend'],
    'tags' => [
        'backend.contextmenu',
    ],
    'imports' => [
        '@josefglatz/development-only/' => 'EXT:development_only/Resources/Public/JavaScript/',
    ],
];
