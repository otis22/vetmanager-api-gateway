<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\ActiveRecord\GoodGroup;

use VetmanagerApiGateway\ActiveRecord\AbstractActiveRecord;
use VetmanagerApiGateway\ActiveRecord\CreatableInterface;
use VetmanagerApiGateway\ActiveRecord\DeletableInterface;
use VetmanagerApiGateway\ActiveRecordFactory;
use VetmanagerApiGateway\DTO\GoodGroup\GoodGroupOnlyDto;
use VetmanagerApiGateway\DTO\GoodGroup\GoodGroupOnlyDtoInterface;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Facade;

///**
// * @property-read GoodGroupOnlyDto $originalDto
// * @property string $title
// * @property ?int $priceId
// * @property bool $isService Default: false
// * @property ?float $markup
// * @property bool $isShowInVaccines Default: false
// * @property-read array{
// *     id: string,
// *     title: string,
// *     is_service: string,
// *     markup: ?string,
// *     is_show_in_vaccines: string,
// *     price_id: ?string
// * } $originalDataArray
// */
class GoodGroup extends AbstractActiveRecord implements GoodGroupOnlyDtoInterface, CreatableInterface, DeletableInterface
{
    public static function getRouteKey(): string
    {
        return 'goodGroup';
    }

    public static function getDtoClass(): string
    {
        return GoodGroupOnlyDto::class;
    }

    public function __construct(ActiveRecordFactory $activeRecordFactory, GoodGroupOnlyDto $modelDTO)
    {
        parent::__construct($activeRecordFactory, $modelDTO);
        $this->modelDTO = $modelDTO;
    }

    /** @throws VetmanagerApiGatewayException */
    public function create(): self
    {
        return (new Facade\GoodGroup($this->activeRecordFactory))->createNewUsingArray($this->getAsArrayWithSetPropertiesOnly());
    }

    /** @throws VetmanagerApiGatewayException */
    public function update(): self
    {
        return (new Facade\GoodGroup($this->activeRecordFactory))->updateUsingIdAndArray($this->getId(), $this->getAsArrayWithSetPropertiesOnly());
    }

    /** @throws VetmanagerApiGatewayException */
    public function delete(): void
    {
        (new Facade\GoodGroup($this->activeRecordFactory))->delete($this->getId());
    }

    /** @inheritDoc */
    public function getId(): int
    {
        return $this->modelDTO->getId();
    }

    public function getTitle(): string
    {
        return $this->modelDTO->getTitle();
    }

    /** @inheritDoc */
    public function getIsService(): bool
    {
        return $this->modelDTO->getIsService();
    }

    /** @inheritDoc */
    public function getMarkup(): ?float
    {
        return $this->modelDTO->getMarkup();
    }

    /** @inheritDoc */
    public function getIsShowInVaccines(): bool
    {
        return $this->modelDTO->getIsShowInVaccines();
    }

    /** @inheritDoc */
    public function getPriceId(): ?int
    {
        return $this->modelDTO->getPriceId();
    }

    /** @inheritDoc */
    public function setTitle(?string $value): static
    {
        return self::setNewModelDtoFluently($this, $this->modelDTO->setTitle($value));
    }

    /** @inheritDoc */
    public function setIsService(?bool $value): static
    {
        return self::setNewModelDtoFluently($this, $this->modelDTO->setIsService($value));
    }

    /** @inheritDoc */
    public function setMarkup(?float $value): static
    {
        return self::setNewModelDtoFluently($this, $this->modelDTO->setMarkup($value));
    }

    /** @inheritDoc */
    public function setIsShowInVaccines(?bool $value): static
    {
        return self::setNewModelDtoFluently($this, $this->modelDTO->setIsShowInVaccines($value));
    }

    /** @inheritDoc */
    public function setPriceId(?int $value): static
    {
        return self::setNewModelDtoFluently($this, $this->modelDTO->setPriceId($value));
    }
}
