<?php

namespace App\Loader;

use Illuminate\Support\Collection;
use App\Exceptions\NoLoaderException;
use App\Exceptions\NoDataFileDefinedException;

trait LoadsData
{
    /**
     * @var Collection
     */
    private $data;

    /**
     * @param string $path
     */
    public function loadData()
    {
        if (! $this->loader) {
            throw new NoLoaderException();
        }

        if (! isset($this->dataFile)) {
            throw new NoDataFileDefinedException();
        }

        if (! $this->data) {
            $this->data = $this->loader->load(resource_path('data/') . $this->dataFile);
        }
    }
}
