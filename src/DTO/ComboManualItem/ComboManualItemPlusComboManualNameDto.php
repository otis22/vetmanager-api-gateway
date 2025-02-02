<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO\ComboManualItem;

class ComboManualItemPlusComboManualNameDto extends ComboManualItemOnlyDto
{
    /**
     * @param int|null $id
     * @param int|null $combo_manual_id
     * @param string|null $title
     * @param string|null $value
     * @param string|null $dop_param1
     * @param string|null $dop_param2
     * @param string|null $dop_param3
     * @param int|null $is_active
     * @param ComboManualItemOnlyDto $comboManualName
     */
    public function __construct(
        protected ?int                   $id,
        protected ?int                   $combo_manual_id,
        protected ?string                $title,
        protected ?string                $value,
        protected ?string                $dop_param1,
        protected ?string                $dop_param2,
        protected ?string                $dop_param3,
        protected ?int                   $is_active,
        protected ComboManualItemOnlyDto $comboManualName
    )
    {
        parent::__construct(
            $id,
            $combo_manual_id,
            $title,
            $value,
            $dop_param1,
            $dop_param2,
            $dop_param3,
            $is_active,
        );
    }

    public function getComboManualItemOnlyDto(): ComboManualItemOnlyDto
    {
        return $this->comboManualName;
    }
}
