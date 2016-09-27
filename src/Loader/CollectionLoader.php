<?php

namespace Eve\Loader;

use Illuminate\Support\Collection;

interface CollectionLoader
{
    public function load(): Collection;
}
