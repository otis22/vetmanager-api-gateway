<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO\Invoice;

use DateTime;
use VetmanagerApiGateway\ApiDataInterpreter\ToDateTime;
use VetmanagerApiGateway\ApiDataInterpreter\ToFloat;
use VetmanagerApiGateway\ApiDataInterpreter\ToInt;
use VetmanagerApiGateway\ApiDataInterpreter\ToString;
use VetmanagerApiGateway\DTO\AbstractDTO;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayInnerException;

class InvoiceOnlyDto extends AbstractDTO implements InvoiceOnlyDtoInterface
{
    /**
     * @param int|null $id
     * @param int|null $doctor_id
     * @param int|null $client_id
     * @param int|null $pet_id
     * @param string|null $description
     * @param string|null $percent
     * @param string|null $amount
     * @param string|null $status
     * @param string|null $invoice_date
     * @param string|null $old_id
     * @param int|null $night
     * @param string|null $increase
     * @param string|null $discount
     * @param int|null $call
     * @param string|null $paid_amount
     * @param string|null $create_date
     * @param string|null $payment_status
     * @param int|null $clinic_id
     * @param int|null $creator_id
     * @param int|null $fiscal_section_id
     */
    public function __construct(
        protected ?int $id,
        protected ?int $doctor_id,
        protected ?int $client_id,
        protected ?int $pet_id,
        protected ?string $description,
        protected ?string $percent,
        protected ?string $amount,
        protected ?string $status,
        protected ?string $invoice_date,
        protected ?string $old_id,
        protected ?int $night,
        protected ?string $increase,
        protected ?string $discount,
        protected ?int $call,
        protected ?string $paid_amount,
        protected ?string $create_date,
        protected ?string $payment_status,
        protected ?int $clinic_id,
        protected ?int $creator_id,
        protected ?int $fiscal_section_id
    )
    {
    }

    public function getId(): int
    {
        return (new ToInt($this->id))->getPositiveIntOrThrow();
    }

    public function getUserId(): ?int
    {
        return (new ToInt($this->doctor_id))->getPositiveIntOrNullOrThrowIfNegative();
    }

    public function getClientId(): int
    {
        return (new ToInt($this->client_id))->getPositiveIntOrThrow();
    }

    public function getPetId(): int
    {
        return (new ToInt($this->pet_id))->getPositiveIntOrThrow();
    }

    public function getDescription(): string
    {
        return ToString::fromStringOrNull($this->description)->getStringEvenIfNullGiven();
    }

    public function getPercent(): ?float
    {
        return ToFloat::fromStringOrNull($this->percent)->getNonZeroFloatOrNull();
    }

    public function getAmount(): ?float
    {
        return ToFloat::fromStringOrNull($this->amount)->getNonZeroFloatOrNull();
    }

    public function getStatusAsString(): string
    {
        return ToString::fromStringOrNull($this->status)->getStringOrThrowIfNull();
    }

    public function getStatusAsEnum(): \VetmanagerApiGateway\DTO\Invoice\StatusEnum
    {
        return \VetmanagerApiGateway\DTO\Invoice\StatusEnum::from($this->status);
    }

    public function getInvoiceDateAsString(): string
    {
        return ToString::fromStringOrNull($this->invoice_date)->getStringOrThrowIfNull();
    }

    public function getInvoiceDateAsDateTime(): DateTime
    {
        return ToDateTime::fromOnlyDateString($this->invoice_date)->getDateTimeOrThrow();
    }

    public function getOldId(): ?int
    {
        return (new ToInt($this->old_id))->getPositiveIntOrNullOrThrowIfNegative();
    }

    public function getNight(): ?int
    {
        return (new ToInt($this->night))->getPositiveIntOrNullOrThrowIfNegative();
    }

    public function getIncrease(): float
    {
        return ToFloat::fromStringOrNull($this->increase)->getNonZeroFloatOrNull();
    }

    public function getDiscount(): float
    {
        return ToFloat::fromStringOrNull($this->discount)->getNonZeroFloatOrNull();
    }

    public function getCallId(): ?int
    {
        return (new ToInt($this->call))->getPositiveIntOrNullOrThrowIfNegative();
    }

    public function getPaidAmount(): float
    {
        return ToFloat::fromStringOrNull($this->paid_amount)->getNonZeroFloatOrNull();
    }

    public function getCreateDateAsString(): string
    {
        return ToString::fromStringOrNull($this->create_date)->getStringOrThrowIfNull();
    }

    public function getCreateDateAsDateTime(): DateTime
    {
        return ToDateTime::fromOnlyDateString($this->create_date)->getDateTimeOrThrow();
    }

    public function getPaymentStatusAsString(): string
    {
        return ToString::fromStringOrNull($this->payment_status)->getStringOrThrowIfNull();
    }

