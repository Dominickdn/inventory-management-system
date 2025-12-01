<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;

class PaginationService
{
    public function paginate(QueryBuilder $qb, int $page = 1, int $limit = 10): array
    {
        $firstResult = ($page - 1) * $limit;

        $qb->setFirstResult($firstResult)
            ->setMaxResults($limit);

        $paginator = new Paginator($qb);

        return [
            'items'        => iterator_to_array($paginator),
            'total'        => count($paginator),
            'page'         => $page,
            'limit'        => $limit,
            'pages'        => ceil(count($paginator) / $limit),
        ];
    }
}