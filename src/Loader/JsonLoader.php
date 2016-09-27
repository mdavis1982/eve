<?php

namespace Eve\Loader;

use Illuminate\Support\Collection;

final class JsonLoader implements CollectionLoader
{
    /**
     * @var string
     */
    private $pathname;

    /**
     * @param string $pathname
     */
    public function __construct(string $pathname)
    {
        $this->pathname = $pathname;
    }

    /**
     * @return Collection
     */
    public function load(): Collection
    {
        return Collection::make(\json_decode(file_get_contents($this->pathname)));
    }
}
