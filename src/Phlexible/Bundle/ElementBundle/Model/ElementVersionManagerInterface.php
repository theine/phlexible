<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\ElementBundle\Model;

use Phlexible\Bundle\ElementBundle\Entity\Element;
use Phlexible\Bundle\ElementBundle\Entity\ElementVersion;

/**
 * Element version manager interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface ElementVersionManagerInterface
{
    /**
     * @param Element $element
     * @param integer $version
     *
     * @return ElementVersion
     */
    public function find(Element $element, $version);

    /**
     * @param Element $element
     *
     * @return array
     */
    public function getVersions(Element $element);

    /**
     * @param ElementVersion $elementVersion
     */
    public function updateElementVersion(ElementVersion $elementVersion);
}