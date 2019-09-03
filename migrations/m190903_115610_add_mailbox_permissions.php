<?php

use artsoft\db\PermissionsMigration;

class m190903_115610_add_mailbox_permissions extends PermissionsMigration
{

    public function beforeUp()
    {
        $this->addPermissionsGroup('mailboxManagement', 'Mailbox Management');
    }

    public function afterDown()
    {
        $this->deletePermissionsGroup('mailboxManagement');
    }

    public function getPermissions()
    {
        return [
            'mailboxManagement' => [
                'links' => [
                    '/admin/mailbox/default/*',
                ],
                'viewMail' => [
                    'title' => 'View Mail',
                    'roles' => [self::ROLE_USER],
                    'links' => [
                        '/admin/mailbox/default/index',
                        '/admin/mailbox/default/index-sent',
                        '/admin/mailbox/default/index-draft',
                        '/admin/mailbox/default/index-trash',
                        '/admin/mailbox/default/view-inbox',
                        '/admin/mailbox/default/view-sent',
                        '/admin/mailbox/default/grid-page-size',
                    ],
                ],
                'composeMail' => [
                    'title' => 'Compose Mail',
                    'roles' => [self::ROLE_USER],
                    'childs' => ['viewMail'],
                    'links' => [
                        '/admin/mailbox/default/compose',
                        '/admin/mailbox/default/update',
                        '/admin/mailbox/default/delete',
                        '/admin/mailbox/default/reply',
                        '/admin/mailbox/default/forward',
                        '/admin/mailbox/default/trash',
                        '/admin/mailbox/default/trash-sent',
                        '/admin/mailbox/default/restore',
                        '/admin/mailbox/default/bulk-mark-read',
                        '/admin/mailbox/default/bulk-mark-unread',
                        '/admin/mailbox/default/bulk-trash',
                        '/admin/mailbox/default/bulk-trash-sent',
                        '/admin/mailbox/default/bulk-delete',
                        '/admin/mailbox/default/bulk-restore',
                        '/admin/mailbox/default/clian-own',
                    ],
                ],
                'cliarTrashMail' => [
                    'title' => 'Cliar Trash Mail',
                    'roles' => [self::ROLE_MODERATOR],
                    'childs' => ['viewMail', 'composeMail'],
                    'links' => [
                        '/admin/mailbox/default/clian',
                    ],
                ],                
            ],
        ];
    }

}
