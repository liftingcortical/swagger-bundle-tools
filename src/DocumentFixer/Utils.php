<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundleTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KleijnWeb\SwaggerBundleTools\DocumentFixer;

class Utils
{
    /**
     * @param array $input
     *
     * @return \stdClass
     */
    public static function arrayToObject(array $input)
    {
        return json_decode(json_encode($input));
    }

    /**
     * @param object|\stdClass $input
     *
     * @return array
     */
    public static function objectToArray($input)
    {
        return json_decode(json_encode($input), true);
    }
}