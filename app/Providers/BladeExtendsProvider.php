<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeExtendsProvider extends ServiceProvider
{
    const PREFIX = 'macros_';
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('macros', function($expression) {
            preg_match('#^([a-z]{1}[a-z0-9\_]*)([\(\,])?(.*?)\)?$#i', $expression, $matchs);
            if (!isset($matchs[1])) {
                return "@macros($expression)";
            }
            $prefix = self::PREFIX;
            // if define macros. Example @macros(macros_name($param1, $param2))
            if ($matchs[2] == '(') {
                if ($matchs[1] && $matchs[3]) {
                    return "<?php function {$prefix}{$matchs[1]}({$matchs[3]}, \$__env) { ?>";
                }
                else if ($matchs[1]) {
                    return "<?php function {$prefix}{$matchs[1]}(\$__env) { ?>";
                }
            }
            // run function
            else {
                return "<?php {$prefix}{$matchs[1]}(" . ($matchs[2] == ',' ? "{$matchs[3]}, " : '') . '$__env) ?>';
            }
        });

        Blade::directive('endmacros', function($expression) {
            return '<?php } ?>';
        });

        Blade::directive('ifmacros', function($expression) {
            $prefix = self::PREFIX;
            $expression = trim($expression, '\'\" ');
            return "<?php if (function_exists('{$prefix}{$expression}')): ?>";
        });

        Blade::directive('unlessmacros', function($expression) {
            $expression = trim($expression, '\'\" ');
            $prefix = self::PREFIX;
            return "<?php if (!function_exists('{$prefix}{$expression}')): ?>";
        });

        Blade::directive('return', function($expression) {
            return "<?php return; ?>";
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
