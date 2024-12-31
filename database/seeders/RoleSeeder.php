<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $viewOwnCharacter = Permission::findOrCreate('view own character');
        $viewAllCharacters = Permission::findOrCreate('view all characters');
        $createCharacter = Permission::findOrCreate('create character');
        $editOwnCharacter = Permission::findOrCreate('edit own character');
        $editAllCharacters = Permission::findOrCreate('edit all characters');
        $endOwnCharacter = Permission::findOrCreate('end own character');
        $endAllCharacters = Permission::findOrCreate('end all characters');
        $viewOwnLog = Permission::findOrCreate('view own log');
        $viewAllLogs = Permission::findOrCreate('view all logs');
        $createLog = Permission::findOrCreate('create log');
        $editOwnLog = Permission::findOrCreate('edit own log');
        $editAllLogs = Permission::findOrCreate('edit all logs');
        $addHiddenNotes = Permission::findOrCreate('add hidden notes');
        $editHiddenNotes = Permission::findOrCreate('edit hidden notes');
        $viewHiddenNotes = Permission::findOrCreate('view hidden notes');
        $addSkill = Permission::findOrCreate('add skill');
        $editSkill = Permission::findOrCreate('edit skill');
        $deleteSkill = Permission::findOrCreate('delete skill');
        $addFeat = Permission::findOrCreate('add feat');
        $editFeat = Permission::findOrCreate('edit feat');
        $deleteFeat = Permission::findOrCreate('delete feat');
        $addBackground = Permission::findOrCreate('add background');
        $editBackground = Permission::findOrCreate('edit background');
        $deleteBackground = Permission::findOrCreate('delete background');
        $modifyRoles = Permission::findOrCreate('modify roles');

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = Role::findOrCreate('admin');
        $plotCoordinator = Role::findOrCreate('plot coordinator');
        $systemReferee = Role::findOrCreate('system referee');
        $player = Role::findOrCreate('player');

        $admin->syncPermissions([
            $modifyRoles,
        ]);

        $plotCoordinator->syncPermissions([
            $viewAllCharacters,
            $editAllCharacters,
            $endAllCharacters,
            $viewAllLogs,
            $editAllLogs,
            $addHiddenNotes,
            $editHiddenNotes,
            $viewHiddenNotes,
        ]);

        $systemReferee->syncPermissions([
            $addSkill,
            $editSkill,
            $deleteSkill,
            $addFeat,
            $editFeat,
            $deleteFeat,
            $addBackground,
            $editBackground,
            $deleteBackground,
        ]);

        $player->syncPermissions([
            $viewOwnCharacter,
            $createCharacter,
            $editOwnCharacter,
            $endOwnCharacter,
            $viewOwnLog,
            $createLog,
            $editOwnLog,
        ]);
    }
}
