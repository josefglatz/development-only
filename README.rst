\|
`Issues`_ \| `Packagist`_ \|

TYPO3 Extension ``development-only``
====================================

  Development Only settings for TYPO3 CMS projects

:Repository:  https://github.com/josefglatz/development-only/



What is it good for?
--------------------

In a development environment you often need adoptions to better concentrate on the project itself. This extension helps
by improving the overall productivity and hides what is not needed in such an environment.

What does the extension offer you?
----------------------------------

  If you have ideas, snippets which needs to be added, please leave me an
  `Issue`_.

Each of the features are enabled automatically if the extension is activated and the ApplicationContext is set to ``Development``.

1. Hide Install Tool Security Check in Reports module (TYPO3 =< 11 LTS only)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

``\TYPO3\CMS\Install\Report\SecurityStatusReport`` is disabled.

* You get no warning if you use ``joh316`` as Install Tool password.
* You get no warning if your Install Tool is activated (forever).


2. Set common $GLOBALS['TYPO3_CONF_VARS'] for development context
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- ``SYS/sitename``: Adding the prefix "DEV"
- ``SYS/devIPmask``: *
- ``SYS/displayErrors``: true
- ``SYS/trustedHostsPattern``: Flexible and not strict value
- ``SYS/exceptionalErrors``: more verbose setting
- ``BE/debug``: true, to show field names, ...
- ``BE/lockSSL``: true, because with DDEV or any other professional dev environment it's o-o-t-b possible to use ssl localy, ...
- ``BE/sessionTimeout``: set to very high value
- ``BE/installToolPassword``: set to classic value ``joh316``
- ``FE/sessionTimeout``: set to very high value
- ``FE/debug``: active

3. Disables yoast-seo-for-typo3/yoast_seo in page module
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The snippet preview in development context is not working and is disabled via
User TSconfig in development context with
`setup.override.hideYoastInPageModule = 1`.

4. Setting backend pagetree settings
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- The PageId is always shown next to the page nav-/title

5. Remove the requirement of a multi-factor authentication for a user
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This makes it possible to create a temporary user for debug situations without
having to deal with MFA things.

------------



Installation
------------

``composer require --dev josefglatz/development-only``

  Since every component works only in the Development Application Context I
  advice you to require the extension only as ``require-dev`` package in your
  TYPO3 instance. Make sure to use ``composer install --no-dev`` while packaging
  your TYPO3 project for the productive hosting environment.


How to check the installation
-----------------------------

For TYPO3 12 and up: You find a status in the TYPO3 report module (if the report
module is installed).

- throws an error notice if the extension is active in a non-development context
- in composer mode: throws an error notice if the extension is in "require"
  section instead of "require-dev" section



------------


.. _Adding documentation: https://docs.typo3.org/typo3cms/CoreApiReference/ExtensionArchitecture/Documentation/Index.html
.. _Issue: https://github.com/josefglatz/development-only/issues/new/choose
.. _Issues: https://github.com/josefglatz/development-only/issues
.. _Packagist: https://packagist.org/packages/josefglatz/development-only
