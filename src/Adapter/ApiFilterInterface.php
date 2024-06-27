<?php

declare(strict_types=1);

namespace Theod02\ApiPlatformFilterMapQueryString\Adapter;

use Doctrine\ORM\QueryBuilder;

interface ApiFilterInterface
{
    public function apply(QueryBuilder $qb): QueryBuilder;
}
