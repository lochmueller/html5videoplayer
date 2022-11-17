<?php

namespace HVP\Html5videoplayer\ViewHelpers\Format;

use \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class WrapCurlyBracesViewHelper extends AbstractViewHelper
{

    /**
     * Specifies whether the escaping interceptors should be disabled or enabled for the result of renderChildren() calls within this ViewHelper
     * @see isChildrenEscapingEnabled()
     *
     * Note: If this is NULL the value of $this->escapingInterceptorEnabled is considered for backwards compatibility
     *
     * @var boolean
     * @api
     */
    protected $escapeChildren = false;

    /**
     * Specifies whether the escaping interceptors should be disabled or enabled for the render-result of this ViewHelper
     * @see isOutputEscapingEnabled()
     *
     * @var boolean
     * @api
     */
    protected $escapeOutput = false;

    /**
     * @return string
     */
    public function render()
    {
        return '{' . $this->renderChildren() . '}';
    }

}
