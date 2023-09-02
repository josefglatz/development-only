<?php
declare(strict_types=1);

namespace JosefGlatz\DevelopmentOnly\Report;

use TYPO3\CMS\Core\Core\Environment;
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
            $contextDev ? 'OK' : 'Not OK.',
            $contextDev ? 'Super! You are running the extension in development context.'  : 'josefglatz/development-only needs to be enabled only in a non-productive environment. (E.g. by requiring the package only in with "composer req josefglatz/development-only --dev"',
            $this->getDevelopmentContextSeverity()
        );

        $pkgManager = GeneralUtility::makeInstance(PackageManager::class);

        debug($pkgManager);

        if ($this->getComposerDistributionStatus()) {
            debug($this->getPackageInstallation());
            die();
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
        
    }
}
