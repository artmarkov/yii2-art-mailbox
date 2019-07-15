<?php

use artsoft\db\PermissionsMigration;

class m160418_220620_add_block_permissions extends PermissionsMigration
{

    public function beforeUp()
    {
        $this->addPermissionsGroup('blockManagement', 'Block Management');
    }

    public function afterDown()
    {
        $this->deletePermissionsGroup('blockManagement');
    }

    public function getPermissions()
    {
        return [
            'blockManagement' => [
                'links' => [
                    '/admin/block/*',
                    '/admin/block/default/*',
                ],
                'viewBlocks' => [
                    'title' => 'View Blocks',
                    'roles' => [self::ROLE_ADMIN],
                    'links' => [
                        '/admin/block/default/index',
                        '/admin/block/default/grid-sort',
                        '/admin/block/default/grid-page-size',
                    ],
                ],
                'editBlocks' => [
                    'title' => 'Edit Blocks',
                    'roles' => [self::ROLE_ADMIN],
                    'childs' => ['viewBlocks'],
                    'links' => [
                        '/admin/block/default/update',
                    ],
                ],
                'createBlocks' => [
                    'title' => 'Create Blocks',
                    'roles' => [self::ROLE_ADMIN],
                    'childs' => ['viewBlocks'],
                    'links' => [
                        '/admin/block/default/create',
                    ],
                ],
                'deleteBlocks' => [
                    'title' => 'Delete Blocks',
                    'roles' => [self::ROLE_ADMIN],
                    'childs' => ['viewBlocks'],
                    'links' => [
                        '/admin/block/default/delete',
                        '/admin/block/default/bulk-delete',
                    ],
                ],
                'fullBlockAccess' => [
                    'title' => 'Full Block Access',
                    'roles' => [self::ROLE_ADMIN],
                ],
            ],
        ];
    }

}
