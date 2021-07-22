<?php

namespace App\Role;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SALES = 'ROLE_SALES';
    const ROLE_TECHNICAL = 'ROLE_TECHNICAL';
    const ROLE_OPERATION = 'ROLE_OPERATION';
    const ROLE_SALES_MANAGER = 'ROLE_SALES_MANAGER';
    const ROLE_TECHNICAL_MANAGER = 'ROLE_TECHNICAL_MANAGER';
    const ROLE_OPERATION_MANAGER = 'ROLE_OPERATION_MANAGER';
    const ROLE_SUPPORT = 'ROLE_SUPPORT';

    /**
     * @var array
     */
    protected static $roleHierarchy = [
        self::ROLE_ADMIN => ['*'],
        self::ROLE_SALES_MANAGER => [
            self::ROLE_SALES_MANAGER,
            self::ROLE_SALES,
            self::ROLE_SUPPORT,
        ],
        self::ROLE_TECHNICAL_MANAGER => [
            self::ROLE_TECHNICAL_MANAGER,
            self::ROLE_TECHNICAL,
            self::ROLE_SUPPORT,
        ],
        self::ROLE_OPERATION_MANAGER => [
            self::ROLE_OPERATION_MANAGER,
            self::ROLE_OPERATION,
            self::ROLE_SUPPORT,
        ],
        self::ROLE_OPERATION => [
            self::ROLE_OPERATION
        ],
        self::ROLE_TECHNICAL => [
            self::ROLE_TECHNICAL
        ],
        self::ROLE_SALES => [
            self::ROLE_SALES
        ],
        self::ROLE_SUPPORT => []
    ];

    /**
     * @param string $role
     * @return array
     */
    public static function getAllowedRoles(string $role)
    {
        if (isset(self::$roleHierarchy[$role])) {
            return self::$roleHierarchy[$role];
        }

        return [];
    }

    /***
     * @return array
     */
    public static function getRoleList()
    {
        return [
            static::ROLE_ADMIN =>'Admin',
            static::ROLE_SALES => 'sales',
            static::ROLE_TECHNICAL => 'technical',
            static::ROLE_OPERATION => 'operation',
            static::ROLE_SALES_MANAGER => 'sales manager',
            static::ROLE_TECHNICAL_MANAGER => 'technical manager',
            static::ROLE_OPERATION_MANAGER => 'operation manager',
            static::ROLE_SUPPORT => 'Support',
        ];
    }
}
