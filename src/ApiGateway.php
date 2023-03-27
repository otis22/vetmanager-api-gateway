<?php
declare(strict_types=1);

namespace VetmanagerApiGateway;

use VetmanagerApiGateway\Enum\ApiRoute;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseEmptyException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Otis22\VetmanagerRestApi\Headers\Auth\ApiKey;
use Otis22\VetmanagerRestApi\Headers\Auth\ByApiKey;
use Otis22\VetmanagerRestApi\Headers\Auth\ByServiceApiKey;
use Otis22\VetmanagerRestApi\Headers\Auth\ServiceName;
use Otis22\VetmanagerRestApi\Headers\WithAuthAndParams;
use Otis22\VetmanagerRestApi\Model;
use Otis22\VetmanagerRestApi\Query\PagedQuery;
use Otis22\VetmanagerRestApi\URI\OnlyModel;
use Otis22\VetmanagerRestApi\URI\RestApiPrefix;
use Otis22\VetmanagerRestApi\URI\WithId;
use Psr\Http\Message\ResponseInterface;
use function Otis22\VetmanagerUrl\url as vetmanager_url;
use function Otis22\VetmanagerUrl\url_test_env as vetmanager_url_test_env;

class ApiGateway
{
    public function __construct(
        protected Client            $guzzleClient,
        protected WithAuthAndParams $allHeaders
    )
    {
    }

    /** @throws VetmanagerApiGatewayRequestException */
    public static function fromDomainAndServiceNameAndApiKey(string $domainName,
                                                             string $serviceName,
                                                             string $apiKey,
                                                             bool   $isProduction,
                                                             string $timezone = '+03:00'): static
    {
        try {
            $baseApiUrl = $isProduction
                ? vetmanager_url($domainName)->asString()
                : vetmanager_url_test_env($domainName)->asString();
        } catch (\Exception $e) {
            throw new VetmanagerApiGatewayRequestException($e->getMessage());
        }

        $guzzleClient = new Client(
            [
                'base_uri' => $baseApiUrl,
                'http_errors' => false,
                'verify' => false,
            ]
        );

        $allHeaders = new WithAuthAndParams(
            new ByServiceApiKey(
                new ServiceName($serviceName),
                new ApiKey($apiKey)
            ),
            [
                'X-REST-TIME-ZONE' => $timezone,
                'X-APP' => 'VM-LINK'
            ]
        );

        return new static($guzzleClient, $allHeaders);
    }

    /** @throws VetmanagerApiGatewayRequestException */
    public static function fromDomainAndApiKey(string $domainName,
                                               string $apiKey,
                                               bool   $isProduction,
                                               string $timezone = '+03:00'): static
    {
        try {
            $baseApiUrl = ($isProduction)
                ? vetmanager_url($domainName)->asString()
                : vetmanager_url_test_env($domainName)->asString();
        } catch (\Exception $e) {
            throw new VetmanagerApiGatewayRequestException($e->getMessage());
        }

        $guzzleClient = new Client(
            [
                'base_uri' => $baseApiUrl,
                'http_errors' => false,
            ]
        );

        $allHeaders = new WithAuthAndParams(
            new ByApiKey(new ApiKey($apiKey)),
            [
                'X-REST-TIME-ZONE' => $timezone,
                'X-APP' => 'VM-LINK'
            ]
        );

        return new static($guzzleClient, $allHeaders);
    }

    /**
     * @param string $getParameters То, что после знака "?" в строке запроса. Например: 'client_id=133'
     * @throws VetmanagerApiGatewayException - общее родительское исключение
     * @throws VetmanagerApiGatewayResponseEmptyException|VetmanagerApiGatewayResponseException|VetmanagerApiGatewayRequestException
     */
    public function getWithGetParametersAsString(ApiRoute $apiRouteKey, string $getParameters): array
    {

        try {
            $url = (new RestApiPrefix())->asString() . $apiRouteKey->value . '?' . $getParameters;
        } catch (\Exception $e) {
            throw new VetmanagerApiGatewayRequestException($e->getMessage());
        }

        $request = $this->getResponseFromGuzzleClient('GET', $url);
        return $this->getDataContentsFromResponseOrThrowOnFail($request);
    }

