<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\ActiveRecord;

use DateTime;
use VetmanagerApiGateway\ActiveRecord\Enum\ApiModel;
use VetmanagerApiGateway\ActiveRecord\Enum\Completeness;
use VetmanagerApiGateway\ActiveRecord\Interface\AllRequestsInterface;
use VetmanagerApiGateway\ActiveRecord\Trait\AllRequestsTrait;
use VetmanagerApiGateway\DTO\Enum\MedicalCard\Status;
use VetmanagerApiGateway\DTO\MedicalCardDto;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

/**
 * @property-read MedicalCardDto $originalDto
 * @property positive-int $id
 * @property positive-int $petId
 * @property DateTime $dateCreate
 * @property DateTime $dateEdit
 * @property string $diagnose Сюда приходит либо "0", либо JSON типа: "[ {"id":32,"type":1}, {"id":35,"type":1}, {"id":77,"type":1} ]". 0 переводим в ''
 * @property string $recommendation Может прийти пустая строка, может просто строка, может HTML
 * @property ?positive-int $invoiceId Возможно null никогда не будет. Invoice ID (таблица invoice)
 * @property ?positive-int $admissionTypeId {@see MedicalCard::admissionType} Тип приема.    LEFT JOIN combo_manual_items ci ON ci.combo_manual_id = {$reasonId} AND ci.value = m.admission_type
 * @property ?float $weight
 * @property ?float $temperature
 * @property ?positive-int $meetResultId Возможно null никогда не будет. Default: 0 (переводим в null). LEFT JOIN combo_manual_items ci2 ON ci2.combo_manual_id = 2 AND ci2.value = m.meet_result_id
 * @property string $description Может быть просто строка, а может HTML-блок
 * @property ?positive-int $nextMeetId Возможно null никогда не будет. Default: 0 - переводим в null.    LEFT JOIN admission ad ON ad.id = m.next_meet_id
 * @property ?positive-int $userId Возможно null никогда не будет. Default: 0 - переводим в null
 * @property ?positive-int $creatorId Возможно null никогда не будет. Default: 0 - переводим в null. Может можно отдельно запрашивать его?
 * @property Status $status Default: 'active'
 * @property ?positive-int $callingId Вроде это ID из модуля задач. Пока непонятно
 * @property ?positive-int $admissionId Возможно null никогда не будет
 * @property string $diagnoseText Пример: "Анемия;\nАнорексия, кахексия;\nАтопия"
 * @property string $diagnoseTypeText Пример: "Анемия (Окончательные);\nАнорексия, кахексия (Окончательные);\nАтопия (Окончательные)"
 * @property ?positive-int $clinicId Может прийти null. Нашел 6 клиник, где не указано
 * @property-read array{
 *     id: numeric-string,
 *     patient_id: numeric-string,
 *     date_create: string,
 *     date_edit: ?string,
 *     diagnos: string,
 *     recomendation: string,
 *     invoice: ?string,
 *     admission_type: ?string,
 *     weight: ?string,
 *     temperature: ?string,
 *     meet_result_id: numeric-string,
 *     description: string,
 *     next_meet_id: numeric-string,
 *     doctor_id: numeric-string,
 *     creator_id: numeric-string,
 *     status: string,
 *     calling_id: numeric-string,
 *     admission_id: numeric-string,
 *     diagnos_text: ?string,
 *     diagnos_type_text: ?string,
 *     clinic_id: numeric-string,
 *     patient: array{
 *          id: numeric-string,
 *          owner_id: ?numeric-string,
 *          type_id: ?numeric-string,
 *          alias: string,
 *          sex: ?string,
 *          date_register: string,
 *          birthday: ?string,
 *          note: string,
 *          breed_id: ?numeric-string,
 *          old_id: ?numeric-string,
 *          color_id: ?numeric-string,
 *          deathnote: ?string,
 *          deathdate: ?string,
 *          chip_number: string,
 *          lab_number: string,
 *          status: string,
 *          picture: ?string,
 *          weight: ?string,
 *          edit_date: string
 *          }
 *      } $originalDataArray
 * @property-read Pet $pet
 * @property-read ?Clinic $clinic
 * @property-read ?bool $isOnlineSigningUpAvailableForClinic Придет null, если clinic_id в БД не указан (очень редкий случай - 6 клиник)
 * @property-read ?Admission $admission
 * @property-read ?Admission $nextMeet
 * @property-read ?ComboManualItem $admissionType
 * @property-read ?ComboManualItem $meetResult
 * @property-read ?Invoice $invoice
 * @property-read ?User $user
 */
final class MedicalCard extends AbstractActiveRecord implements AllRequestsInterface
{

    use AllRequestsTrait;

    /** @return ApiModel::MedicalCard */
    public static function getApiModel(): ApiModel
    {
        return ApiModel::MedicalCard;
    }

    public static function getCompletenessFromGetAllOrByQuery(): Completeness
    {
        return Completeness::Full;
    }

    /** @throws VetmanagerApiGatewayException */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'pet' => ($this->completenessLevel == Completeness::Full)
                ? Pet::fromSingleDtoArrayUsingBasicDto($this->apiGateway, $this->originalDataArray['patient'])
                : Pet::getById($this->apiGateway, $this->petId),
            'clinic' => $this->clinicId ? Clinic::getById($this->apiGateway, $this->clinicId) : null,
            'isOnlineSigningUpAvailableForClinic' => $this->clinicId ? Property::isOnlineSigningUpAvailableForClinic($this->apiGateway, $this->clinicId) : null,
            'admission' => $this->admissionId ? Admission::getById($this->apiGateway, $this->admissionId) : null,
            'nextMeet' => $this->nextMeetId ? Admission::getById($this->apiGateway, $this->nextMeetId) : null,
            'admissionType' => $this->admissionTypeId ? ComboManualItem::getByAdmissionTypeId($this->apiGateway, $this->admissionTypeId) : null,
            'meetResult' => $this->meetResultId ? ComboManualItem::getByAdmissionResultId($this->apiGateway, $this->meetResultId) : null,
            'invoice' => $this->invoiceId ? Invoice::getById($this->apiGateway, $this->invoiceId) : null,
            'user' => $this->userId ? User::getById($this->apiGateway, $this->userId) : null,
            default => $this->originalDto->$name
        };
    }
}
