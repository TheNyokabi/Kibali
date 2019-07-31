<?php
use Phinx\Seed\AbstractSeed;

/**
 * SettingGroup seed.
 */
class SettingGroupSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'slug' => 'ACCESSLST',
                'parent_slug' => 'ACCESSMGT',
                'name' => 'Access Lists',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"admin", "action":"acl", "0" :"aros", "1":"ajax_role_permissions"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '2',
                'slug' => 'ACCESSMGT',
                'parent_slug' => NULL,
                'name' => 'Access Management',
                'icon_code' => 'icon-cog',
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '3',
                'slug' => 'AUTH',
                'parent_slug' => 'ACCESSMGT',
                'name' => 'Authentication ',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"ldapConnectors","action":"authentication"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '4',
                'slug' => 'BANNER',
                'parent_slug' => 'SEC',
                'name' => 'Banners',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '1',
                'order' => '0',
            ],
            [
                'id' => '5',
                'slug' => 'BAR',
                'parent_slug' => 'DB',
                'name' => 'Backup & Restore',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"backupRestore","action":"index", "plugin":"backupRestore"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '6',
                'slug' => 'BFP',
                'parent_slug' => 'SEC',
                'name' => 'Brute Force Protection',
                'icon_code' => NULL,
                'notes' => 'This setting allows you to protect the login page of eramba from being brute-force attacked.',
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '7',
                'slug' => 'CUE',
                'parent_slug' => 'LOC',
                'name' => 'Currency',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '8',
                'slug' => 'DASH',
                'parent_slug' => NULL,
                'name' => 'Dashboard',
                'icon_code' => 'icon-cog',
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '9',
                'slug' => 'DASHRESET',
                'parent_slug' => 'DASH',
                'name' => 'Reset Dashboards',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"settings","action":"resetDashboards"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '10',
                'slug' => 'DB',
                'parent_slug' => NULL,
                'name' => 'Database',
                'icon_code' => 'icon-cog',
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '11',
                'slug' => 'DBCNF',
                'parent_slug' => 'DB',
                'name' => 'Database Configurations',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '1',
                'order' => '0',
            ],
            [
                'id' => '12',
                'slug' => 'DBRESET',
                'parent_slug' => 'DB',
                'name' => 'Reset Database',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"settings","action":"resetDatabase"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '13',
                'slug' => 'DEBUG',
                'parent_slug' => NULL,
                'name' => 'Debug Settings and Logs',
                'icon_code' => 'icon-cog',
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '14',
                'slug' => 'DEBUGCFG',
                'parent_slug' => 'DEBUG',
                'name' => 'Debug Config',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '15',
                'slug' => 'ERRORLOG',
                'parent_slug' => 'DEBUG',
                'name' => 'Error Log',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"settings","action":"logs", "0":"error"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '16',
                'slug' => 'GROUP',
                'parent_slug' => 'ACCESSMGT',
                'name' => 'Groups ',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"groups","action":"index"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '17',
                'slug' => 'LDAP',
                'parent_slug' => 'ACCESSMGT',
                'name' => 'LDAP Connectors',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"ldapConnectors","action":"index"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '18',
                'slug' => 'LOC',
                'parent_slug' => NULL,
                'name' => 'Localization',
                'icon_code' => 'icon-cog',
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '19',
                'slug' => 'MAIL',
                'parent_slug' => NULL,
                'name' => 'Mail',
                'icon_code' => 'icon-cog',
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '20',
                'slug' => 'MAILCNF',
                'parent_slug' => 'MAIL',
                'name' => 'Mail Configurations',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '21',
                'slug' => 'MAILLOG',
                'parent_slug' => 'DEBUG',
                'name' => 'Email Log',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"settings","action":"logs", "0":"email"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '22',
                'slug' => 'PRELOAD',
                'parent_slug' => 'DB',
                'name' => 'Pre-load the database with default databases',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '1',
                'order' => '0',
            ],
            [
                'id' => '23',
                'slug' => 'RISK',
                'parent_slug' => NULL,
                'name' => 'Risk',
                'icon_code' => 'icon-cog',
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '1',
                'order' => '0',
            ],
            [
                'id' => '24',
                'slug' => 'RISKAPPETITE',
                'parent_slug' => 'RISK',
                'name' => 'Risk appetite',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '25',
                'slug' => 'ROLES',
                'parent_slug' => 'ACCESSMGT',
                'name' => 'Roles',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"scopes","action":"index"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '26',
                'slug' => 'SEC',
                'parent_slug' => NULL,
                'name' => 'Security',
                'icon_code' => 'icon-cog',
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '27',
                'slug' => 'SECKEY',
                'parent_slug' => 'SEC',
                'name' => 'Security Key',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '28',
                'slug' => 'USER',
                'parent_slug' => 'ACCESSMGT',
                'name' => 'User Management',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"users","action":"index"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '29',
                'slug' => 'CLRCACHE',
                'parent_slug' => 'DEBUG',
                'name' => 'Clear Cache',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"settings","action":"deleteCache"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '30',
                'slug' => 'CLRACLCACHE',
                'parent_slug' => 'DEBUG',
                'name' => 'Clear ACL Cache',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"settings","action":"deleteCache", "0":"acl"}',
                'hidden' => '1',
                'order' => '0',
            ],
            [
                'id' => '31',
                'slug' => 'LOGO',
                'parent_slug' => 'LOC',
                'name' => 'Custom Logo',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"settings","action":"customLogo"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '32',
                'slug' => 'HEALTH',
                'parent_slug' => 'SEC',
                'name' => 'System Health',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"settings","action":"systemHealth"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '33',
                'slug' => 'TZONE',
                'parent_slug' => 'LOC',
                'name' => 'Timezone',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '34',
                'slug' => 'UPDATES',
                'parent_slug' => 'SEC',
                'name' => 'Updates',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"updates","action":"index"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '35',
                'slug' => 'NOTIFICATION',
                'parent_slug' => 'ACCESSMGT',
                'name' => 'Notifications',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"notificationSystem","action":"listItems"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '36',
                'slug' => 'CRON',
                'parent_slug' => 'ACCESSMGT',
                'name' => 'Cron Jobs',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"cron","action":"index"}',
                'hidden' => '0',
                'order' => '0',
            ],
            [
                'id' => '37',
                'slug' => 'BACKUP',
                'parent_slug' => 'DB',
                'name' => 'Backup Configuration',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => NULL,
                'hidden' => '0',
                'order' => '2',
            ],
            [
                'id' => '38',
                'slug' => 'QUEUE',
                'parent_slug' => 'MAIL',
                'name' => 'Emails In Queue',
                'icon_code' => NULL,
                'notes' => NULL,
                'url' => '{"controller":"queue", "action":"index", "?" :"advanced_filter=1"}',
                'hidden' => '0',
                'order' => '0',
            ],
        ];

        $table = $this->table('setting_groups');
        $table->insert($data)->save();
    }
}
