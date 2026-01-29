<?php


namespace Okay\Modules\Sviat\AIAgentActions;


use Okay\Core\Design;
use Okay\Core\FrontTranslations;
use Okay\Core\Languages;
use Okay\Core\Modules\Modules;
use Okay\Core\OkayContainer\Reference\ServiceReference as SR;
use Okay\Modules\Sviat\AIAgentActions\Extenders\FrontExtender;

return [
    FrontExtender::class => [
        'class' => FrontExtender::class,
        'arguments' => [
            new SR(Design::class),
            new SR(FrontTranslations::class),
            new SR(Languages::class),
            new SR(Modules::class),
        ],
    ],
];
