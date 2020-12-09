<?php

declare(strict_types=1);

namespace App\Application\Http\Request\Advertisement;

use App\Application\Validator\Constraints\EntityExists;
use App\Domain\Advertisement\Entity\Category;
use App\Domain\Currency\Entity\Currency;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

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
     *
     * @EntityExists(class=Currency::class)
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

    /**
     * @var AttachmentDto[]|array
     *
     * @OA\Property(type="array", @OA\Items(ref=@Model(type=AttachmentDto::class)))
     *
     * @Assert\Type(type="array")
     * @Assert\Count(min=1, max=10)
     * @Assert\NotBlank
     * @Assert\Valid
     */
    public $attachments;

    public function addAttachment(AttachmentDto $dto): void
    {
        $this->attachments[] = $dto;
    }
}
