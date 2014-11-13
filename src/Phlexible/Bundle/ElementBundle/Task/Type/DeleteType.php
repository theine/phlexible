<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\ElementBundle\Task\Type;

/**
 * Delete element task type
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class DeleteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'element.delete';
    }

    /**
     * Get required parameters for this task
     *
     * @return array
     */
    public function getRequiredParameters()
    {
        return array('type', 'type_id');
    }

    /**
     * Return the task resource
     *
     * @return string
     */
    public function getResource()
    {
        return 'elements_delete';
    }

    /**
     * @return string
     */
    protected function getTitleKey()
    {
        return 'elements.task_delete_element';
    }

    /**
     * @return string
     */
    protected function getTextKey()
    {
        return 'elements.task_delete_element_template';
    }
}
