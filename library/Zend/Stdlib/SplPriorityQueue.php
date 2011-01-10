<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Stdlib
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Stdlib;

use Serializable;

/**
 * Serializable version of SplPriorityQueue
 *
 * @category   Zend
 * @package    Zend_Stdlib
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SplPriorityQueue extends \SplPriorityQueue implements Serializable
{
    /**
     * Serialize to an array
     *
     * Array will be priority => data pairs
     * 
     * @return array
     */
    public function toArray()
    {
        $this->setExtractFlags(self::EXTR_BOTH);
        $array = array();
        while ($this->valid()) {
            $array[] = $this->current();
            $this->next();
        }
        $this->setExtractFlags(self::EXTR_DATA);

        // Iterating through a priority queue removes items
        foreach ($array as $item) {
            $this->insert($item['data'], $item['priority']);
        }

        // Return only the data
        $return = array();
        foreach ($array as $item) {
            $return[$item['priority']] = $item['data'];
        }

        return $return;
    }

    /**
     * Serialize
     * 
     * @return string
     */
    public function serialize()
    {
        $data = array();
        $this->setExtractFlags(self::EXTR_BOTH);
        while ($this->valid()) {
            $data[] = $this->current();
            $this->next();
        }
        $this->setExtractFlags(self::EXTR_DATA);

        // Iterating through a priority queue removes items
        foreach ($data as $item) {
            $this->insert($item['data'], $item['priority']);
        }

        return serialize($data);
    }

    /**
     * Deserialize
     * 
     * @param  string $data
     * @return void
     */
    public function unserialize($data)
    {
        foreach (unserialize($data) as $item) {
            $this->insert($item['data'], $item['priority']);
        }
    }
}