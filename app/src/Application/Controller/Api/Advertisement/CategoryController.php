<?php

declare(strict_types=1);

namespace App\Application\Controller\Api\Advertisement;

use App\Application\Utils\MessengerUtils;
use App\Domain\Advertisement\Query\GetCategoriesQuery;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @Rest\Route(path="/category")
 */
class CategoryController extends AbstractFOSRestController
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @Rest\Get(path="")
     *
     * @Rest\QueryParam(name="limit", requirements="\d+", strict=true, default=15)
     * @Rest\QueryParam(name="offset", requirements="\d+", strict=true, default=0)
     *
     * @Rest\View
     *
     * @param ParamFetcherInterface $paramFetcher
     * @return mixed
     */
    public function getList(ParamFetcherInterface $paramFetcher)
    {
        return MessengerUtils::getResultFromEnvelope(
            $this->queryBus->dispatch(new GetCategoriesQuery(
                $paramFetcher->get('limit'),
                $paramFetcher->get('offset')
            ))
        );
    }
}
