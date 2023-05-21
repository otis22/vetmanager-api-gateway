<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO;

use DateTime;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;
use VetmanagerApiGateway\Hydrator\ApiDateTime;
use VetmanagerApiGateway\Hydrator\ApiFloat;
use VetmanagerApiGateway\Hydrator\ApiInt;
use VetmanagerApiGateway\Hydrator\ApiString;
use VetmanagerApiGateway\Hydrator\DtoPropertyList;
use VetmanagerApiGateway\Hydrator\Enum\DtoPropertyMode;

/** @psalm-suppress PropertyNotSetInConstructor, RedundantPropertyInitializationCheck - одобрено в доках PSALM для этого случая */
final class MedicalCardAsVaccinationDto extends AbstractDTO
{
    /** @var positive-int id из таблицы vaccine_pets */
    public int $id;
    /** title из таблицы vaccine_pets */
    public string $name;
    /** @var positive-int */
    public int $petId;
    /** Дата без времени. Пример: "2012-09-02 00:00:00", а может прийти, если ничего: "0000-00-00". Из таблицы vaccine_pets*/
    public ?DateTime $date;
    /** Может содержать в себе:
     * 1) Лишь дату. Тогда в {@see $isTimePresentInNextDateTime} будет false
     * 2) Дату со временем. Тогда в {@see $isTimePresentInNextDateTime} будет true
     * 3) Null
     * Значение берется из admission_date из таблицы admission ON admission.id = vaccine_pets.next_admission_id. */
    public ?DateTime $nextDateTime;
    /** Указывает есть ли время в {@see $nextDateTime} */
    public bool $isTimePresentInNextDateTime;
    /** @var positive-int Default in DB: "0". Но не видел нигде 0 - не предусматриваю */
    public int $goodId;
    /** Дата без времени. Пример: "2012-09-02 00:00:00". Может быть и null */
    public ?DateTime $petBirthday;
    /** @var positive-int Default in DB: "0". Но не видел нигде 0 - не предусматриваю */
    public int $medicalCardId;
    /** @var positive-int Default in DB: "0". Но не видел нигде 0 - не предусматриваю */
    public int $doseTypeId;
    /** Default: "1.0000000000". Из таблицы vaccine_pets*/
    public float $doseValue;
    /** @var positive-int Из таблицы vaccine_pets. Но не видел нигде 0 - не предусматриваю */
    public int $saleParamId;
    /** @var ?positive-int Default: "0" - перевожу в null */
    public ?int $vaccineType;
    /** Default: "". Из таблицы vaccine_pets */
    public string $vaccineDescription;
    /** Default: "". Title из таблицы combo_manual_items (строка, где: value = {@see $vaccineType} & combo_manual_id = $comboManualIdOfVaccinationType*/
    public string $vaccineTypeTitle;
    /** @var ?positive-int Default in DB: "0". Перевожу в null. Из таблицы vaccine_pets */
    public ?int $nextAdmissionId;

    /** @param array{
     *     id: numeric-string,
     *     name: string,
     *     pet_id: numeric-string,
     *     date: string,
     *     date_nexttime: string,
     *     vaccine_id: numeric-string,
     *     birthday: ?string,
     *     birthday_at_time: string,
     *     medcard_id: numeric-string,
     *     doza_type_id: numeric-string,
     *     doza_value: string,
     *     sale_param_id: numeric-string,
     *     vaccine_type: string,
     *     vaccine_description: string,
     *     vaccine_type_title: string,
     *     next_admission_id: numeric-string,
     *     next_visit_time: string,
     *     pet_age_at_time_vaccination: string
     * } $originalData
     * @throws VetmanagerApiGatewayException
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public static function fromApiResponseArray(array $originalData): self
    {
        $instance = new self();
        $instance->id = ApiInt::fromStringOrNull($originalData['id'])->positiveInt;
        $instance->name = ApiString::fromStringOrNull($originalData['name'])->string;
        $instance->petId = ApiInt::fromStringOrNull($originalData['pet_id'])->positiveInt;
        $instance->date = ApiDateTime::fromFullDateTimeString($originalData['date'])->dateTimeOrNull;
        $dateTimeServiceForNextDate = ApiDateTime::fromOnlyDateString($originalData['date_nexttime']);
        $instance->nextDateTime = $dateTimeServiceForNextDate->dateTimeOrNull;
        $instance->isTimePresentInNextDateTime = $dateTimeServiceForNextDate->isTimePresent();
        $instance->goodId = ApiInt::fromStringOrNull($originalData['vaccine_id'])->positiveInt;
        $instance->medicalCardId = ApiInt::fromStringOrNull($originalData['medcard_id'])->positiveInt;
        $instance->doseTypeId = ApiInt::fromStringOrNull($originalData['doza_type_id'])->positiveInt;
        $instance->doseValue = ApiFloat::fromStringOrNull($originalData['doza_value'])->float;
        $instance->saleParamId = ApiInt::fromStringOrNull($originalData['sale_param_id'])->positiveInt;
        $instance->vaccineType = ApiInt::fromStringOrNull($originalData['vaccine_type'])->positiveIntOrNull;
        $instance->vaccineDescription = ApiString::fromStringOrNull($originalData['vaccine_description'])->string;
        $instance->vaccineTypeTitle = ApiString::fromStringOrNull($originalData['vaccine_type_title'])->string;
        $instance->nextAdmissionId = ApiInt::fromStringOrNull($originalData['next_admission_id'])->positiveIntOrNull;
        $instance->petBirthday = ApiDateTime::fromOnlyDateString($originalData['birthday'])->dateTimeOrNull;
        // "birthday_at_time" игнорируем. Бред присылается
        // "pet_age_at_time_vaccination" - Тоже игнорируем, ерунда
        return $instance;
    }

    /** @inheritdoc */
    public function getRequiredKeysForPostArray(): array #TODO No Idea
    {
        return [];
    }

    /** @inheritdoc
     * @throws VetmanagerApiGatewayRequestException
     */
    protected function getSetValuesWithoutId(): array
    {
        return (new DtoPropertyList(
            $this,
            ['name', 'name'],
            ['petId', 'pet_id'],
            ['date', 'date'],
            ['nextDateTime', 'date_nexttime', DtoPropertyMode::DateTimeOnlyDate],
            ['goodId', 'vaccine_id'],
            ['medicalCardId', 'medcard_id'],
            ['doseTypeId', 'doza_type_id'],
            ['doseValue', 'doza_value'],
            ['saleParamId', 'sale_param_id'],
            ['vaccineType', 'vaccine_type'],
            ['vaccineDescription', 'vaccine_description'],
            ['vaccineTypeTitle', 'vaccine_type_title'],
            ['nextAdmissionId', 'next_admission_id'],
            ['petBirthday', 'birthday'],
        ))->toArray();
    }
}
