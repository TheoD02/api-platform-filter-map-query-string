<?php

declare(strict_types=1);

namespace Theod02\ApiPlatformFilterMapQueryString\Attribute;

use ApiPlatform\Metadata\FilterInterface;
use ApiPlatform\Metadata\Parameter;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ApiParameter extends Parameter
{
    /**
     * @param array{type?: string}|null    $schema
     * @param array<string, mixed>|null    $extraProperties
     * @param FilterInterface|string|null  $filter
     * @param Constraint|Constraint[]|null $constraints
     */
    public function __construct(
        protected ?string $key = null,
        protected ?array $schema = null,
        protected ?\ApiPlatform\OpenApi\Model\Parameter $openApi = null,
        protected mixed $filter = null,
        protected ?string $property = null,
        protected ?string $description = null,
        protected ?bool $required = null,
        protected ?int $priority = null,
        protected Constraint|array|null $constraints = null,
        protected ?array $extraProperties = [],
    ) {
        parent::__construct(
            key: $key,
            schema: $schema,
            openApi: $openApi,
            filter: $filter,
            property: $property,
            description: $description,
            required: $required,
            priority: $priority,
            constraints: $constraints,
            extraProperties: $extraProperties,
        );
    }
}
