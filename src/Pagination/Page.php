<?php

namespace iggyvolz\contabo\Pagination;

class Page
{
    public function __construct(
        public readonly Pagination $_pagination,
        public readonly array $data,
        public readonly Links $_links,
    )
    {
    }
}