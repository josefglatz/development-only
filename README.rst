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

  The extension is still in development. If you have ideas, snippets which needs to be added, please leave me an `Issue`_.

Each of the features are enabled automatically if the extension is activated and the ApplicationContext is set to ``Development``.

1. Hide Install Tool Security Check in Reports module
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

``\TYPO3\CMS\Install\Report\SecurityStatusReport`` is disabled.

* You get no warning if you use ``joh316`` as Install Tool password.
* You get no warning if your Install Tool is activated (forever).



------------



Installation
------------

``composer require --dev josefglatz/development-only``

  Since every component works only in the Development Application Context I advice you to require the extension only as ``require-dev`` package in your TYPO3 instance. Make sure to use ``composer install --no-dev`` while packaging your TYPO3 project for the productive hosting environment.


.. _Adding documentation: https://docs.typo3.org/typo3cms/CoreApiReference/ExtensionArchitecture/Documentation/Index.html
.. _Issue: https://github.com/josefglatz/development-only/issues/new/choose
.. _Issues: https://github.com/josefglatz/development-only/issues
.. _Packagist: https://packagist.org/packages/josefglatz/development-only
