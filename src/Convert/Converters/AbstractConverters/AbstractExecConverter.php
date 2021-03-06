<?php

namespace WebPConvert\Convert\Converters\AbstractConverters;

use WebPConvert\Convert\Converters\AbstractConverters\AbstractConverter;

use WebPConvert\Convert\Exceptions\ConversionFailedException;
use WebPConvert\Convert\Exceptions\ConversionFailed\ConverterNotOperational\SystemRequirementsNotMetException;

abstract class AbstractExecConverter extends AbstractConverter
{
    protected static function escapeFilename($string)
    {
        // Escaping whitespace
        $string = preg_replace('/\s/', '\\ ', $string);

        // filter_var() is should normally be available, but it is not always
        // - https://stackoverflow.com/questions/11735538/call-to-undefined-function-filter-var
        if (function_exists('filter_var')) {
            // Sanitize quotes
            $string = filter_var($string, FILTER_SANITIZE_MAGIC_QUOTES);

            // Stripping control characters
            // see https://stackoverflow.com/questions/12769462/filter-flag-strip-low-vs-filter-flag-strip-high
            $string = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        }

        return $string;
    }

    protected static function hasNiceSupport()
    {
        exec("nice 2>&1", $niceOutput);

        if (is_array($niceOutput) && isset($niceOutput[0])) {
            if (preg_match('/usage/', $niceOutput[0]) || (preg_match('/^\d+$/', $niceOutput[0]))) {
                /*
                 * Nice is available - default niceness (+10)
                 * https://www.lifewire.com/uses-of-commands-nice-renice-2201087
                 * https://www.computerhope.com/unix/unice.htm
                 */

                return true;
            }
            return false;
        }
    }

    /**
     * Check basis operationality of exec converters.
     *
     * @throws  SystemRequirementsNotMetException
     * @return  void
     */
    protected function checkOperationality()
    {
        if (!function_exists('exec')) {
            throw new SystemRequirementsNotMetException('exec() is not enabled.');
        }
    }
}
