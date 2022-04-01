<?php

namespace iggyvolz\contabo\Pagination;

final class Pagination
{
    public function __construct(
        public readonly int $size,
        public readonly int $totalElements,
        public readonly int $totalPages,
        public readonly int $page,
    )
    {
    }
}