<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO;

use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Hydrator\ApiBool;
use VetmanagerApiGateway\Hydrator\ApiInt;
use VetmanagerApiGateway\Hydrator\ApiString;

/** Обращается в таблицу combo_manual_items
 * @psalm-suppress PropertyNotSetInConstructor, RedundantPropertyInitializationCheck - одобрено в доках PSALM для этого случая
 */
final class ComboManualItemDto extends AbstractDTO
{
    /** @var positive-int ID записи цвета в таблице combo_manual_items */
    public int $id;
    /** @var positive-int Тип "подтаблицы". Например, будет всегда одинаковый id для "подтаблицы" с цветами.
     * В БД Default: 0, но ни в одной таблице такого значения не нашел */
    public int $comboManualId;
    /** Default: '' */
    public string $title;
    /** Default: '' */
    public string $value;
    /** Default: '' */
    public string $dopParam1;
    /** Default: '' */
    public string $dopParam2;
    /** Default: '' */
    public string $dopParam3;
    /** Default: 1 */
    public bool $isActive;

    /** @param array{
     *       id: string,
     *       combo_manual_id: string,
     *       title: string,
     *       value: string,
     *       dop_param1: string,
     *       dop_param2: string,
     *       dop_param3: string,
     *       is_active: string,
     *       comboManualName?: array
     *   } $originalDataArray
     * @throws VetmanagerApiGatewayException
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public static function fromApiResponseArray(array $originalDataArray): self
    {
        $instance = new self($originalDataArray);
        $instance->id = ApiInt::fromStringOrNull($originalDataArray['id'])->positiveInt;
        $instance->comboManualId = ApiInt::fromStringOrNull($originalDataArray['combo_manual_id'])->positiveInt;
        $instance->title = ApiString::fromStringOrNull($originalDataArray['title'])->string;
        $instance->value = ApiString::fromStringOrNull($originalDataArray['value'])->string;
        $instance->dopParam1 = ApiString::fromStringOrNull($originalDataArray['dop_param1'])->string;
        $instance->dopParam2 = ApiString::fromStringOrNull($originalDataArray['dop_param2'])->string;
        $instance->dopParam3 = ApiString::fromStringOrNull($originalDataArray['dop_param3'])->string;
        $instance->isActive = ApiBool::fromStringOrNull($originalDataArray['is_active'])->bool;
        return $instance;
    }

    /** @inheritdoc */
    public function getRequiredKeysForPostArray(): array
    {
        return ['combo_manual_id', 'title', 'value'];
    }

    /** @inheritdoc */
    protected function getSetValuesWithoutId(): array
    {
        return array_merge(
            property_exists($this, 'comboManualId') ? ['combo_manual_id' => $this->comboManualId] : [],
            property_exists($this, 'title') ? ['title' => $this->title] : [],
            property_exists($this, 'value') ? ['value' => $this->value] : [],
            property_exists($this, 'dopParam1') ? ['dop_param1' => $this->dopParam1] : [],
            property_exists($this, 'dopParam2') ? ['dop_param2' => $this->dopParam2] : [],
            property_exists($this, 'dopParam3') ? ['dop_param3' => $this->dopParam3] : [],
            property_exists($this, 'isActive') ? ['is_active' => (int)$this->isActive] : [],
        );
    }
}
