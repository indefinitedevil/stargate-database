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
        $deleteOwnCharacter = Permission::findOrCreate('delete own character');
        $deleteAllCharacters = Permission::findOrCreate('delete all characters');
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
        $addSkillSpecialty = Permission::findOrCreate('add skill specialty');
        $editSkillSpecialty = Permission::findOrCreate('edit skill specialty');
        $deleteSkillSpecialty = Permission::findOrCreate('delete skill specialty');
        $addFeat = Permission::findOrCreate('add feat');
        $editFeat = Permission::findOrCreate('edit feat');
        $deleteFeat = Permission::findOrCreate('delete feat');
        $addBackground = Permission::findOrCreate('add background');
        $editBackground = Permission::findOrCreate('edit background');
        $deleteBackground = Permission::findOrCreate('delete background');
        $modifyRoles = Permission::findOrCreate('modify roles');
        $viewAllUsers = Permission::findOrCreate('view all users');
        $editAllUsers = Permission::findOrCreate('edit all users');
        $deleteAllUsers = Permission::findOrCreate('delete all users');
        $editEvents = Permission::findOrCreate('edit events');
        $viewAttendance = Permission::findOrCreate('view attendance');
        $recordAttendance = Permission::findOrCreate('record attendance');
        $addResearchProjects = Permission::findOrCreate('add research projects');
        $editResearchProjects = Permission::findOrCreate('edit research projects');
        $deleteResearchProjects = Permission::findOrCreate('delete research projects');
        $viewSkillBreakdown = Permission::findOrCreate('view skill breakdown');
        $editDowntimes = Permission::findOrCreate('edit downtimes');
        $accessExecutiveMenu = Permission::findOrCreate('access executive menu');

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = Role::findOrCreate('admin');
        $plotCoordinator = Role::findOrCreate('plot coordinator');
        $systemReferee = Role::findOrCreate('system referee');
        $secretary = Role::findOrCreate('secretary');
        $researcher = Role::findOrCreate('researcher');
        $player = Role::findOrCreate('player');

        $admin->syncPermissions([
            $modifyRoles,
            $viewAllUsers,
            $editAllUsers,
            $deleteAllUsers,
            $accessExecutiveMenu,
        ]);

        $plotCoordinator->syncPermissions([
            $viewAllCharacters,
            $editAllCharacters,
            $deleteAllCharacters,
            $endAllCharacters,
            $viewAllLogs,
            $editAllLogs,
            $addHiddenNotes,
            $editHiddenNotes,
            $viewHiddenNotes,
            $addSkillSpecialty,
            $editSkillSpecialty,
            $deleteSkillSpecialty,
            $viewAllUsers,
            $editEvents,
            $viewAttendance,
            $addResearchProjects,
            $editResearchProjects,
            $deleteResearchProjects,
            $viewSkillBreakdown,
            $editDowntimes,
            $accessExecutiveMenu,
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
            $addSkillSpecialty,
            $editSkillSpecialty,
            $deleteSkillSpecialty,
            $accessExecutiveMenu,
        ]);

        $secretary->syncPermissions([
            $editEvents,
            $viewAttendance,
            $recordAttendance,
            $accessExecutiveMenu,
        ]);

        $researcher->syncPermissions([
            $addResearchProjects,
        ]);

        $player->syncPermissions([
            $viewOwnCharacter,
            $createCharacter,
            $editOwnCharacter,
            $deleteOwnCharacter,
            $endOwnCharacter,
            $viewOwnLog,
            $createLog,
            $editOwnLog,
        ]);
    }
}
