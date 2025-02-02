<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO\Role;

use VetmanagerApiGateway\ApiDataInterpreter\ToBool;
use VetmanagerApiGateway\ApiDataInterpreter\ToInt;
use VetmanagerApiGateway\ApiDataInterpreter\ToString;
use VetmanagerApiGateway\DTO\AbstractDTO;

final class RoleOnlyDto extends AbstractDTO implements RoleOnlyDtoInterface
{
    /**
     * @param int|null $id
     * @param string|null $name
     * @param int|null $super
     */
    public function __construct(
        protected ?int $id,
        protected ?string $name,
        protected ?int $super
    ) {
    }

    public function getId(): int
    {
        return (new ToInt($this->id))->getPositiveIntOrThrow();
    }

    public function getName(): string
    {
        return ToString::fromStringOrNull($this->name)->getStringEvenIfNullGiven();
    }

    public function getIsSuper(): bool
    {
        return ToBool::fromIntOrNull($this->super)->getBoolOrThrowIfNull();
    }

    public function setName(string $value): static
    {
        return self::setPropertyFluently($this, 'name', $value);
    }

    public function setIsSuper(bool $value): static
    {
        return self::setPropertyFluently($this, 'super', (int)$value);
    }

//    /** @param array{
//     *     "id": numeric-string,
//     *     "name": string,
//     *     "super": string,
//     * } $originalDataArray
//     */
}
