<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Role;
use App\User;
/**
 * Description of RoleChecker
 *
 * @author j.mani
 */
class RoleChecker {

   /**
     * @param User $user
     * @param string $role
     * @return bool
     */
    public function check(User $user, string $role)
    {
        // Admin has everything

        if ($user->hasRole(Role::ROLE_ADMIN)) {
            return true;
        }
        
        else if($user->hasRole(Role::ROLE_SALES_MANAGER)) {
            $managementRoles = Role::getAllowedRoles(Role::ROLE_SALES_MANAGER);

            if (in_array($role, $managementRoles)) {
                return true;
            }
        }

        return $user->hasRole($role);
    }
}
