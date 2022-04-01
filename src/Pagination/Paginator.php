<?php

namespace iggyvolz\contabo\Pagination;

use CuyZ\Valinor\MapperBuilder;
use iggyvolz\contabo\Client;

/**
 * @template T
 * @implements \Iterator<T>
 */
class Paginator implements \Iterator
{
    /** @var null|Page<T> */
    private ?Page $currentPage = null;
    private int $index = 0;
    private int $indexCurrentPage = 0;

    public function __construct(
        private readonly Client $client,
        private readonly string $url,
        /** @param class-string<T> */
        private readonly string $t,
    )
    {
        $this->rewind();
    }


    public function current(): mixed
    {
        $res = $this->currentPage?->data[$this->indexCurrentPage] ?? null;
        if(!is_null($res)) {
            return $this->client->mapper->map($this->t, $res);
        } else {
            return null;
        }
    }

    public function next(): void
    {
        $this->index++;
        $this->indexCurrentPage++;
        if(!$this->valid() && $this->currentPage?->_links?->next !== "" && $this->currentPage?->_links?->next !== null) {
            // Go to next page
            $this->currentPage = $this->client->execute("GET", $this->currentPage->_links->next, Page::class);
            $this->indexCurrentPage = 0;
        }
    }

    public function key(): mixed
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return !is_null($this->current());
    }

    public function rewind(): void
    {
        $this->currentPage = $this->client->execute("GET", $this->url, Page::class);
    }
}