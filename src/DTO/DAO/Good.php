<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO\DAO;

use Exception;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DTO;
use VetmanagerApiGateway\DTO\DAO\Interface\AllGetRequestsInterface;
use VetmanagerApiGateway\DTO\DAO\Trait\AllGetRequestsTrait;
use VetmanagerApiGateway\DTO\DAO\Trait\BasicDAOTrait;
use VetmanagerApiGateway\DTO\Enum\ApiRoute;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

class Good extends DTO\Good implements AllGetRequestsInterface
{
    use BasicDAOTrait, AllGetRequestsTrait;

    /** Предзагружен. Нового АПИ запроса не будет */
    public GoodGroup $group;
    /** Предзагружен. Нового АПИ запроса не будет */
    public Unit $unit;
    /** @var GoodSaleParam[] Предзагружены. Нового АПИ запроса не будет */
    public array $goodSaleParams;

    /** @var array{
     *     "id": string,
     *     "group_id": ?string,
     *     "title": string,
     *     "unit_storage_id": ?string,
     *     "is_warehouse_account": string,
     *     "is_active": string,
     *     "code": ?string,
     *     "is_call": string,
     *     "is_for_sale": string,
     *     "barcode": ?string,
     *     "create_date": string,
     *     "description": string,
     *     "prime_cost": string,
     *     "category_id": ?string,
     *     "group": array{
     *              "id": string,
     *              "title": string,
     *              "is_service": string,
     *              "markup": ?string,
     *              "is_show_in_vaccines": string,
     *              "price_id": ?string
     *     },
     *     "unitStorage": array{
     *              "id": string,
     *              "title": string,
     *              "status": string
     *     },
     *     "goodSaleParams": array<int, array{
     *              "id": string,
     *              "good_id": string,
     *              "price": ?string,
     *              "coefficient": string,
     *              "unit_sale_id": string,
     *              "min_price": ?string,
     *              "max_price": ?string,
     *              "barcode": ?string,
     *              "status": string,
     *              "clinic_id": string,
     *              "markup": string,
     *              "price_formation": ?string,
     *     }
     * } $originalData
     */
    protected readonly array $originalData;

    /** @throws VetmanagerApiGatewayException
     * @throws Exception
     */
    public function __construct(protected ApiGateway $apiGateway, array $originalData)
    {
        parent::__construct($apiGateway, $originalData);

        $this->group = GoodGroup::fromSingleObjectContents($this->apiGateway, $this->originalData['group']);
        $this->unit = Unit::fromSingleObjectContents($this->apiGateway, $this->originalData['unitStorage']);
        $this->goodSaleParams = $this->getGoodSaleParams();
    }

    /**
     * @return GoodSaleParam[]
     * @throws VetmanagerApiGatewayException
     */
    private function getGoodSaleParams(): array
    {
        return array_map(
            fn (array $goodSaleParam): GoodSaleParam => GoodSaleParam::fromSingleObjectContents(
                $this->apiGateway,
                array_merge(
                    $goodSaleParam,
                    ["unitSale" => $this->originalData['unitStorage']],
                    ["good" => $this->getOnlyGoodArray()],
                )
            ),
            $this->originalData['goodSaleParams']
        );
    }

    private function getOnlyGoodArray(): array
    {
        $originalData = $this->originalData;
        unset($originalData['group'], $originalData['unitStorage'], $originalData['goodSaleParams']);
        return $originalData;
    }

    public static function getApiModel(): ApiRoute
    {
        return ApiRoute::Good;
    }
}
