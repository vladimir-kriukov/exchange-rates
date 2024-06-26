<?php

declare(strict_types=1);

namespace App\Providers;

use App\Dto\Rate;
use App\Dto\Rate as RateDto;
use App\Parsers\JsonResponseParser;
use App\Parsers\NullResponseParser;
use App\Parsers\XmlResponseParser;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Parent class for all rates providers
 */
abstract class RatesProvider implements RatesProviderInterface
{
    final public function __construct(private readonly HttpClientInterface $client, protected readonly string $url, protected readonly string $base)
    {
    }

    /**
     * @inheritDoc
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function __invoke(): array
    {
        $data = $this->fetch($this->url);
        $data = $this->parse($data);
        $data = $this->transform($data);

        return array_merge($data, $this->reverse($data));
    }

    /**
     * Download rates.
     *
     * @throws TransportExceptionInterface
     */
    protected function fetch(string $url): ResponseInterface
    {
        return $this->client->request('GET', $url);
    }

    /**
     * Parse response.
     *
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    protected function parse(ResponseInterface $response): array
    {
        $contentType = $response->getHeaders()['content-type'][0];

        $parser = match ($contentType) {
            'application/json', 'application/json; charset=utf-8', 'application/javascript' => JsonResponseParser::class,
            'text/xml' => XmlResponseParser::class,
            default => NullResponseParser::class,
        };

        return (new $parser())->parse($response);
    }

    /**
     * Transform parsed content into rates array.
     *
     * @return RateDto[]
     *
     * @throws Exception
     */
    protected function transform(array $data): array
    {
        return array_map(
            static fn (array $rate): Rate => new Rate(
                $rate['currency'],
                $rate['base'],
                $rate['rate'],
                new \DateTimeImmutable($rate['date']),
            ),
            $data
        );
    }

    /**
     * Reverse parsed content into rates array.
     *
     * @return RateDto[]
     *
     * @throws Exception
     */
    protected function reverse(array $data): array
    {
        return array_map(
            static fn (RateDto $rate): Rate => new Rate(
                $rate->base,
                $rate->currency,
                (string)BigDecimal::of(1)->dividedBy(BigDecimal::of($rate->rate), 8, RoundingMode::HALF_DOWN),
                $rate->date,
            ),
            $data
        );
    }
}
