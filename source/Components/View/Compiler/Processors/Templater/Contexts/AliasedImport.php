<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2015
 */
namespace Spiral\Components\View\Compiler\Processors\Templater\Contexts;

use Spiral\Components\View\Compiler\Processors\Templater\ImportInterface;

class AliasedImport implements ImportInterface
{
    protected $path = '';
    protected $alias = '';

    public function __construct($path, $alias)
    {
        $this->path = $path;
        $this->alias = $alias;
    }
}