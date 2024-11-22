<?php
declare(strict_types=1);

namespace JosefGlatz\DevelopmentOnly\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class PagesRepository
 * @package JosefGlatz\DevelopmentOnly\Repository
 * @author Josef Glatz <typo3@josefglatz.at>
 */
class PagesRepository
{

    /**
     * Method to get pages on root level with is_siteroot=0
     * @throws Exception
     */
    public function getPagesOnRootLevelWithNoSiteRoot(): array
    {
        $cnx = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('pages');
        $qB = $cnx->createQueryBuilder();
        // remove restrictions
        $qB->getRestrictions()->removeByType(HiddenRestriction::class);

        return $qB
            ->select('uid')
            ->from('pages')
            ->where(
                $qB->expr()->and(
                    $qB->expr()->eq('doktype', $qB->createNamedParameter(1, ParameterType::INTEGER)),
                    $qB->expr()->eq('pid', $qB->createNamedParameter(0, ParameterType::INTEGER)),
                    $qB->expr()->eq('is_siteroot', $qB->createNamedParameter(0, ParameterType::INTEGER)),
                )

            )
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