    /**
     * Вернет в виде массива либо содержимое модели, либо массивы с моделями
     * @param int $maxLimitOfReturnedModels Ограничение по количеству возвращаемых моделей
     * @throws VetmanagerApiGatewayException - общее родительское исключение
     * @throws VetmanagerApiGatewayResponseEmptyException|VetmanagerApiGatewayResponseException|VetmanagerApiGatewayRequestException
     */
    public function getContentsWithPagedQuery(ApiRoute $apiRouteKey, PagedQuery $pagedQuery, int $maxLimitOfReturnedModels = 100): array
    {
        $apiResponse = $this->getWithPagedQuery($apiRouteKey, $pagedQuery, $maxLimitOfReturnedModels);

        return $apiResponse[$apiRouteKey->getApiModelResponseKey()];
    }

    /**
     * @param int $maxLimitOfReturnedModels Ограничение по количеству возвращаемых моделей
     * @return array{"totalCount": int, MODEL_NAME: array} Ключом второго элемента будет название модели.
     * @throws VetmanagerApiGatewayException - общее родительское исключение
     * @throws VetmanagerApiGatewayResponseEmptyException|VetmanagerApiGatewayResponseException|VetmanagerApiGatewayRequestException
     */
    public function getWithPagedQuery(ApiRoute $apiRouteKey, PagedQuery $pagedQuery, int $maxLimitOfReturnedModels = 100): array
    {
        $modelResponseKeyInJson = $apiRouteKey->getApiModelResponseKey(); #TODO another
        $arrayOfModelsWithTheirContents = [];

        do {
            $modelDataContents = $this->getModelsDataContentsUsingPagedQueryWithOneRequest($apiRouteKey, $pagedQuery);
            $pagedQuery->next();
            $arrayOfModelsWithTheirContents = array_merge($arrayOfModelsWithTheirContents, $modelDataContents[$modelResponseKeyInJson]);
        } while (count($arrayOfModelsWithTheirContents) == $maxLimitOfReturnedModels);

        return [
            'totalCount' => $modelDataContents['totalCount'],
            $modelResponseKeyInJson => $arrayOfModelsWithTheirContents
        ];
    }

    /**
     * @throws VetmanagerApiGatewayException - общее родительское исключение
     * @throws VetmanagerApiGatewayResponseEmptyException|VetmanagerApiGatewayResponseException|VetmanagerApiGatewayRequestException
     */
    private function getModelsDataContentsUsingPagedQueryWithOneRequest(ApiRoute $apiRouteKey, PagedQuery $pagedQuery): array
    {
        $url = $this->getUrlForGuzzleRequest($apiRouteKey);
        $response = $this->getResponseFromGuzzleClient('GET', $url, pagedQuery: $pagedQuery);
        return $this->getDataContentsFromResponseOrThrowOnFail($response);
    }

    /**
     * @return array Содержимое модели (например: ['id' => 1, ... ]
     * @throws VetmanagerApiGatewayException - общее родительское исключение
     * @throws VetmanagerApiGatewayResponseEmptyException|VetmanagerApiGatewayResponseException|VetmanagerApiGatewayRequestException
     */
    public function getWithId(ApiRoute $apiRouteKey, int $modelId): array
    {
        return $this->getModelsContentsAfterMakingRequest('GET', $apiRouteKey, $modelId);
    }

    /**
     * @return array Содержимое модели (например: ['id' => 1, ... ]
     * @throws VetmanagerApiGatewayException - общее родительское исключение
     * @throws VetmanagerApiGatewayResponseEmptyException|VetmanagerApiGatewayResponseException|VetmanagerApiGatewayRequestException
     */
    public function post(ApiRoute $apiRouteKey, array $data): array
    {
        return $this->getModelsContentsAfterMakingRequest('POST', $apiRouteKey, data: $data);
    }

