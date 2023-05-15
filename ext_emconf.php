<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'development only',
    'description' => 'Development Only settings for TYPO3 CMS projects',
    'version' => '1.0.1',
    'state' => 'stable',
    'author' => 'Josef Glatz',
    'author_email' => 'josefglatz@gmailcom',
    'clearCacheOnLoad' => true,
    'category' => 'be',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