    public function getPaymentStatusAsEnum(): PaymentStatusEnum
    {
        return PaymentStatusEnum::from($this->payment_status);
    }

    public function getClinicId(): ?int
    {
        return (new ToInt($this->clinic_id))->getPositiveIntOrNullOrThrowIfNegative();
    }

    public function getCreatorId(): ?int
    {
        return (new ToInt($this->creator_id))->getPositiveIntOrNullOrThrowIfNegative();
    }

    public function getFiscalSectionId(): ?int
    {
        return (new ToInt($this->fiscal_section_id))->getPositiveIntOrNullOrThrowIfNegative();
    }

    public function setUserId(?int $value): static
    {
        return self::setPropertyFluently($this, 'doctor_id', $value);
    }

    public function setClientId(?int $value): static
    {
        return self::setPropertyFluently($this, 'client_id', $value);
    }

    public function setPetId(?int $value): static
    {
        return self::setPropertyFluently($this, 'pet_id', $value);
    }

    public function setDescription(?string $value): static
    {
        return self::setPropertyFluently($this, 'description', $value);
    }

    public function setPercent(?float $value): static
    {
        return self::setPropertyFluently($this, 'percent', (string)$value);
    }

    public function setAmount(?float $value): static
    {
        return self::setPropertyFluently($this, 'amount', is_null($value) ? null : (string)$value);
    }

    public function setStatusFromString(?string $value): static
    {
        return self::setPropertyFluently($this, 'status', $value);
    }

    /** @throws VetmanagerApiGatewayInnerException */
    public function setStatusFromEnum(\VetmanagerApiGateway\DTO\Invoice\StatusEnum $value): static
    {
        return self::setPropertyFluently($this, 'status', $value->value);
    }

    public function setInvoiceDateFromString(?string $value): static
    {
        return self::setPropertyFluently($this, 'invoice_date', $value);
    }

    public function setInvoiceDateFromDateTime(DateTime $value): static
    {
        return self::setPropertyFluently($this, 'invoice_date', $value->format('Y-m-d H:i:s'));
    }

    public function setOldId(?int $value): static
    {
        return self::setPropertyFluently($this, 'old_id', $value);
    }

    public function setNight(?int $value): static
    {
        return self::setPropertyFluently($this, 'night', $value);
    }

    public function setIncrease(?float $value): static
    {
        return self::setPropertyFluently($this, 'increase', is_null($value) ? null : (string)$value);
    }

    public function setDiscount(?float $value): static
    {
        return self::setPropertyFluently($this, 'discount', is_null($value) ? null : (string)$value);
    }

    public function setCallId(?int $value): static
    {
        return self::setPropertyFluently($this, 'call', $value);
    }

    public function setPaidAmount(?float $value): static
    {
        return self::setPropertyFluently($this, 'paid_amount', is_null($value) ? null : (string)$value);
    }

    public function setCreateDateFromString(string $value): static
    {
        return self::setPropertyFluently($this, 'create_date', $value);
    }

    public function setCreateDateFromDateTime(DateTime $value): static
    {
        return self::setPropertyFluently($this, 'create_date', $value->format('Y-m-d H:i:s'));
    }

    public function setPaymentStatusFromString(string $value): static
    {
        return self::setPropertyFluently($this, 'payment_status', $value);
    }

    public function setPaymentStatusFromEnum(PaymentStatusEnum $value): static
    {
        return self::setPropertyFluently($this, 'payment_status', $value->value);
    }

    public function setClinicId(?int $value): static
    {
        return self::setPropertyFluently($this, 'clinic_id', $value);
    }

    public function setCreatorId(?int $value): static
    {
        return self::setPropertyFluently($this, 'creator_id', $value);
    }

    public function setFiscalSectionId(?int $value): static
    {
        return self::setPropertyFluently($this, 'fiscal_section_id', $value);
    }

//    /** @param array{
//     *     id: string,
//     *     doctor_id: ?numeric-string,
//     *     client_id: numeric-string,
//     *     pet_id: numeric-string,
//     *     description: string,
//     *     percent: ?string,
//     *     amount: ?string,
//     *     status: string,
//     *     invoice_date: string,
//     *     old_id: ?numeric-string,
//     *     night: numeric-string,
//     *     increase: ?string,
//     *     discount: ?string,
//     *     call: numeric-string,
//     *     paid_amount: string,
//     *     create_date: string,
//     *     payment_status: string,
//     *     clinic_id: numeric-string,
//     *     creator_id: ?numeric-string,
//     *     fiscal_section_id: numeric-string,
//     *     client?: array,
//     *     pet?: array,
//     *     doctor?: array,
//     *     invoiceDocuments?: array
//     *  } $originalDataArray
//     */
}
