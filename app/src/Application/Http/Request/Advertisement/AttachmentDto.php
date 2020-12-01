<?php

declare(strict_types=1);

namespace App\Application\Http\Request\Advertisement;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class AttachmentDto
{
    /**
     * @var UploadedFile
     *
     * @Assert\Image(maxSize="5m")
     * @Assert\NotBlank
     */
    public $file;

    /**
     * @var bool
     *
     * @Assert\Type(type="bool")
     * @Assert\NotNull
     */
    public $featured;
}
