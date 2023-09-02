<?php
declare(strict_types=1);

namespace JosefGlatz\DevelopmentOnly\Report;

use Nadar\PhpComposerReader\ComposerReader;
use Nadar\PhpComposerReader\RequireDevSection;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Reports\StatusProviderInterface;
use TYPO3\CMS\Reports\Status;

/**
 * Class SelfReport
 * @package JosefGlatz\DevelopmentOnly
 * @author Josef Glatz <typo3@josefglatz.at>
 */
class SelfReport implements StatusProviderInterface
{
    public function getStatus(): array
    {
        $contextDev = Environment::getContext()->isDevelopment();

        $status[] = GeneralUtility::makeInstance(
            Status::class,
            'Instance running in development mode',
            ($contextDev || getenv('IS_DDEV_PROJECT')) ? 'OK' : 'Not OK.',
            ($contextDev || getenv('IS_DDEV_PROJECT')) ? 'Super! You are running the extension in development context.'  : 'josefglatz/development-only needs to be enabled only in a non-productive environment. (E.g. by requiring the package only in with "composer req josefglatz/development-only --dev")',
            $this->getDevelopmentContextSeverity()
        );

        if ($this->getComposerDistributionStatus()) {
            $status[] = $this->getPackageInstallation();
        }

        return $status;
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        // @todo: move report label to xlf file
        return 'Development Only "josefglatz/development-only"';
    }

    protected function getDevelopmentContextSeverity()
    {
        $applicationContextDevelopment = Environment::getContext()->isDevelopment();

        if (!$applicationContextDevelopment && getenv('IS_DDEV_PROJECT') !== 'true') {
            $severity = ContextualFeedbackSeverity::ERROR;
        } else {
            $severity = ContextualFeedbackSeverity::OK;
        }

        return $severity;
    }

    private function getComposerDistributionStatus()
    {
        return Environment::isComposerMode();
    }

    private function getPackageInstallation()
    {
        $composerFileReader = new ComposerReader(Environment::getProjectPath() . '/composer.json');
        if ($composerFileReader->canRead()) {
            $requiredevSection = new RequireDevSection($composerFileReader);
            foreach ($requiredevSection as $key => $package) {
                if ($key === 'josefglatz/development-only') {
                    $installedAsRequireDev = true;
                    break;
                }
                $installedAsRequireDev = false;
            }
        }

        return GeneralUtility::makeInstance(
            Status::class,
            'Package installed as devDependency',
            $installedAsRequireDev ? 'OK' : 'Nope. Please fix it!',
            $installedAsRequireDev ? $this->getLanguageService()->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.devdependencies.message.ok') : $this->getLanguageService()->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.devdependencies.message.notok'),
            $installedAsRequireDev ? ContextualFeedbackSeverity::OK : ContextualFeedbackSeverity::WARNING
        );
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
