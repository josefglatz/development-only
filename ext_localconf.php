<?php
declare(strict_types=1);

call_user_func(
    static function ($extKey) {
        $devContext = class_exists(\TYPO3\CMS\Core\Core\Environment::class)
            ? \TYPO3\CMS\Core\Core\Environment::getContext()->isDevelopment()
            : \TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext()->isDevelopment();


        if ($devContext) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = 'DEV: ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] . '';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'] = '*';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors'] = true;
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = '.+';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['exceptionalErrors'] = E_ALL & ~(E_STRICT | E_NOTICE | E_DEPRECATED | E_USER_DEPRECATED);
            $GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] = true;
            $GLOBALS['TYPO3_CONF_VARS']['BE']['sessionTimeout'] = 31536000;
            // joh316
            $GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = '$argon2i$v=19$m=16384,t=16,p=2$aDZHUmRqTUFUR1dpYWlqdA$aR5bQdCiYwZ6ClUPpTzMqhnQt24CprQWKU2VavXp3T4';

            $GLOBALS['TYPO3_CONF_VARS']['FE']['sessionTimeout'] = 31536000;
            $GLOBALS['TYPO3_CONF_VARS']['FE']['debug'] = true;
        }
    },
    'development_only'
);
