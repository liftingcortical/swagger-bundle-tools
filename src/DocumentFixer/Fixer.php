<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundleTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KleijnWeb\SwaggerBundleTools\DocumentFixer;

use KleijnWeb\PhpApi\Descriptions\Description\Document\Document;

abstract class Fixer
{
    /**
     * @var Fixer
     */
    private $next;

    /**
     * @param Document $document
     */
    public function fix(Document $document)
    {
        $this->process($document);

        if ($this->next) {
            $this->next->fix($document);
        }
    }

    /**
     * @param Fixer $next
     *
     * @return $this
     */
    public function chain(Fixer $next)
    {
        $this->next = $next;

        return $this;
    }

    /**
     * @param Document $document
     *
     * @return void
     */
    abstract public function process(Document $document);
}
