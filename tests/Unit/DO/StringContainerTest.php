<?php

namespace VetmanagerApiGateway\Unit\DO;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;
use VetmanagerApiGateway\Hydrator\ApiString;

#[CoversClass(ApiString::class)]
class StringContainerTest extends TestCase
{
    public static function dataProviderForSting(): array
    {
        return [
            [null, ''],
            ['Hello1', 'Hello1'],
        ];
    }

    #[DataProvider('dataProviderForSting')]
    public function testStringMethod(?string $stringOrNull, ?string $expected, string $messageInCaseOfError = ''): void
    {
        $this->assertEquals(
            $expected,
            ApiString::fromStringOrNull($stringOrNull)->getStringEvenIfNullGiven(),
            $messageInCaseOfError
        );
    }

    /** @throws VetmanagerApiGatewayResponseException */
    public function testStringOrThrowIfNullMethod(): void
    {
        $this->assertEquals('Hello1', ApiString::fromStringOrNull('Hello1')->getStringOrThrowIfNull());
        $this->expectException(VetmanagerApiGatewayResponseException::class);
        ApiString::fromStringOrNull(null)->getStringOrThrowIfNull();
    }

    /** @throws VetmanagerApiGatewayResponseException */
    public function testNonEmptyStringMethod(): void
    {
        $this->assertEquals('Hello1', ApiString::fromStringOrNull('Hello1')->getNonEmptyStringOrThrow());
        $this->expectException(VetmanagerApiGatewayResponseException::class);
        ApiString::fromStringOrNull('')->getNonEmptyStringOrThrow();
        $this->expectException(VetmanagerApiGatewayResponseException::class);
        ApiString::fromStringOrNull(null)->getNonEmptyStringOrThrow();
    }
}
