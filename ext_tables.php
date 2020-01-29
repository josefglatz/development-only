<?php
/**
 * !!!!!!!!
 * HAVE IN MIND that ext_tables.php **ISN'T** loaded in a frontend request
 * !!!!!!!!
 */
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        // unset Install Tool Report for local instances
        if (\TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext()->isDevelopment()
            && isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['security'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['security'] as $key => $value) {
                if ($value === \TYPO3\CMS\Install\Report\SecurityStatusReport::class) {
                    unset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['security'][$key]);
                }
            }
        }
    },
    'development_only'
);
