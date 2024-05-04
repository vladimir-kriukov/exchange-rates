<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RateRepository;
use App\Validator\Constraints\DateRequirements;
use App\Validator\Constraints\RateCurrencyRequirements;
use App\Validator\Constraints\RateRequirements;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

#[ORM\Entity(repositoryClass: RateRepository::class)]
#[ORM\Table(name: 'rates')]
#[ORM\Index(name: 'idx_base_currency', columns: ['base', 'currency'])]
#[ORM\UniqueConstraint(columns: ['date', 'base', 'currency'])]
class Rate
{
    #[ORM\Id, GeneratedValue, Column(type: Types::INTEGER, updatable: false)]
    private int $id;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, updatable: true), DateRequirements]
    private DateTimeImmutable $date;

    #[ORM\Column(type: Types::STRING, length: 3, updatable: false), RateCurrencyRequirements]
    private string $base;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 8, updatable: true), RateRequirements]
    private string $rate;

    #[ORM\Column(type: Types::STRING, length: 3, updatable: false), RateCurrencyRequirements]
    private string $currency;

    public function getId(): int
    {
        return $this->id;
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function setBase(string $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }
}
