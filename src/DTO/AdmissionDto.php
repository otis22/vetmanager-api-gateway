<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO;

use DateInterval;
use DateTime;
use VetmanagerApiGateway\ActiveRecord\User;
use VetmanagerApiGateway\DTO\Enum\Admission\Status;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;
use VetmanagerApiGateway\Hydrator\ApiBool;
use VetmanagerApiGateway\Hydrator\ApiDateInterval;
use VetmanagerApiGateway\Hydrator\ApiDateTime;
use VetmanagerApiGateway\Hydrator\ApiFloat;
use VetmanagerApiGateway\Hydrator\ApiInt;
use VetmanagerApiGateway\Hydrator\ApiString;
use VetmanagerApiGateway\Hydrator\DtoPropertyList;

final class AdmissionDto extends AbstractDTO
{
    /** @var positive-int */
    public int $id;
    /** Пример "2020-12-31 17:51:18". Может быть: "0000-00-00 00:00:00" - переводится в null */
    public ?DateTime $date;
    /** Примеры: "На основании медкарты", "Запись из модуля, к свободному доктору, по услуге Ампутация пальцев" */
    public string $description;
    /** @var ?positive-int */
    public ?int $clientId;
    /** @var ?positive-int */
    public ?int $petId;
    /** @var ?positive-int */
    public ?int $userId;
    /** @var ?positive-int */
    public ?int $typeId;
    /** Примеры: "00:15:00", "00:00:00" (последнее перевожу в null) */
    public ?DateInterval $admissionLength;
    public ?Status $status;
    /** @var ?positive-int В БД встречается "0" - переводим в null */
    public ?int $clinicId;
    /** Насколько я понял, означает: 'Прием без планирования' */
    public bool $isDirectDirection;
    /** @var ?positive-int */
    public ?int $creatorId;
    /** Приходит: "2015-07-08 06:43:44", но бывает и "0000-00-00 00:00:00". Последнее переводится в null */
    public ?DateTime $createDate;
    /** Тут судя по коду, можно привязать еще одного доктора, т.е. ID от {@see User}. Какой-то врач-помощник что ли.
     * Кроме "0" другие значения искал - не нашел. Думаю передумали реализовывать */
    public ?int $escortId;
    /** Искал по всем БД: находил только "vetmanager" и "" или null (редко. Пустые перевожу в null) */
    public string $receptionWriteChannel;
    public bool $isAutoCreate;
    /** Default: 0.0000000000 */
    public float $invoicesSum;

    /** @param array{
     *          id: numeric-string,
     *          admission_date: string,
     *          description: string,
     *          client_id: numeric-string,
     *          patient_id: numeric-string,
     *          user_id: numeric-string,
     *          type_id: numeric-string,
     *          admission_length: string,
     *          status: ?string,
     *          clinic_id: numeric-string,
     *          direct_direction: string,
     *          creator_id: numeric-string,
     *          create_date: string,
     *          escorter_id: ?numeric-string,
     *          reception_write_channel: ?string,
     *          is_auto_create: string,
     *          invoices_sum: string,
     *          client: array,
     *          pet?: array,
     *          wait_time?: string,
     *          invoices?: array,
     *          doctor_data?: array,
     *          admission_type_data?: array
     *     } $originalData
     * @throws VetmanagerApiGatewayException
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public static function fromApiResponseArray(array $originalData): self
    {
        $instance = new self();
        $instance->id = ApiInt::fromStringOrNull($originalData['id'])->positiveInt;
        $instance->date = ApiDateTime::fromFullDateTimeString($originalData['admission_date'])->dateTimeOrNull;
        $instance->description = ApiString::fromStringOrNull($originalData['description'])->string;
        $instance->clientId = ApiInt::fromStringOrNull($originalData['client_id'])->positiveIntOrNull;
        $instance->petId = ApiInt::fromStringOrNull($originalData['patient_id'])->positiveIntOrNull;
        $instance->userId = ApiInt::fromStringOrNull($originalData['user_id'])->positiveIntOrNull;
        $instance->typeId = ApiInt::fromStringOrNull($originalData['type_id'])->positiveIntOrNull;
        $instance->admissionLength = ApiDateInterval::fromStringHMS($originalData['admission_length'])->dateIntervalOrNull;
        $instance->status = $originalData['status'] ? Status::from($originalData['status']) : null;
        $instance->clinicId = ApiInt::fromStringOrNull($originalData['clinic_id'])->positiveIntOrNull;
        $instance->isDirectDirection = ApiBool::fromStringOrNull($originalData['direct_direction'])->bool;
        $instance->creatorId = ApiInt::fromStringOrNull($originalData['creator_id'])->positiveIntOrNull;
        $instance->createDate = ApiDateTime::fromFullDateTimeString($originalData['create_date'])->dateTimeOrNull;
        $instance->escortId = ApiInt::fromStringOrNull($originalData['escorter_id'])->positiveIntOrNull;
        $instance->receptionWriteChannel = ApiString::fromStringOrNull($originalData['reception_write_channel'])->string;
        $instance->isAutoCreate = ApiBool::fromStringOrNull($originalData['is_auto_create'])->bool;
        $instance->invoicesSum = ApiFloat::fromStringOrNull($originalData['invoices_sum'])->float;
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
            ['date', 'admission_date'],
            ['description', 'description'],
            ['clientId', 'client_id'],
            ['petId', 'patient_id'],
            ['userId', 'user_id'],
            ['typeId', 'type_id'],
            ['admissionLength', 'admission_length'],
            ['status', 'status'],
            ['clinicId', 'clinic_id'],
            ['isDirectDirection', 'direct_direction'],
            ['creatorId', 'creator_id'],
            ['createDate', 'create_date'],
            ['escortId', 'escorter_id'],
            ['receptionWriteChannel', 'reception_write_channel'],
            ['isAutoCreate', 'is_auto_create'],
            ['invoicesSum', 'invoices_sum'],
        ))->toArray();
    }
}
