<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\TwigRendererBundle\Twig\Extension;

use Phlexible\Bundle\ElementBundle\Model\ElementStructureValue;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeContext;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\Model\TreeNodeInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Twig url extension
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class UrlExtension extends \Twig_Extension
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ContentTreeManagerInterface
     */
    private $contentTreeManager;

    /**
     * @param RouterInterface             $router
     * @param ContentTreeManagerInterface $contentTreeManager
     */
    public function __construct(RouterInterface $router, ContentTreeManagerInterface $contentTreeManager)
    {
        $this->router = $router;
        $this->contentTreeManager = $contentTreeManager;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('path', array($this, 'path')),
            new \Twig_SimpleFunction('url', array($this, 'url')),
        );
    }

    /**
     * @param string $name
     * @param array  $parameters
     *
     * @return string
     */
    public function path($name, array $parameters = array())
    {
        if ($name instanceof TreeNodeInterface) {
            return $this->router->generate($name, $parameters);
        } elseif ($name instanceof ContentTreeContext) {
            return $this->router->generate($name->getNode(), $parameters);
        } elseif ($name instanceof ElementStructureValue) {
            if ($name->getType() === 'link') {
                $link = $name->getValue();
                if ($link['type'] === 'internal' || $link['type'] === 'intrasiteroot') {
                    $tree = $this->contentTreeManager->findByTreeId($link['tid']);
                    $node = $tree->get($link['tid']);

                    return $this->router->generate($node, $parameters);
                } elseif ($link['type'] === 'external') {
                    return $link['url'];
                } elseif ($link['type'] === 'mailto') {
                    return 'mailto:' . $link['recipient'];
                }
            }
        } elseif (is_string($name)) {
            return $this->router->generate($name, $parameters);
        }

        return '';
    }

    /**
     * @param string $name
     * @param array  $parameters
     *
     * @return string
     */
    public function url($name, array $parameters = array())
    {
        if ($name instanceof TreeNodeInterface) {
            return $this->router->generate($name, $parameters, RouterInterface::ABSOLUTE_URL);
        } elseif ($name instanceof ContentTreeContext) {
            return $this->router->generate($name->getNode(), $parameters, RouterInterface::ABSOLUTE_URL);
        } elseif ($name instanceof ElementStructureValue) {
            if ($name->getType() === 'link') {
                $link = $name->getValue();
                if ($link['type'] === 'internal' || $link['type'] === 'intrasiteroot') {
                    $tree = $this->contentTreeManager->findByTreeId($link['tid']);
                    $node = $tree->get($link['tid']);

                    return $this->router->generate($node, $parameters, RouterInterface::ABSOLUTE_URL);
                } elseif ($link['type'] === 'external') {
                    return $link['url'];
                } elseif ($link['type'] === 'mailto') {
                    return 'mailto:' . $link['recipient'];
                }
            }
        } elseif (is_string($name)) {
            return $this->router->generate($name, $parameters, RouterInterface::ABSOLUTE_URL);
        }

        return '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'phlexible_url';
    }
}
