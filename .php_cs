<?php

$finder = Symfony\CS\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__)
;

return Symfony\CS\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers([])
    ->finder($finder)
;
