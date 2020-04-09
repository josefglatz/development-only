<?php
/**
 * !!!!!!!!
 * HAVE IN MIND that ext_tables.php **ISN'T** loaded in a frontend request
 * !!!!!!!!
 */


defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    static function ($extKey) {

        // @TODO: If TYPO3 =< 9.5 LTS support will be dropped: remove old applicationContext method and simplify check
        $devContext = class_exists(\TYPO3\CMS\Core\Core\Environment::class)
            ? \TYPO3\CMS\Core\Core\Environment::getContext()->isDevelopment()
            : \TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext()->isDevelopment();

        // unset Install Tool Report for local instances
        if ($devContext && isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['security'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['security'] as $key => $value) {
                if ($value === \TYPO3\CMS\Install\Report\SecurityStatusReport::class) {
                    unset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['security'][$key]);
                }
            }
        }
    },
    'development_only'
);
