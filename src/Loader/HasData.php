<?php

namespace Eve\Loader;

interface HasData
{
    /**
     * @param CollectionLoader $loader
     */
    public function setLoader(CollectionLoader $loader);
}
