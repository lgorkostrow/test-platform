<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Entity;

use App\Domain\Advertisement\State\Advertisement\ArchivedState;
use App\Domain\Advertisement\State\Advertisement\DraftState;
use App\Domain\Advertisement\State\Advertisement\OnReviewState;
use App\Domain\Advertisement\State\Advertisement\PublishedState;
use App\Domain\Advertisement\ValueObject\AdvertisementDescription;
use App\Domain\Common\Entity\Timestampable;
use App\Domain\Common\Entity\TimestampableInterface;
use App\Domain\Common\Exception\BusinessException;
use App\Domain\Common\State\AbstractState;
use App\Domain\Common\ValueObject\Price;
use App\Domain\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Advertisement implements TimestampableInterface
{
    use Timestampable;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     */
    private string $id;

    /**
     * @var AbstractState
     *
     * @ORM\Column(type="advertisement_state")
     */
    private AbstractState $state;

    /**
     * @var AdvertisementDescription
     *
     * @ORM\Embedded(class=AdvertisementDescription::class, columnPrefix=false)
     */
    private AdvertisementDescription $description;

    /**
     * @var Price
     *
     * @ORM\Embedded(class=Price::class, columnPrefix=false)
     */
    private Price $price;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private Category $category;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private User $author;

    public function __construct(
        string $id,
        AdvertisementDescription $description,
        Price $price,
        Category $category,
        User $author
    ) {
        $this->id = $id;
        $this->state = new DraftState();
        $this->description = $description;
        $this->price = $price;
        $this->category = $category;
        $this->author = $author;
    }

    public function sendToReview(): void
    {
        $this->changeState(new OnReviewState());
    }

    public function publish(): void
    {
        $this->changeState(new PublishedState());
    }

    public function archive(): void
    {
        $this->changeState(new ArchivedState());
    }

    public function isDraft(): bool
    {
        return $this->state instanceof DraftState;
    }

    public function isOnReview(): bool
    {
        return $this->state instanceof OnReviewState;
    }

    public function isPublished(): bool
    {
        return $this->state instanceof PublishedState;
    }

    public function isArchived(): bool
    {
        return $this->state instanceof ArchivedState;
    }

    private function changeState(AbstractState $state)
    {
        if (!$this->state->canBeChangedTo($state)) {
            throw new BusinessException();
        }

        $this->state = $state;
    }
}
