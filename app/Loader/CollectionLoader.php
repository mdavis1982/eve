<?php

namespace App\Loader;

use Illuminate\Support\Collection;

interface CollectionLoader
{
    /**
     * @param string $path
     *
     * @return Collection
     */
    public function load($path);
}