    /**
     * @return array Содержимое модели (например: ['id' => 1, ... ]
     * @throws VetmanagerApiGatewayException - общее родительское исключение
     * @throws VetmanagerApiGatewayResponseEmptyException|VetmanagerApiGatewayResponseException|VetmanagerApiGatewayRequestException
     */
    public function put(ApiRoute $apiRouteKey, int $modelId, array $data): array
    {
        return $this->getModelsContentsAfterMakingRequest('PUT', $apiRouteKey, $modelId, $data);
    }

    /**
     * @throws VetmanagerApiGatewayException - общее родительское исключение
     * @throws VetmanagerApiGatewayResponseEmptyException|VetmanagerApiGatewayResponseException|VetmanagerApiGatewayRequestException
     */
    private function getModelsContentsAfterMakingRequest(string $method, ApiRoute $apiRouteKey, int $modelId = 0, array $data = []): array
    {
        $url = $this->getUrlForGuzzleRequest($apiRouteKey, $modelId);
        $response = $this->getResponseFromGuzzleClient($method, $url, $data);
        $apiDataContents = $this->getDataContentsFromResponseOrThrowOnFail($response);
        return $apiDataContents[$apiRouteKey->value];
    }

    /**
     * @throws VetmanagerApiGatewayException - общее родительское исключение
     * @throws VetmanagerApiGatewayResponseEmptyException|VetmanagerApiGatewayResponseException|VetmanagerApiGatewayRequestException
     */
    public function delete(ApiRoute $apiRouteKey, int $modelId): void
    {
        $url = $this->getUrlForGuzzleRequest($apiRouteKey, $modelId);
        $response = $this->getResponseFromGuzzleClient('DELETE', $url);
        $this->getDataContentsFromResponseOrThrowOnFail($response);
        // Будет возвращаться только ID, который был удален, поэтому игнорируем. При неудаче все равно исключение кидает
    }

    /** @return array{"totalCount": int, MODEL_NAME: array} Ключом второго элемента будет название модели. При некоторых запросах "totalCount" не будет
     * @throws VetmanagerApiGatewayResponseEmptyException
     * @throws VetmanagerApiGatewayResponseException
     */
    private function getDataContentsFromResponseOrThrowOnFail(ResponseInterface $response): array
    {
        $contents = json_decode($response->getBody()->getContents(), true);

        if (empty($contents) || !array_key_exists('success', $contents)) {
            throw new VetmanagerApiGatewayResponseEmptyException('Пустой ответ апи');
        }

        $success = filter_var($contents['success'], FILTER_VALIDATE_BOOLEAN);

        if (!$success) {
            throw new VetmanagerApiGatewayResponseException($contents['message'] ?? 'Неизвестная ошибка работы с апи');
        }

        return (array)$contents['data'];
    }

    /**
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayResponseException
     */
    private function getResponseFromGuzzleClient(string $method, string $url, array $data = [], ?PagedQuery $pagedQuery = null): ResponseInterface
    {
        $options = $this->getOptionsForGuzzleRequest($data, $pagedQuery);
        try {
            return $this->guzzleClient->request($method, $url, $options);
        } catch (GuzzleException $e) {
            throw new VetmanagerApiGatewayResponseException($e->getMessage());
        }

    }

    /** @throws VetmanagerApiGatewayRequestException */
    private function getUrlForGuzzleRequest(ApiRoute $apiRouteKey, int $modelId = 0): string
    {
        $modelKey = $apiRouteKey->value;

        $uri = ($modelId) ? new WithId(new Model($modelKey), $modelId) : new OnlyModel(new Model($modelKey));

        try {
            return $uri->asString();
        } catch (\Exception $e) {
            throw new VetmanagerApiGatewayRequestException($e->getMessage());
        }
    }

    /** @throws VetmanagerApiGatewayRequestException */
    private function getOptionsForGuzzleRequest(array $data = [], ?PagedQuery $pagedQuery = null): array
    {
        $options = ['headers' => $this->allHeaders->asKeyValue()];

        if ($data) {
            $options['body'] = json_encode($data);
        }

        try {

            if ($pagedQuery) {
                $options['query'] = $pagedQuery->asKeyValue();
            }

        } catch (\Exception $e) {
            throw new VetmanagerApiGatewayRequestException($e->getMessage());
        }

        return $options;
    }
}
