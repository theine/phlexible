<?php

/*
 * This file is part of the phlexible package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Component\AccessControl\Permission;

use Phlexible\Component\AccessControl\Exception\InvalidArgumentException;

/**
 * Permission registry.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class PermissionRegistry
{
    /**
     * @var array
     */
    private $permissionCollections = array();

    /**
     * @param PermissionCollection[] $permissionCollections
     */
    public function __construct(array $permissionCollections = array())
    {
        foreach ($permissionCollections as $permissionCollection) {
            $this->add($permissionCollection);
        }
    }

    /**
     * @param PermissionProviderInterface $provider
     *
     * @return $this
     */
    public function addProvider(PermissionProviderInterface $provider)
    {
        $this->add($provider->getPermissions());

        return $this;
    }

    /**
     * @param PermissionCollection $permissions
     *
     * @return $this
     */
    public function add(PermissionCollection $permissions)
    {
        $this->permissionCollections[$permissions->getObjectType()] = $permissions;

        return $this;
    }

    /**
     * Return all permissions.
     *
     * @return PermissionCollection[]
     */
    public function all()
    {
        return $this->permissionCollections;
    }

    /**
     * Return permissions for object type.
     *
     * @param string $objectType
     *
     * @throws InvalidArgumentException
     *
     * @return PermissionCollection
     */
    public function get($objectType)
    {
        do {
            if (isset($this->permissionCollections[$objectType])) {
                return $this->permissionCollections[$objectType];
            }
            $actualObjectType = $objectType;
            $objectType = get_parent_class($objectType);
        } while ($objectType !== false);

        throw new InvalidArgumentException("No permissions for type $actualObjectType found.");
    }

    /**
     * @param string $objectType
     *
     * @return bool
     */
    public function has($objectType)
    {
        return !empty($this->permissionCollections[$objectType]);
    }
}
