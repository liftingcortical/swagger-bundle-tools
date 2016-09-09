<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundleTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KleijnWeb\SwaggerBundleTools\DocumentFixer\Fixers;

use KleijnWeb\PhpApi\Descriptions\Description\Document\Document;
use KleijnWeb\SwaggerBundleTools\DocumentFixer\Fixer;
use KleijnWeb\SwaggerBundleTools\DocumentFixer\Utils;

class SwaggerBundleResponseFixer extends Fixer
{
    /**
     * @param Document $document
     *
     * @return void
     */
    public function process(Document $document)
    {
        $definition = $document->getDefinition();

        // Ensure responses property
        if (!isset($definition->responses)) {
            $definition->responses = new \stdClass();
        }

        $responseName = 'ServerError';
        if (!isset($definition->responses->$responseName)) {
            $definition->responses->$responseName = Utils::arrayToObject(
                [
                    'description' => 'Server Error',
                    'schema' => ['$ref' => '#/definitions/VndError'],
                ]
            );
        }

        $responseName = 'InputError';
        if (!isset($definition->responses->$responseName)) {
            $definition->responses->$responseName = Utils::arrayToObject(
                [
                    'description' => 'Input Error',
                    'schema' => ['$ref' => '#/definitions/VndError'],
                ]
            );
        }
        unset($responseName);

        // Ensure definitions property
        if (!isset($definition->definitions)) {
            $definition->definitions = new \stdClass();
        }

        $definitionName = 'VndError';
        if (!isset($definition->definitions->$definitionName)) {
            $definition->definitions->$definitionName = Utils::arrayToObject(
                [
                    'type' => 'object',
                    'required' => ['message', 'logref'],
                    'properties' => [
                        'message' => ['type' => 'string'],
                        'logref' => ['type' => 'string'],
                    ],
                ]
            );
        }
        unset($definitionName);

        // Ensure operations responses for generic error messages
        foreach ($definition->paths as &$operations) {
            foreach ($operations as &$operation) {
                $errorCode = 500;
                if (!isset($operation->responses->$errorCode)) {
                    $operation->responses->$errorCode = Utils::arrayToObject(
                        [
                            'description' => 'Generic server error',
                            'schema' => ['$ref' => '#/responses/ServerError'],
                        ]
                    );
                }
                $errorCode = 400;
                if (!isset($operation->responses->$errorCode)) {
                    $operation->responses->$errorCode = Utils::arrayToObject(
                        [
                            'description' => 'Client input error',
                            'schema' => ['$ref' => '#/responses/InputError'],
                        ]
                    );
                }
            }
        }
    }
}
