<?php

namespace Oxalistech\LiveBStack;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \LiveBStack\Forms\Form form()
 * @method static \LiveBStack\Tables\Table table()
 * @method static \LiveBStack\Forms\Field field(string $type, string $name)
 * @method static \LiveBStack\Tables\Column column(string $type, string $name)
 * @method static string version()
 * @method static bool statsEnabled()
 * @method static string theme()
 * @method static array notificationSettings()
 * @method static array tableSettings()
 *
 * @see \LiveBStack\LiveBStack
 */
class LiveBStackFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'livebstack';
    }
}