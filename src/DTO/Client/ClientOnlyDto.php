<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO\Client;

use DateTime;
use VetmanagerApiGateway\ApiDataInterpreter\ToBool;
use VetmanagerApiGateway\ApiDataInterpreter\ToDateTime;
use VetmanagerApiGateway\ApiDataInterpreter\ToFloat;
use VetmanagerApiGateway\ApiDataInterpreter\ToInt;
use VetmanagerApiGateway\ApiDataInterpreter\ToString;
use VetmanagerApiGateway\DTO\AbstractDTO;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayInnerException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;

class ClientOnlyDto extends AbstractDTO implements ClientDtoInterface
{
    /**
     * @param int|null $id
     * @param string|null $address
     * @param string|null $home_phone
     * @param string|null $work_phone
     * @param string|null $note
     * @param int|null $type_id
     * @param int|null $how_find
     * @param string|null $balance
     * @param string|null $email Default: ''
     * @param string|null $city
     * @param int|null $city_id
     * @param string|null $date_register В БД бывает дефолтное значение: '0000-00-00 00:00:00'
     * @param string|null $cell_phone
     * @param string|null $zip
     * @param string|null $registration_index
     * @param int|null $vip
     * @param string|null $last_name
     * @param string|null $first_name
     * @param string|null $middle_name
     * @param string|null $status Default: Active
     * @param int|null $discount
     * @param string|null $passport_series
     * @param string|null $lab_number
     * @param int|null $street_id Default: 0
     * @param string|null $apartment Default: ''
     * @param int|null $unsubscribe Default: 0
     * @param int|null $in_blacklist Default: 0
     * @param string|null $last_visit_date В БД бывает дефолтное значение: '0000-00-00 00:00:00'
     * @param string|null $number_of_journal Default: ''
     * @param string|null $phone_prefix
     */
    public function __construct(
        protected ?int $id,
        protected ?string $address,
        protected ?string $home_phone,
        protected ?string $work_phone,
        protected ?string $note,
        protected ?int $type_id,
        protected ?int $how_find,
        protected ?string $balance,
        protected ?string $email,
        protected ?string $city,
        protected ?int $city_id,
        protected ?string $date_register,
        protected ?string $cell_phone,
        protected ?string $zip,
        protected ?string $registration_index,
        protected ?int $vip,
        protected ?string $last_name,
        protected ?string $first_name,
        protected ?string $middle_name,
        protected ?string $status,
        protected ?int $discount,
        protected ?string $passport_series,
        protected ?string $lab_number,
        protected ?int $street_id,
        protected ?string $apartment,
        protected ?int $unsubscribe,
        protected ?int $in_blacklist,
        protected ?string $last_visit_date,
        protected ?string $number_of_journal,
        protected ?string $phone_prefix
    )
    {
    }

    /** @return positive-int
     * @throws VetmanagerApiGatewayResponseException
     */
    public function getId(): int
    {
        return (new ToInt($this->id))->getPositiveIntOrThrow();
    }

