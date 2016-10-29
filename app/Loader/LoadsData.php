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
            if (is_array($this->dataFile)) {
                foreach ($this->dataFile as $dataFile) {
                    $loadData = $this->loader->load(resource_path('data/') . $dataFile);
                    $jsonName = key(get_object_vars(json_decode($loadData)));

                    $this->data[$jsonName] = $loadData;
                }

                return;
            }

            $this->data = $this->loader->load(resource_path('data/') . $this->dataFile);
        }
    }
}
