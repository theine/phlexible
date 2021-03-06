<?php

/*
 * This file is part of the phlexible package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\ElementBundle\Search;

/**
 * EID search.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class EidSearch extends AbstractSearch
{
    /**
     * {@inheritdoc}
     */
    public function getSearchKey()
    {
        return 'eid';
    }

    /**
     * {@inheritdoc}
     */
    public function search($query)
    {
        $qb = $this->getConnection()->createQueryBuilder();
        $qb
            ->select('t.id')
            ->from('element', 'e')
            ->join('e', 'tree', 't', 'e.eid = t.type_id')
            ->where($qb->expr()->eq('e.eid', $qb->expr()->literal($query)));

        $rows = $this->getConnection()->fetchAll($qb->getSQL());

        return parent::doSearch($rows, 'Elements EID Search');
    }
}
