<?php

use JosefGlatz\DevelopmentOnly\Controller\BackendController;

return [
    // Main backend rendering setup for the TYPO3 Backend
    'development_only_backend_ajaxbackend_rootpages' => [
        'path' => '/development_only/backend/rootpages',
        'target' => BackendController::class . '::rootPagesLookupAction'
    ],
];
