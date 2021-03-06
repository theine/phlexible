<?php

/*
 * This file is part of the phlexible package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\TreeBundle\ContentTree;

use Phlexible\Bundle\SiterootBundle\Entity\Navigation;
use Phlexible\Bundle\SiterootBundle\Entity\Siteroot;
use Phlexible\Bundle\SiterootBundle\Entity\Url;
use Phlexible\Bundle\TreeBundle\Model\TreeInterface;
use Phlexible\Bundle\TreeBundle\Model\TreeNodeInterface;

/**
 * Content tree interface.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface ContentTreeInterface extends TreeInterface
{
    /**
     * @return Siteroot
     */
    public function getSiteroot();

    /**
     * @return bool
     */
    public function isDefaultSiteroot();

    /**
     * @return Url[]
     */
    public function getUrls();

    /**
     * @return Url
     */
    public function getDefaultUrl();

    /**
     * @return Navigation[]
     */
    public function getNavigations();

    /**
     * @param string $language
     *
     * @return array
     */
    public function getSpecialTids($language = null);

    /**
     * @param TreeNodeInterface $treeNode
     *
     * @return ContentTreeNode
     */
    public function createContentTreeNodeFromTreeNode(TreeNodeInterface $treeNode);

    /**
     * @param TreeNodeInterface $node
     * @param string            $language
     *
     * @return int
     */
    public function getVersion(TreeNodeInterface $node, $language);

    /**
     * @param TreeNodeInterface $node
     * @param string            $language
     *
     * @return bool
     */
    public function isViewable(TreeNodeInterface $node, $language = null);

    /**
     * @param TreeNodeInterface $node
     * @param string            $language
     *
     * @return bool
     */
    public function isSluggable(TreeNodeInterface $node, $language = null);

    /**
     * @param TreeNodeInterface $node
     */
    public function getContent(TreeNodeInterface $node);
}
