<?php

declare(strict_types=1);

namespace App\Application\Doctrine\DataFixtures;

use App\Domain\Currency\Entity\Currency;
use App\Domain\Currency\Enum\DefaultCurrencyEnum;
use Doctrine\Persistence\ObjectManager;

class CurrencyFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (array_merge(DefaultCurrencyEnum::LIST, [DefaultCurrencyEnum::UAH]) as $ccy) {
            $manager->persist(new Currency($ccy, 1, 1));
        }

        $manager->flush();
    }
}