    public function getAddress(): string
    {
        return ToString::fromStringOrNull($this->address)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setAddress(string $value): static
    {
        return self::setPropertyFluently($this, 'address', $value);
    }

    public function getHomePhone(): string
    {
        return ToString::fromStringOrNull($this->home_phone)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setHomePhone(string $value): static
    {
        return self::setPropertyFluently($this, 'home_phone', $value);
    }

    public function getWorkPhone(): string
    {
        return ToString::fromStringOrNull($this->work_phone)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setWorkPhone(string $value): static
    {
        return self::setPropertyFluently($this, 'work_phone', $value);
    }

    public function getNote(): string
    {
        return ToString::fromStringOrNull($this->note)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setNote(string $value): static
    {
        return self::setPropertyFluently($this, 'note', $value);
    }

    /** @return ?positive-int
     * @throws VetmanagerApiGatewayResponseException
     */
    public function getTypeId(): ?int
    {
        return (new ToInt($this->type_id))->getPositiveIntOrNullOrThrowIfNegative();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setTypeId(int $value): static
    {
        return self::setPropertyFluently($this, 'type_id', $value);
    }

    /** @return ?positive-int
     * @throws VetmanagerApiGatewayResponseException
     */
    public function getHowFind(): ?int
    {
        return (new ToInt($this->how_find))->getPositiveIntOrNullOrThrowIfNegative();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setHowFind(int $value): static
    {
        return self::setPropertyFluently($this, 'how_find', $value);
    }

    /** @throws VetmanagerApiGatewayResponseException */
    public function getBalance(): float
    {
        return ToFloat::fromStringOrNull($this->balance)->getFloatOrThrowIfNull();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setBalance(float $value): static
    {
        return self::setPropertyFluently($this, 'balance', (string)$value);
    }

    public function getEmail(): string
    {
        return ToString::fromStringOrNull($this->email)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setEmail(string $value): static
    {
        return self::setPropertyFluently($this, 'email', $value);
    }

    public function getCityTitle(): string
    {
        return ToString::fromStringOrNull($this->city)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setCityTitle(string $value): static
    {
        return self::setPropertyFluently($this, 'city', $value);
    }

    /** @return ?positive-int
     * @throws VetmanagerApiGatewayResponseException
     */
    public function getCityId(): ?int
    {
        return (new ToInt($this->city_id))->getPositiveIntOrNullOrThrowIfNegative();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setCityId(int $value): static
    {
        return self::setPropertyFluently($this, 'city_id', $value);
    }

    /** Пустые значения переводятся в null
     * @throws VetmanagerApiGatewayResponseException
     */
    public function getDateRegisterAsDateTime(): ?DateTime
    {
        return ToDateTime::fromFullDateTimeString($this->date_register)->getDateTimeOrThrow();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setDateRegisterFromString(string $value): static
    {
        return self::setPropertyFluently($this, 'date_register', $value);
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setDateRegisterFromDateTime(DateTime $value): static
    {
        return self::setPropertyFluently($this, 'date_register', $value->format('Y-m-d H:i:s'));
    }

    public function getCellPhone(): string
    {
        return ToString::fromStringOrNull($this->cell_phone)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setCellPhone(string $value): static
    {
        return self::setPropertyFluently($this, 'cell_phone', $value);
    }

    public function getZip(): string
    {
        return ToString::fromStringOrNull($this->zip)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setZip(string $value): static
    {
        return self::setPropertyFluently($this, 'zip', $value);
    }

    public function getRegistrationIndex(): string
    {
        return ToString::fromStringOrNull($this->registration_index)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setRegistrationIndex(string $value): static
    {
        return self::setPropertyFluently($this, 'registration_index', $value);
    }

    /** Default: 0
     * @throws VetmanagerApiGatewayResponseException
     */
    public function getIsVip(): bool
    {
        return ToBool::fromIntOrNull($this->vip)->getBoolOrThrowIfNull();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setIsVip(bool $value): static
    {
        return self::setPropertyFluently($this, 'vip', (int)$value);
    }

    public function getLastName(): string
    {
        return ToString::fromStringOrNull($this->last_name)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setLastName(string $value): static
    {
        return self::setPropertyFluently($this, 'last_name', $value);
    }

    public function getFirstName(): string
    {
        return ToString::fromStringOrNull($this->first_name)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setFirstName(string $value): static
    {
        return self::setPropertyFluently($this, 'first_name', $value);
    }

    public function getMiddleName(): string
    {
        return ToString::fromStringOrNull($this->middle_name)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setMiddleName(string $value): static
    {
        return self::setPropertyFluently($this, 'middle_name', $value);
    }

    public function getStatusAsEnum(): StatusEnum
    {
        return StatusEnum::from($this->status);
    }

    public function getStatusAsString(): ?string
    {
        return $this->status;
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setStatusAsString(?string $value): static
    {
        return self::setPropertyFluently($this, 'status', $value);
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setStatusAsEnum(StatusEnum $value): static
    {
        return self::setPropertyFluently($this, 'status', $value->value);
    }

    public function getDiscount(): int
    {
        return (new ToInt($this->discount))->getIntEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setDiscount(int $value): static
    {
        return self::setPropertyFluently($this, 'discount', $value);
    }

    public function getPassportSeries(): string
    {
        return ToString::fromStringOrNull($this->passport_series)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setPassportSeries(string $value): static
    {
        return self::setPropertyFluently($this, 'passport_series', $value);
    }

    public function getLabNumber(): string
    {
        return ToString::fromStringOrNull($this->lab_number)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setLabNumber(string $value): static
    {
        return self::setPropertyFluently($this, 'lab_number', $value);
    }

    /** @return ?positive-int
     * @throws VetmanagerApiGatewayResponseException
     */
    public function getStreetId(): ?int
    {
        return (new ToInt($this->street_id))->getPositiveIntOrNullOrThrowIfNegative();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setStreetId(?int $value): static
    {
        return self::setPropertyFluently(
            $this,
            'streetId',
            is_null($value) ? null : $value
        );
    }

    public function getApartment(): string
    {
        return ToString::fromStringOrNull($this->apartment)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setApartment(string $value): static
    {
        return self::setPropertyFluently($this, 'apartment', $value);
    }

    /** @throws VetmanagerApiGatewayResponseException */
    public function getIsUnsubscribed(): bool
    {
        return ToBool::fromIntOrNull($this->unsubscribe)->getBoolOrThrowIfNull();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setUnsubscribe(bool $value): static
    {
        return self::setPropertyFluently($this, 'unsubscribe', (int)$value);
    }

    /** @throws VetmanagerApiGatewayResponseException */
    public function getIsBlacklisted(): bool
    {
        return ToBool::fromIntOrNull($this->in_blacklist)->getBoolOrThrowIfNull();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setInBlacklist(bool $value): static
    {
        return self::setPropertyFluently($this, 'in_blacklist', (int)$value);
    }

    /** @throws VetmanagerApiGatewayResponseException */
    public function getLastVisitDateAsDateTime(): ?DateTime
    {
        return ToDateTime::fromFullDateTimeString($this->last_visit_date)->getDateTimeOrThrow();
    }

    /** @throws VetmanagerApiGatewayException */
    public function setLastVisitDateFromSting(?string $value): static
    {
        return self::setPropertyFluently($this,'last_visit_date', $value);
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setLastVisitDateFromDateTime(DateTime $value): static
    {
        return self::setPropertyFluently($this, 'last_visit_date', $value->format('Y-m-d H:i:s'));
    }

    public function getNumberOfJournal(): string
    {
        return ToString::fromStringOrNull($this->number_of_journal)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setNumberOfJournal(string $value): static
    {
        return self::setPropertyFluently($this, 'number_of_journal', $value);
    }

    public function getPhonePrefix(): string
    {
        return ToString::fromStringOrNull($this->phone_prefix)->getStringEvenIfNullGiven();
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setPhonePrefix(string $value): static
    {
        return self::setPropertyFluently($this, 'phone_prefix', $value);
    }
}
