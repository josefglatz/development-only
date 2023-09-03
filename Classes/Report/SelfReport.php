<?php
declare(strict_types=1);

namespace JosefGlatz\DevelopmentOnly\Report;

use Nadar\PhpComposerReader\ComposerReader;
use Nadar\PhpComposerReader\RequireDevSection;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Localization\LanguageService;
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
    public const EXTKEY = 'josefglatz/development-only';

    public function getStatus(): array
    {
        $contextDev = Environment::getContext()->isDevelopment();

        $status[] = GeneralUtility::makeInstance(
            Status::class,
            $this->getLanguageService()
                ->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.status.runningdevelopmentmode.title.label'),
            ($contextDev)
                ? $this->getLanguageService()->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.status.generic.title.ok')
                : $this->getLanguageService()->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.status.generic.title.notok'),
            ($contextDev)
                ? $this->getLanguageService()->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.status.runningdevelopmentmode.message.ok')
                : $this->getLanguageService()->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.status.runningdevelopmentmode.message.notok'),
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
        return $this->getLanguageService()
            ->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.label');
    }

    protected function getDevelopmentContextSeverity()
    {
        $applicationContextDevelopment = Environment::getContext()->isDevelopment();

        if (!$applicationContextDevelopment) {
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
                if ($key === self::EXTKEY) {
                    $installedAsRequireDev = true;
                    break;
                }
                $installedAsRequireDev = false;
            }
        }

        return GeneralUtility::makeInstance(
            Status::class,
            'Package installed as devDependency',
            $installedAsRequireDev
                ? $this->getLanguageService()
                ->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.status.generic.title.ok')
                : $this->getLanguageService()
                ->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.status.devdependencies.title.notok'),
            $installedAsRequireDev ? $this->getLanguageService()
                ->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.status.devdependencies.message.ok')
                : $this->getLanguageService()
                ->sL('LLL:EXT:development_only/Resources/Private/Language/locallang.xlf:report.status.devdependencies.message.notok'),
            $installedAsRequireDev ? ContextualFeedbackSeverity::OK : ContextualFeedbackSeverity::WARNING
        );
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
