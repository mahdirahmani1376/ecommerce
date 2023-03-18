<?php

namespace App\States;

use App\States\OrderStates\Failed;
use App\States\OrderStates\Paid;
use App\States\OrderStates\Pending;
use Spatie\ModelStates\Attributes\AllowTransition;
use Spatie\ModelStates\Attributes\DefaultState;
use Spatie\ModelStates\Attributes\RegisterState;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;


abstract class OrderState extends State
{

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Pending::class, Failed::class)
            ->registerState([Paid::class, Failed::class,Pending::class]);
            ;
    }

}
