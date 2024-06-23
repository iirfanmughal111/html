<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\MemberRole;

use Countable;
use ArrayAccess;
use ArrayIterator;
use function count;
use LogicException;
use function sprintf;
use JsonSerializable;
use function is_array;
use IteratorAggregate;
use function array_keys;
use ReturnTypeWillChange;
use function array_replace;
use InvalidArgumentException;
use function array_key_exists;

abstract class AbstractMemberRole implements ArrayAccess, Countable, JsonSerializable, IteratorAggregate
{
    /**
     * @var mixed
     */
    protected $roles = [];

    /**
     * @var array
     */
    protected $defaultPermissions = [];

    /**
     * @var array
     */
    private $permissions = [];

    public function __construct()
    {
        $this->setupDefaults();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * @param array $permissions
     * @return $this
     */
    public function setPermissions(array $permissions)
    {
        $this->permissions = array_replace($this->defaultPermissions, $this->permissions, $permissions);

        return $this;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function has(string $name)
    {
        return array_key_exists($name, $this->permissions)
            ? $this->permissions[$name]
            : null;
    }

    /**
     * @return string
     */
    abstract public function getRoleGroupId();

    /**
     * @return string|\XF\Phrase
     */
    abstract public function getRoleGroupTitle();

    /**
     * @return void
     */
    protected function setupDefaults()
    {
    }

    /**
     * @param string $roleId
     * @param string|\XF\Phrase $title
     * @param string $explain
     * @return $this
     */
    public function addRole(string $roleId, $title, string $explain = ''): AbstractMemberRole
    {
        $this->roles[$roleId] = [
            'title' => $title,
            'explain' => $explain
        ];

        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->roles);
    }

    /**
     * @return mixed
     * @phpstan-return \Traversable<mixed, mixed>
     */
    #[ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->roles);
    }

    /**
     * @return array
     */
    public function getRoleFilterRules(): array
    {
        $rules = [];
        foreach (array_keys($this->roles) as $role) {
            $rules[$role] = 'bool';
        }

        return [$this->getRoleGroupId() => $rules];
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getRoleGroupId(),
            'title' => $this->getRoleGroupTitle(),
            'roles' => $this->getRoles()
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->roles);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    #[ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->roles[$offset];
        }

        throw new InvalidArgumentException(sprintf(
            'Role (%s) does not exists in group (%s)',
            $offset,
            $this->getRoleGroupId()
        ));
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     * @return AbstractMemberRole
     */
    #[ReturnTypeWillChange]
    public function offsetSet($offset, $value): AbstractMemberRole
    {
        if (is_array($value)) {
            if ($value['title'] !== '') {
                return $this->addRole($offset, $value['title'], isset($value['explain']) ? $value['explain'] : '');
            }

            throw new InvalidArgumentException('Value must be contains `title` keys.');
        }

        return $this->addRole($offset, $value);
    }

    /**
     * @param mixed $offset
     * @return void
     */
    #[ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new LogicException('Unsupported to remove role.');
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * @param mixed $name
     * @param mixed $value
     * @return AbstractMemberRole
     */
    public function __set($name, $value)
    {
        return $this->offsetSet($name, $value);
    }

    /**
     * @param mixed $name
     * @return void
     */
    public function __unset($name)
    {
        $this->offsetUnset($name);
    }
}
