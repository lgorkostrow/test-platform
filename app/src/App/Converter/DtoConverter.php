<?php

namespace App\App\Converter;

use Symfony\Component\Serializer\SerializerInterface;

class DtoConverter
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function convertToDto(string $dtoClass, array $data): object
    {
        return $this->serializer->denormalize(
            $data,
            $dtoClass,
        );
    }
}
