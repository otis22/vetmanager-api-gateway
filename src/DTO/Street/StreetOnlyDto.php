<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO\Street;

use VetmanagerApiGateway\ApiDataInterpreter\DtoPropertyList;
use VetmanagerApiGateway\ApiDataInterpreter\ToInt;
use VetmanagerApiGateway\ApiDataInterpreter\ToString;
use VetmanagerApiGateway\DTO\AbstractDTO;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;

class StreetOnlyDto extends AbstractDTO
{

    /** @var positive-int */
    public int $id;
    /** Default: '' */
    public string $title;
    /** Default: 'street'*/
    public TypeEnum $type;
    /** @var positive-int В БД Default: '0' (но никогда не видел 0) */
    public int $cityId;

    /** @param array{
     *     "id": string,
     *     "title": string,
     *     "city_id": string,
     *     "type": string,
     *     "city"?: array{
     *              "id": string,
     *              "title": ?string,
     *              "type_id": ?string
     *     }
     * } $originalDataArray
     * @throws VetmanagerApiGatewayException
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public static function fromApiResponseArray(array $originalDataArray): self
    {
        $instance = new self($originalDataArray);
        $instance->id = ToInt::fromStringOrNull($originalDataArray['id'])->getPositiveInt();
        $instance->title = ToString::fromStringOrNull($originalDataArray['title'])->getStringEvenIfNullGiven();
        $instance->cityId = ToInt::fromStringOrNull($originalDataArray['city_id'])->getPositiveInt();
        $instance->type = TypeEnum::from($originalDataArray['type']);
        return $instance;
    }

    /** @inheritdoc
     * @throws VetmanagerApiGatewayRequestException
     */
    protected function getSetValuesWithoutId(): array
    {
        return (new DtoPropertyList(
            $this,
            ['title', 'title'],
            ['cityId', 'city_id'],
            ['type', 'type'],
        ))->toArray();
    }
}
