<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\TreeBundle\Doctrine;

use Doctrine\DBAL\Connection;
use Phlexible\Bundle\ElementBundle\Model\ElementHistoryManagerInterface;
use Phlexible\Bundle\TreeBundle\Model\StateManagerInterface;
use Phlexible\Bundle\TreeBundle\Model\TreeNodeInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * State manager
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class StateManager implements StateManagerInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ElementHistoryManagerInterface
     */
    private $historyManager;

    /**
     * @param Connection                     $connection
     * @param EventDispatcherInterface       $dispatcher
     * @param ElementHistoryManagerInterface $historyManager
     */
    public function __construct(
        Connection $connection,
        EventDispatcherInterface $dispatcher,
        ElementHistoryManagerInterface $historyManager)
    {
        $this->connection = $connection;
        $this->dispatcher = $dispatcher;
        $this->historyManager = $historyManager;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished($node, $language)
    {
        $publishedVersions = $this->getPublishedVersions($node);

        return isset($publishedVersions[$language]);
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedLanguages($node)
    {
        return array_keys($this->getPublishedVersions($node));
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedVersions($node)
    {
        if ($node instanceof TreeNodeInterface) {
            $nodeId = $node->getId();
        } else {
            $nodeId = $node;
        }

        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select(array('eto.language', 'eto.version'))
            ->from('tree_online', 'eto')
            ->where($qb->expr()->eq('eto.tree_id', $nodeId));

        $statement = $this->connection->executeQuery($qb->getSQL());

        $versions = array();
        while ($row = $statement->fetch()) {
            $versions[$row['language']] = (int) $row['version'];
        }

        return $versions;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedVersion($node, $language)
    {
        $publishedVersions = $this->getPublishedVersions($node);
        if (!isset($publishedVersions[$language])) {
            return null;
        }

        return $publishedVersions[$language];
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishInfo($node, $language)
    {
        if ($node instanceof TreeNodeInterface) {
            $nodeId = $node->getId();
        } else {
            $nodeId = $node;
        }

        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('to.*')
            ->from('tree_online', 'to')
            ->where($qb->expr()->eq('to.tree_id', $nodeId))
            ->andWhere($qb->expr()->eq('to.language', $qb->expr()->literal($language)));

        $statement = $this->connection->executeQuery($qb->getSQL());

        $versions = array();
        while ($row = $statement->fetch()) {
            $versions[$row['language']] = (int) $row['version'];
        }

        return $versions;
    }

    /**
     * {@inheritdoc}
     */
    public function isAsync($node, $language)
    {
        if ($node instanceof TreeNodeInterface) {
            $nodeId = $node->getId();
        } else {
            $nodeId = $node;
        }

        return true;
    }
}