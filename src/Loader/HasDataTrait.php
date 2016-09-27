<?php

namespace Eve\Loader;

use Illuminate\Support\Collection;
use Eve\Loader\Exception\LoaderException;

trait HasDataTrait
{
    /**
     * @var CollectionLoader
     */
    private $loader;

    /**
     * @var Collection
     */
    private $data;

    /**
     * @param CollectionLoader $loader
     *
     * @return $this
     */
    public function setLoader(CollectionLoader $loader)
    {
        $this->loader = $loader;

        return $this;
    }

    private function loadData()
    {
        if (!$this->loader) {
            throw LoaderException::create();
        }

        if (!$this->data) {
            $this->data = $this->loader->load();
        }
    }
}
