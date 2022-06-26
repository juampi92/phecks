<?php

use Juampi92\Phecks\Domain\Checks;

return [
    /*
    |--------------------------------------------------------------------------
    | Checks
    |--------------------------------------------------------------------------
    |
    | Description.
     */

    'checks' => [
        Checks\RouteNameCheck::class,
        Checks\RoutePathCheck::class,
        Checks\ModelsNamespaceCheck::class,
        Checks\ActionsShouldNotExtendCheck::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Baseline
    |--------------------------------------------------------------------------
    |
    | Where the baseline is located.
     */

    'baseline' => '.phecks.baseline.json',
];
