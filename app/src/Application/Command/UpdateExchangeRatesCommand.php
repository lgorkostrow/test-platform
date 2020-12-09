<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Currency\Enum\ExchangeRatesProviderEnum;
use App\Domain\Currency\UseCase\UpdateExchangeRatesCommand as DomainCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateExchangeRatesCommand extends Command
{
    protected static $defaultName = 'app:update:exchange-rate';

    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        parent::__construct();

        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('provider', InputArgument::REQUIRED, 'Exchange rate provider')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $provider = $input->getArgument('provider');
        if (!in_array($provider, ExchangeRatesProviderEnum::VALID_CHOICES, true)) {
            throw new InvalidArgumentException(
                'Accepted providers: ' . implode(',', ExchangeRatesProviderEnum::VALID_CHOICES)
            );
        }

        $this->commandBus->dispatch(new DomainCommand($provider));

        return Command::SUCCESS;
    }
}
