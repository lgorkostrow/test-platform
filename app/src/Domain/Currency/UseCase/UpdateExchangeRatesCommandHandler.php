<?php

declare(strict_types=1);

namespace App\Domain\Currency\UseCase;

use App\Domain\Common\Message\CommandHandlerInterface;
use App\Domain\Currency\Manager\ExchangeRatesManager;

class UpdateExchangeRatesCommandHandler implements CommandHandlerInterface
{
    private ExchangeRatesManager $manager;

    public function __construct(ExchangeRatesManager $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(UpdateExchangeRatesCommand $command): void
    {
        $this->manager->update($command->getProvider());
    }
}
