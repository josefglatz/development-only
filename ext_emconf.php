<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'development only',
    'description' => 'Development Only settings for TYPO3 CMS projects',
    'version' => '2.0.0',
    'state' => 'stable',
    'author' => 'Josef Glatz',
    'author_email' => 'typo3@josefglatz.at',
    'clearCacheOnLoad' => true,
    'category' => 'be',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
