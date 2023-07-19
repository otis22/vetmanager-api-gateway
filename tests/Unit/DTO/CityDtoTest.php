<?php

namespace VetmanagerApiGateway\Unit\DTO;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use VetmanagerApiGateway\DTO\CityDto;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;

#[CoversClass(CityDto::class)]
class CityDtoTest extends TestCase
{
    public static function dataProviderClientJson(): array
    {
        return [
            [
                /** @lang JSON */
                <<<'EOF'
{
    "id": "255",
    "title": "city_001",
    "type_id": "1"
}
EOF
                , "getId", 255]
        ];
    }

    /** @throws VetmanagerApiGatewayResponseException */
    #[DataProvider('dataProviderClientJson')]
    public function testDeserialization(string $json, string $getMethodName, int|string $expected): void
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $dto = $serializer->deserialize($json, CityDto::class, 'json');
        $this->assertEquals($expected, $dto->$getMethodName());
    }

    /** @throws VetmanagerApiGatewayResponseException */
    #[DataProvider('dataProviderClientJson')]
    public function testDenormalization(string $json, string $getMethodName, int|string $expected): void
    {
        $array = json_decode($json, true);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $dto = $serializer->denormalize($array, CityDto::class);
        $this->assertEquals($expected, $dto->$getMethodName());
    }
}
