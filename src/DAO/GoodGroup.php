<?php
declare(strict_types=1);

namespace VetmanagerApiGateway\DAO;

use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DAO\Interface\AllConstructorsInterface;
use VetmanagerApiGateway\DAO\Trait\AllConstructorsTrait;
use VetmanagerApiGateway\DTO\AbstractDTO;
use VetmanagerApiGateway\Enum\ApiRoute;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use Exception;

class GoodGroup extends AbstractDTO implements AllConstructorsInterface
{
    use AllConstructorsTrait;

    public int $id;
    public string $title;
    public ?int $priceId;
    /** Default: 0 */
    public bool $isService;
    public ?float $markup;
    /** Default: 0 */
    public bool $isShowInVaccines;

    /** @var array{
     *     "id": string,
     *     "title": string,
     *     "is_service": string,
     *     "markup": ?string,
     *     "is_show_in_vaccines": string,
     *     "price_id": ?string
     * } $originalData
     */
    readonly protected array $originalData;

    /** @throws VetmanagerApiGatewayException
     * @throws Exception
     */
    public function __construct(protected ApiGateway $apiGateway, array $originalData)
    {
        parent::__construct($apiGateway, $originalData);

        $this->id = (int)$this->originalData['id'];
        $this->title = (string)$this->originalData['title'];
        $this->priceId = $this->originalData['price_id'] ? (int)$this->originalData['price_id'] : null;
        $this->isService = (bool)$this->originalData['is_service'];
        $this->markup = $this->originalData['markup'] ? (float)$this->originalData['markup'] : null;
        $this->isShowInVaccines = (bool)$this->originalData['is_show_in_vaccines'];
    }

    public static function getApiModel(): ApiRoute
    {
        return ApiRoute::GoodGroup;
    }
}
