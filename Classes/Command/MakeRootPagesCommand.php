<?php

declare(strict_types=1);

namespace JosefGlatz\DevelopmentOnly\Command;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use JosefGlatz\DevelopmentOnly\Repository\PagesRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class MakeRootPagesCommand extends Command
{
    private PagesRepository $pagesRepository;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->pagesRepository = new PagesRepository();
    }

    protected function configure(): void
    {
        $this->setDescription('Mark pages in first level as root for a TYPO3 sites');
        $this->setHelp('Every not deleted page on root level gets updated if not already defined as root page.');
    }

    /**
     * Main method of command
     * @throws Exception
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!Environment::getContext()->isDevelopment()) {
            $output->writeln('<error>This command can only be executed in Development application context!</error>');
            return Command::INVALID;
        }

        // Step 1: Fetch all pages with pid=0
        $pages = $this->getPages();

        // Exit early if no update is needed
        if (empty($pages)) {
            $output->writeln('<info>No pages found to update on root level.</info>');
            $output->writeln('<error>Aborting command.</error>');
            return Command::FAILURE;
        }

        // Step 2: Prepare data map for TYPO3 DataHandler
        $dataMap = [];
        foreach ($pages as $page) {
            if (MathUtility::canBeInterpretedAsInteger($page['uid'])) {
                $dataMap['pages'][$page['uid']] = [
                    'is_siteroot' => 1,
                    'hidden' => 0,
                ];
            }
        }
        $output->writeln(
            '<info>Processing ' . count($pages) . ' page records with TYPO3 DataHandler...</info>'
        );

        // Step 3: Initialize and process DataHandler
        \TYPO3\CMS\Core\Core\Bootstrap::initializeBackendAuthentication();
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        // Set data-array
        $dataHandler->start($dataMap, []);
        // Processing the data-array set by start()
        $dataHandler->process_datamap();

        // Step 4: Inform human and return command
        if (!empty($dataHandler->errorLog)) {
            foreach ($dataHandler->errorLog as $error) {
                $output->writeln('<error>' . $error . '</error>');
            }
            return Command::FAILURE;
        }
        $output->writeln('<info>Updated ' . count($pages) . ' pages: "is_siteroot" and "hidden" properties where set to 1 successfully.</info>');
        return Command::SUCCESS;
    }

    /**
     * Method to get pages on root level with is_siteroot=0
     * @throws Exception
     */
    protected function getPages(): array
    {
        return $this->pagesRepository->getPagesOnRootLevelWithNoSiteRoot();
    }
}
