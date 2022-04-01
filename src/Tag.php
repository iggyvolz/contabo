<?php

namespace iggyvolz\contabo;

final class Tag
{
    public function __construct(
        public readonly int $tagId,
        public readonly int $tagName,
    )
    {
    }
}