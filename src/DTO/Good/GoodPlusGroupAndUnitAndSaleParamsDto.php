<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO\Good;

use VetmanagerApiGateway\DTO\GoodGroup\GoodGroupOnlyDto;
use VetmanagerApiGateway\DTO\GoodSaleParam\GoodSaleParamOnlyDto;
use VetmanagerApiGateway\DTO\Unit\UnitOnlyDto;

final class GoodPlusGroupAndUnitAndSaleParamsDto extends GoodOnlyDTO
{
    /**
     * @param int|null $id
     * @param int|null $group_id
     * @param string|null $title
     * @param int|null $unit_storage_id
     * @param int|null $is_warehouse_account
     * @param int|null $is_active
     * @param string|null $code
     * @param int|null $is_call
     * @param int|null $is_for_sale
     * @param string|null $barcode
     * @param string|null $create_date
     * @param string|null $description
     * @param string|null $prime_cost
     * @param int|null $category_id
     * @param GoodGroupOnlyDto|null $group
     * @param UnitOnlyDto|null $unitStorage
     * @param GoodSaleParamOnlyDto[] $goodSaleParams
     */
    public function __construct(
        protected ?int              $id,
        protected ?int              $group_id,
        protected ?string           $title,
        protected ?int              $unit_storage_id,
        protected ?int              $is_warehouse_account,
        protected ?int              $is_active,
        protected ?string           $code,
        protected ?int              $is_call,
        protected ?int              $is_for_sale,
        protected ?string           $barcode,
        protected ?string           $create_date,
        protected ?string           $description,
        protected ?string           $prime_cost,
        protected ?int              $category_id,
        protected ?GoodGroupOnlyDto $group,
        protected ?UnitOnlyDto      $unitStorage,
        protected ?array            $goodSaleParams
    )
    {
        parent::__construct(
            $id,
            $group_id,
            $title,
            $unit_storage_id,
            $is_warehouse_account,
            $is_active,
            $code,
            $is_call,
            $is_for_sale,
            $barcode,
            $create_date,
            $description,
            $prime_cost,
            $category_id
        );
    }

    public function getGoodGroupOnlyDto(): ?GoodGroupOnlyDto
    {
        return $this->group;
    }

    public function getUnitOnlyDto(): ?UnitOnlyDto
    {
        return $this->unitStorage;
    }

    /** @return GoodSaleParamOnlyDto[] */
    public function getGoodSaleParamsOnlyDtos(): array
    {
        return $this->goodSaleParams;
    }
}
