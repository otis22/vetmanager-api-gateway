<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO\City;

use VetmanagerApiGateway\ApiDataInterpreter\ToInt;
use VetmanagerApiGateway\ApiDataInterpreter\ToString;
use VetmanagerApiGateway\DTO\AbstractDTO;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayInnerException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;

final class CityOnlyDto extends AbstractDTO implements CityDtoInterface
{
    /**
     * @param int|string|null $id
     * @param string|null $title
     * @param int|null $type_id Default: 1
     */
    public function __construct(
        protected mixed $id,
        protected ?string $title,
        protected ?int $type_id
    )
    {
    }

    /** @return positive-int
     * @throws VetmanagerApiGatewayResponseException
     */
    public function getId(): int
    {
        return (new ToInt((int) $this->id))->getPositiveIntOrThrow();
    }

    public function getTitle(): string
    {
        return ToString::fromStringOrNull($this->title)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setTitle(string $value): static
    {
        return self::setPropertyFluently($this, 'title', $value);
    }

    /** @return positive-int
     * @throws VetmanagerApiGatewayResponseException
     */
    public function getTypeId(): int
    {
        return (new ToInt($this->type_id))->getPositiveIntOrThrow();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setTypeId(int $value): static
    {
        return self::setPropertyFluently($this, 'type_id', $value);
    }
//    /** @param array{
//     *     "id": string,
//     *     "title": string,
//     *     "type_id": string,
//     * } $originalDataArray
//     */
}
