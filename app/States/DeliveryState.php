<?php

namespace App\States;

use App\Models\Delivery;
use App\States\DeliveryStates\Refounded;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class DeliveryState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Delivery::class)
            ->allowTransition(Delivery::class, Refounded::class)
            ->allowTransition(Refounded::class, Delivery::class)
            ->registerState([Delivery::class, Refounded::class]);
    }
}
