<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\ActiveRecord\Unit;

use VetmanagerApiGateway\ActiveRecord\AbstractActiveRecord;
use VetmanagerApiGateway\ActiveRecordFactory;
use VetmanagerApiGateway\DTO\Unit\StatusEnum;
use VetmanagerApiGateway\DTO\Unit\UnitOnlyDto;
use VetmanagerApiGateway\DTO\Unit\UnitOnlyDtoInterface;

///**
// * @property-read UnitOnlyDto $originalDto
// * @property int $id
// * @property string $title
// * @property StatusEnum $status Default: 'active'
// * @property-read array{
// *     id: numeric-string,
// *     title: string,
// *     status: string
// * } $originalDataArray
// */
final class Unit extends AbstractActiveRecord implements UnitOnlyDtoInterface
{
    public static function getRouteKey(): string
    {
        return 'unit';
    }

    public static function getDtoClass(): string
    {
        return UnitOnlyDto::class;
    }

    public function __construct(ActiveRecordFactory $activeRecordFactory, UnitOnlyDto $modelDTO)
    {
        parent::__construct($activeRecordFactory, $modelDTO);
        $this->modelDTO = $modelDTO;
    }

    /** @inheritDoc */
    public function getId(): int
    {
        return $this->modelDTO->getId();
    }

    public function getTitle(): ?string
    {
        return $this->modelDTO->getTitle();
    }

    /** @inheritDoc */
    public function getStatus(): StatusEnum
    {
        return $this->modelDTO->getStatus();
    }

    /** @inheritDoc */
    public function setTitle(?string $value): static
    {
        return self::setNewModelDtoFluently($this, $this->modelDTO->setTitle($value));
    }

    /** @inheritDoc */
    public function setStatusFromString(?string $value): static
    {
        return self::setNewModelDtoFluently($this, $this->modelDTO->setStatusFromString($value));
    }

    /** @inheritDoc */
    public function setStatusFromEnum(StatusEnum $value): static
    {
        return self::setNewModelDtoFluently($this, $this->modelDTO->setStatusFromEnum($value));
    }
}
