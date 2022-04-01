<?php

namespace iggyvolz\contabo\Pagination;

final class Links
{
    public function __construct(
        public readonly string $first,
        public readonly string $previous,
        public readonly string $self,
        public readonly string $next,
        public readonly string $last,
    )
    {
    }
}