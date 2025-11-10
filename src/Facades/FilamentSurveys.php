<?php

namespace ElmudoDev\FilamentSurveys\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ElmudoDev\FilamentSurveys\FilamentSurveys
 */
class FilamentSurveys extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ElmudoDev\FilamentSurveys\FilamentSurveys::class;
    }
}
