<?php
declare(strict_types=1);

namespace VetmanagerApiGateway\DAO;

use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DAO\Interface\AllConstructorsInterface;
use VetmanagerApiGateway\DAO\Trait\AllConstructorsTrait;
use VetmanagerApiGateway\DTO\AbstractDTO;
use VetmanagerApiGateway\Enum\ApiRoute;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseEmptyException;

/** @property-read ?Clinic $clinic */
class Property extends AbstractDTO implements AllConstructorsInterface
{
    use AllConstructorsTrait;

    public int $id;
    /** Default: '' */
    public string $name;
    public string $value;
    public ?string $title;
    /** Default: '0' */
    public int $clinicId;

    /** @var array{
     *     "id": string,
     *     "property_name": string,
     *     "property_value": string,
     *     "property_title": ?string,
     *     "clinic_id": string
     * } $originalData
     */
    readonly protected array $originalData;

    /** @throws VetmanagerApiGatewayException */
    public function __construct(protected ApiGateway $apiGateway, array $originalData)
    {
        parent::__construct($apiGateway, $originalData);

        $this->id = (int)$this->originalData['id'];
        $this->name = (string)$this->originalData['property_name'];
        $this->value = (string)$this->originalData['property_value'];
        $this->title = $this->originalData['property_title'] ? (string)$this->originalData['property_title'] : null;
        $this->clinicId = (int)$this->originalData['clinic_id'];
    }

    /**
     * @throws VetmanagerApiGatewayResponseEmptyException Если нет такого в БД
     * @throws VetmanagerApiGatewayException
     */
    public static function fromApiAndClinicIdAndPropertyName(ApiGateway $api, int $clinicId, string $propertyName): self
    {
        $filteredProperties = self::fromRequestByQueryBuilder(
            $api,
            (new Builder())
                ->where('property_name', $propertyName)
                ->where('clinic_id', (string)$clinicId)
                ->top(1)
        );

        return $filteredProperties[0];
    }

    public static function getApiModel(): ApiRoute
    {
        return ApiRoute::Property;
    }

    /** @throws VetmanagerApiGatewayException */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'clinic' => $this->clinicId ? Clinic::fromRequestById($this->apiGateway, $this->clinicId) : null,
            default => $this->$name,
        };
    }
}
