<?php
use Phinx\Migration\AbstractMigration;

class CommunityRelease15 extends AbstractMigration
{

    public function up()
    {
        $this->table('awareness_program_missed_recurrences')
            ->dropForeignKey([], 'awareness_program_missed_recurrences_ibfk_2')
            ->dropForeignKey([], 'awareness_program_missed_recurrences_ibfk_3')
            ->update();
        $this->table('awareness_trainings')
            ->dropForeignKey([], 'awareness_trainings_ibfk_3')
            ->update();

        $table = $this->table('business_continuity_plans');
        $column = $table->hasColumn('task_owner_id');
        if ($column) {
            $this->table('business_continuity_plans')
                ->dropForeignKey('task_owner_id')
                ->removeIndexByName('task_owner_id')
                ->update();

            $this->table('business_continuity_plans')
                ->removeColumn('task_owner_id')
                ->update();
        }

        // $this->table('business_continuity_plan_audits')
        //     ->dropForeignKey([], 'business_continuity_plan_audits_ibfk_3')
        //     ->update();

        $this->table('compliance_audit_settings')
            ->dropForeignKey([], 'compliance_audit_settings_ibfk_3')
            ->removeIndexByName('workflow_owner_id')
            ->update();

        $this->table('compliance_audit_settings')
            ->removeColumn('auditee_notifications')
            ->removeColumn('auditee_emails')
            ->removeColumn('auditor_notifications')
            ->removeColumn('auditor_emails')
            ->removeColumn('workflow_status')
            ->removeColumn('workflow_owner_id')
            ->update();
        $this->table('compliance_findings')
            ->dropForeignKey([], 'compliance_findings_ibfk_4')
            ->removeIndexByName('workflow_owner_id')
            ->update();

        $this->table('compliance_findings')
            ->removeColumn('workflow_owner_id')
            ->removeColumn('workflow_status')
            ->changeColumn('deadline', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->update();

        $this->table('notification_system_items')
            ->changeColumn('type', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->update();

        $this->table('awareness_programs')
            ->changeColumn('questionnaire', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->update();

        $this->table('business_continuities')
            ->changeColumn('risk_score', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->changeColumn('residual_risk', 'float', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->update();

        $this->table('compliance_audit_settings_auditees')
            ->removeColumn('created')
            ->update();

        $this->table('cron')
            ->changeColumn('type', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => false,
            ])
            ->update();

        $this->table('ldap_connectors')
            ->removeColumn('ssl_enabled')
            ->removeColumn('ldap_groupmemberlist_name')
            ->update();

        $this->table('log_security_policies')
            ->changeColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->update();

        $this->table('notification_system_items_objects')
            ->changeColumn('foreign_key', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('risk_classifications')
            ->changeColumn('value', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->update();

        $this->table('risks')
            ->changeColumn('risk_score', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->changeColumn('residual_risk', 'float', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->update();

        $this->table('security_policies')
            ->changeColumn('short_description', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->update();

        $this->table('third_party_risks')
            ->changeColumn('risk_score', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->changeColumn('residual_risk', 'float', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->update();

        $this->table('advanced_filter_cron_result_items')
            ->addColumn('advanced_filter_cron_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'advanced_filter_cron_id',
                ]
            )
            ->create();

        $this->table('advanced_filter_crons')
            ->addColumn('advanced_filter_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('cron_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('type', 'integer', [
                'default' => null,
                'limit' => 4,
                'null' => true,
            ])
            ->addColumn('result', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('execution_time', 'float', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'advanced_filter_id',
                ]
            )
            ->addIndex(
                [
                    'cron_id',
                ]
            )
            ->create();

        $this->table('advanced_filter_user_settings')
            ->addColumn('advanced_filter_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('default_index', 'integer', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'advanced_filter_id',
                ]
            )
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('advanced_filter_values')
            ->addColumn('advanced_filter_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('field', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('value', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('many', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'advanced_filter_id',
                ]
            )
            ->create();

        $this->table('advanced_filters')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('private', 'integer', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('log_result_count', 'integer', [
                'default' => null,
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('log_result_data', 'integer', [
                'default' => null,
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('deleted', 'integer', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('deleted_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('assets_related')
            ->addColumn('asset_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('asset_related_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'asset_id',
                ]
            )
            ->addIndex(
                [
                    'asset_related_id',
                ]
            )
            ->create();

        $this->table('audit_deltas', ['id' => false, 'primary_key' => ['id']])
            ->addColumn('id', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('audit_id', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('property_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('old_value', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('new_value', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'audit_id',
                ]
            )
            ->create();

        $this->table('audits', ['id' => false, 'primary_key' => ['id']])
            ->addColumn('id', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('version', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('event', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('entity_id', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('request_id', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('json_object', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('source_id', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('restore_id', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'restore_id',
                ]
            )
            ->create();

        $this->table('awareness_program_compliant_users')
            ->addColumn('awareness_program_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('uid', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'awareness_program_id',
                ]
            )
            ->create();

        $this->table('awareness_program_not_compliant_users')
            ->addColumn('awareness_program_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('uid', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'awareness_program_id',
                ]
            )
            ->create();

        $this->table('awareness_programs_security_policies')
            ->addColumn('security_policy_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('awareness_program_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'awareness_program_id',
                ]
            )
            ->addIndex(
                [
                    'security_policy_id',
                ]
            )
            ->create();

        $this->table('backups')
            ->addColumn('sql_file', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('deleted_files', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('bulk_action_objects')
            ->addColumn('bulk_action_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('foreign_key', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'bulk_action_id',
                ]
            )
            ->create();

        $this->table('bulk_actions')
            ->addColumn('type', 'integer', [
                'default' => null,
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('json_data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('compliance_audit_provided_feedbacks')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('compliance_audit_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'compliance_audit_id',
                ]
            )
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('compliance_exceptions_compliance_findings')
            ->addColumn('compliance_exception_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('compliance_finding_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'compliance_exception_id',
                ]
            )
            ->addIndex(
                [
                    'compliance_finding_id',
                ]
            )
            ->create();

        $this->table('compliance_findings_third_party_risks')
            ->addColumn('compliance_finding_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('third_party_risk_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'compliance_finding_id',
                ]
            )
            ->addIndex(
                [
                    'third_party_risk_id',
                ]
            )
            ->create();

        $this->table('custom_field_options')
            ->addColumn('custom_field_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('value', 'string', [
                'default' => null,
                'limit' => 155,
                'null' => false,
            ])
            ->addIndex(
                [
                    'custom_field_id',
                ]
            )
            ->create();

        $this->table('custom_field_settings')
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 155,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'default' => '0',
                'limit' => 3,
                'null' => false,
            ])
            ->addIndex(
                [
                    'model',
                ]
            )
            ->create();

        $this->table('custom_field_values')
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('foreign_key', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('custom_field_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('value', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'custom_field_id',
                ]
            )
            ->addIndex(
                [
                    'model',
                ]
            )
            ->addIndex(
                [
                    'foreign_key',
                ]
            )
            ->create();

        $this->table('custom_fields')
            ->addColumn('custom_form_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('type', 'integer', [
                'default' => null,
                'limit' => 3,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'slug',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'custom_form_id',
                ]
            )
            ->create();

        $this->table('custom_forms')
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 155,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 155,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 155,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'slug',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'model',
                ]
            )
            ->create();

        $this->table('queue')
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => 16777215,
                'null' => true,
            ])
            ->addColumn('queue_id', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('status', 'integer', [
                'default' => '0',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('risk_calculation_values')
            ->addColumn('risk_calculation_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('field', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('value', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'risk_calculation_id',
                ]
            )
            ->create();

        $this->table('risk_calculations')
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('method', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('advanced_filter_cron_result_items')
            ->addForeignKey(
                'advanced_filter_cron_id',
                'advanced_filter_crons',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('advanced_filter_crons')
            ->addForeignKey(
                'advanced_filter_id',
                'advanced_filters',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'cron_id',
                'cron',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('advanced_filter_user_settings')
            ->addForeignKey(
                'advanced_filter_id',
                'advanced_filters',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('advanced_filter_values')
            ->addForeignKey(
                'advanced_filter_id',
                'advanced_filters',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('advanced_filters')
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('assets_related')
            ->addForeignKey(
                'asset_id',
                'assets',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'asset_related_id',
                'assets',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('audit_deltas')
            ->addForeignKey(
                'audit_id',
                'audits',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('audits')
            ->addForeignKey(
                'restore_id',
                'audits',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'SET_NULL'
                ]
            )
            ->update();

        $this->table('awareness_program_compliant_users')
            ->addForeignKey(
                'awareness_program_id',
                'awareness_programs',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('awareness_program_not_compliant_users')
            ->addForeignKey(
                'awareness_program_id',
                'awareness_programs',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('awareness_programs_security_policies')
            ->addForeignKey(
                'awareness_program_id',
                'awareness_programs',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'security_policy_id',
                'security_policies',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('bulk_action_objects')
            ->addForeignKey(
                'bulk_action_id',
                'bulk_actions',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('bulk_actions')
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'SET_NULL'
                ]
            )
            ->update();

        $this->table('compliance_audit_provided_feedbacks')
            ->addForeignKey(
                'compliance_audit_id',
                'compliance_audits',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('compliance_exceptions_compliance_findings')
            ->addForeignKey(
                'compliance_exception_id',
                'compliance_exceptions',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'compliance_finding_id',
                'compliance_findings',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('compliance_findings_third_party_risks')
            ->addForeignKey(
                'compliance_finding_id',
                'compliance_findings',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'third_party_risk_id',
                'third_party_risks',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('custom_field_options')
            ->addForeignKey(
                'custom_field_id',
                'custom_fields',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('custom_field_values')
            ->addForeignKey(
                'custom_field_id',
                'custom_fields',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('custom_fields')
            ->addForeignKey(
                'custom_form_id',
                'custom_forms',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('risk_calculation_values')
            ->addForeignKey(
                'risk_calculation_id',
                'risk_calculations',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('compliance_audit_settings')
            ->addColumn('deleted', 'integer', [
                'after' => 'modified',
                'default' => '0',
                'length' => 2,
                'null' => false,
            ])
            ->addColumn('deleted_date', 'datetime', [
                'after' => 'deleted',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->update();

        $this->table('compliance_audits')
            ->addColumn('third_party_contact_id', 'integer', [
                'after' => 'auditor_id',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->addColumn('auditee_title', 'string', [
                'after' => 'end_date',
                'default' => null,
                'length' => 155,
                'null' => false,
            ])
            ->addColumn('auditee_instructions', 'text', [
                'after' => 'auditee_title',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('use_default_template', 'integer', [
                'after' => 'auditee_instructions',
                'default' => '1',
                'length' => 1,
                'null' => false,
            ])
            ->addColumn('email_subject', 'string', [
                'after' => 'use_default_template',
                'default' => null,
                'length' => 255,
                'null' => false,
            ])
            ->addColumn('email_body', 'text', [
                'after' => 'email_subject',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('auditee_notifications', 'boolean', [
                'after' => 'email_body',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('auditee_emails', 'boolean', [
                'after' => 'auditee_notifications',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('auditor_notifications', 'boolean', [
                'after' => 'auditee_emails',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('auditor_emails', 'boolean', [
                'after' => 'auditor_notifications',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('show_analyze_title', 'boolean', [
                'after' => 'auditor_emails',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('show_analyze_description', 'boolean', [
                'after' => 'show_analyze_title',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('show_analyze_audit_criteria', 'boolean', [
                'after' => 'show_analyze_description',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('show_findings', 'boolean', [
                'after' => 'show_analyze_audit_criteria',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('status', 'string', [
                'after' => 'show_findings',
                'comment' => 'started or stopped',
                'default' => 'started',
                'length' => 50,
                'null' => false,
            ])
            ->addColumn('deleted', 'integer', [
                'after' => 'modified',
                'default' => '0',
                'length' => 2,
                'null' => false,
            ])
            ->addColumn('deleted_date', 'datetime', [
                'after' => 'deleted',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'third_party_contact_id',
                ],
                [
                    'name' => 'third_party_contact_id',
                ]
            )
            ->update();

        $this->table('compliance_findings')
            ->addColumn('deleted', 'integer', [
                'after' => 'modified',
                'default' => '0',
                'length' => 2,
                'null' => false,
            ])
            ->addColumn('deleted_date', 'datetime', [
                'after' => 'deleted',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->update();

        $this->table('notification_system_items')
            ->addColumn('automated', 'integer', [
                'after' => 'trigger_period',
                'default' => '0',
                'length' => 1,
                'null' => false,
            ])
            ->addColumn('email_customized', 'integer', [
                'after' => 'automated',
                'default' => '0',
                'length' => 1,
                'null' => false,
            ])
            ->addColumn('email_subject', 'string', [
                'after' => 'email_customized',
                'default' => null,
                'length' => 255,
                'null' => false,
            ])
            ->addColumn('email_body', 'text', [
                'after' => 'email_subject',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('report_send_empty_results', 'integer', [
                'after' => 'email_body',
                'default' => null,
                'length' => 2,
                'null' => true,
            ])
            ->addColumn('report_attachment_type', 'integer', [
                'after' => 'report_send_empty_results',
                'default' => null,
                'length' => 2,
                'null' => true,
            ])
            ->addColumn('advanced_filter_id', 'integer', [
                'after' => 'report_attachment_type',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->addIndex(
                [
                    'advanced_filter_id',
                ],
                [
                    'name' => 'advanced_filter_id',
                ]
            )
            ->update();

        $this->table('asset_classifications')
            ->addColumn('value', 'float', [
                'after' => 'criteria',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->update();

        $this->table('awareness_program_active_users')
            ->addColumn('email', 'string', [
                'after' => 'uid',
                'default' => null,
                'length' => 100,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'after' => 'email',
                'default' => null,
                'length' => 155,
                'null' => false,
            ])
            ->update();

        $this->table('awareness_programs')
            ->addColumn('text_file', 'string', [
                'after' => 'questionnaire',
                'default' => null,
                'length' => 255,
                'null' => true,
            ])
            ->addColumn('text_file_extension', 'string', [
                'after' => 'text_file',
                'default' => null,
                'length' => 50,
                'null' => true,
            ])
            ->addColumn('uploads_sort_json', 'text', [
                'after' => 'text_file_extension',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('email_reminder_custom', 'integer', [
                'after' => 'email_body',
                'default' => '0',
                'length' => 1,
                'null' => false,
            ])
            ->addColumn('email_reminder_subject', 'string', [
                'after' => 'email_reminder_custom',
                'default' => null,
                'length' => 150,
                'null' => false,
            ])
            ->addColumn('email_reminder_body', 'text', [
                'after' => 'email_reminder_subject',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('active_users', 'integer', [
                'after' => 'awareness_training_count',
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->addColumn('active_users_percentage', 'integer', [
                'after' => 'active_users',
                'default' => null,
                'length' => 3,
                'null' => false,
            ])
            ->addColumn('ignored_users', 'integer', [
                'after' => 'active_users_percentage',
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->addColumn('ignored_users_percentage', 'integer', [
                'after' => 'ignored_users',
                'default' => null,
                'length' => 3,
                'null' => true,
            ])
            ->addColumn('compliant_users', 'integer', [
                'after' => 'ignored_users_percentage',
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->addColumn('compliant_users_percentage', 'integer', [
                'after' => 'compliant_users',
                'default' => null,
                'length' => 3,
                'null' => false,
            ])
            ->addColumn('not_compliant_users', 'integer', [
                'after' => 'compliant_users_percentage',
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->addColumn('not_compliant_users_percentage', 'integer', [
                'after' => 'not_compliant_users',
                'default' => null,
                'length' => 3,
                'null' => false,
            ])
            ->addColumn('stats_update_status', 'integer', [
                'after' => 'not_compliant_users_percentage',
                'default' => '0',
                'length' => 2,
                'null' => false,
            ])
            ->update();

        $this->table('awareness_reminders')
            ->addColumn('email', 'string', [
                'after' => 'uid',
                'default' => null,
                'length' => 100,
                'null' => false,
            ])
            ->addColumn('reminder_type', 'integer', [
                'after' => 'demo',
                'default' => null,
                'length' => 2,
                'null' => false,
            ])
            ->update();

        $this->table('ldap_connectors')
            ->addColumn('ldap_group_account_attribute', 'string', [
                'after' => 'ldap_groupmemberlist_filter',
                'default' => null,
                'length' => 150,
                'null' => true,
            ])
            ->addColumn('ldap_group_fetch_email_type', 'string', [
                'after' => 'ldap_group_account_attribute',
                'default' => null,
                'length' => 150,
                'null' => true,
            ])
            ->addColumn('ldap_group_email_attribute', 'string', [
                'after' => 'ldap_group_fetch_email_type',
                'default' => null,
                'length' => 150,
                'null' => true,
            ])
            ->addColumn('ldap_group_mail_domain', 'string', [
                'after' => 'ldap_group_email_attribute',
                'default' => null,
                'length' => 150,
                'null' => true,
            ])
            ->update();

        $this->table('reviews')
            ->addColumn('version', 'string', [
                'after' => 'completed',
                'default' => null,
                'length' => 150,
                'null' => true,
            ])
            ->update();

        $this->table('risks')
            ->addColumn('risk_score_formula', 'text', [
                'after' => 'risk_score',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->update();

        $this->table('risks_security_policies')
            ->addColumn('type', 'string', [
                'after' => 'security_policy_id',
                'comment' => '\'treatment\',\'incident\'',
                'default' => 'treatment',
                'length' => 50,
                'null' => false,
            ])
            ->update();

        $this->table('security_policies')
            ->addColumn('url', 'text', [
                'after' => 'description',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->update();

        $this->table('third_party_risks')
            ->addColumn('risk_score_formula', 'text', [
                'after' => 'risk_score',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->update();

        $this->table('users')
            ->addColumn('api_allow', 'integer', [
                'after' => 'local_account',
                'default' => '0',
                'length' => 2,
                'null' => false,
            ])
            ->update();

        $this->table('awareness_program_missed_recurrences')
            ->addForeignKey(
                'awareness_program_recurrence_id',
                'awareness_program_recurrences',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'awareness_program_id',
                'awareness_programs',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('awareness_trainings')
            ->addForeignKey(
                'awareness_program_id',
                'awareness_programs',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        // $this->table('business_continuity_plan_audits')
        //     ->addForeignKey(
        //         'business_continuity_plan_id',
        //         'business_continuity_plans',
        //         'id',
        //         [
        //             'update' => 'CASCADE',
        //             'delete' => 'CASCADE'
        //         ]
        //     )
        //     ->update();

        $this->table('compliance_audits')
            ->addForeignKey(
                'third_party_contact_id',
                'users',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'SET_NULL'
                ]
            )
            ->update();

        $this->table('notification_system_items')
            ->addForeignKey(
                'advanced_filter_id',
                'advanced_filters',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        /*$q = "INSERT INTO `setting_groups` (`id`, `slug`, `parent_slug`, `name`, `icon_code`, `notes`, `url`, `hidden`, `order`) VALUES 
    (34,'UPDATES','SEC','Updates',NULL,NULL,'{\"controller\":\"updates\",\"action\":\"index\"}',0,0);";*/

        $q = "INSERT INTO `risk_calculations` (`id`, `model`, `method`, `modified`) VALUES 
    (1,'Risk','eramba','2016-11-18 14:38:23'),
    (2,'ThirdPartyRisk','eramba','2016-11-18 14:38:23'),
    (3,'BusinessContinuity','eramba','2016-11-18 14:38:23');";

        $q .= "INSERT INTO `custom_field_settings` (`id`, `model`, `status`) VALUES 
    (1,'SecurityService',0),
    (2,'SecurityServiceAudit',0),
    (3,'SecurityServiceMaintenance',0),
    (4,'BusinessUnit',0),
    (5,'Process',0),
    (6,'ThirdParty',0),
    (7,'Asset',0),
    (8,'Risk',0),
    (9,'ThirdPartyRisk',0),
    (10,'BusinessContinuity',0);";

        $q .= "INSERT INTO `setting_groups` (`slug`, `parent_slug`, `name`, `icon_code`, `notes`, `url`, `hidden`, `order`) VALUES 
    ('NOTIFICATION','ACCESSMGT','Notifications',NULL,NULL,'{\"controller\":\"notificationSystem\",\"action\":\"listItems\"}',0,0);";

        $q .= "INSERT INTO `setting_groups` (`slug`, `parent_slug`, `name`, `icon_code`, `notes`, `url`, `hidden`, `order`) VALUES 
    ('CRON',NULL,'Crontab History',NULL,NULL,'{\"controller\":\"cron\",\"action\":\"index\"}',0,0);";

        $q .= "INSERT INTO `setting_groups` (`slug`, `parent_slug`, `name`, `icon_code`, `notes`, `url`, `hidden`, `order`) VALUES 
    ('BACKUP','DB','Backup Configuration',NULL,NULL,NULL,0,2),
    ('QUEUE','MAIL','Emails In Queue',NULL,NULL,'{\"controller\":\"queue\", \"action\":\"index\", \"?\" :\"advanced_filter=1\"}',0,0);";

        $q .= "INSERT INTO `settings` (`active`, `name`, `variable`, `value`, `default_value`, `values`, `type`, `options`, `hidden`, `required`, `setting_group_slug`, `setting_type`, `order`, `modified`, `created`) VALUES 
    (1,'Backups Enabled','BACKUPS_ENABLED','0',NULL,NULL,'checkbox',NULL,0,0,'BACKUP','constant',0,'2017-02-22 21:32:29','2017-02-22 21:32:29'),
    (1,'Backup Day Period','BACKUP_DAY_PERIOD','1',NULL,NULL,'select','{\"1\":\"Every day\",\"2\":\"Every 2 days\",\"3\":\"Every 3 days\",\"4\":\"Every 4 days\",\"5\":\"Every 5 days\",\"6\":\"Every 6 days\",\"7\":\"Every 7 days\"}',0,0,'BACKUP','constant',0,'2017-02-22 21:32:29','2017-02-22 21:32:29'),
    (1,'Backup Files Limit','BACKUP_FILES_LIMIT','15',NULL,NULL,'select','{\"1\":\"1\",\"5\":\"5\",\"10\":\"10\",\"15\":\"15\"}',0,0,'BACKUP','constant',0,'2017-02-22 21:32:29','2017-02-22 21:32:29'),
    (1,'Name','EMAIL_NAME','',NULL,NULL,'text',NULL,0,0,'MAILCNF','constant',6,'2017-02-22 21:32:29','2017-02-22 21:32:29');";

        $this->query($q);
    }

    public function down()
    {
        $this->table('advanced_filter_cron_result_items')
            ->dropForeignKey(
                'advanced_filter_cron_id'
            );

        $this->table('advanced_filter_crons')
            ->dropForeignKey(
                'advanced_filter_id'
            )
            ->dropForeignKey(
                'cron_id'
            );

        $this->table('advanced_filter_user_settings')
            ->dropForeignKey(
                'advanced_filter_id'
            )
            ->dropForeignKey(
                'user_id'
            );

        $this->table('advanced_filter_values')
            ->dropForeignKey(
                'advanced_filter_id'
            );

        $this->table('advanced_filters')
            ->dropForeignKey(
                'user_id'
            );

        $this->table('assets_related')
            ->dropForeignKey(
                'asset_id'
            )
            ->dropForeignKey(
                'asset_related_id'
            );

        $this->table('audit_deltas')
            ->dropForeignKey(
                'audit_id'
            );

        $this->table('audits')
            ->dropForeignKey(
                'restore_id'
            );

        $this->table('awareness_program_compliant_users')
            ->dropForeignKey(
                'awareness_program_id'
            );

        $this->table('awareness_program_not_compliant_users')
            ->dropForeignKey(
                'awareness_program_id'
            );

        $this->table('awareness_programs_security_policies')
            ->dropForeignKey(
                'awareness_program_id'
            )
            ->dropForeignKey(
                'security_policy_id'
            );

        $this->table('bulk_action_objects')
            ->dropForeignKey(
                'bulk_action_id'
            );

        $this->table('bulk_actions')
            ->dropForeignKey(
                'user_id'
            );

        $this->table('compliance_audit_provided_feedbacks')
            ->dropForeignKey(
                'compliance_audit_id'
            )
            ->dropForeignKey(
                'user_id'
            );

        $this->table('compliance_exceptions_compliance_findings')
            ->dropForeignKey(
                'compliance_exception_id'
            )
            ->dropForeignKey(
                'compliance_finding_id'
            );

        $this->table('compliance_findings_third_party_risks')
            ->dropForeignKey(
                'compliance_finding_id'
            )
            ->dropForeignKey(
                'third_party_risk_id'
            );

        $this->table('custom_field_options')
            ->dropForeignKey(
                'custom_field_id'
            );

        $this->table('custom_field_values')
            ->dropForeignKey(
                'custom_field_id'
            );

        $this->table('custom_fields')
            ->dropForeignKey(
                'custom_form_id'
            );

        $this->table('risk_calculation_values')
            ->dropForeignKey(
                'risk_calculation_id'
            );

        $this->table('awareness_program_missed_recurrences')
            ->dropForeignKey(
                'awareness_program_recurrence_id'
            )
            ->dropForeignKey(
                'awareness_program_id'
            );

        $this->table('awareness_trainings')
            ->dropForeignKey(
                'awareness_program_id'
            );

        // $this->table('business_continuity_plan_audits')
        //     ->dropForeignKey(
        //         'business_continuity_plan_id'
        //     );

        $this->table('compliance_audits')
            ->dropForeignKey(
                'third_party_contact_id'
            );

        $this->table('notification_system_items')
            ->dropForeignKey(
                'advanced_filter_id'
            );

        // $this->table('business_continuity_plans')
        //     ->addColumn('task_owner_id', 'integer', [
        //         'after' => 'owner_id',
        //         'default' => null,
        //         'length' => 11,
        //         'null' => false,
        //     ])
        //     ->addIndex(
        //         [
        //             'task_owner_id',
        //         ],
        //         [
        //             'name' => 'task_owner_id',
        //         ]
        //     )
        //     ->update();

        $this->table('compliance_audit_settings')
            ->addColumn('auditee_notifications', 'integer', [
                'after' => 'status',
                'default' => '0',
                'length' => 1,
                'null' => false,
            ])
            ->addColumn('auditee_emails', 'integer', [
                'after' => 'auditee_notifications',
                'default' => '0',
                'length' => 1,
                'null' => false,
            ])
            ->addColumn('auditor_notifications', 'integer', [
                'after' => 'auditee_emails',
                'default' => '0',
                'length' => 1,
                'null' => false,
            ])
            ->addColumn('auditor_emails', 'integer', [
                'after' => 'auditor_notifications',
                'default' => '0',
                'length' => 1,
                'null' => false,
            ])
            ->addColumn('workflow_status', 'integer', [
                'after' => 'compliance_audit_feedback_profile_id',
                'default' => '0',
                'length' => 1,
                'null' => false,
            ])
            ->addColumn('workflow_owner_id', 'integer', [
                'after' => 'workflow_status',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->removeColumn('deleted')
            ->removeColumn('deleted_date')
            ->addIndex(
                [
                    'workflow_owner_id',
                ],
                [
                    'name' => 'workflow_owner_id',
                ]
            )
            ->update();

        $this->table('compliance_audits')
            ->removeIndexByName('third_party_contact_id')
            ->update();

        $this->table('compliance_audits')
            ->removeColumn('third_party_contact_id')
            ->removeColumn('auditee_title')
            ->removeColumn('auditee_instructions')
            ->removeColumn('use_default_template')
            ->removeColumn('email_subject')
            ->removeColumn('email_body')
            ->removeColumn('auditee_notifications')
            ->removeColumn('auditee_emails')
            ->removeColumn('auditor_notifications')
            ->removeColumn('auditor_emails')
            ->removeColumn('show_analyze_title')
            ->removeColumn('show_analyze_description')
            ->removeColumn('show_analyze_audit_criteria')
            ->removeColumn('show_findings')
            ->removeColumn('status')
            ->removeColumn('deleted')
            ->removeColumn('deleted_date')
            ->update();

        $this->table('compliance_findings')
            ->addColumn('workflow_owner_id', 'integer', [
                'after' => 'type',
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->addColumn('workflow_status', 'integer', [
                'after' => 'workflow_owner_id',
                'default' => '0',
                'length' => 1,
                'null' => false,
            ])
            ->changeColumn('deadline', 'date', [
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->removeColumn('deleted')
            ->removeColumn('deleted_date')
            ->addIndex(
                [
                    'workflow_owner_id',
                ],
                [
                    'name' => 'workflow_owner_id',
                ]
            )
            ->update();

        $this->table('notification_system_items')
            ->removeIndexByName('advanced_filter_id')
            ->update();

        $this->table('notification_system_items')
            ->changeColumn('type', 'string', [
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->removeColumn('automated')
            ->removeColumn('email_customized')
            ->removeColumn('email_subject')
            ->removeColumn('email_body')
            ->removeColumn('report_send_empty_results')
            ->removeColumn('report_attachment_type')
            ->removeColumn('advanced_filter_id')
            ->update();

        $this->table('asset_classifications')
            ->removeColumn('value')
            ->update();

        $this->table('awareness_program_active_users')
            ->removeColumn('email')
            ->removeColumn('name')
            ->update();

        $this->table('awareness_programs')
            ->changeColumn('questionnaire', 'string', [
                'default' => null,
                'length' => 255,
                'null' => false,
            ])
            ->removeColumn('text_file')
            ->removeColumn('text_file_extension')
            ->removeColumn('uploads_sort_json')
            ->removeColumn('email_reminder_custom')
            ->removeColumn('email_reminder_subject')
            ->removeColumn('email_reminder_body')
            ->removeColumn('active_users')
            ->removeColumn('active_users_percentage')
            ->removeColumn('ignored_users')
            ->removeColumn('ignored_users_percentage')
            ->removeColumn('compliant_users')
            ->removeColumn('compliant_users_percentage')
            ->removeColumn('not_compliant_users')
            ->removeColumn('not_compliant_users_percentage')
            ->removeColumn('stats_update_status')
            ->update();

        $this->table('awareness_reminders')
            ->removeColumn('email')
            ->removeColumn('reminder_type')
            ->update();

        $this->table('business_continuities')
            ->changeColumn('risk_score', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->changeColumn('residual_risk', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->update();

        $this->table('compliance_audit_settings_auditees')
            ->addColumn('created', 'datetime', [
                'after' => 'auditee_id',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->update();

        $this->table('cron')
            ->changeColumn('type', 'string', [
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->update();

        $this->table('ldap_connectors')
            ->addColumn('ssl_enabled', 'integer', [
                'after' => 'port',
                'default' => null,
                'length' => 1,
                'null' => false,
            ])
            ->addColumn('ldap_groupmemberlist_name', 'string', [
                'after' => 'ldap_groupmemberlist_filter',
                'default' => null,
                'length' => 150,
                'null' => true,
            ])
            ->removeColumn('ldap_group_account_attribute')
            ->removeColumn('ldap_group_fetch_email_type')
            ->removeColumn('ldap_group_email_attribute')
            ->removeColumn('ldap_group_mail_domain')
            ->update();

        $this->table('log_security_policies')
            ->changeColumn('description', 'text', [
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->update();

        $this->table('notification_system_items_objects')
            ->changeColumn('foreign_key', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->update();

        $this->table('reviews')
            ->removeColumn('version')
            ->update();

        $this->table('risk_classifications')
            ->changeColumn('value', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => true,
            ])
            ->update();

        $this->table('risks')
            ->changeColumn('risk_score', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->changeColumn('residual_risk', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->removeColumn('risk_score_formula')
            ->update();

        $this->table('risks_security_policies')
            ->removeColumn('type')
            ->update();

        $this->table('security_policies')
            ->changeColumn('short_description', 'string', [
                'default' => null,
                'length' => 150,
                'null' => false,
            ])
            ->removeColumn('url')
            ->update();

        $this->table('third_party_risks')
            ->changeColumn('risk_score', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->changeColumn('residual_risk', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->removeColumn('risk_score_formula')
            ->update();

        $this->table('users')
            ->removeColumn('api_allow')
            ->update();

        $this->table('awareness_program_missed_recurrences')
            ->addForeignKey(
                'awareness_program_recurrence_id',
                'awareness_program_recurrences',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'SET_NULL'
                ]
            )
            ->addForeignKey(
                'awareness_program_id',
                'awareness_programs',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'SET_NULL'
                ]
            )
            ->update();

        $this->table('awareness_trainings')
            ->addForeignKey(
                'awareness_program_id',
                'awareness_programs',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'SET_NULL'
                ]
            )
            ->update();

        // $this->table('business_continuity_plan_audits')
        //     ->addForeignKey(
        //         'business_continuity_plan_id',
        //         'business_continuity_plans',
        //         'id',
        //         [
        //             'update' => 'CASCADE',
        //             'delete' => 'CASCADE'
        //         ]
        //     )
        //     ->update();

        // $this->table('business_continuity_plans')
        //     ->addForeignKey(
        //         'task_owner_id',
        //         'users',
        //         'id',
        //         [
        //             'update' => 'CASCADE',
        //             'delete' => 'CASCADE'
        //         ]
        //     )
        //     ->update();

        $this->table('compliance_audit_settings')
            ->addForeignKey(
                'workflow_owner_id',
                'users',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('compliance_findings')
            ->addForeignKey(
                'workflow_owner_id',
                'users',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->dropTable('advanced_filter_cron_result_items');

        $this->dropTable('advanced_filter_crons');

        $this->dropTable('advanced_filter_user_settings');

        $this->dropTable('advanced_filter_values');

        $this->dropTable('advanced_filters');

        $this->dropTable('assets_related');

        $this->dropTable('audit_deltas');

        $this->dropTable('audits');

        $this->dropTable('awareness_program_compliant_users');

        $this->dropTable('awareness_program_not_compliant_users');

        $this->dropTable('awareness_programs_security_policies');

        $this->dropTable('backups');

        $this->dropTable('bulk_action_objects');

        $this->dropTable('bulk_actions');

        $this->dropTable('compliance_audit_provided_feedbacks');

        $this->dropTable('compliance_exceptions_compliance_findings');

        $this->dropTable('compliance_findings_third_party_risks');

        $this->dropTable('custom_field_options');

        $this->dropTable('custom_field_settings');

        $this->dropTable('custom_field_values');

        $this->dropTable('custom_fields');

        $this->dropTable('custom_forms');

        $this->dropTable('queue');

        $this->dropTable('risk_calculation_values');

        $this->dropTable('risk_calculations');
    }
}

