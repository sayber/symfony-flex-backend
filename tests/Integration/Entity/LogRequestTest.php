<?php
declare(strict_types=1);
/**
 * /tests/Integration/Entity/LogRequestTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests\Integration\Entity;

use App\Entity\ApiKey;
use App\Entity\LogRequest;
use App\Entity\User;
use App\Utils\Tests\PHPUnitUtil;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LogRequestTest
 *
 * @package App\Tests\Integration\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class LogRequestTest extends EntityTestCase
{
    /**
     * @var string
     */
    protected $entityName = LogRequest::class;

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * @param string $field
     * @param string $type
     * @param array  $meta
    */
    public function testThatSetterOnlyAcceptSpecifiedType(
        string $field = null,
        string $type = null,
        array $meta = null
    ): void {
        static::markTestSkipped('There is not setter in read only entity...');
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * @param string $field
     * @param string $type
     * @param array  $meta
     */
    public function testThatSetterReturnsInstanceOfEntity(
        string $field = null,
        string $type = null,
        array $meta = null
    ): void {
        static::markTestSkipped('There is not setter in read only entity...');
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * @dataProvider dataProviderTestThatSetterAndGettersWorks
     *
     * @param string $field
     * @param string $type
     * @param array  $meta
     */
    public function testThatGetterReturnsExpectedValue(string $field, string $type, array $meta): void
    {
        $getter = 'get' . \ucfirst($field);

        if ($type === 'boolean') {
            $getter = 'is' . \ucfirst($field);
        }

        $logRequest = new LogRequest(
            Request::create(''),
            Response::create('abcdefgh'),
            new User(),
            new ApiKey()
        );

        if (!(\array_key_exists('columnName', $meta) || \array_key_exists('joinColumns', $meta))) {
            $type = ArrayCollection::class;

            static::assertInstanceOf($type, $logRequest->$getter());
        }

        try {
            if (static::isType($type)) {
                static::assertInternalType($type, $logRequest->$getter());
            }
        } /** @noinspection BadExceptionsProcessingInspection */ catch (\Exception $error) {
            static::assertInstanceOf($type, $logRequest->$getter());
        }

        unset($logRequest);
    }

    /**
     * @dataProvider dataProviderTestThatSensitiveDataIsCleaned
     *
     * @param array $headers
     * @param array $expected
     */
    public function testThatSensitiveDataIsCleanedFromHeaders(array $headers, array $expected)
    {
        $request = Request::create('');
        $request->headers->replace($headers);

        $logRequest = new LogRequest($request, Response::create());

        static::assertSame($expected, $logRequest->getHeaders());

        unset($logRequest, $request);
    }

    /**
     * @dataProvider dataProviderTestThatSensitiveDataIsCleaned
     *
     * @param array $parameters
     * @param array $expected
     */
    public function testThatSensitiveDataIsCleanedFromParameters(array $parameters, array $expected)
    {
        $request = Request::create('', 'POST');
        $request->request->replace($parameters);

        $logRequest = new LogRequest($request, Response::create());

        static::assertSame($expected, $logRequest->getParameters());

        unset($logRequest, $request);
    }

    /**
     * @dataProvider dataProviderTestThatDetermineParametersWorksLikeExpected
     *
     * @param   string $content
     * @param   array  $expected
     *
     * @throws \ReflectionException
     */
    public function testThatDetermineParametersWorksLikeExpected(string $content, array $expected)
    {
        $logRequest = new LogRequest(Request::create(''), Response::create());

        $request = Request::create('', 'GET', [], [], [], [], $content);

        static::assertSame($expected, PHPUnitUtil::callMethod($logRequest, 'determineParameters', [$request]));

        unset($request, $logRequest);
    }

    /**
     * @return array
     */
    public function dataProviderTestThatSensitiveDataIsCleaned(): array
    {
        return [
            [
                ['password' => 'password'],
                ['password' => '*** REPLACED ***'],
            ],
            [
                ['token' => 'secret token'],
                ['token' => '*** REPLACED ***'],
            ],
            [
                ['authorization' => 'authorization bearer'],
                ['authorization' => '*** REPLACED ***'],
            ],
            [
                ['cookie' => 'cookie'],
                ['cookie' => '*** REPLACED ***'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderTestThatDetermineParametersWorksLikeExpected(): array
    {
        return [
            [
                '{"foo":"bar"}',
                ['foo' => 'bar'],
            ],
            [
                'foo=bar',
                ['foo' => 'bar'],
            ],
            [
                'false',
                [false],
            ]
        ];
    }
}
