<?php

declare(strict_types=1);

namespace App\Application\Http\Request\Advertisement;

use App\Application\Validator\Constraints\EntityExists;
use App\Domain\Common\Enum\CurrencyEnum;
use App\Domain\Advertisement\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAdvertisementRequest
{
    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    public $title;

    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\Length(max="10000")
     * @Assert\NotBlank
     */
    public $description;

    /**
     * @var float
     *
     * @Assert\Type("numeric")
     * @Assert\NotBlank
     * @Assert\GreaterThan(0)
     */
    public $price;

    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\NotBlank
     * @Assert\Choice(choices=CurrencyEnum::VALID_CHOICES)
     */
    public $currency;

    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\NotBlank
     *
     * @EntityExists(class=Category::class)
     */
    public $category;
}
