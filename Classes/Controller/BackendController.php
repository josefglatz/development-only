<?php

namespace JosefGlatz\DevelopmentOnly\Controller;

use JosefGlatz\DevelopmentOnly\Repository\PagesRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

final class BackendController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly PagesRepository $pagesRepository,
    ) {}

    /**
     * @throws \JsonException
     */
    public function rootPagesLookupAction(ServerRequestInterface $request): ResponseInterface
    {
        // Setup response
        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'application/json; charset=utf-8');

        // fetch affected pages
        $pages = $this->pagesRepository->getPagesOnRootLevelWithNoSiteRoot();

        if (empty($pages)) {
            $response->getBody()->write(
                json_encode([
                    'processedPages' => 0,
                ], JSON_THROW_ON_ERROR),
            );

            return $response;
        }
        $dataMap = [];
        foreach ($pages as $page) {
            if (MathUtility::canBeInterpretedAsInteger($page['uid'])) {
                $dataMap['pages'][$page['uid']] = [
                    'is_siteroot' => 1,
                    'hidden' => 0,
                ];
            }
        }

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        // Set data-array
        $dataHandler->start($dataMap, []);
        // Processing the data-array set by start()
        $dataHandler->process_datamap();

        $response->getBody()->write(
            json_encode([
                'processedPages' => count($dataMap['pages']),
                'dataHandlerErrors' => count($dataHandler->errorLog),

            ], JSON_THROW_ON_ERROR),
        );
        return $response;
    }
}
