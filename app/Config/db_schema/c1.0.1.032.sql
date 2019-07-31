#
# SQL Export
# Created by Querious (201026)
# Created: 20 March 2018 at 15:16:11 CET
# Encoding: Unicode (UTF-8)
#


SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


DROP TABLE IF EXISTS `workflows_validators`;
DROP TABLE IF EXISTS `workflows_validator_scopes`;
DROP TABLE IF EXISTS `workflows_custom_validators`;
DROP TABLE IF EXISTS `workflows_custom_approvers`;
DROP TABLE IF EXISTS `workflows_approvers`;
DROP TABLE IF EXISTS `workflows_approver_scopes`;
DROP TABLE IF EXISTS `workflows_all_validator_items`;
DROP TABLE IF EXISTS `workflows_all_approver_items`;
DROP TABLE IF EXISTS `workflows`;
DROP TABLE IF EXISTS `workflow_logs`;
DROP TABLE IF EXISTS `workflow_items`;
DROP TABLE IF EXISTS `workflow_acknowledgements`;
DROP TABLE IF EXISTS `wf_stage_step_conditions`;
DROP TABLE IF EXISTS `wf_settings`;
DROP TABLE IF EXISTS `wf_instance_requests`;
DROP TABLE IF EXISTS `wf_stage_steps`;
DROP TABLE IF EXISTS `wf_instance_logs`;
DROP TABLE IF EXISTS `wf_instances`;
DROP TABLE IF EXISTS `wf_instance_approvals`;
DROP TABLE IF EXISTS `wf_stages`;
DROP TABLE IF EXISTS `wf_accesses`;
DROP TABLE IF EXISTS `wf_access_types`;
DROP TABLE IF EXISTS `wf_access_models`;
DROP TABLE IF EXISTS `vulnerabilities`;
DROP TABLE IF EXISTS `visualisation_share_users`;
DROP TABLE IF EXISTS `visualisation_share_groups`;
DROP TABLE IF EXISTS `visualisation_share`;
DROP TABLE IF EXISTS `visualisation_settings_users`;
DROP TABLE IF EXISTS `visualisation_settings_groups`;
DROP TABLE IF EXISTS `visualisation_settings`;
DROP TABLE IF EXISTS `vendor_assessments`;
DROP TABLE IF EXISTS `vendor_assessment_questions`;
DROP TABLE IF EXISTS `vendor_assessment_questionnaires`;
DROP TABLE IF EXISTS `vendor_assessment_options`;
DROP TABLE IF EXISTS `vendor_assessment_findings_questions`;
DROP TABLE IF EXISTS `vendor_assessment_findings`;
DROP TABLE IF EXISTS `vendor_assessment_files`;
DROP TABLE IF EXISTS `vendor_assessment_feedbacks`;
DROP TABLE IF EXISTS `users_vendor_assessments`;
DROP TABLE IF EXISTS `users_vendor_assessment_findings`;
DROP TABLE IF EXISTS `users_groups`;
DROP TABLE IF EXISTS `user_fields_users`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_fields_groups`;
DROP TABLE IF EXISTS `user_bans`;
DROP TABLE IF EXISTS `tickets`;
DROP TABLE IF EXISTS `threats`;
DROP TABLE IF EXISTS `third_party_types`;
DROP TABLE IF EXISTS `third_party_risks_vulnerabilities`;
DROP TABLE IF EXISTS `third_party_risks_threats`;
DROP TABLE IF EXISTS `third_party_risks`;
DROP TABLE IF EXISTS `third_party_risk_overtime_graphs`;
DROP TABLE IF EXISTS `third_party_overtime_graphs`;
DROP TABLE IF EXISTS `third_party_incident_overtime_graphs`;
DROP TABLE IF EXISTS `third_party_audit_overtime_graphs`;
DROP TABLE IF EXISTS `third_parties_vendor_assessments`;
DROP TABLE IF EXISTS `third_parties_third_party_risks`;
DROP TABLE IF EXISTS `third_parties`;
DROP TABLE IF EXISTS `team_roles`;
DROP TABLE IF EXISTS `tags`;
DROP TABLE IF EXISTS `system_records`;
DROP TABLE IF EXISTS `system_logs`;
DROP TABLE IF EXISTS `suggestions`;
DROP TABLE IF EXISTS `status_triggers`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `setting_groups`;
DROP TABLE IF EXISTS `service_contracts`;
DROP TABLE IF EXISTS `service_classifications`;
DROP TABLE IF EXISTS `security_services_third_party_risks`;
DROP TABLE IF EXISTS `security_services_service_contracts`;
DROP TABLE IF EXISTS `security_service_types`;
DROP TABLE IF EXISTS `security_service_maintenances`;
DROP TABLE IF EXISTS `security_service_maintenance_dates`;
DROP TABLE IF EXISTS `security_service_classifications`;
DROP TABLE IF EXISTS `security_service_audit_improvements`;
DROP TABLE IF EXISTS `security_service_audits`;
DROP TABLE IF EXISTS `security_service_audit_dates`;
DROP TABLE IF EXISTS `security_policy_reviews`;
DROP TABLE IF EXISTS `security_policy_ldap_groups`;
DROP TABLE IF EXISTS `security_policy_document_types`;
DROP TABLE IF EXISTS `security_policies_security_services`;
DROP TABLE IF EXISTS `security_policies_related`;
DROP TABLE IF EXISTS `security_policies`;
DROP TABLE IF EXISTS `security_incidents_third_parties`;
DROP TABLE IF EXISTS `security_incidents_security_services`;
DROP TABLE IF EXISTS `security_incidents_security_service_audit_improvements`;
DROP TABLE IF EXISTS `security_incidents`;
DROP TABLE IF EXISTS `security_incident_statuses`;
DROP TABLE IF EXISTS `security_incident_stages_security_incidents`;
DROP TABLE IF EXISTS `security_incident_stages`;
DROP TABLE IF EXISTS `security_incident_classifications`;
DROP TABLE IF EXISTS `sections`;
DROP TABLE IF EXISTS `scopes`;
DROP TABLE IF EXISTS `schema_migrations`;
DROP TABLE IF EXISTS `risks_vulnerabilities`;
DROP TABLE IF EXISTS `risks_threats`;
DROP TABLE IF EXISTS `risks_security_services`;
DROP TABLE IF EXISTS `risks_security_policies`;
DROP TABLE IF EXISTS `risks_security_incidents`;
DROP TABLE IF EXISTS `risk_overtime_graphs`;
DROP TABLE IF EXISTS `risk_mitigation_strategies`;
DROP TABLE IF EXISTS `risk_exceptions_third_party_risks`;
DROP TABLE IF EXISTS `risk_exceptions_risks`;
DROP TABLE IF EXISTS `risks`;
DROP TABLE IF EXISTS `risk_exceptions`;
DROP TABLE IF EXISTS `risk_classifications_third_party_risks`;
DROP TABLE IF EXISTS `risk_classifications_risks`;
DROP TABLE IF EXISTS `risk_classifications`;
DROP TABLE IF EXISTS `risk_classification_types`;
DROP TABLE IF EXISTS `risk_calculation_values`;
DROP TABLE IF EXISTS `risk_calculations`;
DROP TABLE IF EXISTS `risk_appetites_risk_classification_types`;
DROP TABLE IF EXISTS `risk_appetites`;
DROP TABLE IF EXISTS `risk_appetite_thresholds_risks`;
DROP TABLE IF EXISTS `risk_appetite_threshold_risk_classifications`;
DROP TABLE IF EXISTS `risk_appetite_thresholds`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `queue`;
DROP TABLE IF EXISTS `projects_third_party_risks`;
DROP TABLE IF EXISTS `projects_security_services`;
DROP TABLE IF EXISTS `security_services`;
DROP TABLE IF EXISTS `projects_security_service_audit_improvements`;
DROP TABLE IF EXISTS `projects_security_policies`;
DROP TABLE IF EXISTS `projects_risks`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `project_statuses`;
DROP TABLE IF EXISTS `project_overtime_graphs`;
DROP TABLE IF EXISTS `project_expenses`;
DROP TABLE IF EXISTS `project_achievements`;
DROP TABLE IF EXISTS `program_scopes`;
DROP TABLE IF EXISTS `program_issue_types`;
DROP TABLE IF EXISTS `program_issues`;
DROP TABLE IF EXISTS `processes`;
DROP TABLE IF EXISTS `policy_users`;
DROP TABLE IF EXISTS `policy_exceptions_third_parties`;
DROP TABLE IF EXISTS `policy_exceptions_security_policies`;
DROP TABLE IF EXISTS `policy_exception_classifications`;
DROP TABLE IF EXISTS `policy_exceptions`;
DROP TABLE IF EXISTS `phinxlog`;
DROP TABLE IF EXISTS `object_status_object_statuses`;
DROP TABLE IF EXISTS `object_status_statuses`;
DROP TABLE IF EXISTS `oauth_connectors`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `notification_system_items_users`;
DROP TABLE IF EXISTS `notification_system_items_scopes`;
DROP TABLE IF EXISTS `notification_system_items_objects`;
DROP TABLE IF EXISTS `notification_system_items`;
DROP TABLE IF EXISTS `notification_system_item_feedbacks`;
DROP TABLE IF EXISTS `notification_system_item_logs`;
DROP TABLE IF EXISTS `notification_system_item_emails`;
DROP TABLE IF EXISTS `notification_system_item_custom_users`;
DROP TABLE IF EXISTS `notification_system_item_custom_roles`;
DROP TABLE IF EXISTS `log_security_policies`;
DROP TABLE IF EXISTS `legals_third_parties`;
DROP TABLE IF EXISTS `legals`;
DROP TABLE IF EXISTS `ldap_connector_authentication`;
DROP TABLE IF EXISTS `ldap_connectors`;
DROP TABLE IF EXISTS `issues`;
DROP TABLE IF EXISTS `groups`;
DROP TABLE IF EXISTS `goals_third_party_risks`;
DROP TABLE IF EXISTS `goals_security_services`;
DROP TABLE IF EXISTS `goals_security_policies`;
DROP TABLE IF EXISTS `goals_risks`;
DROP TABLE IF EXISTS `goals_projects`;
DROP TABLE IF EXISTS `goals_program_issues`;
DROP TABLE IF EXISTS `goal_audits`;
DROP TABLE IF EXISTS `goals`;
DROP TABLE IF EXISTS `goal_audit_improvements_security_incidents`;
DROP TABLE IF EXISTS `goal_audit_improvements_projects`;
DROP TABLE IF EXISTS `goal_audit_improvements`;
DROP TABLE IF EXISTS `goal_audit_dates`;
DROP TABLE IF EXISTS `data_assets_third_parties`;
DROP TABLE IF EXISTS `data_assets_security_services`;
DROP TABLE IF EXISTS `data_assets_security_policies`;
DROP TABLE IF EXISTS `data_assets_risks`;
DROP TABLE IF EXISTS `data_assets_projects`;
DROP TABLE IF EXISTS `data_assets`;
DROP TABLE IF EXISTS `data_asset_statuses`;
DROP TABLE IF EXISTS `data_asset_settings_users`;
DROP TABLE IF EXISTS `data_asset_settings_third_parties`;
DROP TABLE IF EXISTS `data_asset_settings`;
DROP TABLE IF EXISTS `data_asset_instances`;
DROP TABLE IF EXISTS `data_asset_gdpr_third_party_countries`;
DROP TABLE IF EXISTS `data_asset_gdpr_lawful_bases`;
DROP TABLE IF EXISTS `data_asset_gdpr_data_types`;
DROP TABLE IF EXISTS `data_asset_gdpr_collection_methods`;
DROP TABLE IF EXISTS `data_asset_gdpr_archiving_drivers`;
DROP TABLE IF EXISTS `data_asset_gdpr`;
DROP TABLE IF EXISTS `dashboard_kpi_value_logs`;
DROP TABLE IF EXISTS `dashboard_kpi_values`;
DROP TABLE IF EXISTS `dashboard_kpi_logs`;
DROP TABLE IF EXISTS `dashboard_kpi_attributes`;
DROP TABLE IF EXISTS `dashboard_kpis`;
DROP TABLE IF EXISTS `custom_validator_fields`;
DROP TABLE IF EXISTS `custom_roles_users`;
DROP TABLE IF EXISTS `custom_roles_role_users`;
DROP TABLE IF EXISTS `custom_roles_role_groups`;
DROP TABLE IF EXISTS `custom_roles_roles`;
DROP TABLE IF EXISTS `custom_roles_groups`;
DROP TABLE IF EXISTS `custom_field_values`;
DROP TABLE IF EXISTS `custom_fields`;
DROP TABLE IF EXISTS `custom_forms`;
DROP TABLE IF EXISTS `custom_field_settings`;
DROP TABLE IF EXISTS `custom_field_options`;
DROP TABLE IF EXISTS `cron`;
DROP TABLE IF EXISTS `countries`;
DROP TABLE IF EXISTS `compliance_treatment_strategies`;
DROP TABLE IF EXISTS `compliance_statuses`;
DROP TABLE IF EXISTS `compliance_package_items`;
DROP TABLE IF EXISTS `compliance_packages`;
DROP TABLE IF EXISTS `compliance_managements_third_party_risks`;
DROP TABLE IF EXISTS `compliance_managements_security_services`;
DROP TABLE IF EXISTS `compliance_managements_security_policies`;
DROP TABLE IF EXISTS `compliance_managements_risks`;
DROP TABLE IF EXISTS `compliance_managements_projects`;
DROP TABLE IF EXISTS `compliance_findings_third_party_risks`;
DROP TABLE IF EXISTS `compliance_findings`;
DROP TABLE IF EXISTS `compliance_finding_statuses`;
DROP TABLE IF EXISTS `compliance_finding_classifications`;
DROP TABLE IF EXISTS `compliance_exceptions_compliance_managements`;
DROP TABLE IF EXISTS `compliance_managements`;
DROP TABLE IF EXISTS `compliance_exceptions_compliance_findings`;
DROP TABLE IF EXISTS `compliance_exceptions`;
DROP TABLE IF EXISTS `compliance_audit_settings_auditees`;
DROP TABLE IF EXISTS `compliance_audit_setting_notifications`;
DROP TABLE IF EXISTS `compliance_audit_settings`;
DROP TABLE IF EXISTS `compliance_audit_provided_feedbacks`;
DROP TABLE IF EXISTS `compliance_audit_overtime_graphs`;
DROP TABLE IF EXISTS `compliance_audit_feedbacks_compliance_audits`;
DROP TABLE IF EXISTS `compliance_audits`;
DROP TABLE IF EXISTS `compliance_audit_auditee_feedbacks`;
DROP TABLE IF EXISTS `compliance_audit_feedbacks`;
DROP TABLE IF EXISTS `compliance_audit_feedback_profiles`;
DROP TABLE IF EXISTS `compliance_analysis_findings_third_parties`;
DROP TABLE IF EXISTS `compliance_analysis_findings_compliance_package_items`;
DROP TABLE IF EXISTS `compliance_analysis_findings_compliance_managements`;
DROP TABLE IF EXISTS `compliance_analysis_findings`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `cake_sessions`;
DROP TABLE IF EXISTS `business_units_legals`;
DROP TABLE IF EXISTS `business_units_data_assets`;
DROP TABLE IF EXISTS `business_units`;
DROP TABLE IF EXISTS `business_continuity_task_reminders`;
DROP TABLE IF EXISTS `business_continuity_tasks`;
DROP TABLE IF EXISTS `business_continuity_plan_audits`;
DROP TABLE IF EXISTS `business_continuity_plans`;
DROP TABLE IF EXISTS `business_continuity_plan_audit_improvements_security_incidents`;
DROP TABLE IF EXISTS `business_continuity_plan_audit_improvements_projects`;
DROP TABLE IF EXISTS `business_continuity_plan_audit_improvements`;
DROP TABLE IF EXISTS `business_continuity_plan_audit_dates`;
DROP TABLE IF EXISTS `business_continuities_vulnerabilities`;
DROP TABLE IF EXISTS `business_continuities_threats`;
DROP TABLE IF EXISTS `business_continuities_security_services`;
DROP TABLE IF EXISTS `business_continuities_risk_exceptions`;
DROP TABLE IF EXISTS `business_continuities_risk_classifications`;
DROP TABLE IF EXISTS `business_continuities_projects`;
DROP TABLE IF EXISTS `business_continuities_processes`;
DROP TABLE IF EXISTS `business_continuities_goals`;
DROP TABLE IF EXISTS `business_continuities_compliance_managements`;
DROP TABLE IF EXISTS `business_continuities_business_units`;
DROP TABLE IF EXISTS `business_continuities_business_continuity_plans`;
DROP TABLE IF EXISTS `business_continuities`;
DROP TABLE IF EXISTS `bulk_action_objects`;
DROP TABLE IF EXISTS `bulk_actions`;
DROP TABLE IF EXISTS `backups`;
DROP TABLE IF EXISTS `awareness_trainings`;
DROP TABLE IF EXISTS `awareness_users`;
DROP TABLE IF EXISTS `awareness_reminders`;
DROP TABLE IF EXISTS `awareness_programs_security_policies`;
DROP TABLE IF EXISTS `awareness_program_not_compliant_users`;
DROP TABLE IF EXISTS `awareness_program_missed_recurrences`;
DROP TABLE IF EXISTS `awareness_program_recurrences`;
DROP TABLE IF EXISTS `awareness_program_ldap_groups`;
DROP TABLE IF EXISTS `awareness_program_ignored_users`;
DROP TABLE IF EXISTS `awareness_program_demos`;
DROP TABLE IF EXISTS `awareness_program_compliant_users`;
DROP TABLE IF EXISTS `awareness_program_active_users`;
DROP TABLE IF EXISTS `awareness_overtime_graphs`;
DROP TABLE IF EXISTS `awareness_programs`;
DROP TABLE IF EXISTS `audit_deltas`;
DROP TABLE IF EXISTS `audits`;
DROP TABLE IF EXISTS `attachments`;
DROP TABLE IF EXISTS `assets_third_party_risks`;
DROP TABLE IF EXISTS `assets_security_incidents`;
DROP TABLE IF EXISTS `assets_risks`;
DROP TABLE IF EXISTS `assets_related`;
DROP TABLE IF EXISTS `assets_policy_exceptions`;
DROP TABLE IF EXISTS `assets_legals`;
DROP TABLE IF EXISTS `assets_compliance_managements`;
DROP TABLE IF EXISTS `assets_business_units`;
DROP TABLE IF EXISTS `assets`;
DROP TABLE IF EXISTS `asset_media_types_vulnerabilities`;
DROP TABLE IF EXISTS `asset_media_types_threats`;
DROP TABLE IF EXISTS `asset_media_types`;
DROP TABLE IF EXISTS `asset_labels`;
DROP TABLE IF EXISTS `asset_classifications_assets`;
DROP TABLE IF EXISTS `asset_classifications`;
DROP TABLE IF EXISTS `asset_classification_types`;
DROP TABLE IF EXISTS `aros_acos`;
DROP TABLE IF EXISTS `aros`;
DROP TABLE IF EXISTS `advanced_filter_values`;
DROP TABLE IF EXISTS `advanced_filter_user_settings`;
DROP TABLE IF EXISTS `advanced_filter_cron_result_items`;
DROP TABLE IF EXISTS `advanced_filter_crons`;
DROP TABLE IF EXISTS `advanced_filters`;
DROP TABLE IF EXISTS `acos`;


CREATE TABLE `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2581 DEFAULT CHARSET=utf8;


CREATE TABLE `advanced_filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `private` int(2) NOT NULL DEFAULT 0,
  `log_result_count` int(2) NOT NULL,
  `log_result_data` int(2) NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `advanced_filters_ibfk_1` (`user_id`),
  CONSTRAINT `advanced_filters_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `advanced_filter_crons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advanced_filter_id` int(11) NOT NULL,
  `cron_id` int(11) DEFAULT NULL,
  `type` int(4) DEFAULT NULL,
  `result` int(11) DEFAULT NULL,
  `execution_time` float NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `advanced_filter_id` (`advanced_filter_id`),
  KEY `cron_id` (`cron_id`),
  CONSTRAINT `advanced_filter_cron_ibfk_1` FOREIGN KEY (`advanced_filter_id`) REFERENCES `advanced_filters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `advanced_filter_cron_ibfk_2` FOREIGN KEY (`cron_id`) REFERENCES `cron` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `advanced_filter_cron_result_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advanced_filter_cron_id` int(11) NOT NULL,
  `data` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `advanced_filter_cron_id` (`advanced_filter_cron_id`),
  CONSTRAINT `advanced_filter_cron_result_items_ibfk_1` FOREIGN KEY (`advanced_filter_cron_id`) REFERENCES `advanced_filter_crons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `advanced_filter_user_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advanced_filter_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `default_index` int(2) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `advanced_filter_id` (`advanced_filter_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `advanced_filter_user_settings_ib_fk_1` FOREIGN KEY (`advanced_filter_id`) REFERENCES `advanced_filters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `advanced_filter_user_settings_ib_fk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `advanced_filter_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advanced_filter_id` int(11) NOT NULL,
  `field` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `many` int(4) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `advanced_filter_values_ibfk_1` (`advanced_filter_id`),
  CONSTRAINT `advanced_filter_values_ibfk_1` FOREIGN KEY (`advanced_filter_id`) REFERENCES `advanced_filters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;


CREATE TABLE `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;


CREATE TABLE `asset_classification_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `asset_classification_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `asset_classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `criteria` text NOT NULL,
  `value` float DEFAULT NULL,
  `asset_classification_type_id` int(11) NOT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_classification_type_id` (`asset_classification_type_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `asset_classifications_ibfk_1` FOREIGN KEY (`asset_classification_type_id`) REFERENCES `asset_classification_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asset_classifications_ibfk_2` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `asset_classifications_assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_classification_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_classification_id` (`asset_classification_id`),
  KEY `asset_id` (`asset_id`),
  CONSTRAINT `asset_classifications_assets_ibfk_1` FOREIGN KEY (`asset_classification_id`) REFERENCES `asset_classifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asset_classifications_assets_ibfk_2` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `asset_labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `asset_labels_ibfk_1` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `asset_media_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `editable` int(11) DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;


CREATE TABLE `asset_media_types_threats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_media_type_id` int(11) NOT NULL,
  `threat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_media_type_id` (`asset_media_type_id`),
  KEY `threat_id` (`threat_id`),
  CONSTRAINT `FK_asset_media_types_threats_asset_media_types` FOREIGN KEY (`asset_media_type_id`) REFERENCES `asset_media_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_asset_media_types_threats_threats` FOREIGN KEY (`threat_id`) REFERENCES `threats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;


CREATE TABLE `asset_media_types_vulnerabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_media_type_id` int(11) NOT NULL,
  `vulnerability_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_media_type_id` (`asset_media_type_id`),
  KEY `vulnerability_id` (`vulnerability_id`),
  CONSTRAINT `FK_asset_media_types_vulnerabilities_asset_media_types` FOREIGN KEY (`asset_media_type_id`) REFERENCES `asset_media_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_asset_media_types_vulnerabilities_vulnerabilities` FOREIGN KEY (`vulnerability_id`) REFERENCES `vulnerabilities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;


CREATE TABLE `assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `asset_label_id` int(11) DEFAULT NULL,
  `asset_media_type_id` int(11) DEFAULT NULL,
  `asset_owner_id` int(11) DEFAULT NULL,
  `asset_guardian_id` int(11) DEFAULT NULL,
  `asset_user_id` int(11) DEFAULT NULL,
  `review` date NOT NULL,
  `expired_reviews` int(1) NOT NULL DEFAULT 0,
  `security_incident_open_count` int(11) NOT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_label_id` (`asset_label_id`),
  KEY `asset_media_type_id` (`asset_media_type_id`),
  KEY `asset_owner_id` (`asset_owner_id`),
  KEY `asset_guardian_id` (`asset_guardian_id`),
  KEY `asset_user_id` (`asset_user_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`asset_label_id`) REFERENCES `asset_labels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_ibfk_2` FOREIGN KEY (`asset_media_type_id`) REFERENCES `asset_media_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_ibfk_4` FOREIGN KEY (`asset_owner_id`) REFERENCES `business_units` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_ibfk_5` FOREIGN KEY (`asset_user_id`) REFERENCES `business_units` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_ibfk_6` FOREIGN KEY (`asset_guardian_id`) REFERENCES `business_units` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_ibfk_7` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `assets_business_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `business_unit_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_id` (`asset_id`),
  KEY `business_unit_id` (`business_unit_id`),
  CONSTRAINT `assets_business_units_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assets_business_units_ibfk_2` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `assets_compliance_managements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `compliance_management_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_id` (`asset_id`),
  KEY `compliance_management_id` (`compliance_management_id`),
  CONSTRAINT `assets_compliance_managements_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assets_compliance_managements_ibfk_2` FOREIGN KEY (`compliance_management_id`) REFERENCES `compliance_managements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `assets_legals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `legal_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_id` (`asset_id`),
  KEY `legal_id` (`legal_id`),
  CONSTRAINT `assets_legals_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assets_legals_ibfk_2` FOREIGN KEY (`legal_id`) REFERENCES `legals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `assets_policy_exceptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `policy_exception_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_id` (`asset_id`),
  KEY `policy_exception_id` (`policy_exception_id`),
  CONSTRAINT `assets_policy_exceptions_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assets_policy_exceptions_ibfk_2` FOREIGN KEY (`policy_exception_id`) REFERENCES `policy_exceptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `assets_related` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `asset_related_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_id` (`asset_id`),
  KEY `asset_related_id` (`asset_related_id`),
  CONSTRAINT `assets_related_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assets_related_ibfk_2` FOREIGN KEY (`asset_related_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `assets_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `risk_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_id` (`asset_id`),
  KEY `risk_id` (`risk_id`),
  CONSTRAINT `assets_risks_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assets_risks_ibfk_2` FOREIGN KEY (`risk_id`) REFERENCES `risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `assets_security_incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `security_incident_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_id` (`asset_id`),
  KEY `security_incident_id` (`security_incident_id`),
  CONSTRAINT `assets_security_incidents_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assets_security_incidents_ibfk_2` FOREIGN KEY (`security_incident_id`) REFERENCES `security_incidents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `assets_third_party_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `third_party_risk_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_id` (`asset_id`),
  KEY `third_party_risk_id` (`third_party_risk_id`),
  CONSTRAINT `assets_third_party_risks_ibfk_1` FOREIGN KEY (`third_party_risk_id`) REFERENCES `third_party_risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assets_third_party_risks_ibfk_2` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(45) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `filename` text NOT NULL,
  `extension` varchar(155) NOT NULL,
  `mime_type` varchar(155) NOT NULL,
  `file_size` int(11) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_attachments_users` (`user_id`),
  CONSTRAINT `FK_attachments_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `audits` (
  `id` varchar(36) NOT NULL,
  `version` int(11) NOT NULL,
  `event` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `entity_id` varchar(36) NOT NULL,
  `request_id` varchar(36) NOT NULL,
  `json_object` text NOT NULL,
  `description` text DEFAULT NULL,
  `source_id` varchar(255) DEFAULT NULL,
  `restore_id` varchar(36) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `restore_id` (`restore_id`),
  CONSTRAINT `audits_ibfk_1` FOREIGN KEY (`restore_id`) REFERENCES `audits` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `audit_deltas` (
  `id` varchar(36) NOT NULL,
  `audit_id` varchar(36) NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_id` (`audit_id`),
  CONSTRAINT `audit_deltas_ibfk_1` FOREIGN KEY (`audit_id`) REFERENCES `audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `recurrence` int(5) NOT NULL,
  `reminder_apart` int(11) NOT NULL,
  `reminder_amount` int(11) NOT NULL,
  `redirect` varchar(255) NOT NULL,
  `ldap_connector_id` int(11) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `video_extension` varchar(50) DEFAULT NULL,
  `video_mime_type` varchar(150) DEFAULT NULL,
  `video_file_size` int(11) DEFAULT NULL,
  `questionnaire` varchar(255) DEFAULT NULL,
  `text_file` varchar(255) DEFAULT NULL,
  `text_file_extension` varchar(50) DEFAULT NULL,
  `text_file_frame_size` int(11) DEFAULT NULL,
  `uploads_sort_json` text NOT NULL,
  `welcome_text` text NOT NULL,
  `welcome_sub_text` text NOT NULL,
  `thank_you_text` text NOT NULL,
  `thank_you_sub_text` text NOT NULL,
  `email_subject` varchar(150) NOT NULL,
  `email_body` text NOT NULL,
  `email_reminder_custom` int(1) NOT NULL DEFAULT 0,
  `email_reminder_subject` varchar(150) NOT NULL,
  `email_reminder_body` text NOT NULL,
  `status` enum('started','stopped') NOT NULL DEFAULT 'stopped',
  `awareness_training_count` int(11) NOT NULL,
  `active_users` int(11) NOT NULL,
  `active_users_percentage` int(3) NOT NULL,
  `ignored_users` int(11) NOT NULL,
  `ignored_users_percentage` int(3) DEFAULT NULL,
  `compliant_users` int(11) NOT NULL,
  `compliant_users_percentage` int(3) NOT NULL,
  `not_compliant_users` int(11) NOT NULL,
  `not_compliant_users_percentage` int(3) NOT NULL,
  `stats_update_status` int(2) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ldap_connector_id` (`ldap_connector_id`),
  CONSTRAINT `awareness_programs_ibfk_1` FOREIGN KEY (`ldap_connector_id`) REFERENCES `ldap_connectors` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_overtime_graphs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `awareness_program_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `doing` decimal(8,2) NOT NULL,
  `missing` decimal(8,2) NOT NULL,
  `correct_answers` decimal(8,2) NOT NULL,
  `average` decimal(8,2) NOT NULL,
  `timestamp` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `awareness_program_id` (`awareness_program_id`),
  CONSTRAINT `awareness_overtime_graphs_ibfk_1` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_program_active_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `awareness_program_id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(155) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `awareness_program_id` (`awareness_program_id`),
  CONSTRAINT `awareness_program_active_users_ibfk_1` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_program_compliant_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `awareness_program_id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `awareness_program_id` (`awareness_program_id`),
  CONSTRAINT `awareness_program_compliant_users_ibfk_1` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_program_demos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(100) NOT NULL,
  `awareness_program_id` int(11) NOT NULL,
  `completed` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `awareness_program_id` (`awareness_program_id`),
  CONSTRAINT `awareness_program_demos_ibfk_1` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


CREATE TABLE `awareness_program_ignored_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `awareness_program_id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `awareness_program_id` (`awareness_program_id`),
  CONSTRAINT `awareness_program_ignored_users_ibfk_1` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_program_ldap_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `awareness_program_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `awareness_program_id` (`awareness_program_id`),
  CONSTRAINT `awareness_program_ldap_groups_ibfk_1` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_program_recurrences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `awareness_program_id` int(11) NOT NULL,
  `start` date NOT NULL,
  `awareness_training_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `awareness_program_id` (`awareness_program_id`),
  CONSTRAINT `awareness_program_recurrences_ibfk_1` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_program_missed_recurrences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(100) NOT NULL,
  `awareness_program_id` int(11) DEFAULT NULL,
  `awareness_program_recurrence_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `awareness_program_recurrence_id` (`awareness_program_recurrence_id`),
  KEY `awareness_program_id` (`awareness_program_id`),
  CONSTRAINT `awareness_program_missed_recurrences_ibfk_2` FOREIGN KEY (`awareness_program_recurrence_id`) REFERENCES `awareness_program_recurrences` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `awareness_program_missed_recurrences_ibfk_3` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_program_not_compliant_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `awareness_program_id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `awareness_program_id` (`awareness_program_id`),
  CONSTRAINT `awareness_program_not_compliant_users_ibfk_1` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_programs_security_policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_policy_id` int(11) NOT NULL,
  `awareness_program_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_policy_id` (`security_policy_id`),
  KEY `awareness_program_id` (`awareness_program_id`),
  CONSTRAINT `awareness_programs_security_policies_ibfk_1` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `awareness_programs_security_policies_ibfk_2` FOREIGN KEY (`security_policy_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `awareness_program_id` int(11) NOT NULL,
  `demo` int(1) NOT NULL DEFAULT 0,
  `reminder_type` int(2) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `awareness_user_id` (`uid`),
  KEY `awareness_program_id` (`awareness_program_id`),
  CONSTRAINT `awareness_reminders_ibfk_1` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `awareness_trainings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `awareness_user_id` int(11) NOT NULL,
  `awareness_program_id` int(11) DEFAULT NULL,
  `awareness_program_recurrence_id` int(11) DEFAULT NULL,
  `answers_json` text DEFAULT NULL,
  `correct` int(11) DEFAULT NULL,
  `wrong` int(11) DEFAULT NULL,
  `demo` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `awareness_user_id` (`awareness_user_id`),
  KEY `awareness_program_id` (`awareness_program_id`),
  KEY `awareness_program_recurrence_id` (`awareness_program_recurrence_id`),
  CONSTRAINT `awareness_trainings_ibfk_1` FOREIGN KEY (`awareness_user_id`) REFERENCES `awareness_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `awareness_trainings_ibfk_3` FOREIGN KEY (`awareness_program_id`) REFERENCES `awareness_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `awareness_trainings_ibfk_4` FOREIGN KEY (`awareness_program_recurrence_id`) REFERENCES `awareness_program_recurrences` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `backups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sql_file` varchar(255) NOT NULL,
  `deleted_files` int(4) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `bulk_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(2) NOT NULL,
  `model` varchar(150) NOT NULL,
  `json_data` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `bulk_actions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `bulk_action_objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bulk_action_id` int(11) NOT NULL,
  `model` varchar(150) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bulk_action_objects_ibfk_1` (`bulk_action_id`),
  CONSTRAINT `bulk_action_objects_ibfk_1` FOREIGN KEY (`bulk_action_id`) REFERENCES `bulk_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `impact` text NOT NULL,
  `threats` text NOT NULL,
  `vulnerabilities` text NOT NULL,
  `description` text DEFAULT NULL,
  `residual_score` int(11) NOT NULL,
  `risk_score` float DEFAULT NULL,
  `risk_score_formula` text NOT NULL,
  `residual_risk` float NOT NULL,
  `residual_risk_formula` text NOT NULL,
  `review` date NOT NULL,
  `expired` int(1) NOT NULL DEFAULT 0,
  `exceptions_issues` int(1) NOT NULL DEFAULT 0,
  `controls_issues` int(1) NOT NULL DEFAULT 0,
  `control_in_design` int(1) NOT NULL DEFAULT 0,
  `expired_reviews` int(1) NOT NULL DEFAULT 0,
  `risk_above_appetite` int(1) NOT NULL DEFAULT 0,
  `plans_issues` int(1) NOT NULL DEFAULT 0,
  `risk_mitigation_strategy_id` int(11) DEFAULT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_mitigation_strategy_id` (`risk_mitigation_strategy_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `business_continuities_ibfk_2` FOREIGN KEY (`risk_mitigation_strategy_id`) REFERENCES `risk_mitigation_strategies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuities_ibfk_5` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities_business_continuity_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_id` int(11) NOT NULL,
  `business_continuity_plan_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_id` (`business_continuity_id`),
  KEY `business_continuity_plan_id` (`business_continuity_plan_id`),
  CONSTRAINT `business_continuities_business_continuity_plans_ibfk_1` FOREIGN KEY (`business_continuity_id`) REFERENCES `business_continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuities_business_continuity_plans_ibfk_2` FOREIGN KEY (`business_continuity_plan_id`) REFERENCES `business_continuity_plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities_business_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_id` int(11) NOT NULL,
  `business_unit_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_id` (`business_continuity_id`),
  KEY `business_unit_id` (`business_unit_id`),
  CONSTRAINT `business_continuities_business_units_ibfk_1` FOREIGN KEY (`business_continuity_id`) REFERENCES `business_continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuities_business_units_ibfk_2` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities_compliance_managements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_id` int(11) NOT NULL,
  `compliance_management_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_management_id` (`compliance_management_id`),
  KEY `business_continuity_id` (`business_continuity_id`),
  CONSTRAINT `business_continuities_compliance_managements_ibfk_1` FOREIGN KEY (`business_continuity_id`) REFERENCES `business_continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuities_compliance_managements_ibfk_2` FOREIGN KEY (`compliance_management_id`) REFERENCES `compliance_managements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities_goals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_id` (`business_continuity_id`),
  KEY `goal_id` (`goal_id`),
  CONSTRAINT `business_continuities_goals_ibfk_1` FOREIGN KEY (`business_continuity_id`) REFERENCES `business_continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuities_goals_ibfk_2` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities_processes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_id` int(11) NOT NULL,
  `process_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_id` (`business_continuity_id`),
  KEY `process_id` (`process_id`),
  CONSTRAINT `business_continuities_processes_ibfk_1` FOREIGN KEY (`business_continuity_id`) REFERENCES `business_continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuities_processes_ibfk_2` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `business_continuity_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_business_continuities_projects_projects` (`project_id`),
  KEY `FK_business_continuities_projects_business_continuities` (`business_continuity_id`),
  CONSTRAINT `FK_business_continuities_projects_business_continuities` FOREIGN KEY (`business_continuity_id`) REFERENCES `business_continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_business_continuities_projects_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities_risk_classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_id` int(11) NOT NULL,
  `risk_classification_id` int(11) NOT NULL,
  `type` int(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `business_continuity_id` (`business_continuity_id`),
  KEY `risk_classification_id` (`risk_classification_id`),
  CONSTRAINT `business_continuities_risk_classifications_ibfk_1` FOREIGN KEY (`business_continuity_id`) REFERENCES `business_continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuities_risk_classifications_ibfk_2` FOREIGN KEY (`risk_classification_id`) REFERENCES `risk_classifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities_risk_exceptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_id` int(11) NOT NULL,
  `risk_exception_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_id` (`business_continuity_id`),
  KEY `risk_exception_id` (`risk_exception_id`),
  CONSTRAINT `business_continuities_risk_exceptions_ibfk_1` FOREIGN KEY (`business_continuity_id`) REFERENCES `business_continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuities_risk_exceptions_ibfk_2` FOREIGN KEY (`risk_exception_id`) REFERENCES `risk_exceptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities_security_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_id` int(11) NOT NULL,
  `security_service_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities_threats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_id` int(11) NOT NULL,
  `threat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_id` (`business_continuity_id`),
  KEY `threat_id` (`threat_id`),
  CONSTRAINT `business_continuities_threats_ibfk_1` FOREIGN KEY (`business_continuity_id`) REFERENCES `business_continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuities_threats_ibfk_2` FOREIGN KEY (`threat_id`) REFERENCES `threats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuities_vulnerabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_id` int(11) NOT NULL,
  `vulnerability_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_id` (`business_continuity_id`),
  KEY `vulnerability_id` (`vulnerability_id`),
  CONSTRAINT `business_continuities_vulnerabilities_ibfk_1` FOREIGN KEY (`business_continuity_id`) REFERENCES `business_continuities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuities_vulnerabilities_ibfk_2` FOREIGN KEY (`vulnerability_id`) REFERENCES `vulnerabilities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuity_plan_audit_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_plan_id` int(11) NOT NULL,
  `day` int(2) NOT NULL,
  `month` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_plan_id` (`business_continuity_plan_id`),
  CONSTRAINT `business_continuity_plan_audit_dates_ibfk_1` FOREIGN KEY (`business_continuity_plan_id`) REFERENCES `business_continuity_plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuity_plan_audit_improvements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_plan_audit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_plan_audit_id` (`business_continuity_plan_audit_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `business_continuity_plan_audit_improvements_ibfk_1` FOREIGN KEY (`business_continuity_plan_audit_id`) REFERENCES `business_continuity_plan_audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuity_plan_audit_improvements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuity_plan_audit_improvements_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_plan_audit_improvement_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_plan_audit_improvement_id` (`business_continuity_plan_audit_improvement_id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `business_continuity_plan_audit_improvements_projects_ibfk_1` FOREIGN KEY (`business_continuity_plan_audit_improvement_id`) REFERENCES `business_continuity_plan_audit_improvements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuity_plan_audit_improvements_projects_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuity_plan_audit_improvements_security_incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_plan_audit_improvement_id` int(11) NOT NULL,
  `security_incident_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_plan_audit_improvement_id` (`business_continuity_plan_audit_improvement_id`),
  KEY `security_incident_id` (`security_incident_id`),
  CONSTRAINT `business_continuity_plan_audit_improvements_incidents_ibfk_1` FOREIGN KEY (`business_continuity_plan_audit_improvement_id`) REFERENCES `business_continuity_plan_audit_improvements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuity_plan_audit_improvements_incidents_ibfk_2` FOREIGN KEY (`security_incident_id`) REFERENCES `security_incidents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuity_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `objective` text NOT NULL,
  `audit_metric` text NOT NULL,
  `audit_success_criteria` text NOT NULL,
  `launch_criteria` text NOT NULL,
  `security_service_type_id` int(11) DEFAULT NULL,
  `opex` float NOT NULL,
  `capex` float NOT NULL,
  `resource_utilization` int(11) NOT NULL,
  `regular_review` date NOT NULL,
  `awareness_recurrence` varchar(150) DEFAULT NULL,
  `audits_all_done` int(1) NOT NULL,
  `audits_last_missing` int(1) NOT NULL,
  `audits_last_passed` int(1) NOT NULL,
  `audits_improvements` int(1) NOT NULL,
  `ongoing_corrective_actions` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_service_type_id` (`security_service_type_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `business_continuity_plans_ibfk_1` FOREIGN KEY (`security_service_type_id`) REFERENCES `security_service_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `business_continuity_plans_ibfk_2` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuity_plan_audits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_plan_id` int(11) NOT NULL,
  `audit_metric_description` text NOT NULL,
  `audit_success_criteria` text NOT NULL,
  `result` int(1) DEFAULT NULL,
  `result_description` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `planned_date` date NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  KEY `user_id` (`user_id`),
  KEY `business_continuity_plan_id` (`business_continuity_plan_id`),
  CONSTRAINT `business_continuity_plan_audits_ibfk_1` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuity_plan_audits_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `business_continuity_plan_audits_ibfk_5` FOREIGN KEY (`business_continuity_plan_id`) REFERENCES `business_continuity_plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuity_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_plan_id` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  `when` text NOT NULL,
  `who` text NOT NULL,
  `does` text NOT NULL,
  `where` text NOT NULL,
  `how` text NOT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  KEY `business_continuity_plan_id` (`business_continuity_plan_id`),
  CONSTRAINT `business_continuity_tasks_ibfk_2` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_continuity_tasks_ibfk_3` FOREIGN KEY (`business_continuity_plan_id`) REFERENCES `business_continuity_plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_continuity_task_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_continuity_task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `acknowledged` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_continuity_task_id` (`business_continuity_task_id`),
  CONSTRAINT `business_continuity_task_reminders_ibfk_1` FOREIGN KEY (`business_continuity_task_id`) REFERENCES `business_continuity_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `_hidden` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `business_units_ibfk_1` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `business_units_data_assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_unit_id` int(11) NOT NULL,
  `data_asset_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `business_units_legals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_unit_id` int(11) NOT NULL,
  `legal_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `business_unit_id` (`business_unit_id`),
  KEY `legal_id` (`legal_id`),
  CONSTRAINT `business_units_legals_ibfk_1` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `business_units_legals_ibfk_2` FOREIGN KEY (`legal_id`) REFERENCES `legals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `cake_sessions` (
  `id` varchar(255) NOT NULL DEFAULT '',
  `data` text DEFAULT NULL,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(150) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `message` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_analysis_findings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `due_date` date DEFAULT NULL,
  `expired` int(1) NOT NULL DEFAULT 0,
  `status` int(3) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_analysis_findings_compliance_managements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_analysis_finding_id` int(11) NOT NULL,
  `compliance_management_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_analysis_finding_id` (`compliance_analysis_finding_id`),
  KEY `compliance_management_id` (`compliance_management_id`),
  CONSTRAINT `compliance_analysis_findings_compliance_managements_ibfk_1` FOREIGN KEY (`compliance_analysis_finding_id`) REFERENCES `compliance_analysis_findings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_analysis_findings_compliance_managements_ibfk_2` FOREIGN KEY (`compliance_management_id`) REFERENCES `compliance_managements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_analysis_findings_compliance_package_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_analysis_finding_id` int(11) NOT NULL,
  `compliance_package_item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_analysis_finding_id` (`compliance_analysis_finding_id`),
  KEY `compliance_package_item_id` (`compliance_package_item_id`),
  CONSTRAINT `compliance_analysis_findings_compliance_package_items_ibfk_1` FOREIGN KEY (`compliance_analysis_finding_id`) REFERENCES `compliance_analysis_findings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_analysis_findings_compliance_package_items_ibfk_2` FOREIGN KEY (`compliance_package_item_id`) REFERENCES `compliance_package_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_analysis_findings_third_parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_analysis_finding_id` int(11) NOT NULL,
  `third_party_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_analysis_finding_id` (`compliance_analysis_finding_id`),
  KEY `third_party_id` (`third_party_id`),
  CONSTRAINT `compliance_analysis_findings_third_parties_ibfk_1` FOREIGN KEY (`compliance_analysis_finding_id`) REFERENCES `compliance_analysis_findings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_analysis_findings_third_parties_ibfk_2` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_audit_feedback_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `compliance_audit_feedback_count` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_audit_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_audit_feedback_profile_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_audit_feedback_profile_id` (`compliance_audit_feedback_profile_id`),
  CONSTRAINT `compliance_audit_feedbacks_ibfk_1` FOREIGN KEY (`compliance_audit_feedback_profile_id`) REFERENCES `compliance_audit_feedback_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_audit_auditee_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `compliance_audit_setting_id` int(11) NOT NULL,
  `compliance_audit_feedback_profile_id` int(11) NOT NULL,
  `compliance_audit_feedback_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_audit_setting_id` (`compliance_audit_setting_id`),
  KEY `compliance_audit_feedback_profile_id` (`compliance_audit_feedback_profile_id`),
  KEY `compliance_audit_feedback_id` (`compliance_audit_feedback_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `FK_compliance_audit_auditee_feedbacks_compliance_audit_feedback` FOREIGN KEY (`compliance_audit_feedback_id`) REFERENCES `compliance_audit_feedbacks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_compliance_audit_auditee_feedbacks_compliance_audit_settings` FOREIGN KEY (`compliance_audit_setting_id`) REFERENCES `compliance_audit_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_compliance_audit_auditee_feedbacks_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_audit_auditee_feedbacks_ibfk_3` FOREIGN KEY (`compliance_audit_feedback_profile_id`) REFERENCES `compliance_audit_feedback_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_audits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `third_party_id` int(11) NOT NULL,
  `auditor_id` int(11) NOT NULL,
  `third_party_contact_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `auditee_title` varchar(155) NOT NULL,
  `auditee_instructions` text NOT NULL,
  `use_default_template` int(1) NOT NULL DEFAULT 1,
  `email_subject` varchar(255) NOT NULL,
  `email_body` text NOT NULL,
  `auditee_notifications` tinyint(1) NOT NULL DEFAULT 0,
  `auditee_emails` tinyint(1) NOT NULL DEFAULT 0,
  `auditor_notifications` tinyint(1) NOT NULL DEFAULT 0,
  `auditor_emails` tinyint(1) NOT NULL DEFAULT 0,
  `show_analyze_title` tinyint(1) NOT NULL DEFAULT 0,
  `show_analyze_description` tinyint(1) NOT NULL DEFAULT 0,
  `show_analyze_audit_criteria` tinyint(1) NOT NULL DEFAULT 0,
  `show_findings` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT 'started' COMMENT 'started or stopped',
  `compliance_finding_count` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `third_party_id` (`third_party_id`),
  KEY `auditor_id` (`auditor_id`),
  KEY `third_party_contact_id` (`third_party_contact_id`),
  CONSTRAINT `compliance_audits_ibfk_1` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_audits_ibfk_2` FOREIGN KEY (`auditor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_audits_ibfk_3` FOREIGN KEY (`third_party_contact_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_audit_feedbacks_compliance_audits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_audit_feedback_id` int(11) NOT NULL,
  `compliance_audit_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_audit_feedback_id` (`compliance_audit_feedback_id`),
  KEY `compliance_audit_id` (`compliance_audit_id`),
  CONSTRAINT `compliance_audit_feedbacks_compliance_audits_ibfk_1` FOREIGN KEY (`compliance_audit_feedback_id`) REFERENCES `compliance_audit_feedbacks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_audit_feedbacks_compliance_audits_ibfk_2` FOREIGN KEY (`compliance_audit_id`) REFERENCES `compliance_audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_audit_overtime_graphs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_audit_id` int(11) NOT NULL,
  `open` int(3) NOT NULL,
  `closed` int(3) NOT NULL,
  `expired` int(3) NOT NULL,
  `no_evidence` int(3) NOT NULL,
  `waiting_evidence` int(3) NOT NULL,
  `provided_evidence` int(3) NOT NULL,
  `timestamp` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_audit_id` (`compliance_audit_id`),
  CONSTRAINT `compliance_audit_overtime_graphs_ibfk_1` FOREIGN KEY (`compliance_audit_id`) REFERENCES `compliance_audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_audit_provided_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `compliance_audit_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `compliance_audit_id` (`compliance_audit_id`),
  CONSTRAINT `compliance_audit_provided_feedbacks_ib_fk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_audit_provided_feedbacks_ib_fk_2` FOREIGN KEY (`compliance_audit_id`) REFERENCES `compliance_audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_audit_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_audit_id` int(11) NOT NULL,
  `compliance_package_item_id` int(11) NOT NULL,
  `status` int(1) DEFAULT NULL,
  `compliance_audit_feedback_profile_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_audit_id` (`compliance_audit_id`),
  KEY `compliance_package_item_id` (`compliance_package_item_id`),
  KEY `compliance_audit_feedback_profile_id` (`compliance_audit_feedback_profile_id`),
  CONSTRAINT `compliance_audit_settings_ibfk_1` FOREIGN KEY (`compliance_audit_id`) REFERENCES `compliance_audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_audit_settings_ibfk_2` FOREIGN KEY (`compliance_package_item_id`) REFERENCES `compliance_package_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_audit_settings_ibfk_4` FOREIGN KEY (`compliance_audit_feedback_profile_id`) REFERENCES `compliance_audit_feedback_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_audit_setting_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_audit_setting_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_audit_setting_id` (`compliance_audit_setting_id`),
  CONSTRAINT `compliance_audit_setting_notifications_ibfk_1` FOREIGN KEY (`compliance_audit_setting_id`) REFERENCES `compliance_audit_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_audit_settings_auditees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_audit_setting_id` int(11) NOT NULL,
  `auditee_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_audit_setting_id` (`compliance_audit_setting_id`),
  KEY `auditee_id` (`auditee_id`),
  CONSTRAINT `compliance_audit_settings_auditees_ibfk_1` FOREIGN KEY (`compliance_audit_setting_id`) REFERENCES `compliance_audit_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_audit_settings_auditees_ibfk_2` FOREIGN KEY (`auditee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_exceptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `expiration` date NOT NULL,
  `expired` int(1) NOT NULL DEFAULT 0,
  `closure_date_toggle` tinyint(1) NOT NULL DEFAULT 1,
  `closure_date` date DEFAULT NULL,
  `status` int(1) NOT NULL COMMENT '0-closed, 1-open',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_exceptions_compliance_findings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_exception_id` int(11) NOT NULL,
  `compliance_finding_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_exception_id` (`compliance_exception_id`),
  KEY `compliance_finding_id` (`compliance_finding_id`),
  CONSTRAINT `compliance_exceptions_compliance_findings_ibfk1` FOREIGN KEY (`compliance_exception_id`) REFERENCES `compliance_exceptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_exceptions_compliance_findings_ibfk2` FOREIGN KEY (`compliance_finding_id`) REFERENCES `compliance_findings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_managements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_package_item_id` int(11) NOT NULL,
  `compliance_treatment_strategy_id` int(11) DEFAULT NULL,
  `legal_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `efficacy` int(3) NOT NULL,
  `description` text NOT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_package_item_id` (`compliance_package_item_id`),
  KEY `compliance_treatment_strategy_id` (`compliance_treatment_strategy_id`),
  KEY `FK_compliance_managements_legals` (`legal_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `FK_compliance_managements_legals` FOREIGN KEY (`legal_id`) REFERENCES `legals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_managements_ibfk_1` FOREIGN KEY (`compliance_package_item_id`) REFERENCES `compliance_package_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_managements_ibfk_2` FOREIGN KEY (`compliance_treatment_strategy_id`) REFERENCES `compliance_treatment_strategies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `compliance_managements_ibfk_4` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_exceptions_compliance_managements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_exception_id` int(11) NOT NULL,
  `compliance_management_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_exception_id` (`compliance_exception_id`),
  KEY `compliance_management_id` (`compliance_management_id`),
  CONSTRAINT `compliance_exceptions_compliance_managements_ibfk_1` FOREIGN KEY (`compliance_exception_id`) REFERENCES `compliance_exceptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_exceptions_compliance_managements_ibfk_2` FOREIGN KEY (`compliance_management_id`) REFERENCES `compliance_managements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_finding_classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_finding_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_finding_id` (`compliance_finding_id`),
  CONSTRAINT `compliance_finding_classifications_ibfk_1` FOREIGN KEY (`compliance_finding_id`) REFERENCES `compliance_findings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_finding_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_findings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `deadline` date DEFAULT NULL,
  `expired` int(1) NOT NULL DEFAULT 0,
  `compliance_finding_status_id` int(11) DEFAULT NULL,
  `compliance_audit_id` int(11) NOT NULL,
  `compliance_package_item_id` int(11) DEFAULT NULL,
  `type` int(1) NOT NULL DEFAULT 1 COMMENT '1-audit finding, 2-assesed item',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_finding_status_id` (`compliance_finding_status_id`),
  KEY `compliance_audit_id` (`compliance_audit_id`),
  KEY `compliance_package_item_id` (`compliance_package_item_id`),
  CONSTRAINT `compliance_findings_ibfk_1` FOREIGN KEY (`compliance_finding_status_id`) REFERENCES `compliance_finding_statuses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `compliance_findings_ibfk_2` FOREIGN KEY (`compliance_audit_id`) REFERENCES `compliance_audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_findings_ibfk_3` FOREIGN KEY (`compliance_package_item_id`) REFERENCES `compliance_package_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_findings_third_party_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_finding_id` int(11) NOT NULL,
  `third_party_risk_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_finding_id` (`compliance_finding_id`),
  KEY `third_party_risk_id` (`third_party_risk_id`),
  CONSTRAINT `compliance_findings_third_party_risks_ibfk1` FOREIGN KEY (`compliance_finding_id`) REFERENCES `compliance_findings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_findings_third_party_risks_ibfk2` FOREIGN KEY (`third_party_risk_id`) REFERENCES `third_party_risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_managements_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_management_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_compliance_managements_projects_compliance_managements` (`compliance_management_id`),
  KEY `FK_compliance_managements_projects_projects` (`project_id`),
  CONSTRAINT `FK_compliance_managements_projects_compliance_managements` FOREIGN KEY (`compliance_management_id`) REFERENCES `compliance_managements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_compliance_managements_projects_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_managements_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_management_id` int(11) NOT NULL,
  `risk_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_id` (`risk_id`),
  KEY `compliance_management_id` (`compliance_management_id`),
  CONSTRAINT `compliance_managements_risks_ibfk_1` FOREIGN KEY (`compliance_management_id`) REFERENCES `compliance_managements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_managements_risks_ibfk_2` FOREIGN KEY (`risk_id`) REFERENCES `risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_managements_security_policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_management_id` int(11) NOT NULL,
  `security_policy_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_management_id` (`compliance_management_id`),
  KEY `security_policy_id` (`security_policy_id`),
  CONSTRAINT `compliance_managements_security_policies_ibfk_1` FOREIGN KEY (`compliance_management_id`) REFERENCES `compliance_managements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_managements_security_policies_ibfk_2` FOREIGN KEY (`security_policy_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_managements_security_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_management_id` int(11) NOT NULL,
  `security_service_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_management_id` (`compliance_management_id`),
  KEY `security_service_id` (`security_service_id`),
  CONSTRAINT `compliance_managements_security_services_ibfk_1` FOREIGN KEY (`compliance_management_id`) REFERENCES `compliance_managements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_managements_security_services_ibfk_2` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_managements_third_party_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compliance_management_id` int(11) NOT NULL,
  `third_party_risk_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_management_id` (`compliance_management_id`),
  KEY `third_party_risk_id` (`third_party_risk_id`),
  CONSTRAINT `compliance_managements_third_party_risks_ibfk_1` FOREIGN KEY (`compliance_management_id`) REFERENCES `compliance_managements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_managements_third_party_risks_ibfk_2` FOREIGN KEY (`third_party_risk_id`) REFERENCES `third_party_risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` varchar(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `third_party_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `third_party_id` (`third_party_id`),
  CONSTRAINT `compliance_packages_ibfk_1` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_package_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `audit_questionaire` text NOT NULL,
  `compliance_package_id` int(11) NOT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_package_id` (`compliance_package_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `compliance_package_items_ibfk_1` FOREIGN KEY (`compliance_package_id`) REFERENCES `compliance_packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compliance_package_items_ibfk_2` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


CREATE TABLE `compliance_treatment_strategies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(3) DEFAULT NULL,
  `model` varchar(50) NOT NULL DEFAULT '',
  `foreign_key` int(11) NOT NULL,
  `country_id` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `foreign_key` (`foreign_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(128) NOT NULL,
  `execution_time` float DEFAULT NULL,
  `status` enum('success','error') DEFAULT 'success',
  `request_id` varchar(36) DEFAULT NULL,
  `created` datetime NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `custom_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_field_id` int(11) NOT NULL,
  `value` varchar(155) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_field_id` (`custom_field_id`),
  CONSTRAINT `FK_custom_field_options_custom_fields` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `custom_field_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) NOT NULL,
  `status` int(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `model` (`model`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;


CREATE TABLE `custom_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) NOT NULL,
  `name` varchar(155) NOT NULL,
  `slug` varchar(155) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `model` (`model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_form_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` int(3) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `custom_form_id` (`custom_form_id`),
  CONSTRAINT `FK_custom_fields_custom_forms` FOREIGN KEY (`custom_form_id`) REFERENCES `custom_forms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `custom_field_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `field_name` (`custom_field_id`),
  KEY `model` (`model`),
  KEY `foreign_key` (`foreign_key`),
  CONSTRAINT `FK_custom_field_values_custom_fields` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `custom_roles_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `custom_roles_groups_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


CREATE TABLE `custom_roles_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) DEFAULT NULL,
  `field` varchar(155) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_model` (`model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `custom_roles_role_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `custom_roles_role_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_roles_role_id` (`custom_roles_role_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `custom_roles_role_groups_ibfk_1` FOREIGN KEY (`custom_roles_role_id`) REFERENCES `custom_roles_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `custom_roles_role_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `custom_roles_role_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `custom_roles_role_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_roles_role_id` (`custom_roles_role_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `custom_roles_role_users_ibfk_1` FOREIGN KEY (`custom_roles_role_id`) REFERENCES `custom_roles_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `custom_roles_role_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `custom_roles_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `custom_roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `custom_validator_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL DEFAULT '',
  `validator` varchar(255) NOT NULL DEFAULT '',
  `field` varchar(255) NOT NULL DEFAULT '',
  `type` int(11) NOT NULL,
  `validation` text DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `dashboard_kpis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(155) DEFAULT NULL,
  `title` varchar(155) DEFAULT NULL,
  `model` varchar(155) NOT NULL DEFAULT '',
  `type` int(3) NOT NULL DEFAULT 0,
  `category` int(3) NOT NULL DEFAULT 0,
  `owner_id` int(11) DEFAULT NULL,
  `dashboard_kpi_attribute_count` int(11) NOT NULL DEFAULT 0,
  `json` text DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `value` int(8) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;


CREATE TABLE `dashboard_kpi_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kpi_id` int(11) NOT NULL,
  `model` varchar(128) DEFAULT NULL,
  `foreign_key` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `kpi_id` (`kpi_id`),
  CONSTRAINT `dashboard_kpi_attributes_ibfk_1` FOREIGN KEY (`kpi_id`) REFERENCES `dashboard_kpis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8;


CREATE TABLE `dashboard_kpi_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kpi_id` int(11) NOT NULL DEFAULT 0,
  `value` int(7) NOT NULL,
  `timestamp` int(10) NOT NULL,
  `current_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `kpi_id` (`kpi_id`),
  CONSTRAINT `dashboard_kpi_logs_ibfk_1` FOREIGN KEY (`kpi_id`) REFERENCES `dashboard_kpis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `dashboard_kpi_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kpi_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `value` int(8) DEFAULT NULL,
  `type` int(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `kpi_id` (`kpi_id`),
  CONSTRAINT `dashboard_kpi_values_ibfk_1` FOREIGN KEY (`kpi_id`) REFERENCES `dashboard_kpis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;


CREATE TABLE `dashboard_kpi_value_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kpi_value_id` int(11) NOT NULL DEFAULT 0,
  `kpi_id` int(11) NOT NULL DEFAULT 0,
  `value` int(7) NOT NULL DEFAULT 0,
  `request_id` varchar(36) DEFAULT NULL,
  `timestamp` int(10) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `kpi_value_id` (`kpi_value_id`),
  CONSTRAINT `dashboard_kpi_value_logs_ibfk_1` FOREIGN KEY (`kpi_value_id`) REFERENCES `dashboard_kpi_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8;


CREATE TABLE `data_asset_gdpr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_asset_id` int(11) NOT NULL,
  `purpose` text NOT NULL,
  `right_to_be_informed` text NOT NULL,
  `data_subject` text NOT NULL,
  `volume` text NOT NULL,
  `recived_data` text NOT NULL,
  `contracts` text NOT NULL,
  `retention` text NOT NULL,
  `encryption` text NOT NULL,
  `right_to_erasure` text NOT NULL,
  `archiving_driver_empty` int(3) NOT NULL DEFAULT 0,
  `origin` text NOT NULL,
  `destination` text NOT NULL,
  `transfer_outside_eea` int(3) NOT NULL DEFAULT 0,
  `third_party_involved_all` int(3) NOT NULL DEFAULT 0,
  `security` text NOT NULL,
  `right_to_portability` text NOT NULL,
  `stakeholders` text NOT NULL,
  `accuracy` text NOT NULL,
  `right_to_access` text NOT NULL,
  `right_to_rectification` text NOT NULL,
  `right_to_decision` text NOT NULL,
  `right_to_object` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_id` (`data_asset_id`),
  CONSTRAINT `data_asset_gdpr_ibfk_1` FOREIGN KEY (`data_asset_id`) REFERENCES `data_assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_asset_gdpr_archiving_drivers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_asset_gdpr_id` int(11) NOT NULL,
  `archiving_driver` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_gdpr_id` (`data_asset_gdpr_id`),
  CONSTRAINT `data_asset_gdpr_archiving_drivers_ibfk_1` FOREIGN KEY (`data_asset_gdpr_id`) REFERENCES `data_asset_gdpr` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_asset_gdpr_collection_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_asset_gdpr_id` int(11) NOT NULL,
  `collection_method` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_gdpr_id` (`data_asset_gdpr_id`),
  CONSTRAINT `data_asset_gdpr_collection_methods_ibfk_1` FOREIGN KEY (`data_asset_gdpr_id`) REFERENCES `data_asset_gdpr` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_asset_gdpr_data_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_asset_gdpr_id` int(11) NOT NULL,
  `data_type` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_gdpr_id` (`data_asset_gdpr_id`),
  CONSTRAINT `data_asset_gdpr_data_types_ibfk_1` FOREIGN KEY (`data_asset_gdpr_id`) REFERENCES `data_asset_gdpr` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_asset_gdpr_lawful_bases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_asset_gdpr_id` int(11) NOT NULL,
  `lawful_base` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_gdpr_id` (`data_asset_gdpr_id`),
  CONSTRAINT `data_asset_gdpr_lawful_bases_ibfk_1` FOREIGN KEY (`data_asset_gdpr_id`) REFERENCES `data_asset_gdpr` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_asset_gdpr_third_party_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_asset_gdpr_id` int(11) NOT NULL,
  `third_party_country` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_gdpr_id` (`data_asset_gdpr_id`),
  CONSTRAINT `data_asset_gdpr_third_party_countries_ibfk_1` FOREIGN KEY (`data_asset_gdpr_id`) REFERENCES `data_asset_gdpr` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_asset_instances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `analysis_unlocked` int(3) NOT NULL DEFAULT 0,
  `asset_missing_review` int(3) NOT NULL DEFAULT 0,
  `controls_with_issues` int(3) NOT NULL DEFAULT 0,
  `controls_with_failed_audits` int(3) NOT NULL DEFAULT 0,
  `controls_with_missing_audits` int(3) NOT NULL DEFAULT 0,
  `policies_with_missing_reviews` int(3) NOT NULL DEFAULT 0,
  `risks_with_missing_reviews` int(3) NOT NULL DEFAULT 0,
  `project_expired` int(3) NOT NULL DEFAULT 0,
  `expired_tasks` int(3) NOT NULL DEFAULT 0,
  `incomplete_analysis` int(3) NOT NULL DEFAULT 0,
  `incomplete_gdpr_analysis` int(3) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_id` (`asset_id`),
  CONSTRAINT `data_asset_instances_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_asset_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `data_asset_instance_id` int(11) NOT NULL,
  `gdpr_enabled` int(1) NOT NULL,
  `driver_for_compliance` text NOT NULL,
  `dpo_empty` int(3) NOT NULL DEFAULT 0,
  `controller_representative_empty` int(3) DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_instance_id` (`data_asset_instance_id`),
  CONSTRAINT `data_asset_settings_ibfk_1` FOREIGN KEY (`data_asset_instance_id`) REFERENCES `data_asset_instances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_asset_settings_third_parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '',
  `data_asset_setting_id` int(11) NOT NULL,
  `third_party_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_setting_id` (`data_asset_setting_id`),
  KEY `third_party_id` (`third_party_id`),
  CONSTRAINT `data_asset_settings_third_parties_ibfk_1` FOREIGN KEY (`data_asset_setting_id`) REFERENCES `data_asset_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `data_asset_settings_third_parties_ibfk_2` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_asset_settings_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `data_asset_setting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_setting_id` (`data_asset_setting_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `data_asset_settings_users_ibfk_1` FOREIGN KEY (`data_asset_setting_id`) REFERENCES `data_asset_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `data_asset_settings_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_asset_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


CREATE TABLE `data_assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `data_asset_instance_id` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `data_asset_status_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_status_id` (`data_asset_status_id`),
  KEY `FK_data_assets_data_asset_instances` (`data_asset_instance_id`),
  KEY `FK_data_assets_data_assets` (`order`),
  CONSTRAINT `data_assets_ibfk_2` FOREIGN KEY (`data_asset_status_id`) REFERENCES `data_asset_statuses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `data_assets_ibfk_4` FOREIGN KEY (`data_asset_instance_id`) REFERENCES `data_asset_instances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_assets_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `data_asset_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_data_assets_projects_projects` (`project_id`),
  KEY `FK_data_assets_projects_data_assets` (`data_asset_id`),
  CONSTRAINT `FK_data_assets_projects_data_assets` FOREIGN KEY (`data_asset_id`) REFERENCES `data_assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_data_assets_projects_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_assets_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(20) NOT NULL DEFAULT '0',
  `data_asset_id` int(11) NOT NULL,
  `risk_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_id` (`data_asset_id`),
  KEY `risk_id` (`risk_id`),
  CONSTRAINT `data_assets_risks_ibfk_1` FOREIGN KEY (`data_asset_id`) REFERENCES `data_assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_assets_security_policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_asset_id` int(11) NOT NULL,
  `security_policy_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_id` (`data_asset_id`),
  KEY `security_policy_id` (`security_policy_id`),
  CONSTRAINT `data_assets_security_policies_ibfk_1` FOREIGN KEY (`data_asset_id`) REFERENCES `data_assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `data_assets_security_policies_ibfk_2` FOREIGN KEY (`security_policy_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_assets_security_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_asset_id` int(11) NOT NULL,
  `security_service_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_id` (`data_asset_id`),
  KEY `security_service_id` (`security_service_id`),
  CONSTRAINT `data_assets_security_services_ibfk_1` FOREIGN KEY (`data_asset_id`) REFERENCES `data_assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `data_assets_security_services_ibfk_2` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `data_assets_third_parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_asset_id` int(11) NOT NULL,
  `third_party_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data_asset_id` (`data_asset_id`),
  KEY `third_party_id` (`third_party_id`),
  CONSTRAINT `data_assets_third_parties_ibfk_1` FOREIGN KEY (`data_asset_id`) REFERENCES `data_assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `data_assets_third_parties_ibfk_2` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goal_audit_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_id` int(11) NOT NULL,
  `day` int(2) NOT NULL,
  `month` int(2) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goal_id` (`goal_id`),
  CONSTRAINT `goal_audit_dates_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goal_audit_improvements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_audit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goal_audit_id` (`goal_audit_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `goal_audit_improvements_ibfk_1` FOREIGN KEY (`goal_audit_id`) REFERENCES `goal_audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goal_audit_improvements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goal_audit_improvements_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_audit_improvement_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goal_audit_improvement_id` (`goal_audit_improvement_id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `goal_audit_improvements_projects_ibfk_1` FOREIGN KEY (`goal_audit_improvement_id`) REFERENCES `goal_audit_improvements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goal_audit_improvements_projects_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goal_audit_improvements_security_incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_audit_improvement_id` int(11) NOT NULL,
  `security_incident_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goal_audit_improvement_id` (`goal_audit_improvement_id`),
  KEY `security_incident_id` (`security_incident_id`),
  CONSTRAINT `goal_audit_improvements_security_incidents_ibfk_1` FOREIGN KEY (`goal_audit_improvement_id`) REFERENCES `goal_audit_improvements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goal_audit_improvements_security_incidents_ibfk_2` FOREIGN KEY (`security_incident_id`) REFERENCES `security_incidents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(155) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `audit_metric` text NOT NULL,
  `audit_criteria` text NOT NULL,
  `metrics_last_missing` int(1) NOT NULL,
  `ongoing_corrective_actions` int(1) NOT NULL DEFAULT 0,
  `status` enum('draft','discarded','current') NOT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goal_audits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_id` int(11) NOT NULL,
  `audit_metric_description` text NOT NULL,
  `audit_success_criteria` text NOT NULL,
  `result` int(1) DEFAULT NULL COMMENT 'null-not defined, 0-fail, 1-pass',
  `result_description` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `planned_date` date NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goal_id` (`goal_id`),
  KEY `user_id` (`user_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `goal_audits_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goal_audits_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `goal_audits_ibfk_3` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goals_program_issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_id` int(11) NOT NULL,
  `program_issue_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goal_id` (`goal_id`),
  KEY `program_issue_id` (`program_issue_id`),
  CONSTRAINT `goals_program_issues_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goals_program_issues_ibfk_2` FOREIGN KEY (`program_issue_id`) REFERENCES `program_issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goals_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goal_id` (`goal_id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `goals_projects_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goals_projects_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goals_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_id` int(11) NOT NULL,
  `risk_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goal_id` (`goal_id`),
  KEY `risk_id` (`risk_id`),
  CONSTRAINT `goals_risks_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goals_risks_ibfk_2` FOREIGN KEY (`risk_id`) REFERENCES `risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goals_security_policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_id` int(11) NOT NULL,
  `security_policy_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goal_id` (`goal_id`),
  KEY `security_policy_id` (`security_policy_id`),
  CONSTRAINT `goals_security_policies_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goals_security_policies_ibfk_2` FOREIGN KEY (`security_policy_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goals_security_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_id` int(11) NOT NULL,
  `security_service_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goal_id` (`goal_id`),
  KEY `security_service_id` (`security_service_id`),
  CONSTRAINT `goals_security_services_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goals_security_services_ibfk_2` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `goals_third_party_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_id` int(11) NOT NULL,
  `third_party_risk_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goal_id` (`goal_id`),
  KEY `third_party_risk_id` (`third_party_risk_id`),
  CONSTRAINT `goals_third_party_risks_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goals_third_party_risks_ibfk_2` FOREIGN KEY (`third_party_risk_id`) REFERENCES `third_party_risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` int(11) DEFAULT 1 COMMENT '0-non active, 1-active',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;


CREATE TABLE `issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(150) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_issues_users` (`user_id`),
  CONSTRAINT `FK_issues_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `ldap_connectors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `host` varchar(150) NOT NULL,
  `domain` varchar(150) DEFAULT NULL,
  `port` int(11) NOT NULL DEFAULT 389,
  `ldap_bind_dn` varchar(255) NOT NULL DEFAULT '',
  `ldap_bind_pw` varchar(150) NOT NULL,
  `ldap_base_dn` varchar(255) NOT NULL DEFAULT '',
  `type` enum('authenticator','group') NOT NULL,
  `ldap_auth_filter` varchar(255) DEFAULT '(| (sn=%USERNAME%) )',
  `ldap_auth_attribute` varchar(150) DEFAULT NULL,
  `ldap_name_attribute` varchar(150) DEFAULT NULL,
  `ldap_email_attribute` varchar(150) DEFAULT NULL,
  `ldap_memberof_attribute` varchar(150) DEFAULT NULL,
  `ldap_grouplist_filter` varchar(150) DEFAULT NULL,
  `ldap_grouplist_name` varchar(150) DEFAULT NULL,
  `ldap_groupmemberlist_filter` varchar(255) DEFAULT NULL,
  `ldap_group_account_attribute` varchar(150) DEFAULT NULL,
  `ldap_group_fetch_email_type` varchar(150) DEFAULT NULL,
  `ldap_group_email_attribute` varchar(150) DEFAULT NULL,
  `ldap_group_mail_domain` varchar(150) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0-disabled,1-active',
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `ldap_connector_authentication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auth_users` int(1) NOT NULL,
  `auth_users_id` int(11) DEFAULT NULL,
  `oauth_google` int(1) NOT NULL,
  `oauth_google_id` int(11) NOT NULL,
  `auth_awareness` int(1) NOT NULL,
  `auth_awareness_id` int(11) DEFAULT NULL,
  `auth_policies` int(1) NOT NULL,
  `auth_policies_id` int(11) DEFAULT NULL,
  `auth_compliance_audit` int(1) NOT NULL DEFAULT 0,
  `auth_vendor_assessment` int(1) NOT NULL DEFAULT 0,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auth_users_id` (`auth_users_id`),
  KEY `auth_awareness_id` (`auth_awareness_id`),
  KEY `auth_policies_id` (`auth_policies_id`),
  CONSTRAINT `ldap_connector_authentication_ibfk_1` FOREIGN KEY (`auth_users_id`) REFERENCES `ldap_connectors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ldap_connector_authentication_ibfk_2` FOREIGN KEY (`auth_awareness_id`) REFERENCES `ldap_connectors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `ldap_connector_authentication_ibfk_3` FOREIGN KEY (`auth_policies_id`) REFERENCES `ldap_connectors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `legals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `risk_magnifier` float DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `legals_ibfk_1` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `legals_third_parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `legal_id` int(11) NOT NULL,
  `third_party_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `legal_id` (`legal_id`),
  KEY `third_party_id` (`third_party_id`),
  CONSTRAINT `legals_third_parties_ibfk_1` FOREIGN KEY (`legal_id`) REFERENCES `legals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `legals_third_parties_ibfk_2` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `log_security_policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_policy_id` int(11) NOT NULL,
  `index` varchar(100) NOT NULL,
  `short_description` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `document_type` enum('policy','standard','procedure') NOT NULL,
  `version` varchar(50) NOT NULL,
  `published_date` date NOT NULL,
  `next_review_date` date NOT NULL,
  `permission` enum('public','private','logged') NOT NULL,
  `ldap_connector_id` int(11) DEFAULT NULL,
  `asset_label_id` int(11) DEFAULT NULL,
  `user_edit_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notification_system_item_custom_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_system_item_id` int(11) NOT NULL,
  `custom_identifier` varchar(255) DEFAULT NULL,
  `migration_updated` int(3) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_system_item_id` (`notification_system_item_id`),
  CONSTRAINT `notification_system_item_custom_roles_ibfk_1` FOREIGN KEY (`notification_system_item_id`) REFERENCES `notification_system_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notification_system_item_custom_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_system_item_object_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `notification_system_item_object_id` (`notification_system_item_object_id`),
  CONSTRAINT `notification_system_item_custom_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notification_system_item_custom_users_ibfk_3` FOREIGN KEY (`notification_system_item_object_id`) REFERENCES `notification_system_items_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notification_system_item_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_system_item_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_system_item_id` (`notification_system_item_id`),
  CONSTRAINT `notification_system_item_emails_ibfk_1` FOREIGN KEY (`notification_system_item_id`) REFERENCES `notification_system_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notification_system_item_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_system_item_object_id` int(11) NOT NULL,
  `is_new` int(1) NOT NULL DEFAULT 1 COMMENT '1-new, 0-reminder',
  `feedback_resolved` int(1) DEFAULT 0 COMMENT '1-feedback entered',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_system_item_object_id` (`notification_system_item_object_id`),
  CONSTRAINT `notification_system_item_logs_ibfk_1` FOREIGN KEY (`notification_system_item_object_id`) REFERENCES `notification_system_items_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notification_system_item_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_system_item_log_id` int(11) NOT NULL,
  `notification_system_item_object_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_system_item_log_id` (`notification_system_item_log_id`),
  KEY `user_id` (`user_id`),
  KEY `notification_system_item_object_id` (`notification_system_item_object_id`),
  CONSTRAINT `notification_system_item_feedbacks_ibfk_1` FOREIGN KEY (`notification_system_item_log_id`) REFERENCES `notification_system_item_logs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notification_system_item_feedbacks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notification_system_item_feedbacks_ibfk_4` FOREIGN KEY (`notification_system_item_object_id`) REFERENCES `notification_system_items_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notification_system_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `model` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `email_notification` int(1) NOT NULL DEFAULT 0,
  `header_notification` int(1) NOT NULL DEFAULT 0,
  `feedback` int(1) NOT NULL DEFAULT 0,
  `chase_interval` int(2) DEFAULT NULL,
  `chase_amount` int(3) DEFAULT NULL COMMENT 'how many times a notification will be remindered',
  `trigger_period` int(5) DEFAULT NULL COMMENT 'awareness uses this field',
  `automated` int(1) NOT NULL DEFAULT 0,
  `email_customized` int(1) NOT NULL DEFAULT 0,
  `email_subject` varchar(255) NOT NULL,
  `email_body` text NOT NULL,
  `report_send_empty_results` int(2) unsigned DEFAULT NULL,
  `report_attachment_type` int(2) unsigned DEFAULT NULL,
  `advanced_filter_id` int(11) DEFAULT NULL,
  `type` varchar(45) NOT NULL,
  `status_feedback` int(2) NOT NULL DEFAULT 0 COMMENT '0-ok, 1- waiting for feedback, 2-feedback ignored',
  `log_count` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `advanced_filter_id` (`advanced_filter_id`),
  CONSTRAINT `notification_system_items_ibfk_1` FOREIGN KEY (`advanced_filter_id`) REFERENCES `advanced_filters` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notification_system_items_objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_system_item_id` int(11) NOT NULL,
  `model` varchar(250) NOT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `status_feedback` int(2) NOT NULL DEFAULT 0 COMMENT '0-ok, 1- waiting for feedback, 2-feedback ignored',
  `log_count` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '0-disabled, 1-enabled',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_system_item_id` (`notification_system_item_id`),
  CONSTRAINT `notification_system_items_objects_ibfk_1` FOREIGN KEY (`notification_system_item_id`) REFERENCES `notification_system_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notification_system_items_scopes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_system_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `custom_identifier` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_system_item_id` (`notification_system_item_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notification_system_items_scopes_ibfk_1` FOREIGN KEY (`notification_system_item_id`) REFERENCES `notification_system_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notification_system_items_scopes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notification_system_items_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_system_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_system_item_id` (`notification_system_item_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notification_system_items_users_ibfk_1` FOREIGN KEY (`notification_system_item_id`) REFERENCES `notification_system_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notification_system_items_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `model` varchar(150) NOT NULL,
  `user_id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '1-new, 0-seen',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `oauth_connectors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `client_secret` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `object_status_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `object_status_object_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(100) NOT NULL DEFAULT '',
  `foreign_key` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `status` int(3) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status_id` (`status_id`),
  KEY `foreign_key` (`foreign_key`),
  CONSTRAINT `object_status_object_statuses_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `object_status_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `policy_exceptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `expiration` date NOT NULL,
  `expired` int(1) NOT NULL DEFAULT 0,
  `closure_date_toggle` tinyint(1) NOT NULL DEFAULT 1,
  `closure_date` date DEFAULT NULL,
  `status` int(1) NOT NULL COMMENT '0-closed, 1-open',
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `policy_exceptions_ibfk_3` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `policy_exception_classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_exception_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `policy_exception_id` (`policy_exception_id`),
  CONSTRAINT `policy_exception_classifications_ibfk_1` FOREIGN KEY (`policy_exception_id`) REFERENCES `policy_exceptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `policy_exceptions_security_policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_exception_id` int(11) NOT NULL,
  `security_policy_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `policy_exception_id` (`policy_exception_id`),
  KEY `security_policy_id` (`security_policy_id`),
  CONSTRAINT `policy_exceptions_security_policies_ibfk_1` FOREIGN KEY (`policy_exception_id`) REFERENCES `policy_exceptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `policy_exceptions_security_policies_ibfk_2` FOREIGN KEY (`security_policy_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `policy_exceptions_third_parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_exception_id` int(11) NOT NULL,
  `third_party_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_policy_exceptions_third_parties_policy_exceptions` (`policy_exception_id`),
  KEY `FK_policy_exceptions_third_parties_third_parties` (`third_party_id`),
  CONSTRAINT `FK_policy_exceptions_third_parties_policy_exceptions` FOREIGN KEY (`policy_exception_id`) REFERENCES `policy_exceptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_policy_exceptions_third_parties_third_parties` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `policy_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `processes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_unit_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `rto` int(11) DEFAULT NULL,
  `rpo` int(11) DEFAULT NULL,
  `rpd` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `business_unit_id` (`business_unit_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `processes_ibfk_1` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `processes_ibfk_2` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `program_issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(155) NOT NULL,
  `issue_source` enum('internal','external') NOT NULL,
  `description` text NOT NULL,
  `status` enum('draft','discarded','current') NOT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `program_issue_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_issue_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `program_issue_id` (`program_issue_id`),
  CONSTRAINT `program_issue_types_ibfk_1` FOREIGN KEY (`program_issue_id`) REFERENCES `program_issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `program_scopes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `status` enum('draft','discarded','current') NOT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `project_achievements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `expired` int(1) NOT NULL DEFAULT 0,
  `completion` int(3) NOT NULL,
  `project_id` int(11) NOT NULL,
  `task_order` int(3) NOT NULL DEFAULT 1,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `project_achievements_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `project_achievements_ibfk_4` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `project_expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` float NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `project_id` int(11) NOT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `project_expenses_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `project_expenses_ibfk_2` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `project_overtime_graphs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `current_budget` int(11) NOT NULL,
  `budget` int(11) NOT NULL,
  `timestamp` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `project_overtime_graphs_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `project_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `goal` text NOT NULL,
  `start` date NOT NULL,
  `deadline` date NOT NULL,
  `plan_budget` int(11) DEFAULT NULL,
  `project_status_id` int(11) DEFAULT NULL,
  `over_budget` int(1) NOT NULL DEFAULT 0,
  `expired_tasks` int(1) NOT NULL DEFAULT 0,
  `expired` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_status_id` (`project_status_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`project_status_id`) REFERENCES `project_statuses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `projects_ibfk_3` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `projects_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `risk_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_projects_risks_projects` (`project_id`),
  KEY `FK_projects_risks_risks` (`risk_id`),
  CONSTRAINT `FK_projects_risks_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_projects_risks_risks` FOREIGN KEY (`risk_id`) REFERENCES `risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `projects_security_policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `security_policy_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_projects_security_policies_projects` (`project_id`),
  KEY `FK_projects_security_policies_security_policies` (`security_policy_id`),
  CONSTRAINT `FK_projects_security_policies_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_projects_security_policies_security_policies` FOREIGN KEY (`security_policy_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `projects_security_service_audit_improvements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `security_service_audit_improvement_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `security_service_audit_improvement_id` (`security_service_audit_improvement_id`),
  CONSTRAINT `projects_security_service_audit_improvements_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projects_security_service_audit_improvements_ibfk_2` FOREIGN KEY (`security_service_audit_improvement_id`) REFERENCES `security_service_audit_improvements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `objective` text NOT NULL,
  `security_service_type_id` int(11) DEFAULT NULL,
  `service_classification_id` int(11) DEFAULT NULL,
  `documentation_url` text NOT NULL,
  `audit_metric_description` text NOT NULL,
  `audit_success_criteria` text NOT NULL,
  `maintenance_metric_description` text NOT NULL,
  `opex` float NOT NULL,
  `capex` float NOT NULL,
  `resource_utilization` int(11) NOT NULL,
  `audits_all_done` int(1) NOT NULL,
  `audits_not_all_done` int(1) NOT NULL,
  `audits_last_missing` int(1) NOT NULL,
  `audits_last_passed` int(1) NOT NULL,
  `audits_improvements` int(1) NOT NULL,
  `audits_status` int(1) NOT NULL,
  `maintenances_all_done` int(1) NOT NULL,
  `maintenances_not_all_done` int(1) NOT NULL,
  `maintenances_last_missing` int(1) NOT NULL,
  `maintenances_last_passed` int(1) NOT NULL,
  `ongoing_security_incident` int(1) NOT NULL DEFAULT 0,
  `security_incident_open_count` int(11) NOT NULL,
  `control_with_issues` int(1) NOT NULL DEFAULT 0,
  `ongoing_corrective_actions` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `security_service_type_id` (`security_service_type_id`),
  KEY `service_classification_id` (`service_classification_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `security_services_ibfk_1` FOREIGN KEY (`security_service_type_id`) REFERENCES `security_service_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `security_services_ibfk_2` FOREIGN KEY (`service_classification_id`) REFERENCES `service_classifications` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `security_services_ibfk_4` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `projects_security_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `security_service_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_projects_security_services_projects` (`project_id`),
  KEY `FK_projects_security_services_security_services` (`security_service_id`),
  CONSTRAINT `FK_projects_security_services_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_projects_security_services_security_services` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `projects_third_party_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `third_party_risk_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_projects_third_party_risks_projects` (`project_id`),
  KEY `FK_projects_third_party_risks_third_party_risks` (`third_party_risk_id`),
  CONSTRAINT `FK_projects_third_party_risks_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_projects_third_party_risks_third_party_risks` FOREIGN KEY (`third_party_risk_id`) REFERENCES `third_party_risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` mediumtext DEFAULT NULL,
  `queue_id` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` int(4) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(150) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `planned_date` date DEFAULT NULL,
  `actual_date` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `completed` int(1) NOT NULL DEFAULT 0,
  `version` varchar(150) DEFAULT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_reviews_users` (`user_id`),
  CONSTRAINT `FK_reviews_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_appetite_thresholds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_appetite_id` int(11) NOT NULL,
  `title` varchar(155) NOT NULL,
  `description` text NOT NULL,
  `color` text NOT NULL,
  `type` int(3) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_appetite_id` (`risk_appetite_id`),
  CONSTRAINT `risk_appetite_thresholds_ibfk_1` FOREIGN KEY (`risk_appetite_id`) REFERENCES `risk_appetites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_appetite_threshold_risk_classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_appetite_threshold_id` int(11) NOT NULL,
  `risk_classification_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_appetite_threshold_id` (`risk_appetite_threshold_id`),
  KEY `risk_classification_id` (`risk_classification_id`),
  CONSTRAINT `risk_appetite_threshold_risk_classifications_ibfk_1` FOREIGN KEY (`risk_appetite_threshold_id`) REFERENCES `risk_appetite_thresholds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `risk_appetite_threshold_risk_classifications_ibfk_2` FOREIGN KEY (`risk_classification_id`) REFERENCES `risk_classifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_appetite_thresholds_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `risk_appetite_threshold_id` int(11) NOT NULL,
  `type` int(3) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_appetite_threshold_id` (`risk_appetite_threshold_id`),
  CONSTRAINT `risk_appetite_thresholds_risks_ibfk_1` FOREIGN KEY (`risk_appetite_threshold_id`) REFERENCES `risk_appetite_thresholds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_appetites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `method` int(11) NOT NULL DEFAULT 0,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `risk_appetites_risk_classification_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_appetite_id` int(11) NOT NULL,
  `risk_classification_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_appetite_id` (`risk_appetite_id`),
  KEY `risk_classification_type_id` (`risk_classification_type_id`),
  CONSTRAINT `risk_appetites_risk_classification_types_ibfk_1` FOREIGN KEY (`risk_appetite_id`) REFERENCES `risk_appetites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `risk_appetites_risk_classification_types_ibfk_2` FOREIGN KEY (`risk_classification_type_id`) REFERENCES `risk_classification_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_calculations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `risk_calculation_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_calculation_id` int(11) NOT NULL,
  `field` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_calculation_id` (`risk_calculation_id`),
  CONSTRAINT `risk_calculation_values_ibfk_1` FOREIGN KEY (`risk_calculation_id`) REFERENCES `risk_calculations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_classification_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `risk_classification_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `criteria` text NOT NULL,
  `value` float DEFAULT NULL,
  `risk_classification_type_id` int(11) DEFAULT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_classification_type_id` (`risk_classification_type_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `risk_classifications_ibfk_1` FOREIGN KEY (`risk_classification_type_id`) REFERENCES `risk_classification_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `risk_classifications_ibfk_2` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_classifications_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_classification_id` int(11) NOT NULL,
  `risk_id` int(11) NOT NULL,
  `type` int(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `risk_classification_id` (`risk_classification_id`),
  KEY `risk_id` (`risk_id`),
  CONSTRAINT `risk_classifications_risks_ibfk_1` FOREIGN KEY (`risk_classification_id`) REFERENCES `risk_classifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `risk_classifications_risks_ibfk_2` FOREIGN KEY (`risk_id`) REFERENCES `risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_classifications_third_party_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_classification_id` int(11) NOT NULL,
  `third_party_risk_id` int(11) NOT NULL,
  `type` int(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `risk_classification_id` (`risk_classification_id`),
  KEY `third_party_risk_id` (`third_party_risk_id`),
  CONSTRAINT `risk_classifications_third_party_risks_ibfk_1` FOREIGN KEY (`risk_classification_id`) REFERENCES `risk_classifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `risk_classifications_third_party_risks_ibfk_2` FOREIGN KEY (`third_party_risk_id`) REFERENCES `third_party_risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_exceptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `expiration` date NOT NULL,
  `expired` int(1) NOT NULL DEFAULT 0,
  `closure_date_toggle` tinyint(1) NOT NULL DEFAULT 1,
  `closure_date` date DEFAULT NULL,
  `status` int(1) NOT NULL COMMENT '0-closed, 1-open',
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `risk_exceptions_ibfk_1` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `threats` text NOT NULL,
  `vulnerabilities` text NOT NULL,
  `description` text DEFAULT NULL,
  `residual_score` int(11) NOT NULL,
  `risk_score` float DEFAULT NULL,
  `risk_score_formula` text NOT NULL,
  `residual_risk` float NOT NULL,
  `residual_risk_formula` text NOT NULL,
  `review` date NOT NULL,
  `expired` int(1) NOT NULL DEFAULT 0,
  `exceptions_issues` int(1) NOT NULL DEFAULT 0,
  `controls_issues` int(1) NOT NULL DEFAULT 0,
  `control_in_design` int(1) NOT NULL DEFAULT 0,
  `expired_reviews` int(1) NOT NULL DEFAULT 0,
  `risk_above_appetite` int(1) NOT NULL DEFAULT 0,
  `risk_mitigation_strategy_id` int(11) DEFAULT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_mitigation_strategy_id` (`risk_mitigation_strategy_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `risks_ibfk_2` FOREIGN KEY (`risk_mitigation_strategy_id`) REFERENCES `risk_mitigation_strategies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `risks_ibfk_4` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_exceptions_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_id` int(11) NOT NULL,
  `risk_exception_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_id` (`risk_id`),
  KEY `risk_exception_id` (`risk_exception_id`),
  CONSTRAINT `risk_exceptions_risks_ibfk_1` FOREIGN KEY (`risk_id`) REFERENCES `risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `risk_exceptions_risks_ibfk_2` FOREIGN KEY (`risk_exception_id`) REFERENCES `risk_exceptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_exceptions_third_party_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_exception_id` int(11) NOT NULL,
  `third_party_risk_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risk_mitigation_strategies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


CREATE TABLE `risk_overtime_graphs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_count` int(11) NOT NULL,
  `risk_score` int(11) NOT NULL,
  `residual_score` int(11) NOT NULL,
  `timestamp` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risks_security_incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_id` int(11) NOT NULL,
  `security_incident_id` int(11) NOT NULL,
  `risk_type` enum('asset-risk','third-party-risk','business-risk') NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_incident_id` (`security_incident_id`),
  CONSTRAINT `risks_security_incidents_ibfk_2` FOREIGN KEY (`security_incident_id`) REFERENCES `security_incidents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risks_security_policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_id` int(11) NOT NULL,
  `security_policy_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'treatment' COMMENT '''treatment'',''incident''',
  `risk_type` enum('asset-risk','third-party-risk','business-risk') NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_policy_id` (`security_policy_id`),
  CONSTRAINT `risks_security_policies_ibfk_2` FOREIGN KEY (`security_policy_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risks_security_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_id` int(11) NOT NULL,
  `security_service_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_id` (`risk_id`),
  KEY `security_service_id` (`security_service_id`),
  CONSTRAINT `risks_security_services_ibfk_1` FOREIGN KEY (`risk_id`) REFERENCES `risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `risks_security_services_ibfk_2` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risks_threats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_id` int(11) NOT NULL,
  `threat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_id` (`risk_id`),
  KEY `threat_id` (`threat_id`),
  CONSTRAINT `risks_threats_ibfk_1` FOREIGN KEY (`risk_id`) REFERENCES `risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `risks_threats_ibfk_2` FOREIGN KEY (`threat_id`) REFERENCES `threats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `risks_vulnerabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_id` int(11) NOT NULL,
  `vulnerability_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_id` (`risk_id`),
  KEY `vulnerability_id` (`vulnerability_id`),
  CONSTRAINT `risks_vulnerabilities_ibfk_1` FOREIGN KEY (`risk_id`) REFERENCES `risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `risks_vulnerabilities_ibfk_2` FOREIGN KEY (`vulnerability_id`) REFERENCES `vulnerabilities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `schema_migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;


CREATE TABLE `scopes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ciso_role_id` int(11) DEFAULT NULL,
  `ciso_deputy_id` int(11) DEFAULT NULL,
  `board_representative_id` int(11) DEFAULT NULL,
  `board_representative_deputy_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ciso_role_id` (`ciso_role_id`),
  KEY `ciso_deputy_id` (`ciso_deputy_id`),
  KEY `board_representative_id` (`board_representative_id`),
  KEY `board_representative_deputy_id` (`board_representative_deputy_id`),
  CONSTRAINT `scopes_ibfk_1` FOREIGN KEY (`ciso_role_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `scopes_ibfk_2` FOREIGN KEY (`ciso_deputy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `scopes_ibfk_3` FOREIGN KEY (`board_representative_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `scopes_ibfk_4` FOREIGN KEY (`board_representative_deputy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_incident_classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_incident_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_incident_id` (`security_incident_id`),
  CONSTRAINT `security_incident_classifications_ibfk_1` FOREIGN KEY (`security_incident_id`) REFERENCES `security_incidents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_incident_stages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_incident_stages_security_incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_incident_stage_id` int(11) NOT NULL,
  `security_incident_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_security_incident_stages_security_incidents` (`security_incident_id`),
  KEY `FK_security_incident_stages_security_incident_stages` (`security_incident_stage_id`),
  CONSTRAINT `FK_security_incident_stages_security_incident_stages` FOREIGN KEY (`security_incident_stage_id`) REFERENCES `security_incident_stages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_security_incident_stages_security_incidents` FOREIGN KEY (`security_incident_id`) REFERENCES `security_incidents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_incident_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `security_incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `reporter` varchar(100) NOT NULL,
  `victim` varchar(100) NOT NULL,
  `open_date` date NOT NULL,
  `closure_date` date NOT NULL,
  `expired` int(1) NOT NULL DEFAULT 0,
  `type` enum('event','possible-incident','incident') NOT NULL,
  `security_incident_status_id` int(11) DEFAULT NULL,
  `auto_close_incident` int(1) DEFAULT 0,
  `security_incident_classification_id` int(11) DEFAULT NULL,
  `lifecycle_incomplete` int(11) DEFAULT 1,
  `ongoing_incident` int(1) NOT NULL DEFAULT 0,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `security_incident_status_id` (`security_incident_status_id`),
  KEY `security_incident_classification_id` (`security_incident_classification_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `security_incidents_ibfk_1` FOREIGN KEY (`security_incident_status_id`) REFERENCES `security_incident_statuses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `security_incidents_ibfk_3` FOREIGN KEY (`security_incident_classification_id`) REFERENCES `security_incident_classifications` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `security_incidents_ibfk_6` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_incidents_security_service_audit_improvements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_incident_id` int(11) NOT NULL,
  `security_service_audit_improvement_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_incident_id` (`security_incident_id`),
  KEY `security_service_audit_improvement_id` (`security_service_audit_improvement_id`),
  CONSTRAINT `security_incidents_security_service_audit_improvements_ibfk_1` FOREIGN KEY (`security_incident_id`) REFERENCES `security_incidents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `security_incidents_security_service_audit_improvements_ibfk_2` FOREIGN KEY (`security_service_audit_improvement_id`) REFERENCES `security_service_audit_improvements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_incidents_security_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_incident_id` int(11) NOT NULL,
  `security_service_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_incident_id` (`security_incident_id`),
  KEY `security_service_id` (`security_service_id`),
  CONSTRAINT `security_incidents_security_services_ibfk_1` FOREIGN KEY (`security_incident_id`) REFERENCES `security_incidents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `security_incidents_security_services_ibfk_2` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_incidents_third_parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_incident_id` int(11) NOT NULL,
  `third_party_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_incident_id` (`security_incident_id`),
  KEY `third_party_id` (`third_party_id`),
  CONSTRAINT `security_incidents_third_parties_ibfk_1` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `security_incidents_third_parties_ibfk_2` FOREIGN KEY (`security_incident_id`) REFERENCES `security_incidents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `index` varchar(100) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `use_attachments` int(1) NOT NULL DEFAULT 0,
  `document_type` enum('policy','standard','procedure') NOT NULL,
  `security_policy_document_type_id` int(11) DEFAULT NULL,
  `version` varchar(50) NOT NULL,
  `published_date` date NOT NULL,
  `next_review_date` date NOT NULL,
  `permission` enum('public','private','logged') NOT NULL,
  `ldap_connector_id` int(11) DEFAULT NULL,
  `asset_label_id` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0-draft, 1-released',
  `expired_reviews` int(1) NOT NULL DEFAULT 0,
  `hash` varchar(255) DEFAULT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  KEY `asset_label_id` (`asset_label_id`),
  KEY `ldap_connector_id` (`ldap_connector_id`),
  KEY `security_policy_document_type_id` (`security_policy_document_type_id`),
  CONSTRAINT `security_policies_ibfk_1` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `security_policies_ibfk_3` FOREIGN KEY (`asset_label_id`) REFERENCES `asset_labels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `security_policies_ibfk_4` FOREIGN KEY (`ldap_connector_id`) REFERENCES `ldap_connectors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `security_policies_ibfk_5` FOREIGN KEY (`security_policy_document_type_id`) REFERENCES `security_policy_document_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_policies_related` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_policy_id` int(11) NOT NULL,
  `related_document_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_policy_id` (`security_policy_id`),
  KEY `related_document_id` (`related_document_id`),
  CONSTRAINT `security_policies_related_ibfk_1` FOREIGN KEY (`security_policy_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `security_policies_related_ibfk_2` FOREIGN KEY (`related_document_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_policies_security_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_policy_id` int(11) NOT NULL,
  `security_service_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_policy_id` (`security_policy_id`),
  KEY `security_service_id` (`security_service_id`),
  CONSTRAINT `security_policies_security_services_ibfk_1` FOREIGN KEY (`security_policy_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `security_policies_security_services_ibfk_2` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_policy_document_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `editable` int(11) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `security_policy_ldap_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_policy_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_policy_id` (`security_policy_id`),
  CONSTRAINT `security_policy_ldap_groups_ibfk_1` FOREIGN KEY (`security_policy_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_policy_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_policy_id` int(11) NOT NULL,
  `planned_date` date NOT NULL,
  `actual_review_date` date DEFAULT NULL,
  `reviewer_id` int(11) DEFAULT NULL,
  `comments` text NOT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `reviewer_id` (`reviewer_id`),
  KEY `security_policy_id` (`security_policy_id`),
  CONSTRAINT `security_policy_reviews_ibfk_1` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `security_policy_reviews_ibfk_2` FOREIGN KEY (`security_policy_id`) REFERENCES `security_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_service_audit_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_service_id` int(11) NOT NULL,
  `day` int(2) NOT NULL,
  `month` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_service_id` (`security_service_id`),
  CONSTRAINT `security_service_audit_dates_ibfk_1` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_service_audits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_service_id` int(11) NOT NULL,
  `audit_metric_description` text NOT NULL,
  `audit_success_criteria` text NOT NULL,
  `result` int(1) DEFAULT NULL COMMENT 'null-not defined, 0-fail, 1-pass',
  `result_description` text NOT NULL,
  `planned_date` date NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `security_service_id` (`security_service_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `security_service_audits_ibfk_1` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `security_service_audits_ibfk_3` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_service_audit_improvements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_service_audit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_service_audit_id` (`security_service_audit_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `security_service_audit_improvements_ibfk_1` FOREIGN KEY (`security_service_audit_id`) REFERENCES `security_service_audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `security_service_audit_improvements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_service_classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_service_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_service_id` (`security_service_id`),
  CONSTRAINT `security_service_classifications_ibfk_1` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_service_maintenance_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_service_id` int(11) NOT NULL,
  `day` int(2) NOT NULL,
  `month` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_service_id` (`security_service_id`),
  CONSTRAINT `security_service_maintenance_dates_ibfk_1` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_service_maintenances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_service_id` int(11) NOT NULL,
  `task` text NOT NULL,
  `task_conclusion` text NOT NULL,
  `planned_date` date NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `result` int(1) DEFAULT NULL COMMENT 'null-not defined, 0-fail, 1-pass',
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  KEY `security_service_id` (`security_service_id`),
  CONSTRAINT `security_service_maintenances_ibfk_1` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `security_service_maintenances_ibfk_3` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_service_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


CREATE TABLE `security_services_service_contracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_service_id` int(11) NOT NULL,
  `service_contract_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_service_id` (`security_service_id`),
  KEY `service_contract_id` (`service_contract_id`),
  CONSTRAINT `security_services_service_contracts_ibfk_1` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `security_services_service_contracts_ibfk_2` FOREIGN KEY (`service_contract_id`) REFERENCES `service_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `security_services_third_party_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_service_id` int(11) NOT NULL,
  `third_party_risk_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `security_service_id` (`security_service_id`),
  KEY `third_party_risk_id` (`third_party_risk_id`),
  CONSTRAINT `security_services_third_party_risks_ibfk_1` FOREIGN KEY (`third_party_risk_id`) REFERENCES `third_party_risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `security_services_third_party_risks_ibfk_2` FOREIGN KEY (`security_service_id`) REFERENCES `security_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `service_classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `service_classifications_ibfk_1` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `service_contracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `third_party_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `value` int(11) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `expired` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  KEY `third_party_id` (`third_party_id`),
  CONSTRAINT `service_contracts_ibfk_1` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `service_contracts_ibfk_2` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `setting_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) NOT NULL,
  `parent_slug` varchar(50) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `icon_code` varchar(150) DEFAULT NULL,
  `notes` varchar(250) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `hidden` tinyint(4) DEFAULT 0,
  `order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `FK_setting_groups_setting_groups` (`parent_slug`),
  CONSTRAINT `FK_setting_groups_setting_groups` FOREIGN KEY (`parent_slug`) REFERENCES `setting_groups` (`slug`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;


CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `variable` varchar(100) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `values` varchar(255) DEFAULT NULL,
  `type` enum('text','number','select','multiselect','checkbox','textarea','password') NOT NULL DEFAULT 'text',
  `options` varchar(150) DEFAULT NULL,
  `hidden` int(1) NOT NULL DEFAULT 0,
  `required` int(1) NOT NULL DEFAULT 0,
  `setting_group_slug` varchar(50) DEFAULT NULL,
  `setting_type` enum('constant','config') DEFAULT 'constant',
  `order` int(11) NOT NULL DEFAULT 0,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_settings_setting_groups` (`setting_group_slug`),
  CONSTRAINT `FK_settings_setting_groups` FOREIGN KEY (`setting_group_slug`) REFERENCES `setting_groups` (`slug`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;


CREATE TABLE `status_triggers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `config_name` varchar(155) NOT NULL,
  `column_name` varchar(155) NOT NULL,
  `value` varchar(155) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `suggestions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `suggestion` varchar(255) NOT NULL,
  `model` varchar(155) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL DEFAULT '',
  `foreign_key` int(11) DEFAULT NULL,
  `action` int(11) NOT NULL,
  `result` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `user_model` varchar(255) DEFAULT '',
  `user_id` int(11) DEFAULT NULL,
  `ip` varchar(255) NOT NULL DEFAULT '',
  `uri` text NOT NULL,
  `request_id` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `system_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(70) NOT NULL,
  `model_nice` varchar(70) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `item` varchar(100) NOT NULL,
  `notes` text DEFAULT NULL,
  `type` int(1) NOT NULL COMMENT '1-insert, 2-update, 3-delete, 4-login, 5-wrong login',
  `workflow_status` int(1) DEFAULT NULL,
  `workflow_comment` text DEFAULT NULL,
  `ip` varchar(45) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `system_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(250) NOT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `team_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role` varchar(155) NOT NULL,
  `responsibilities` text NOT NULL,
  `competences` text NOT NULL,
  `status` enum('active','discarded') NOT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `team_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `third_parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `third_party_type_id` int(11) DEFAULT NULL,
  `security_incident_count` int(11) NOT NULL,
  `security_incident_open_count` int(11) NOT NULL,
  `service_contract_count` int(11) NOT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `_hidden` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `third_party_type_id` (`third_party_type_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `third_parties_ibfk_1` FOREIGN KEY (`third_party_type_id`) REFERENCES `third_party_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `third_parties_ibfk_2` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `third_parties_third_party_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `third_party_risk_id` int(11) NOT NULL,
  `third_party_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `third_party_risk_id` (`third_party_risk_id`),
  KEY `third_party_id` (`third_party_id`),
  CONSTRAINT `third_parties_third_party_risks_ibfk_1` FOREIGN KEY (`third_party_risk_id`) REFERENCES `third_party_risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `third_parties_third_party_risks_ibfk_2` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `third_parties_vendor_assessments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `third_party_id` int(11) NOT NULL,
  `vendor_assessment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `third_party_id` (`third_party_id`),
  KEY `vendor_assessment_id` (`vendor_assessment_id`),
  CONSTRAINT `third_parties_vendor_assessments_ibfk_1` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `third_parties_vendor_assessments_ibfk_2` FOREIGN KEY (`vendor_assessment_id`) REFERENCES `vendor_assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `third_party_audit_overtime_graphs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `third_party_id` int(11) NOT NULL,
  `open` int(3) DEFAULT NULL,
  `closed` int(3) DEFAULT NULL,
  `expired` int(3) DEFAULT NULL,
  `no_evidence` int(3) NOT NULL,
  `waiting_evidence` int(3) NOT NULL,
  `provided_evidence` int(3) NOT NULL,
  `timestamp` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `third_party_id` (`third_party_id`),
  CONSTRAINT `third_party_audit_overtime_graphs_ibfk_1` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `third_party_incident_overtime_graphs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `third_party_id` int(11) NOT NULL,
  `security_incident_count` int(11) NOT NULL,
  `timestamp` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `third_party_id` (`third_party_id`),
  CONSTRAINT `third_party_incident_overtime_graphs_ibfk_1` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `third_party_overtime_graphs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `third_party_id` int(11) NOT NULL,
  `no_controls` int(3) NOT NULL,
  `failed_controls` int(3) NOT NULL,
  `ok_controls` int(3) NOT NULL,
  `average_effectiveness` int(3) NOT NULL,
  `timestamp` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `third_party_id` (`third_party_id`),
  CONSTRAINT `third_party_overtime_graphs_ibfk_1` FOREIGN KEY (`third_party_id`) REFERENCES `third_parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `third_party_risk_overtime_graphs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risk_count` int(11) NOT NULL,
  `risk_score` int(11) NOT NULL,
  `residual_score` int(11) NOT NULL,
  `timestamp` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `third_party_risks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `shared_information` text NOT NULL,
  `controlled` text NOT NULL,
  `threats` text NOT NULL,
  `vulnerabilities` text NOT NULL,
  `description` text DEFAULT NULL,
  `residual_score` int(11) NOT NULL,
  `risk_score` float DEFAULT NULL,
  `risk_score_formula` text NOT NULL,
  `residual_risk` float NOT NULL,
  `residual_risk_formula` text NOT NULL,
  `review` date NOT NULL,
  `expired` int(1) NOT NULL DEFAULT 0,
  `exceptions_issues` int(1) NOT NULL DEFAULT 0,
  `controls_issues` int(1) NOT NULL DEFAULT 0,
  `control_in_design` int(1) NOT NULL DEFAULT 0,
  `expired_reviews` int(1) NOT NULL DEFAULT 0,
  `risk_above_appetite` int(1) NOT NULL DEFAULT 0,
  `risk_mitigation_strategy_id` int(11) DEFAULT NULL,
  `workflow_owner_id` int(11) DEFAULT NULL,
  `workflow_status` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_mitigation_strategy_id` (`risk_mitigation_strategy_id`),
  KEY `workflow_owner_id` (`workflow_owner_id`),
  CONSTRAINT `third_party_risks_ibfk_2` FOREIGN KEY (`risk_mitigation_strategy_id`) REFERENCES `risk_mitigation_strategies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `third_party_risks_ibfk_4` FOREIGN KEY (`workflow_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `third_party_risks_threats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `third_party_risk_id` int(11) NOT NULL,
  `threat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `third_party_risk_id` (`third_party_risk_id`),
  KEY `threat_id` (`threat_id`),
  CONSTRAINT `third_party_risks_threats_ibfk_1` FOREIGN KEY (`third_party_risk_id`) REFERENCES `third_party_risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `third_party_risks_threats_ibfk_2` FOREIGN KEY (`threat_id`) REFERENCES `threats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `third_party_risks_vulnerabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `third_party_risk_id` int(11) NOT NULL,
  `vulnerability_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `third_party_risk_id` (`third_party_risk_id`),
  KEY `vulnerability_id` (`vulnerability_id`),
  CONSTRAINT `third_party_risks_vulnerabilities_ibfk_1` FOREIGN KEY (`third_party_risk_id`) REFERENCES `third_party_risks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `third_party_risks_vulnerabilities_ibfk_2` FOREIGN KEY (`vulnerability_id`) REFERENCES `vulnerabilities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `third_party_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `threats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;


CREATE TABLE `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `hash` varchar(50) DEFAULT NULL,
  `data` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `user_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `until` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_bans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `user_fields_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `field` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `user_fields_groups_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `surname` varchar(45) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `login` varchar(45) NOT NULL,
  `password` varchar(100) NOT NULL,
  `language` varchar(10) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0-non active, 1-active',
  `blocked` int(1) NOT NULL DEFAULT 0,
  `local_account` int(3) DEFAULT 1,
  `api_allow` int(2) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `user_fields_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `field` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_fields_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `users_vendor_assessment_findings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `vendor_assessment_finding_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `vendor_assessment_finding_id` (`vendor_assessment_finding_id`),
  CONSTRAINT `users_vendor_assessment_findings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_vendor_assessment_findings_ibfk_2` FOREIGN KEY (`vendor_assessment_finding_id`) REFERENCES `vendor_assessment_findings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users_vendor_assessments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `vendor_assessment_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `vendor_assessment_id` (`vendor_assessment_id`),
  CONSTRAINT `users_vendor_assessments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_vendor_assessments_ibfk_2` FOREIGN KEY (`vendor_assessment_id`) REFERENCES `vendor_assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_assessment_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_assessment_id` int(11) NOT NULL,
  `vendor_assessment_question_id` int(11) NOT NULL,
  `vendor_assessment_option_id` int(11) DEFAULT NULL,
  `answer` text NOT NULL,
  `completed` int(11) NOT NULL DEFAULT 0,
  `locked` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_assessment_id` (`vendor_assessment_id`),
  KEY `vendor_assessment_option_id` (`vendor_assessment_option_id`),
  KEY `vendor_assessment_question_id` (`vendor_assessment_question_id`),
  CONSTRAINT `vendor_assessment_feedbacks_ibfk_1` FOREIGN KEY (`vendor_assessment_id`) REFERENCES `vendor_assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_assessment_feedbacks_ibfk_2` FOREIGN KEY (`vendor_assessment_option_id`) REFERENCES `vendor_assessment_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_assessment_feedbacks_ibfk_3` FOREIGN KEY (`vendor_assessment_question_id`) REFERENCES `vendor_assessment_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_assessment_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_assessment_findings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_assessment_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `deadline` date NOT NULL,
  `close_date` date DEFAULT NULL,
  `auto_close_date` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL,
  `expired` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_assessment_id` (`vendor_assessment_id`),
  CONSTRAINT `vendor_assessment_findings_ibfk_1` FOREIGN KEY (`vendor_assessment_id`) REFERENCES `vendor_assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_assessment_findings_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_assessment_finding_id` int(11) NOT NULL,
  `vendor_assessment_question_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_assessment_finding_id` (`vendor_assessment_finding_id`),
  KEY `vendor_assessment_question_id` (`vendor_assessment_question_id`),
  CONSTRAINT `vendor_assessment_findings_questions_ibfk_1` FOREIGN KEY (`vendor_assessment_finding_id`) REFERENCES `vendor_assessment_findings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_assessment_findings_questions_ibfk_2` FOREIGN KEY (`vendor_assessment_question_id`) REFERENCES `vendor_assessment_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_assessment_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_assessment_question_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `warning` text NOT NULL,
  `weight` decimal(11,4) NOT NULL DEFAULT 1.0000,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_assessment_question_id` (`vendor_assessment_question_id`),
  CONSTRAINT `vendor_assessment_options_ibfk_1` FOREIGN KEY (`vendor_assessment_question_id`) REFERENCES `vendor_assessment_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_assessment_questionnaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `vendor_assessment_file_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_assessment_file_id` (`vendor_assessment_file_id`),
  CONSTRAINT `vendor_assessment_questionnaires_ibfk_1` FOREIGN KEY (`vendor_assessment_file_id`) REFERENCES `vendor_assessment_files` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_assessment_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_assessment_questionnaire_id` int(11) NOT NULL,
  `chapter_number` varchar(255) NOT NULL DEFAULT '',
  `chapter_title` varchar(255) NOT NULL DEFAULT '',
  `chapter_description` text NOT NULL,
  `number` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `answer_type` int(11) NOT NULL,
  `score` decimal(11,4) NOT NULL,
  `widget_type` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_assessment_questionnaire_id` (`vendor_assessment_questionnaire_id`),
  CONSTRAINT `vendor_assessment_questions_ibfk_1` FOREIGN KEY (`vendor_assessment_questionnaire_id`) REFERENCES `vendor_assessment_questionnaires` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_assessments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `hash` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `vendor_assessment_questionnaire_id` int(11) NOT NULL,
  `portal_title` varchar(255) NOT NULL DEFAULT '',
  `portal_description` text NOT NULL,
  `finding_download` int(11) NOT NULL,
  `questions_download` int(11) NOT NULL,
  `incomplete_submit` int(11) NOT NULL,
  `scheduling` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `recurrence` int(11) NOT NULL,
  `recurrence_period` int(11) NOT NULL,
  `recurrence_auto_load` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `submited` int(11) NOT NULL DEFAULT 0,
  `submit_date` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_assessment_questionnaire_id` (`vendor_assessment_questionnaire_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `vendor_assessments_ibfk_1` FOREIGN KEY (`vendor_assessment_questionnaire_id`) REFERENCES `vendor_assessment_questionnaires` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `vendor_assessments_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `vendor_assessments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `visualisation_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) NOT NULL,
  `status` int(3) NOT NULL DEFAULT 0,
  `order` int(3) NOT NULL DEFAULT 999,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;


CREATE TABLE `visualisation_settings_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visualisation_setting_id` int(11) NOT NULL,
  `aros_acos_id` int(11) DEFAULT NULL,
  `user_fields_group_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aros_acos_id` (`aros_acos_id`),
  KEY `user_fields_group_id` (`user_fields_group_id`),
  KEY `visualisation_setting_id` (`visualisation_setting_id`),
  CONSTRAINT `visualisation_settings_groups_ibfk_1` FOREIGN KEY (`aros_acos_id`) REFERENCES `aros_acos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `visualisation_settings_groups_ibfk_2` FOREIGN KEY (`user_fields_group_id`) REFERENCES `user_fields_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `visualisation_settings_groups_ibfk_3` FOREIGN KEY (`visualisation_setting_id`) REFERENCES `visualisation_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `visualisation_settings_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visualisation_setting_id` int(11) NOT NULL,
  `aros_acos_id` int(11) DEFAULT NULL,
  `user_fields_user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aros_acos_id` (`aros_acos_id`),
  KEY `visualisation_setting_id` (`visualisation_setting_id`),
  KEY `user_fields_user_id` (`user_fields_user_id`),
  CONSTRAINT `visualisation_settings_users_ibfk_3` FOREIGN KEY (`visualisation_setting_id`) REFERENCES `visualisation_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `visualisation_settings_users_ibfk_4` FOREIGN KEY (`aros_acos_id`) REFERENCES `aros_acos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `visualisation_settings_users_ibfk_5` FOREIGN KEY (`user_fields_user_id`) REFERENCES `user_fields_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `visualisation_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(128) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `visualisation_share_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visualisation_share_id` int(11) NOT NULL,
  `aros_acos_id` int(11) DEFAULT NULL,
  `user_fields_group_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aros_acos_id` (`aros_acos_id`),
  KEY `user_fields_group_id` (`user_fields_group_id`),
  KEY `visualisation_share_id` (`visualisation_share_id`),
  CONSTRAINT `visualisation_share_groups_ibfk_1` FOREIGN KEY (`aros_acos_id`) REFERENCES `aros_acos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `visualisation_share_groups_ibfk_2` FOREIGN KEY (`user_fields_group_id`) REFERENCES `user_fields_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `visualisation_share_groups_ibfk_3` FOREIGN KEY (`visualisation_share_id`) REFERENCES `visualisation_share` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `visualisation_share_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visualisation_share_id` int(11) NOT NULL,
  `aros_acos_id` int(11) DEFAULT NULL,
  `user_fields_user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aros_acos_id` (`aros_acos_id`),
  KEY `user_fields_user_id` (`user_fields_user_id`),
  KEY `visualisation_share_id` (`visualisation_share_id`),
  CONSTRAINT `visualisation_share_users_ibfk_1` FOREIGN KEY (`aros_acos_id`) REFERENCES `aros_acos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `visualisation_share_users_ibfk_2` FOREIGN KEY (`user_fields_user_id`) REFERENCES `user_fields_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `visualisation_share_users_ibfk_3` FOREIGN KEY (`visualisation_share_id`) REFERENCES `visualisation_share` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `vulnerabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;


CREATE TABLE `wf_access_models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin` varchar(155) DEFAULT NULL,
  `name` varchar(155) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `wf_access_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(155) NOT NULL,
  `model` varchar(155) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `model` (`model`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `wf_accesses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wf_access_model` varchar(155) NOT NULL DEFAULT '',
  `wf_access_foreign_key` int(11) NOT NULL DEFAULT 0,
  `wf_access_type` varchar(155) NOT NULL DEFAULT '',
  `foreign_key` varchar(155) NOT NULL,
  `access` int(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `wf_access_model` (`wf_access_model`),
  KEY `wf_access_type` (`wf_access_type`),
  CONSTRAINT `wf_accesses_ibfk_1` FOREIGN KEY (`wf_access_model`) REFERENCES `wf_access_models` (`name`) ON UPDATE CASCADE,
  CONSTRAINT `wf_accesses_ibfk_2` FOREIGN KEY (`wf_access_type`) REFERENCES `wf_access_types` (`slug`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `wf_stages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) NOT NULL DEFAULT '',
  `wf_setting_id` int(11) NOT NULL,
  `name` varchar(155) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `stage_type` int(2) NOT NULL,
  `approval_method` int(2) NOT NULL,
  `timeout` int(5) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `model` (`model`),
  KEY `wf_setting_id` (`wf_setting_id`),
  CONSTRAINT `wf_stages_ibfk_1` FOREIGN KEY (`model`) REFERENCES `wf_settings` (`model`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_stages_ibfk_2` FOREIGN KEY (`wf_setting_id`) REFERENCES `wf_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `wf_instance_approvals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wf_instance_request_id` int(11) NOT NULL,
  `wf_stage_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wf_instance_request_id` (`wf_instance_request_id`),
  KEY `wf_stage_id` (`wf_stage_id`),
  CONSTRAINT `wf_instance_approvals_ibfk_1` FOREIGN KEY (`wf_instance_request_id`) REFERENCES `wf_instance_requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_instance_approvals_ibfk_2` FOREIGN KEY (`wf_stage_id`) REFERENCES `wf_stages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `wf_instances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `wf_stage_id` int(11) NOT NULL,
  `wf_stage_step_id` int(11) NOT NULL DEFAULT 0,
  `stage_init_date` datetime NOT NULL,
  `status` int(3) NOT NULL DEFAULT 1,
  `pending_requests` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `model` (`model`),
  KEY `wf_stage_id` (`wf_stage_id`),
  CONSTRAINT `wf_instances_ibfk_1` FOREIGN KEY (`model`) REFERENCES `wf_settings` (`model`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_instances_ibfk_2` FOREIGN KEY (`wf_stage_id`) REFERENCES `wf_stages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `wf_instance_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wf_instance_id` int(11) NOT NULL,
  `wf_stage_id` int(11) DEFAULT NULL,
  `type` int(3) NOT NULL,
  `message` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `wf_instance_id` (`wf_instance_id`),
  CONSTRAINT `wf_instance_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_instance_logs_ibfk_2` FOREIGN KEY (`wf_instance_id`) REFERENCES `wf_instances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `wf_stage_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wf_stage_id` int(11) NOT NULL,
  `wf_next_stage_id` int(11) NOT NULL,
  `step_type` int(3) NOT NULL,
  `notification_message` text DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wf_next_stage_id` (`wf_next_stage_id`),
  KEY `wf_stage_id` (`wf_stage_id`),
  CONSTRAINT `wf_stage_steps_ibfk_1` FOREIGN KEY (`wf_next_stage_id`) REFERENCES `wf_stages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_stage_steps_ibfk_2` FOREIGN KEY (`wf_stage_id`) REFERENCES `wf_stages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `wf_instance_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wf_instance_id` int(11) NOT NULL,
  `wf_stage_id` int(11) NOT NULL,
  `wf_stage_step_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT 1,
  `approvals_count` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `wf_instance_id` (`wf_instance_id`),
  KEY `wf_stage_id` (`wf_stage_id`),
  KEY `wf_stage_step_id` (`wf_stage_step_id`),
  CONSTRAINT `wf_instance_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `wf_instance_requests_ibfk_2` FOREIGN KEY (`wf_instance_id`) REFERENCES `wf_instances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_instance_requests_ibfk_3` FOREIGN KEY (`wf_stage_id`) REFERENCES `wf_stages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_instance_requests_ibfk_4` FOREIGN KEY (`wf_stage_step_id`) REFERENCES `wf_stage_steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `wf_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(155) NOT NULL,
  `name` varchar(155) NOT NULL,
  `description` text DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `model` (`model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `wf_stage_step_conditions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wf_stage_step_id` int(11) NOT NULL,
  `field` varchar(155) NOT NULL,
  `comparison_type` varchar(55) NOT NULL,
  `value` varchar(155) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wf_stage_step_id` (`wf_stage_step_id`),
  CONSTRAINT `wf_stage_step_conditions_ibfk_1` FOREIGN KEY (`wf_stage_step_id`) REFERENCES `wf_stage_steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workflow_acknowledgements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('edit','delete') NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `resolved` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `workflow_acknowledgements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workflow_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workflow_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `workflow_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workflows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `notifications` int(1) NOT NULL DEFAULT 1,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `workflows_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;


CREATE TABLE `workflows_all_approver_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workflow_id` int(11) NOT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_id` (`workflow_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `workflows_all_approver_items_ibfk_1` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `workflows_all_approver_items_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workflows_all_validator_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workflow_id` int(11) NOT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_id` (`workflow_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `workflows_all_validator_items_ibfk_1` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `workflows_all_validator_items_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workflows_approver_scopes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workflow_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `custom_identifier` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_id` (`workflow_id`),
  KEY `scope_id` (`user_id`),
  CONSTRAINT `workflows_approver_scopes_ibfk_1` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `workflows_approver_scopes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workflows_approvers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `workflow_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `workflow_id` (`workflow_id`),
  CONSTRAINT `workflows_approvers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `workflows_approvers_ibfk_2` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workflows_custom_approvers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workflow_id` int(11) NOT NULL,
  `custom_identifier` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workflows_custom_validators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workflow_id` int(11) NOT NULL,
  `custom_identifier` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workflows_validator_scopes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workflow_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `custom_identifier` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workflow_id` (`workflow_id`),
  KEY `scope_id` (`user_id`),
  CONSTRAINT `workflows_validator_scopes_ibfk_1` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `workflows_validator_scopes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `workflows_validators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `workflow_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `workflow_id` (`workflow_id`),
  CONSTRAINT `workflows_validators_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `workflows_validators_ibfk_2` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


LOCK TABLES `acos` WRITE;
ALTER TABLE `acos` DISABLE KEYS;
INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES 
	(1,NULL,NULL,NULL,'controllers',1,2350),
	(2,1,NULL,NULL,'Ajax',2,11),
	(3,2,NULL,NULL,'modalSidebarWidget',3,4),
	(4,2,NULL,NULL,'cancelAction',5,6),
	(5,1,NULL,NULL,'AssetClassifications',12,29),
	(7,5,NULL,NULL,'delete',13,14),
	(8,5,NULL,NULL,'add',15,16),
	(9,5,NULL,NULL,'edit',17,18),
	(10,5,NULL,NULL,'addClassificationType',19,20),
	(11,5,NULL,NULL,'cancelAction',21,22),
	(12,1,NULL,NULL,'AssetLabels',30,45),
	(13,12,NULL,NULL,'index',31,32),
	(14,12,NULL,NULL,'delete',33,34),
	(15,12,NULL,NULL,'add',35,36),
	(16,12,NULL,NULL,'edit',37,38),
	(17,12,NULL,NULL,'cancelAction',39,40),
	(18,1,NULL,NULL,'AssetMediaTypes',46,61),
	(19,18,NULL,NULL,'index',47,48),
	(20,18,NULL,NULL,'liveEdit',49,50),
	(21,18,NULL,NULL,'add',51,52),
	(22,18,NULL,NULL,'delete',53,54),
	(23,18,NULL,NULL,'cancelAction',55,56),
	(24,1,NULL,NULL,'Assets',62,85),
	(25,24,NULL,NULL,'index',63,64),
	(26,24,NULL,NULL,'delete',65,66),
	(27,24,NULL,NULL,'add',67,68),
	(28,24,NULL,NULL,'edit',69,70),
	(30,24,NULL,NULL,'exportPdf',71,72),
	(31,24,NULL,NULL,'cancelAction',73,74),
	(32,1,NULL,NULL,'Attachments',86,109),
	(33,32,NULL,NULL,'index',87,88),
	(34,32,NULL,NULL,'delete',89,90),
	(36,32,NULL,NULL,'add',91,92),
	(37,32,NULL,NULL,'addAjax',93,94),
	(38,32,NULL,NULL,'getList',95,96),
	(39,32,NULL,NULL,'download',97,98),
	(41,32,NULL,NULL,'cancelAction',99,100),
	(42,1,NULL,NULL,'Awareness',110,139),
	(43,42,NULL,NULL,'index',111,112),
	(44,42,NULL,NULL,'training',113,114),
	(45,42,NULL,NULL,'demo',115,116),
	(46,42,NULL,NULL,'video',117,118),
	(47,42,NULL,NULL,'questionnaire',119,120),
	(48,42,NULL,NULL,'results',121,122),
	(49,42,NULL,NULL,'login',123,124),
	(50,42,NULL,NULL,'logout',125,126),
	(54,42,NULL,NULL,'cancelAction',127,128),
	(55,1,NULL,NULL,'AwarenessPrograms',140,183),
	(56,55,NULL,NULL,'index',141,142),
	(57,55,NULL,NULL,'delete',143,144),
	(58,55,NULL,NULL,'add',145,146),
	(59,55,NULL,NULL,'edit',147,148),
	(60,55,NULL,NULL,'ldapGroups',149,150),
	(61,55,NULL,NULL,'ldapIgnoredUsers',151,152),
	(62,55,NULL,NULL,'deleteVideo',153,154),
	(63,55,NULL,NULL,'deleteQuestionnaire',155,156),
	(64,55,NULL,NULL,'start',157,158),
	(65,55,NULL,NULL,'stop',159,160),
	(66,55,NULL,NULL,'demo',161,162),
	(67,55,NULL,NULL,'clean',163,164),
	(68,55,NULL,NULL,'initEmailFromComponent',165,166),
	(70,55,NULL,NULL,'cancelAction',167,168),
	(71,1,NULL,NULL,'BackupRestore',184,197),
	(75,1,NULL,NULL,'BusinessContinuities',198,227),
	(76,75,NULL,NULL,'index',199,200),
	(77,75,NULL,NULL,'delete',201,202),
	(78,75,NULL,NULL,'add',203,204),
	(79,75,NULL,NULL,'edit',205,206),
	(81,75,NULL,NULL,'getThreatsVulnerabilities',207,208),
	(82,75,NULL,NULL,'exportPdf',209,210),
	(83,75,NULL,NULL,'cancelAction',211,212),
	(84,1,NULL,NULL,'BusinessContinuityPlanAuditImprovements',228,243),
	(85,84,NULL,NULL,'delete',229,230),
	(86,84,NULL,NULL,'add',231,232),
	(87,84,NULL,NULL,'edit',233,234),
	(88,84,NULL,NULL,'cancelAction',235,236),
	(89,1,NULL,NULL,'BusinessContinuityPlanAudits',244,263),
	(90,89,NULL,NULL,'index',245,246),
	(91,89,NULL,NULL,'delete',247,248),
	(92,89,NULL,NULL,'edit',249,250),
	(93,89,NULL,NULL,'cancelAction',251,252),
	(94,1,NULL,NULL,'BusinessContinuityPlans',264,297),
	(95,94,NULL,NULL,'index',265,266),
	(96,94,NULL,NULL,'delete',267,268),
	(97,94,NULL,NULL,'acknowledge',269,270),
	(98,94,NULL,NULL,'acknowledgeItem',271,272),
	(99,94,NULL,NULL,'add',273,274),
	(100,94,NULL,NULL,'edit',275,276),
	(102,94,NULL,NULL,'auditCalendarFormEntry',277,278),
	(103,94,NULL,NULL,'export',279,280),
	(104,94,NULL,NULL,'exportAudits',281,282),
	(105,94,NULL,NULL,'exportTask',283,284),
	(106,94,NULL,NULL,'exportPdf',285,286),
	(107,94,NULL,NULL,'cancelAction',287,288),
	(108,1,NULL,NULL,'BusinessContinuityTasks',298,315),
	(109,108,NULL,NULL,'delete',299,300),
	(110,108,NULL,NULL,'add',301,302),
	(111,108,NULL,NULL,'edit',303,304),
	(112,108,NULL,NULL,'cancelAction',305,306),
	(113,1,NULL,NULL,'BusinessUnits',316,335),
	(114,113,NULL,NULL,'index',317,318),
	(115,113,NULL,NULL,'delete',319,320),
	(116,113,NULL,NULL,'add',321,322),
	(117,113,NULL,NULL,'edit',323,324),
	(118,113,NULL,NULL,'cancelAction',325,326),
	(119,1,NULL,NULL,'Comments',336,359),
	(120,119,NULL,NULL,'index',337,338),
	(121,119,NULL,NULL,'delete',339,340),
	(122,119,NULL,NULL,'add',341,342),
	(123,119,NULL,NULL,'addAjax',343,344),
	(124,119,NULL,NULL,'edit',345,346),
	(125,119,NULL,NULL,'listComments',347,348),
	(127,119,NULL,NULL,'getIndexUrlFromComponent',349,350),
	(128,119,NULL,NULL,'initEmailFromComponent',351,352),
	(129,119,NULL,NULL,'cancelAction',353,354),
	(130,1,NULL,NULL,'ComplianceAuditFeedbacks',360,377),
	(131,130,NULL,NULL,'index',361,362),
	(132,130,NULL,NULL,'delete',363,364),
	(133,130,NULL,NULL,'add',365,366),
	(134,130,NULL,NULL,'edit',367,368),
	(135,130,NULL,NULL,'addClassificationType',369,370),
	(136,130,NULL,NULL,'cancelAction',371,372),
	(137,1,NULL,NULL,'ComplianceAuditSettings',378,401),
	(138,137,NULL,NULL,'edit',379,380),
	(139,137,NULL,NULL,'setup',381,382),
	(140,137,NULL,NULL,'sendNotifications',383,384),
	(141,137,NULL,NULL,'cancelAction',385,386),
	(142,1,NULL,NULL,'ComplianceAudits',402,433),
	(143,142,NULL,NULL,'index',403,404),
	(144,142,NULL,NULL,'analyze',405,406),
	(147,142,NULL,NULL,'delete',407,408),
	(148,142,NULL,NULL,'add',409,410),
	(149,142,NULL,NULL,'edit',411,412),
	(150,142,NULL,NULL,'duplicate',413,414),
	(151,142,NULL,NULL,'export',415,416),
	(152,142,NULL,NULL,'cancelAction',417,418),
	(153,1,NULL,NULL,'ComplianceExceptions',434,455),
	(154,153,NULL,NULL,'index',435,436),
	(155,153,NULL,NULL,'delete',437,438),
	(156,153,NULL,NULL,'add',439,440),
	(157,153,NULL,NULL,'edit',441,442),
	(159,153,NULL,NULL,'exportPdf',443,444),
	(160,153,NULL,NULL,'cancelAction',445,446),
	(161,1,NULL,NULL,'ComplianceFindings',456,481),
	(162,161,NULL,NULL,'index',457,458),
	(163,161,NULL,NULL,'delete',459,460),
	(164,161,NULL,NULL,'add',461,462),
	(165,161,NULL,NULL,'edit',463,464),
	(166,161,NULL,NULL,'cancelAction',465,466),
	(167,1,NULL,NULL,'ComplianceManagements',482,505),
	(168,167,NULL,NULL,'index',483,484),
	(169,167,NULL,NULL,'analyze',485,486),
	(170,167,NULL,NULL,'add',487,488),
	(171,167,NULL,NULL,'edit',489,490),
	(172,167,NULL,NULL,'export',491,492),
	(173,167,NULL,NULL,'exportPdf',493,494),
	(174,167,NULL,NULL,'cancelAction',495,496),
	(175,1,NULL,NULL,'CompliancePackageItems',506,521),
	(176,175,NULL,NULL,'delete',507,508),
	(177,175,NULL,NULL,'add',509,510),
	(178,175,NULL,NULL,'edit',511,512),
	(179,175,NULL,NULL,'cancelAction',513,514),
	(180,1,NULL,NULL,'CompliancePackages',522,545),
	(181,180,NULL,NULL,'index',523,524),
	(182,180,NULL,NULL,'delete',525,526),
	(183,180,NULL,NULL,'add',527,528),
	(184,180,NULL,NULL,'edit',529,530),
	(185,180,NULL,NULL,'import',531,532),
	(186,180,NULL,NULL,'cancelAction',533,534),
	(187,1,NULL,NULL,'ComplianceReports',546,557),
	(188,187,NULL,NULL,'index',547,548),
	(189,187,NULL,NULL,'awareness',549,550),
	(190,187,NULL,NULL,'cancelAction',551,552),
	(191,1,NULL,NULL,'Cron',558,575),
	(199,1,NULL,NULL,'DataAssets',576,605),
	(200,199,NULL,NULL,'index',577,578),
	(201,199,NULL,NULL,'delete',579,580),
	(202,199,NULL,NULL,'add',581,582),
	(203,199,NULL,NULL,'edit',583,584),
	(205,199,NULL,NULL,'cancelAction',585,586),
	(206,1,NULL,NULL,'GoalAuditImprovements',606,621),
	(207,206,NULL,NULL,'delete',607,608),
	(208,206,NULL,NULL,'add',609,610),
	(209,206,NULL,NULL,'edit',611,612),
	(210,206,NULL,NULL,'cancelAction',613,614),
	(211,1,NULL,NULL,'GoalAudits',622,637),
	(212,211,NULL,NULL,'index',623,624),
	(213,211,NULL,NULL,'delete',625,626),
	(214,211,NULL,NULL,'edit',627,628),
	(215,211,NULL,NULL,'cancelAction',629,630),
	(216,1,NULL,NULL,'Goals',638,661),
	(217,216,NULL,NULL,'index',639,640),
	(218,216,NULL,NULL,'delete',641,642),
	(219,216,NULL,NULL,'add',643,644),
	(220,216,NULL,NULL,'edit',645,646),
	(221,216,NULL,NULL,'auditCalendarFormEntry',647,648),
	(222,216,NULL,NULL,'exportPdf',649,650),
	(223,216,NULL,NULL,'cancelAction',651,652),
	(224,1,NULL,NULL,'Groups',662,677),
	(225,224,NULL,NULL,'index',663,664),
	(226,224,NULL,NULL,'add',665,666),
	(227,224,NULL,NULL,'edit',667,668),
	(228,224,NULL,NULL,'delete',669,670),
	(229,224,NULL,NULL,'cancelAction',671,672),
	(230,1,NULL,NULL,'Issues',678,693),
	(231,230,NULL,NULL,'index',679,680),
	(232,230,NULL,NULL,'add',681,682),
	(233,230,NULL,NULL,'edit',683,684),
	(234,230,NULL,NULL,'delete',685,686),
	(235,230,NULL,NULL,'cancelAction',687,688),
	(236,1,NULL,NULL,'LdapConnectors',694,713),
	(237,236,NULL,NULL,'index',695,696),
	(238,236,NULL,NULL,'delete',697,698),
	(239,236,NULL,NULL,'add',699,700),
	(240,236,NULL,NULL,'edit',701,702),
	(241,236,NULL,NULL,'authentication',703,704),
	(242,236,NULL,NULL,'testLdap',705,706),
	(243,236,NULL,NULL,'cancelAction',707,708),
	(244,1,NULL,NULL,'Legals',714,733),
	(245,244,NULL,NULL,'index',715,716),
	(246,244,NULL,NULL,'delete',717,718),
	(247,244,NULL,NULL,'add',719,720),
	(248,244,NULL,NULL,'edit',721,722),
	(249,244,NULL,NULL,'cancelAction',723,724),
	(250,1,NULL,NULL,'NotificationSystem',734,771),
	(252,250,NULL,NULL,'index',735,736),
	(253,250,NULL,NULL,'enableForObject',737,738),
	(254,250,NULL,NULL,'enableForAll',739,740),
	(255,250,NULL,NULL,'disableForObject',741,742),
	(256,250,NULL,NULL,'disableForAll',743,744),
	(258,250,NULL,NULL,'associateAjax',745,746),
	(259,250,NULL,NULL,'remove',747,748),
	(260,250,NULL,NULL,'attach',749,750),
	(261,250,NULL,NULL,'delete',751,752),
	(262,250,NULL,NULL,'addNotification',753,754),
	(263,250,NULL,NULL,'feedback',755,756),
	(264,250,NULL,NULL,'addFeedbackAttachment',757,758),
	(265,250,NULL,NULL,'cancelAction',759,760),
	(266,1,NULL,NULL,'Notifications',772,781),
	(267,266,NULL,NULL,'setNotificationsAsSeen',773,774),
	(268,266,NULL,NULL,'cancelAction',775,776),
	(269,1,NULL,NULL,'Pages',782,797),
	(271,269,NULL,NULL,'dashboard',783,784),
	(272,269,NULL,NULL,'about',785,786),
	(273,269,NULL,NULL,'cancelAction',787,788),
	(274,1,NULL,NULL,'Policy',798,821),
	(275,274,NULL,NULL,'login',799,800),
	(276,274,NULL,NULL,'guestLogin',801,802),
	(277,274,NULL,NULL,'logout',803,804),
	(278,274,NULL,NULL,'index',805,806),
	(279,274,NULL,NULL,'isGuest',807,808),
	(280,274,NULL,NULL,'document',809,810),
	(281,274,NULL,NULL,'documentDirect',811,812),
	(282,274,NULL,NULL,'documentPdf',813,814),
	(283,274,NULL,NULL,'cancelAction',815,816),
	(284,1,NULL,NULL,'PolicyExceptions',822,841),
	(285,284,NULL,NULL,'index',823,824),
	(286,284,NULL,NULL,'delete',825,826),
	(287,284,NULL,NULL,'add',827,828),
	(288,284,NULL,NULL,'edit',829,830),
	(290,284,NULL,NULL,'cancelAction',831,832),
	(291,1,NULL,NULL,'Processes',842,861),
	(292,291,NULL,NULL,'index',843,844),
	(293,291,NULL,NULL,'delete',845,846),
	(294,291,NULL,NULL,'add',847,848),
	(295,291,NULL,NULL,'edit',849,850),
	(296,291,NULL,NULL,'cancelAction',851,852),
	(297,1,NULL,NULL,'ProgramIssues',862,883),
	(298,297,NULL,NULL,'index',863,864),
	(299,297,NULL,NULL,'delete',865,866),
	(300,297,NULL,NULL,'add',867,868),
	(301,297,NULL,NULL,'edit',869,870),
	(302,297,NULL,NULL,'exportPdf',871,872),
	(303,297,NULL,NULL,'cancelAction',873,874),
	(304,1,NULL,NULL,'ProgramScopes',884,905),
	(305,304,NULL,NULL,'index',885,886),
	(306,304,NULL,NULL,'delete',887,888),
	(307,304,NULL,NULL,'add',889,890),
	(308,304,NULL,NULL,'edit',891,892),
	(309,304,NULL,NULL,'exportPdf',893,894),
	(310,304,NULL,NULL,'cancelAction',895,896),
	(311,1,NULL,NULL,'ProjectAchievements',906,925),
	(312,311,NULL,NULL,'index',907,908),
	(313,311,NULL,NULL,'delete',909,910),
	(314,311,NULL,NULL,'add',911,912),
	(315,311,NULL,NULL,'edit',913,914),
	(316,311,NULL,NULL,'cancelAction',915,916),
	(317,1,NULL,NULL,'ProjectExpenses',926,945),
	(318,317,NULL,NULL,'index',927,928),
	(319,317,NULL,NULL,'delete',929,930),
	(320,317,NULL,NULL,'add',931,932),
	(321,317,NULL,NULL,'edit',933,934),
	(322,317,NULL,NULL,'cancelAction',935,936),
	(323,1,NULL,NULL,'Projects',946,967),
	(324,323,NULL,NULL,'index',947,948),
	(325,323,NULL,NULL,'delete',949,950),
	(326,323,NULL,NULL,'add',951,952),
	(327,323,NULL,NULL,'edit',953,954),
	(329,323,NULL,NULL,'exportPdf',955,956),
	(330,323,NULL,NULL,'cancelAction',957,958),
	(331,1,NULL,NULL,'Reports',968,977),
	(332,331,NULL,NULL,'awareness',969,970),
	(333,331,NULL,NULL,'cancelAction',971,972),
	(334,1,NULL,NULL,'Reviews',978,1003),
	(335,334,NULL,NULL,'index',979,980),
	(336,334,NULL,NULL,'add',981,982),
	(337,334,NULL,NULL,'edit',983,984),
	(338,334,NULL,NULL,'delete',985,986),
	(339,334,NULL,NULL,'cancelAction',987,988),
	(340,1,NULL,NULL,'RiskClassifications',1004,1021),
	(341,340,NULL,NULL,'index',1005,1006),
	(342,340,NULL,NULL,'delete',1007,1008),
	(343,340,NULL,NULL,'add',1009,1010),
	(344,340,NULL,NULL,'edit',1011,1012),
	(345,340,NULL,NULL,'addClassificationType',1013,1014),
	(346,340,NULL,NULL,'cancelAction',1015,1016),
	(347,1,NULL,NULL,'RiskExceptions',1022,1043),
	(348,347,NULL,NULL,'index',1023,1024),
	(349,347,NULL,NULL,'delete',1025,1026),
	(350,347,NULL,NULL,'add',1027,1028),
	(351,347,NULL,NULL,'edit',1029,1030),
	(353,347,NULL,NULL,'exportPdf',1031,1032),
	(354,347,NULL,NULL,'cancelAction',1033,1034),
	(355,1,NULL,NULL,'RiskReports',1044,1055),
	(356,355,NULL,NULL,'index',1045,1046),
	(357,355,NULL,NULL,'awareness',1047,1048),
	(358,355,NULL,NULL,'cancelAction',1049,1050),
	(359,1,NULL,NULL,'Risks',1056,1087),
	(360,359,NULL,NULL,'index',1057,1058),
	(361,359,NULL,NULL,'delete',1059,1060),
	(362,359,NULL,NULL,'add',1061,1062),
	(363,359,NULL,NULL,'edit',1063,1064),
	(364,359,NULL,NULL,'calculateRiskScoreAjax',1065,1066),
	(365,359,NULL,NULL,'getThreatsVulnerabilities',1067,1068),
	(367,359,NULL,NULL,'exportPdf',1069,1070),
	(368,359,NULL,NULL,'cancelAction',1071,1072),
	(369,1,NULL,NULL,'Scopes',1088,1103),
	(370,369,NULL,NULL,'index',1089,1090),
	(371,369,NULL,NULL,'delete',1091,1092),
	(372,369,NULL,NULL,'add',1093,1094),
	(373,369,NULL,NULL,'edit',1095,1096),
	(374,369,NULL,NULL,'cancelAction',1097,1098),
	(375,1,NULL,NULL,'SecurityControlReports',1104,1115),
	(376,375,NULL,NULL,'index',1105,1106),
	(377,375,NULL,NULL,'awareness',1107,1108),
	(378,375,NULL,NULL,'cancelAction',1109,1110),
	(379,1,NULL,NULL,'SecurityIncidentClassifications',1116,1131),
	(380,379,NULL,NULL,'index',1117,1118),
	(381,379,NULL,NULL,'delete',1119,1120),
	(382,379,NULL,NULL,'add',1121,1122),
	(383,379,NULL,NULL,'edit',1123,1124),
	(384,379,NULL,NULL,'cancelAction',1125,1126),
	(385,1,NULL,NULL,'SecurityIncidentStages',1132,1153),
	(386,385,NULL,NULL,'index',1133,1134),
	(387,385,NULL,NULL,'add',1135,1136),
	(388,385,NULL,NULL,'edit',1137,1138),
	(389,385,NULL,NULL,'delete',1139,1140),
	(390,385,NULL,NULL,'pocessStage',1141,1142),
	(391,385,NULL,NULL,'cancelAction',1143,1144),
	(392,1,NULL,NULL,'SecurityIncidents',1154,1185),
	(393,392,NULL,NULL,'index',1155,1156),
	(395,392,NULL,NULL,'delete',1157,1158),
	(396,392,NULL,NULL,'add',1159,1160),
	(397,392,NULL,NULL,'edit',1161,1162),
	(398,392,NULL,NULL,'getAssets',1163,1164),
	(399,392,NULL,NULL,'getThirdParties',1165,1166),
	(401,392,NULL,NULL,'exportPdf',1167,1168),
	(402,392,NULL,NULL,'cancelAction',1169,1170),
	(403,1,NULL,NULL,'SecurityOperationReports',1186,1197),
	(404,403,NULL,NULL,'index',1187,1188),
	(405,403,NULL,NULL,'awareness',1189,1190),
	(406,403,NULL,NULL,'cancelAction',1191,1192),
	(407,1,NULL,NULL,'SecurityPolicies',1198,1225),
	(408,407,NULL,NULL,'index',1199,1200),
	(409,407,NULL,NULL,'delete',1201,1202),
	(410,407,NULL,NULL,'add',1203,1204),
	(411,407,NULL,NULL,'edit',1205,1206),
	(412,407,NULL,NULL,'getDirectLink',1207,1208),
	(413,407,NULL,NULL,'duplicate',1209,1210),
	(414,407,NULL,NULL,'ldapGroups',1211,1212),
	(416,407,NULL,NULL,'sendNotifications',1213,1214),
	(417,407,NULL,NULL,'cancelAction',1215,1216),
	(418,1,NULL,NULL,'SecurityPolicyReviews',1226,1239),
	(419,418,NULL,NULL,'index',1227,1228),
	(420,418,NULL,NULL,'edit',1229,1230),
	(421,418,NULL,NULL,'delete',1231,1232),
	(422,418,NULL,NULL,'cancelAction',1233,1234),
	(423,1,NULL,NULL,'SecurityServiceAuditImprovements',1240,1255),
	(424,423,NULL,NULL,'delete',1241,1242),
	(425,423,NULL,NULL,'add',1243,1244),
	(426,423,NULL,NULL,'edit',1245,1246),
	(427,423,NULL,NULL,'cancelAction',1247,1248),
	(428,1,NULL,NULL,'SecurityServiceAudits',1256,1279),
	(429,428,NULL,NULL,'index',1257,1258),
	(430,428,NULL,NULL,'delete',1259,1260),
	(431,428,NULL,NULL,'edit',1261,1262),
	(432,428,NULL,NULL,'cancelAction',1263,1264),
	(433,1,NULL,NULL,'SecurityServiceMaintenances',1280,1299),
	(434,433,NULL,NULL,'index',1281,1282),
	(435,433,NULL,NULL,'delete',1283,1284),
	(436,433,NULL,NULL,'edit',1285,1286),
	(437,433,NULL,NULL,'cancelAction',1287,1288),
	(438,1,NULL,NULL,'SecurityServices',1300,1323),
	(439,438,NULL,NULL,'index',1301,1302),
	(440,438,NULL,NULL,'delete',1303,1304),
	(441,438,NULL,NULL,'add',1305,1306),
	(442,438,NULL,NULL,'edit',1307,1308),
	(444,438,NULL,NULL,'auditCalendarFormEntry',1309,1310),
	(448,438,NULL,NULL,'exportPdf',1311,1312),
	(449,438,NULL,NULL,'cancelAction',1313,1314),
	(450,1,NULL,NULL,'ServiceClassifications',1324,1339),
	(451,450,NULL,NULL,'index',1325,1326),
	(452,450,NULL,NULL,'delete',1327,1328),
	(453,450,NULL,NULL,'add',1329,1330),
	(454,450,NULL,NULL,'edit',1331,1332),
	(455,450,NULL,NULL,'cancelAction',1333,1334),
	(456,1,NULL,NULL,'ServiceContracts',1340,1359),
	(457,456,NULL,NULL,'index',1341,1342),
	(458,456,NULL,NULL,'delete',1343,1344),
	(459,456,NULL,NULL,'add',1345,1346),
	(460,456,NULL,NULL,'edit',1347,1348),
	(461,456,NULL,NULL,'cancelAction',1349,1350),
	(462,1,NULL,NULL,'Settings',1360,1395),
	(463,462,NULL,NULL,'index',1361,1362),
	(464,462,NULL,NULL,'edit',1363,1364),
	(465,462,NULL,NULL,'logs',1365,1366),
	(466,462,NULL,NULL,'deleteLogs',1367,1368),
	(467,462,NULL,NULL,'testMailConnection',1369,1370),
	(468,462,NULL,NULL,'resetDashboards',1371,1372),
	(469,462,NULL,NULL,'customLogo',1373,1374),
	(470,462,NULL,NULL,'deleteCache',1375,1376),
	(471,462,NULL,NULL,'resetDatabase',1377,1378),
	(472,462,NULL,NULL,'systemHealth',1379,1380),
	(473,462,NULL,NULL,'cancelAction',1381,1382),
	(474,1,NULL,NULL,'SystemRecords',1396,1407),
	(475,474,NULL,NULL,'index',1397,1398),
	(476,474,NULL,NULL,'export',1399,1400),
	(477,474,NULL,NULL,'cancelAction',1401,1402),
	(478,1,NULL,NULL,'TeamRoles',1408,1429),
	(479,478,NULL,NULL,'index',1409,1410),
	(480,478,NULL,NULL,'delete',1411,1412),
	(481,478,NULL,NULL,'add',1413,1414),
	(482,478,NULL,NULL,'edit',1415,1416),
	(483,478,NULL,NULL,'exportPdf',1417,1418),
	(484,478,NULL,NULL,'cancelAction',1419,1420),
	(485,1,NULL,NULL,'ThirdParties',1430,1449),
	(486,485,NULL,NULL,'index',1431,1432),
	(487,485,NULL,NULL,'delete',1433,1434),
	(488,485,NULL,NULL,'add',1435,1436),
	(489,485,NULL,NULL,'edit',1437,1438),
	(490,485,NULL,NULL,'cancelAction',1439,1440),
	(491,1,NULL,NULL,'ThirdPartyRisks',1450,1477),
	(492,491,NULL,NULL,'index',1451,1452),
	(493,491,NULL,NULL,'delete',1453,1454),
	(494,491,NULL,NULL,'add',1455,1456),
	(495,491,NULL,NULL,'edit',1457,1458),
	(498,491,NULL,NULL,'exportPdf',1459,1460),
	(499,491,NULL,NULL,'cancelAction',1461,1462),
	(500,1,NULL,NULL,'Threats',1478,1493),
	(501,500,NULL,NULL,'index',1479,1480),
	(502,500,NULL,NULL,'liveEdit',1481,1482),
	(503,500,NULL,NULL,'add',1483,1484),
	(504,500,NULL,NULL,'delete',1485,1486),
	(505,500,NULL,NULL,'cancelAction',1487,1488),
	(506,1,NULL,NULL,'Users',1494,1529),
	(507,506,NULL,NULL,'index',1495,1496),
	(508,506,NULL,NULL,'add',1497,1498),
	(509,506,NULL,NULL,'edit',1499,1500),
	(510,506,NULL,NULL,'delete',1501,1502),
	(511,506,NULL,NULL,'profile',1503,1504),
	(512,506,NULL,NULL,'resetpassword',1505,1506),
	(513,506,NULL,NULL,'useticket',1507,1508),
	(514,506,NULL,NULL,'login',1509,1510),
	(515,506,NULL,NULL,'logout',1511,1512),
	(516,506,NULL,NULL,'chooseLdapUser',1513,1514),
	(517,506,NULL,NULL,'cancelAction',1515,1516),
	(518,1,NULL,NULL,'Vulnerabilities',1530,1545),
	(519,518,NULL,NULL,'index',1531,1532),
	(520,518,NULL,NULL,'liveEdit',1533,1534),
	(521,518,NULL,NULL,'add',1535,1536),
	(522,518,NULL,NULL,'delete',1537,1538),
	(523,518,NULL,NULL,'cancelAction',1539,1540),
	(524,1,NULL,NULL,'Workflows',1546,1605),
	(610,32,NULL,NULL,'getIndexUrlFromComponent',101,102),
	(611,32,NULL,NULL,'initEmailFromComponent',103,104),
	(613,250,NULL,NULL,'associateForObject',761,762),
	(614,250,NULL,NULL,'associateForAll',763,764),
	(615,1,NULL,NULL,'ProgramHealth',1606,1617),
	(616,615,NULL,NULL,'index',1607,1608),
	(617,615,NULL,NULL,'cancelAction',1609,1610),
	(658,269,NULL,NULL,'license',789,790),
	(659,615,NULL,NULL,'exportPdf',1611,1612),
	(696,506,NULL,NULL,'changeLanguage',1517,1518),
	(733,108,NULL,NULL,'index',307,308),
	(806,2,NULL,NULL,'isAuthorized',7,8),
	(807,5,NULL,NULL,'isAuthorized',23,24),
	(808,12,NULL,NULL,'isAuthorized',41,42),
	(809,18,NULL,NULL,'isAuthorized',57,58),
	(810,24,NULL,NULL,'isAuthorized',75,76),
	(811,32,NULL,NULL,'isAuthorized',105,106),
	(812,42,NULL,NULL,'isAuthorized',129,130),
	(813,55,NULL,NULL,'isAuthorized',169,170),
	(815,75,NULL,NULL,'isAuthorized',213,214),
	(816,84,NULL,NULL,'isAuthorized',237,238),
	(817,89,NULL,NULL,'isAuthorized',253,254),
	(818,94,NULL,NULL,'isAuthorized',289,290),
	(819,108,NULL,NULL,'isAuthorized',309,310),
	(820,113,NULL,NULL,'isAuthorized',327,328),
	(821,119,NULL,NULL,'isAuthorized',355,356),
	(822,130,NULL,NULL,'isAuthorized',373,374),
	(823,137,NULL,NULL,'isAuthorized',387,388),
	(824,142,NULL,NULL,'isAuthorized',419,420),
	(825,153,NULL,NULL,'isAuthorized',447,448),
	(826,161,NULL,NULL,'isAuthorized',467,468),
	(827,167,NULL,NULL,'isAuthorized',497,498),
	(828,175,NULL,NULL,'isAuthorized',515,516),
	(829,180,NULL,NULL,'isAuthorized',535,536),
	(830,187,NULL,NULL,'isAuthorized',553,554),
	(832,199,NULL,NULL,'isAuthorized',587,588),
	(833,206,NULL,NULL,'isAuthorized',615,616),
	(834,211,NULL,NULL,'isAuthorized',631,632),
	(835,216,NULL,NULL,'isAuthorized',653,654),
	(836,224,NULL,NULL,'isAuthorized',673,674),
	(837,230,NULL,NULL,'isAuthorized',689,690),
	(838,236,NULL,NULL,'isAuthorized',709,710),
	(839,244,NULL,NULL,'isAuthorized',725,726),
	(840,250,NULL,NULL,'isAuthorized',765,766),
	(841,266,NULL,NULL,'isAuthorized',777,778),
	(842,269,NULL,NULL,'isAuthorized',791,792),
	(843,274,NULL,NULL,'isAuthorized',817,818),
	(844,284,NULL,NULL,'isAuthorized',833,834),
	(845,291,NULL,NULL,'isAuthorized',853,854),
	(846,615,NULL,NULL,'isAuthorized',1613,1614),
	(847,297,NULL,NULL,'isAuthorized',875,876),
	(848,304,NULL,NULL,'isAuthorized',897,898),
	(849,311,NULL,NULL,'isAuthorized',917,918),
	(850,317,NULL,NULL,'isAuthorized',937,938),
	(851,323,NULL,NULL,'isAuthorized',959,960),
	(852,331,NULL,NULL,'isAuthorized',973,974),
	(853,334,NULL,NULL,'isAuthorized',989,990),
	(854,340,NULL,NULL,'isAuthorized',1017,1018),
	(855,347,NULL,NULL,'isAuthorized',1035,1036),
	(856,355,NULL,NULL,'isAuthorized',1051,1052),
	(857,359,NULL,NULL,'isAuthorized',1073,1074),
	(858,369,NULL,NULL,'isAuthorized',1099,1100),
	(859,375,NULL,NULL,'isAuthorized',1111,1112),
	(860,379,NULL,NULL,'isAuthorized',1127,1128),
	(861,385,NULL,NULL,'isAuthorized',1145,1146),
	(862,392,NULL,NULL,'isAuthorized',1171,1172),
	(863,403,NULL,NULL,'isAuthorized',1193,1194),
	(864,407,NULL,NULL,'isAuthorized',1217,1218),
	(865,418,NULL,NULL,'isAuthorized',1235,1236),
	(866,423,NULL,NULL,'isAuthorized',1249,1250),
	(867,428,NULL,NULL,'isAuthorized',1265,1266),
	(868,433,NULL,NULL,'isAuthorized',1289,1290),
	(869,438,NULL,NULL,'isAuthorized',1315,1316),
	(870,450,NULL,NULL,'isAuthorized',1335,1336),
	(871,456,NULL,NULL,'isAuthorized',1351,1352),
	(872,462,NULL,NULL,'isAuthorized',1383,1384),
	(873,474,NULL,NULL,'isAuthorized',1403,1404),
	(874,478,NULL,NULL,'isAuthorized',1421,1422),
	(875,485,NULL,NULL,'isAuthorized',1441,1442),
	(876,491,NULL,NULL,'isAuthorized',1463,1464),
	(877,500,NULL,NULL,'isAuthorized',1489,1490),
	(878,506,NULL,NULL,'isAuthorized',1519,1520),
	(879,518,NULL,NULL,'isAuthorized',1541,1542),
	(961,269,NULL,NULL,'welcome',793,794),
	(1042,506,NULL,NULL,'unblock',1521,1522),
	(1083,392,NULL,NULL,'reloadLifecycle',1173,1174),
	(1124,1,NULL,NULL,'Acl',1618,1691),
	(1125,1124,NULL,NULL,'Acl',1619,1630),
	(1126,1125,NULL,NULL,'index',1620,1621),
	(1127,1125,NULL,NULL,'admin_index',1622,1623),
	(1128,1125,NULL,NULL,'isAuthorized',1624,1625),
	(1129,1125,NULL,NULL,'cancelAction',1626,1627),
	(1130,1124,NULL,NULL,'Acos',1631,1648),
	(1131,1130,NULL,NULL,'admin_index',1632,1633),
	(1132,1130,NULL,NULL,'admin_empty_acos',1634,1635),
	(1133,1130,NULL,NULL,'admin_build_acl',1636,1637),
	(1134,1130,NULL,NULL,'admin_prune_acos',1638,1639),
	(1135,1130,NULL,NULL,'admin_synchronize',1640,1641),
	(1136,1130,NULL,NULL,'isAuthorized',1642,1643),
	(1137,1130,NULL,NULL,'cancelAction',1644,1645),
	(1138,1124,NULL,NULL,'Aros',1649,1690),
	(1139,1138,NULL,NULL,'admin_index',1650,1651),
	(1140,1138,NULL,NULL,'admin_check',1652,1653),
	(1141,1138,NULL,NULL,'admin_users',1654,1655),
	(1142,1138,NULL,NULL,'admin_update_user_role',1656,1657),
	(1143,1138,NULL,NULL,'admin_ajax_role_permissions',1658,1659),
	(1144,1138,NULL,NULL,'admin_role_permissions',1660,1661),
	(1145,1138,NULL,NULL,'admin_user_permissions',1662,1663),
	(1146,1138,NULL,NULL,'admin_empty_permissions',1664,1665),
	(1147,1138,NULL,NULL,'admin_clear_user_specific_permissions',1666,1667),
	(1148,1138,NULL,NULL,'admin_grant_all_controllers',1668,1669),
	(1149,1138,NULL,NULL,'admin_deny_all_controllers',1670,1671),
	(1150,1138,NULL,NULL,'admin_get_role_controller_permission',1672,1673),
	(1151,1138,NULL,NULL,'admin_grant_role_permission',1674,1675),
	(1152,1138,NULL,NULL,'admin_deny_role_permission',1676,1677),
	(1153,1138,NULL,NULL,'admin_get_user_controller_permission',1678,1679),
	(1154,1138,NULL,NULL,'admin_grant_user_permission',1680,1681),
	(1155,1138,NULL,NULL,'admin_deny_user_permission',1682,1683),
	(1156,1138,NULL,NULL,'isAuthorized',1684,1685),
	(1157,1138,NULL,NULL,'cancelAction',1686,1687),
	(1158,1,NULL,NULL,'DebugKit',1692,1705),
	(1159,1158,NULL,NULL,'ToolbarAccess',1693,1704),
	(1160,1159,NULL,NULL,'history_state',1694,1695),
	(1161,1159,NULL,NULL,'sql_explain',1696,1697),
	(1162,1159,NULL,NULL,'isAuthorized',1698,1699),
	(1163,1159,NULL,NULL,'cancelAction',1700,1701),
	(1208,89,NULL,NULL,'getIndexUrlFromComponent',255,256),
	(1209,89,NULL,NULL,'initEmailFromComponent',257,258),
	(1303,1,NULL,NULL,'News',1706,1717),
	(1304,1303,NULL,NULL,'index',1707,1708),
	(1305,1303,NULL,NULL,'markAsRead',1709,1710),
	(1306,1303,NULL,NULL,'isAuthorized',1711,1712),
	(1307,1303,NULL,NULL,'cancelAction',1713,1714),
	(1368,1,NULL,NULL,'RiskCalculations',1718,1729),
	(1369,1368,NULL,NULL,'warning',1719,1720),
	(1370,1368,NULL,NULL,'edit',1721,1722),
	(1371,1368,NULL,NULL,'isAuthorized',1723,1724),
	(1372,1368,NULL,NULL,'cancelAction',1725,1726),
	(1429,428,NULL,NULL,'getIndexUrlFromComponent',1267,1268),
	(1430,428,NULL,NULL,'initEmailFromComponent',1269,1270),
	(1475,1,NULL,NULL,'Updates',1730,1741),
	(1476,1475,NULL,NULL,'index',1731,1732),
	(1477,1475,NULL,NULL,'update',1733,1734),
	(1478,1475,NULL,NULL,'isAuthorized',1735,1736),
	(1479,1475,NULL,NULL,'cancelAction',1737,1738),
	(1484,506,NULL,NULL,'searchLdapUsers',1523,1524),
	(1513,137,NULL,NULL,'index',389,390),
	(1522,1,NULL,NULL,'CustomFields',1742,1791),
	(1547,161,NULL,NULL,'initEmailFromComponent',469,470),
	(1548,161,NULL,NULL,'getIndexUrlFromComponent',471,472),
	(1549,167,NULL,NULL,'getPolicies',499,500),
	(1550,250,NULL,NULL,'listItems',767,768),
	(1551,392,NULL,NULL,'getControls',1175,1176),
	(1552,392,NULL,NULL,'getRiskProcedures',1177,1178),
	(1553,462,NULL,NULL,'getTimeByTimezone',1385,1386),
	(1554,75,NULL,NULL,'getPolicies',215,216),
	(1555,142,NULL,NULL,'start',421,422),
	(1556,142,NULL,NULL,'stop',423,424),
	(1557,142,NULL,NULL,'exportPdf',425,426),
	(1558,161,NULL,NULL,'exportPdf',473,474),
	(1559,359,NULL,NULL,'getPolicies',1075,1076),
	(1561,491,NULL,NULL,'getPolicies',1465,1466),
	(1562,1,NULL,NULL,'ImportTool',1792,1807),
	(1563,1562,NULL,NULL,'ImportTool',1793,1806),
	(1564,1563,NULL,NULL,'index',1794,1795),
	(1565,1563,NULL,NULL,'downloadTemplate',1796,1797),
	(1566,1563,NULL,NULL,'preview',1798,1799),
	(1567,1563,NULL,NULL,'isAuthorized',1800,1801),
	(1568,1563,NULL,NULL,'cancelAction',1802,1803),
	(1573,24,NULL,NULL,'getLegals',77,78),
	(1574,42,NULL,NULL,'text',131,132),
	(1575,1,NULL,NULL,'AwarenessProgramUsers',1808,1817),
	(1576,1575,NULL,NULL,'index',1809,1810),
	(1577,1575,NULL,NULL,'isAuthorized',1811,1812),
	(1578,1575,NULL,NULL,'cancelAction',1813,1814),
	(1583,55,NULL,NULL,'deleteTextFile',171,172),
	(1584,55,NULL,NULL,'downloadExample',173,174),
	(1585,1,NULL,NULL,'AwarenessReminders',1818,1829),
	(1586,1585,NULL,NULL,'index',1819,1820),
	(1587,1585,NULL,NULL,'isAuthorized',1821,1822),
	(1588,1585,NULL,NULL,'cancelAction',1823,1824),
	(1593,1,NULL,NULL,'AwarenessTrainings',1830,1841),
	(1594,1593,NULL,NULL,'index',1831,1832),
	(1595,1593,NULL,NULL,'isAuthorized',1833,1834),
	(1596,1593,NULL,NULL,'cancelAction',1835,1836),
	(1601,175,NULL,NULL,'index',517,518),
	(1603,334,NULL,NULL,'filterIndex',991,992),
	(1604,1,NULL,NULL,'ComplianceAuditAuditeeFeedbacks',1842,1851),
	(1605,1604,NULL,NULL,'index',1843,1844),
	(1606,1604,NULL,NULL,'isAuthorized',1845,1846),
	(1607,1604,NULL,NULL,'cancelAction',1847,1848),
	(1612,1,NULL,NULL,'Api',1852,1883),
	(1613,1612,NULL,NULL,'ApiSecurityIncidentStages',1853,1864),
	(1614,1613,NULL,NULL,'index',1854,1855),
	(1615,1613,NULL,NULL,'view',1856,1857),
	(1616,1613,NULL,NULL,'isAuthorized',1858,1859),
	(1617,1613,NULL,NULL,'cancelAction',1860,1861),
	(1622,1612,NULL,NULL,'ApiSecurityIncidents',1865,1882),
	(1623,1622,NULL,NULL,'index',1866,1867),
	(1624,1622,NULL,NULL,'add',1868,1869),
	(1625,1622,NULL,NULL,'view',1870,1871),
	(1626,1622,NULL,NULL,'edit',1872,1873),
	(1627,1622,NULL,NULL,'delete',1874,1875),
	(1628,1622,NULL,NULL,'isAuthorized',1876,1877),
	(1629,1622,NULL,NULL,'cancelAction',1878,1879),
	(1818,1522,NULL,NULL,'CustomFieldSettings',1743,1752),
	(1819,1818,NULL,NULL,'edit',1744,1745),
	(1820,1818,NULL,NULL,'isAuthorized',1746,1747),
	(1821,1818,NULL,NULL,'cancelAction',1748,1749),
	(1828,1522,NULL,NULL,'CustomFields',1753,1774),
	(1829,1828,NULL,NULL,'delete',1754,1755),
	(1830,1828,NULL,NULL,'add',1756,1757),
	(1831,1828,NULL,NULL,'warning',1758,1759),
	(1832,1828,NULL,NULL,'edit',1760,1761),
	(1833,1828,NULL,NULL,'saveOptions',1762,1763),
	(1834,1828,NULL,NULL,'deleteOptions',1764,1765),
	(1835,1828,NULL,NULL,'getOptions',1766,1767),
	(1836,1828,NULL,NULL,'isAuthorized',1768,1769),
	(1837,1828,NULL,NULL,'cancelAction',1770,1771),
	(1844,1522,NULL,NULL,'CustomForms',1775,1790),
	(1845,1844,NULL,NULL,'delete',1776,1777),
	(1846,1844,NULL,NULL,'index',1778,1779),
	(1847,1844,NULL,NULL,'add',1780,1781),
	(1848,1844,NULL,NULL,'edit',1782,1783),
	(1849,1844,NULL,NULL,'isAuthorized',1784,1785),
	(1850,1844,NULL,NULL,'cancelAction',1786,1787),
	(1870,42,NULL,NULL,'downloadStepFile',133,134),
	(1871,42,NULL,NULL,'viewText',135,136),
	(1902,142,NULL,NULL,'trash',427,428),
	(1908,161,NULL,NULL,'trash',475,476),
	(1915,180,NULL,NULL,'duplicate',537,538),
	(1947,274,NULL,NULL,'downloadAttachment',819,820),
	(1966,1,NULL,NULL,'Queue',1884,1897),
	(1967,1966,NULL,NULL,'index',1885,1886),
	(1968,1966,NULL,NULL,'delete',1887,1888),
	(1970,1966,NULL,NULL,'isAuthorized',1889,1890),
	(1971,1966,NULL,NULL,'cancelAction',1891,1892),
	(2021,462,NULL,NULL,'downloadLogs',1387,1388),
	(2022,462,NULL,NULL,'getLogo',1389,1390),
	(2047,71,NULL,NULL,'BackupRestore',185,196),
	(2048,2047,NULL,NULL,'index',186,187),
	(2049,2047,NULL,NULL,'getBackup',188,189),
	(2051,2047,NULL,NULL,'isAuthorized',190,191),
	(2052,2047,NULL,NULL,'cancelAction',192,193),
	(2060,1,NULL,NULL,'BulkActions',1898,1911),
	(2061,2060,NULL,NULL,'BulkActions',1899,1910),
	(2062,2061,NULL,NULL,'apply',1900,1901),
	(2063,2061,NULL,NULL,'submit',1902,1903),
	(2065,2061,NULL,NULL,'isAuthorized',1904,1905),
	(2066,2061,NULL,NULL,'cancelAction',1906,1907),
	(2082,1,NULL,NULL,'ObjectVersion',1912,1925),
	(2083,2082,NULL,NULL,'ObjectVersion',1913,1924),
	(2084,2083,NULL,NULL,'history',1914,1915),
	(2085,2083,NULL,NULL,'restore',1916,1917),
	(2086,2083,NULL,NULL,'cancelAction',1918,1919),
	(2088,2083,NULL,NULL,'isAuthorized',1920,1921),
	(2104,NULL,NULL,NULL,'visualisation',2351,2432),
	(2105,2104,NULL,NULL,'models',2352,2429),
	(2106,2104,NULL,NULL,'objects',2430,2431),
	(2107,2105,'Asset',NULL,'Asset::',2353,2360),
	(2108,2107,'AssetReview',NULL,'AssetReview::',2354,2355),
	(2109,2105,'Risk',NULL,'Risk::',2361,2364),
	(2110,2105,'ThirdPartyRisk',NULL,'ThirdPartyRisk::',2365,2368),
	(2111,2105,'BusinessContinuity',NULL,'BusinessContinuity::',2369,2372),
	(2112,2109,'RiskReview',NULL,'RiskReview::',2362,2363),
	(2113,2110,'ThirdPartyRiskReview',NULL,'ThirdPartyRiskReview::',2366,2367),
	(2114,2111,'BusinessContinuityReview',NULL,'BusinessContinuityReview::',2370,2371),
	(2115,2105,'SecurityPolicy',NULL,'SecurityPolicy::',2373,2376),
	(2116,2115,'SecurityPolicyReview',NULL,'SecurityPolicyReview::',2374,2375),
	(2117,2105,'SecurityService',NULL,'SecurityService::',2377,2382),
	(2118,2117,'SecurityServiceAudit',NULL,'SecurityServiceAudit::',2378,2379),
	(2119,2117,'SecurityServiceMaintenance',NULL,'SecurityServiceMaintenance::',2380,2381),
	(2120,2105,'ComplianceException',NULL,'ComplianceException::',2383,2384),
	(2121,2105,'ComplianceAudit',NULL,'ComplianceAudit::',2385,2390),
	(2122,2105,'ComplianceAnalysisFinding',NULL,'ComplianceAnalysisFinding::',2391,2392),
	(2123,2121,'ComplianceAuditSetting',NULL,'ComplianceAuditSetting::',2386,2387),
	(2124,2121,'ComplianceFinding',NULL,'ComplianceFinding::',2388,2389),
	(2125,2105,'BusinessUnit',NULL,'BusinessUnit::',2393,2396),
	(2126,2105,'AwarenessProgram',NULL,'AwarenessProgram::',2397,2398),
	(2127,2105,'BusinessContinuityPlan',NULL,'BusinessContinuityPlan::',2399,2402),
	(2128,2127,'BusinessContinuityPlanAudit',NULL,'BusinessContinuityPlanAudit::',2400,2401),
	(2129,2107,'DataAssetInstance',NULL,'DataAssetInstance::',2356,2359),
	(2130,2129,'DataAsset',NULL,'DataAsset::',2357,2358),
	(2131,2105,'Goal',NULL,'Goal::',2403,2404),
	(2132,2105,'Legal',NULL,'Legal::',2405,2406),
	(2133,2105,'PolicyException',NULL,'PolicyException::',2407,2408),
	(2134,2125,'Process',NULL,'Process::',2394,2395),
	(2135,2105,'ProgramIssue',NULL,'ProgramIssue::',2409,2410),
	(2136,2105,'ProgramScope',NULL,'ProgramScope::',2411,2412),
	(2137,2105,'Project',NULL,'Project::',2413,2416),
	(2138,2137,'ProjectAchievement',NULL,'ProjectAchievement::',2414,2415),
	(2139,2105,'ProjectExpense',NULL,'ProjectExpense::',2417,2418),
	(2140,2105,'RiskException',NULL,'RiskException::',2419,2420),
	(2141,2105,'SecurityIncident',NULL,'SecurityIncident::',2421,2422),
	(2142,2105,'ThirdParty',NULL,'ThirdParty::',2423,2424),
	(2143,2105,'VendorAssessment',NULL,'VendorAssessment::',2425,2428),
	(2144,2143,'VendorAssessmentFinding',NULL,'VendorAssessmentFinding::',2426,2427),
	(2145,2,NULL,NULL,'downloadAttachment',9,10),
	(2146,5,NULL,NULL,'model',25,26),
	(2147,5,NULL,NULL,'downloadAttachment',27,28),
	(2148,12,NULL,NULL,'downloadAttachment',43,44),
	(2149,18,NULL,NULL,'downloadAttachment',59,60),
	(2150,24,NULL,NULL,'trash',79,80),
	(2151,24,NULL,NULL,'model',81,82),
	(2152,24,NULL,NULL,'downloadAttachment',83,84),
	(2153,32,NULL,NULL,'downloadAttachment',107,108),
	(2154,42,NULL,NULL,'downloadAttachment',137,138),
	(2155,1575,NULL,NULL,'downloadAttachment',1815,1816),
	(2156,55,NULL,NULL,'ldapCheck',175,176),
	(2157,55,NULL,NULL,'model',177,178),
	(2158,55,NULL,NULL,'downloadAttachment',179,180),
	(2159,55,NULL,NULL,'trash',181,182),
	(2160,1585,NULL,NULL,'model',1825,1826),
	(2161,1585,NULL,NULL,'downloadAttachment',1827,1828),
	(2162,1593,NULL,NULL,'model',1837,1838),
	(2163,1593,NULL,NULL,'downloadAttachment',1839,1840),
	(2164,75,NULL,NULL,'trash',217,218),
	(2165,75,NULL,NULL,'processClassifications',219,220),
	(2166,75,NULL,NULL,'initOptions',221,222),
	(2167,75,NULL,NULL,'model',223,224),
	(2168,75,NULL,NULL,'downloadAttachment',225,226),
	(2169,84,NULL,NULL,'model',239,240),
	(2170,84,NULL,NULL,'downloadAttachment',241,242),
	(2171,89,NULL,NULL,'model',259,260),
	(2172,89,NULL,NULL,'downloadAttachment',261,262),
	(2173,94,NULL,NULL,'model',291,292),
	(2174,94,NULL,NULL,'downloadAttachment',293,294),
	(2175,94,NULL,NULL,'trash',295,296),
	(2176,108,NULL,NULL,'model',311,312),
	(2177,108,NULL,NULL,'downloadAttachment',313,314),
	(2178,113,NULL,NULL,'model',329,330),
	(2179,113,NULL,NULL,'downloadAttachment',331,332),
	(2180,113,NULL,NULL,'trash',333,334),
	(2181,119,NULL,NULL,'downloadAttachment',357,358),
	(2182,1,NULL,NULL,'ComplianceAnalysisFindings',1926,1951),
	(2183,2182,NULL,NULL,'index',1927,1928),
	(2184,2182,NULL,NULL,'delete',1929,1930),
	(2185,2182,NULL,NULL,'trash',1931,1932),
	(2186,2182,NULL,NULL,'add',1933,1934),
	(2187,2182,NULL,NULL,'edit',1935,1936),
	(2188,2182,NULL,NULL,'loadPackageItems',1937,1938),
	(2189,2182,NULL,NULL,'initPackageItems',1939,1940),
	(2190,2182,NULL,NULL,'exportPdf',1941,1942),
	(2191,2182,NULL,NULL,'model',1943,1944),
	(2192,2182,NULL,NULL,'isAuthorized',1945,1946),
	(2193,2182,NULL,NULL,'cancelAction',1947,1948),
	(2194,2182,NULL,NULL,'downloadAttachment',1949,1950),
	(2195,1604,NULL,NULL,'downloadAttachment',1849,1850),
	(2196,130,NULL,NULL,'downloadAttachment',375,376),
	(2197,137,NULL,NULL,'model',391,392),
	(2198,137,NULL,NULL,'downloadAttachment',393,394),
	(2199,137,NULL,NULL,'add',395,396),
	(2200,137,NULL,NULL,'delete',397,398),
	(2201,137,NULL,NULL,'trash',399,400),
	(2202,142,NULL,NULL,'model',429,430),
	(2203,142,NULL,NULL,'downloadAttachment',431,432),
	(2204,153,NULL,NULL,'trash',449,450),
	(2205,153,NULL,NULL,'model',451,452),
	(2206,153,NULL,NULL,'downloadAttachment',453,454),
	(2207,161,NULL,NULL,'model',477,478),
	(2208,161,NULL,NULL,'downloadAttachment',479,480),
	(2209,167,NULL,NULL,'model',501,502),
	(2210,167,NULL,NULL,'downloadAttachment',503,504),
	(2211,175,NULL,NULL,'downloadAttachment',519,520),
	(2212,180,NULL,NULL,'trash',539,540),
	(2213,180,NULL,NULL,'model',541,542),
	(2214,180,NULL,NULL,'downloadAttachment',543,544),
	(2215,187,NULL,NULL,'downloadAttachment',555,556),
	(2216,1,NULL,NULL,'DataAssetGdpr',1952,1961),
	(2217,2216,NULL,NULL,'model',1953,1954),
	(2218,2216,NULL,NULL,'isAuthorized',1955,1956),
	(2219,2216,NULL,NULL,'cancelAction',1957,1958),
	(2220,2216,NULL,NULL,'downloadAttachment',1959,1960),
	(2221,1,NULL,NULL,'DataAssetInstances',1962,1977),
	(2222,2221,NULL,NULL,'index',1963,1964),
	(2223,2221,NULL,NULL,'listDataAssets',1965,1966),
	(2224,2221,NULL,NULL,'exportPdf',1967,1968),
	(2225,2221,NULL,NULL,'model',1969,1970),
	(2226,2221,NULL,NULL,'isAuthorized',1971,1972),
	(2227,2221,NULL,NULL,'cancelAction',1973,1974),
	(2228,2221,NULL,NULL,'downloadAttachment',1975,1976),
	(2229,1,NULL,NULL,'DataAssetSettings',1978,1989),
	(2230,2229,NULL,NULL,'setup',1979,1980),
	(2231,2229,NULL,NULL,'model',1981,1982),
	(2232,2229,NULL,NULL,'isAuthorized',1983,1984),
	(2233,2229,NULL,NULL,'cancelAction',1985,1986),
	(2234,2229,NULL,NULL,'downloadAttachment',1987,1988),
	(2235,199,NULL,NULL,'getStatusInfo',589,590),
	(2236,199,NULL,NULL,'getAssociatedRiskData',591,592),
	(2237,199,NULL,NULL,'getAssociatedThirdPartyRiskData',593,594),
	(2238,199,NULL,NULL,'getAssociatedBusinessContinuityData',595,596),
	(2239,199,NULL,NULL,'getAssociatedSecurityServices',597,598),
	(2240,199,NULL,NULL,'model',599,600),
	(2241,199,NULL,NULL,'downloadAttachment',601,602),
	(2242,199,NULL,NULL,'trash',603,604),
	(2243,206,NULL,NULL,'model',617,618),
	(2244,206,NULL,NULL,'downloadAttachment',619,620),
	(2245,211,NULL,NULL,'model',633,634),
	(2246,211,NULL,NULL,'downloadAttachment',635,636),
	(2247,216,NULL,NULL,'model',655,656),
	(2248,216,NULL,NULL,'downloadAttachment',657,658),
	(2249,216,NULL,NULL,'trash',659,660),
	(2250,224,NULL,NULL,'downloadAttachment',675,676),
	(2251,230,NULL,NULL,'downloadAttachment',691,692),
	(2252,236,NULL,NULL,'downloadAttachment',711,712),
	(2253,244,NULL,NULL,'model',727,728),
	(2254,244,NULL,NULL,'downloadAttachment',729,730),
	(2255,244,NULL,NULL,'trash',731,732),
	(2256,1303,NULL,NULL,'downloadAttachment',1715,1716),
	(2257,250,NULL,NULL,'downloadAttachment',769,770),
	(2258,266,NULL,NULL,'downloadAttachment',779,780),
	(2259,1,NULL,NULL,'OauthConnectors',1990,2009),
	(2260,2259,NULL,NULL,'add',1991,1992),
	(2261,2259,NULL,NULL,'edit',1993,1994),
	(2262,2259,NULL,NULL,'delete',1995,1996),
	(2263,2259,NULL,NULL,'model',1997,1998),
	(2264,2259,NULL,NULL,'isAuthorized',1999,2000),
	(2265,2259,NULL,NULL,'cancelAction',2001,2002),
	(2266,2259,NULL,NULL,'downloadAttachment',2003,2004),
	(2267,2259,NULL,NULL,'index',2005,2006),
	(2268,2259,NULL,NULL,'trash',2007,2008),
	(2269,269,NULL,NULL,'downloadAttachment',795,796),
	(2270,284,NULL,NULL,'model',835,836),
	(2271,284,NULL,NULL,'downloadAttachment',837,838),
	(2272,284,NULL,NULL,'trash',839,840),
	(2273,291,NULL,NULL,'model',855,856),
	(2274,291,NULL,NULL,'downloadAttachment',857,858),
	(2275,291,NULL,NULL,'trash',859,860),
	(2276,615,NULL,NULL,'downloadAttachment',1615,1616),
	(2277,297,NULL,NULL,'model',877,878),
	(2278,297,NULL,NULL,'downloadAttachment',879,880),
	(2279,297,NULL,NULL,'trash',881,882),
	(2280,304,NULL,NULL,'model',899,900),
	(2281,304,NULL,NULL,'downloadAttachment',901,902),
	(2282,304,NULL,NULL,'trash',903,904),
	(2283,311,NULL,NULL,'model',919,920),
	(2284,311,NULL,NULL,'downloadAttachment',921,922),
	(2285,311,NULL,NULL,'trash',923,924),
	(2286,317,NULL,NULL,'model',939,940),
	(2287,317,NULL,NULL,'downloadAttachment',941,942),
	(2288,317,NULL,NULL,'trash',943,944),
	(2289,323,NULL,NULL,'model',961,962),
	(2290,323,NULL,NULL,'downloadAttachment',963,964),
	(2291,323,NULL,NULL,'trash',965,966),
	(2292,1966,NULL,NULL,'model',1893,1894),
	(2293,1966,NULL,NULL,'downloadAttachment',1895,1896),
	(2294,331,NULL,NULL,'downloadAttachment',975,976),
	(2295,334,NULL,NULL,'getReviewModel',993,994),
	(2296,334,NULL,NULL,'getRelatedModel',995,996),
	(2297,334,NULL,NULL,'trash',997,998),
	(2298,334,NULL,NULL,'model',999,1000),
	(2299,334,NULL,NULL,'downloadAttachment',1001,1002),
	(2300,1,NULL,NULL,'RiskAppetites',2010,2023),
	(2301,2300,NULL,NULL,'index',2011,2012),
	(2302,2300,NULL,NULL,'edit',2013,2014),
	(2303,2300,NULL,NULL,'thresholdItem',2015,2016),
	(2304,2300,NULL,NULL,'isAuthorized',2017,2018),
	(2305,2300,NULL,NULL,'cancelAction',2019,2020),
	(2306,2300,NULL,NULL,'downloadAttachment',2021,2022),
	(2307,1368,NULL,NULL,'downloadAttachment',1727,1728),
	(2308,340,NULL,NULL,'downloadAttachment',1019,1020),
	(2309,347,NULL,NULL,'model',1037,1038),
	(2310,347,NULL,NULL,'downloadAttachment',1039,1040),
	(2311,347,NULL,NULL,'trash',1041,1042),
	(2312,355,NULL,NULL,'downloadAttachment',1053,1054),
	(2313,359,NULL,NULL,'trash',1077,1078),
	(2314,359,NULL,NULL,'processClassifications',1079,1080),
	(2315,359,NULL,NULL,'initOptions',1081,1082),
	(2316,359,NULL,NULL,'model',1083,1084),
	(2317,359,NULL,NULL,'downloadAttachment',1085,1086),
	(2318,369,NULL,NULL,'downloadAttachment',1101,1102),
	(2319,1,NULL,NULL,'SectionBase',2024,2033),
	(2320,2319,NULL,NULL,'model',2025,2026),
	(2321,2319,NULL,NULL,'isAuthorized',2027,2028),
	(2322,2319,NULL,NULL,'cancelAction',2029,2030),
	(2323,2319,NULL,NULL,'downloadAttachment',2031,2032),
	(2324,375,NULL,NULL,'downloadAttachment',1113,1114),
	(2325,379,NULL,NULL,'downloadAttachment',1129,1130),
	(2326,385,NULL,NULL,'model',1147,1148),
	(2327,385,NULL,NULL,'downloadAttachment',1149,1150),
	(2328,385,NULL,NULL,'trash',1151,1152),
	(2329,1,NULL,NULL,'SecurityIncidentStagesSecurityIncidents',2034,2041),
	(2330,2329,NULL,NULL,'isAuthorized',2035,2036),
	(2331,2329,NULL,NULL,'cancelAction',2037,2038),
	(2332,2329,NULL,NULL,'downloadAttachment',2039,2040),
	(2333,392,NULL,NULL,'model',1179,1180),
	(2334,392,NULL,NULL,'downloadAttachment',1181,1182),
	(2335,392,NULL,NULL,'trash',1183,1184),
	(2336,403,NULL,NULL,'downloadAttachment',1195,1196),
	(2337,407,NULL,NULL,'trash',1219,1220),
	(2338,407,NULL,NULL,'model',1221,1222),
	(2339,407,NULL,NULL,'downloadAttachment',1223,1224),
	(2340,1,NULL,NULL,'SecurityPolicyDocumentTypes',2042,2059),
	(2341,2340,NULL,NULL,'index',2043,2044),
	(2342,2340,NULL,NULL,'add',2045,2046),
	(2343,2340,NULL,NULL,'edit',2047,2048),
	(2344,2340,NULL,NULL,'delete',2049,2050),
	(2345,2340,NULL,NULL,'model',2051,2052),
	(2346,2340,NULL,NULL,'isAuthorized',2053,2054),
	(2347,2340,NULL,NULL,'cancelAction',2055,2056),
	(2348,2340,NULL,NULL,'downloadAttachment',2057,2058),
	(2349,418,NULL,NULL,'downloadAttachment',1237,1238),
	(2350,423,NULL,NULL,'model',1251,1252),
	(2351,423,NULL,NULL,'downloadAttachment',1253,1254),
	(2352,428,NULL,NULL,'trash',1271,1272),
	(2353,428,NULL,NULL,'add',1273,1274),
	(2354,428,NULL,NULL,'model',1275,1276),
	(2355,428,NULL,NULL,'downloadAttachment',1277,1278),
	(2356,433,NULL,NULL,'trash',1291,1292),
	(2357,433,NULL,NULL,'add',1293,1294),
	(2358,433,NULL,NULL,'model',1295,1296),
	(2359,433,NULL,NULL,'downloadAttachment',1297,1298),
	(2360,438,NULL,NULL,'trash',1317,1318),
	(2361,438,NULL,NULL,'model',1319,1320),
	(2362,438,NULL,NULL,'downloadAttachment',1321,1322),
	(2363,450,NULL,NULL,'downloadAttachment',1337,1338),
	(2364,456,NULL,NULL,'model',1353,1354),
	(2365,456,NULL,NULL,'downloadAttachment',1355,1356),
	(2366,456,NULL,NULL,'trash',1357,1358),
	(2367,462,NULL,NULL,'residualRisk',1391,1392),
	(2368,462,NULL,NULL,'downloadAttachment',1393,1394),
	(2369,474,NULL,NULL,'downloadAttachment',1405,1406),
	(2370,478,NULL,NULL,'model',1423,1424),
	(2371,478,NULL,NULL,'downloadAttachment',1425,1426),
	(2372,478,NULL,NULL,'trash',1427,1428),
	(2373,485,NULL,NULL,'model',1443,1444),
	(2374,485,NULL,NULL,'downloadAttachment',1445,1446),
	(2375,485,NULL,NULL,'trash',1447,1448),
	(2376,491,NULL,NULL,'trash',1467,1468),
	(2377,491,NULL,NULL,'processClassifications',1469,1470),
	(2378,491,NULL,NULL,'initOptions',1471,1472),
	(2379,491,NULL,NULL,'model',1473,1474),
	(2380,491,NULL,NULL,'downloadAttachment',1475,1476),
	(2381,500,NULL,NULL,'downloadAttachment',1491,1492),
	(2382,1475,NULL,NULL,'downloadAttachment',1739,1740),
	(2383,506,NULL,NULL,'checkConflicts',1525,1526),
	(2384,506,NULL,NULL,'downloadAttachment',1527,1528),
	(2385,518,NULL,NULL,'downloadAttachment',1543,1544),
	(2386,1125,NULL,NULL,'downloadAttachment',1628,1629),
	(2387,1130,NULL,NULL,'downloadAttachment',1646,1647),
	(2388,1138,NULL,NULL,'downloadAttachment',1688,1689),
	(2389,1,NULL,NULL,'AclExtras',2060,2061),
	(2390,1,NULL,NULL,'AdvancedFilters',2062,2085),
	(2391,2390,NULL,NULL,'AdvancedFilters',2063,2084),
	(2392,2391,NULL,NULL,'redirectAdvancedFilter',2064,2065),
	(2393,2391,NULL,NULL,'saveAdvancedFilter',2066,2067),
	(2394,2391,NULL,NULL,'deleteAdvancedFilter',2068,2069),
	(2395,2391,NULL,NULL,'exportAdvancedFilterToPdf',2070,2071),
	(2396,2391,NULL,NULL,'exportAdvancedFilterToCsv',2072,2073),
	(2397,2391,NULL,NULL,'exportDailyCountResults',2074,2075),
	(2398,2391,NULL,NULL,'exportDailyDataResults',2076,2077),
	(2399,2391,NULL,NULL,'isAuthorized',2078,2079),
	(2400,2391,NULL,NULL,'cancelAction',2080,2081),
	(2401,2391,NULL,NULL,'downloadAttachment',2082,2083),
	(2402,1613,NULL,NULL,'downloadAttachment',1862,1863),
	(2403,1622,NULL,NULL,'downloadAttachment',1880,1881),
	(2404,1,NULL,NULL,'AuditLog',2086,2087),
	(2405,1,NULL,NULL,'AutoComplete',2088,2089),
	(2406,2047,NULL,NULL,'downloadAttachment',194,195),
	(2407,2061,NULL,NULL,'downloadAttachment',1908,1909),
	(2408,1,NULL,NULL,'CakePdf',2090,2091),
	(2409,191,NULL,NULL,'Cron',559,574),
	(2410,2409,NULL,NULL,'job',560,561),
	(2411,2409,NULL,NULL,'index',562,563),
	(2412,2409,NULL,NULL,'getIndexUrlFromComponent',564,565),
	(2413,2409,NULL,NULL,'initEmailFromComponent',566,567),
	(2414,2409,NULL,NULL,'isAuthorized',568,569),
	(2415,2409,NULL,NULL,'cancelAction',570,571),
	(2416,2409,NULL,NULL,'downloadAttachment',572,573),
	(2417,1,NULL,NULL,'Crud',2092,2093),
	(2418,1,NULL,NULL,'CsvView',2094,2095),
	(2419,1818,NULL,NULL,'downloadAttachment',1750,1751),
	(2420,1828,NULL,NULL,'downloadAttachment',1772,1773),
	(2421,1844,NULL,NULL,'downloadAttachment',1788,1789),
	(2422,1,NULL,NULL,'CustomRoles',2096,2097),
	(2423,1,NULL,NULL,'CustomValidator',2098,2113),
	(2424,2423,NULL,NULL,'CustomValidator',2099,2112),
	(2425,2424,NULL,NULL,'index',2100,2101),
	(2426,2424,NULL,NULL,'setup',2102,2103),
	(2427,2424,NULL,NULL,'getValidation',2104,2105),
	(2428,2424,NULL,NULL,'isAuthorized',2106,2107),
	(2429,2424,NULL,NULL,'cancelAction',2108,2109),
	(2430,2424,NULL,NULL,'downloadAttachment',2110,2111),
	(2431,1,NULL,NULL,'Dashboard',2114,2145),
	(2432,2431,NULL,NULL,'DashboardKpis',2115,2132),
	(2433,2432,NULL,NULL,'user',2116,2117),
	(2434,2432,NULL,NULL,'admin',2118,2119),
	(2435,2432,NULL,NULL,'add',2120,2121),
	(2436,2432,NULL,NULL,'delete',2122,2123),
	(2437,2432,NULL,NULL,'sync',2124,2125),
	(2438,2432,NULL,NULL,'isAuthorized',2126,2127),
	(2439,2432,NULL,NULL,'cancelAction',2128,2129),
	(2440,2432,NULL,NULL,'downloadAttachment',2130,2131),
	(2441,2431,NULL,NULL,'Dashboards',2133,2144),
	(2442,2441,NULL,NULL,'user',2134,2135),
	(2443,2441,NULL,NULL,'saveFilter',2136,2137),
	(2444,2441,NULL,NULL,'isAuthorized',2138,2139),
	(2445,2441,NULL,NULL,'cancelAction',2140,2141),
	(2446,2441,NULL,NULL,'downloadAttachment',2142,2143),
	(2447,1159,NULL,NULL,'downloadAttachment',1702,1703),
	(2448,1,NULL,NULL,'EventManager',2146,2147),
	(2449,1,NULL,NULL,'FieldData',2148,2149),
	(2450,1,NULL,NULL,'HtmlPurifier',2150,2151),
	(2451,1563,NULL,NULL,'downloadAttachment',1804,1805),
	(2452,1,NULL,NULL,'InspectLog',2152,2153),
	(2453,1,NULL,NULL,'Migrations',2154,2155),
	(2454,1,NULL,NULL,'ModuleSettings',2156,2157),
	(2455,1,NULL,NULL,'ObjectStatus',2158,2159),
	(2456,2083,NULL,NULL,'downloadAttachment',1922,1923),
	(2457,1,NULL,NULL,'ReviewsPlanner',2160,2161),
	(2458,1,NULL,NULL,'Search',2162,2163),
	(2459,1,NULL,NULL,'SystemLogs',2164,2177),
	(2460,2459,NULL,NULL,'SystemLogs',2165,2176),
	(2461,2460,NULL,NULL,'index',2166,2167),
	(2462,2460,NULL,NULL,'model',2168,2169),
	(2463,2460,NULL,NULL,'isAuthorized',2170,2171),
	(2464,2460,NULL,NULL,'cancelAction',2172,2173),
	(2465,2460,NULL,NULL,'downloadAttachment',2174,2175),
	(2466,1,NULL,NULL,'ThirdPartyAudits',2178,2201),
	(2467,2466,NULL,NULL,'ThirdPartyAudits',2179,2200),
	(2468,2467,NULL,NULL,'login',2180,2181),
	(2469,2467,NULL,NULL,'logout',2182,2183),
	(2470,2467,NULL,NULL,'index',2184,2185),
	(2471,2467,NULL,NULL,'analyze',2186,2187),
	(2472,2467,NULL,NULL,'auditeeFeedbackStats',2188,2189),
	(2473,2467,NULL,NULL,'auditeeFeedback',2190,2191),
	(2474,2467,NULL,NULL,'auditeeExportFindings',2192,2193),
	(2475,2467,NULL,NULL,'isAuthorized',2194,2195),
	(2476,2467,NULL,NULL,'cancelAction',2196,2197),
	(2477,2467,NULL,NULL,'downloadAttachment',2198,2199),
	(2478,1,NULL,NULL,'Uploader',2202,2203),
	(2479,1,NULL,NULL,'UserFields',2204,2205),
	(2480,1,NULL,NULL,'Utils',2206,2207),
	(2481,1,NULL,NULL,'VendorAssessments',2208,2319),
	(2482,2481,NULL,NULL,'VendorAssessmentFeedbacks',2209,2238),
	(2483,2482,NULL,NULL,'login',2210,2211),
	(2484,2482,NULL,NULL,'logout',2212,2213),
	(2485,2482,NULL,NULL,'index',2214,2215),
	(2486,2482,NULL,NULL,'assessment',2216,2217),
	(2487,2482,NULL,NULL,'lockFeedback',2218,2219),
	(2488,2482,NULL,NULL,'questionAnswer',2220,2221),
	(2489,2482,NULL,NULL,'downloadQuestions',2222,2223),
	(2490,2482,NULL,NULL,'downloadFindings',2224,2225),
	(2491,2482,NULL,NULL,'initEmailFromComponent',2226,2227),
	(2492,2482,NULL,NULL,'getIndexUrlFromComponent',2228,2229),
	(2493,2482,NULL,NULL,'model',2230,2231),
	(2494,2482,NULL,NULL,'isAuthorized',2232,2233),
	(2495,2482,NULL,NULL,'cancelAction',2234,2235),
	(2496,2482,NULL,NULL,'downloadAttachment',2236,2237),
	(2497,2481,NULL,NULL,'VendorAssessmentFindings',2239,2258),
	(2498,2497,NULL,NULL,'index',2240,2241),
	(2499,2497,NULL,NULL,'add',2242,2243),
	(2500,2497,NULL,NULL,'edit',2244,2245),
	(2501,2497,NULL,NULL,'delete',2246,2247),
	(2502,2497,NULL,NULL,'trash',2248,2249),
	(2503,2497,NULL,NULL,'model',2250,2251),
	(2504,2497,NULL,NULL,'isAuthorized',2252,2253),
	(2505,2497,NULL,NULL,'cancelAction',2254,2255),
	(2506,2497,NULL,NULL,'downloadAttachment',2256,2257),
	(2507,2481,NULL,NULL,'VendorAssessmentQuestionnaires',2259,2276),
	(2508,2507,NULL,NULL,'add',2260,2261),
	(2509,2507,NULL,NULL,'upload',2262,2263),
	(2510,2507,NULL,NULL,'csv',2264,2265),
	(2511,2507,NULL,NULL,'downloadCsv',2266,2267),
	(2512,2507,NULL,NULL,'model',2268,2269),
	(2513,2507,NULL,NULL,'isAuthorized',2270,2271),
	(2514,2507,NULL,NULL,'cancelAction',2272,2273),
	(2515,2507,NULL,NULL,'downloadAttachment',2274,2275),
	(2516,2481,NULL,NULL,'VendorAssessmentQuestions',2277,2288),
	(2517,2516,NULL,NULL,'index',2278,2279),
	(2518,2516,NULL,NULL,'model',2280,2281),
	(2519,2516,NULL,NULL,'isAuthorized',2282,2283),
	(2520,2516,NULL,NULL,'cancelAction',2284,2285),
	(2521,2516,NULL,NULL,'downloadAttachment',2286,2287),
	(2522,2481,NULL,NULL,'VendorAssessments',2289,2318),
	(2523,2522,NULL,NULL,'index',2290,2291),
	(2524,2522,NULL,NULL,'add',2292,2293),
	(2525,2522,NULL,NULL,'edit',2294,2295),
	(2526,2522,NULL,NULL,'validateStep',2296,2297),
	(2527,2522,NULL,NULL,'start',2298,2299),
	(2528,2522,NULL,NULL,'stop',2300,2301),
	(2529,2522,NULL,NULL,'initEmailFromComponent',2302,2303),
	(2530,2522,NULL,NULL,'getIndexUrlFromComponent',2304,2305),
	(2531,2522,NULL,NULL,'model',2306,2307),
	(2532,2522,NULL,NULL,'isAuthorized',2308,2309),
	(2533,2522,NULL,NULL,'cancelAction',2310,2311),
	(2534,2522,NULL,NULL,'downloadAttachment',2312,2313),
	(2535,2522,NULL,NULL,'delete',2314,2315),
	(2536,2522,NULL,NULL,'trash',2316,2317),
	(2537,1,NULL,NULL,'Visualisation',2320,2349),
	(2538,2537,NULL,NULL,'Visualisation',2321,2334),
	(2539,2538,NULL,NULL,'redirectGateway',2322,2323),
	(2540,2538,NULL,NULL,'share',2324,2325),
	(2541,2538,NULL,NULL,'share_old',2326,2327),
	(2542,2538,NULL,NULL,'isAuthorized',2328,2329),
	(2543,2538,NULL,NULL,'cancelAction',2330,2331),
	(2544,2538,NULL,NULL,'downloadAttachment',2332,2333),
	(2545,2537,NULL,NULL,'VisualisationSettings',2335,2348),
	(2546,2545,NULL,NULL,'index',2336,2337),
	(2547,2545,NULL,NULL,'edit',2338,2339),
	(2548,2545,NULL,NULL,'sync',2340,2341),
	(2549,2545,NULL,NULL,'isAuthorized',2342,2343),
	(2550,2545,NULL,NULL,'cancelAction',2344,2345),
	(2551,2545,NULL,NULL,'downloadAttachment',2346,2347),
	(2552,524,NULL,NULL,'WorkflowInstances',1547,1560),
	(2553,2552,NULL,NULL,'manage',1548,1549),
	(2554,2552,NULL,NULL,'handleRequest',1550,1551),
	(2555,2552,NULL,NULL,'forceStageForm',1552,1553),
	(2556,2552,NULL,NULL,'isAuthorized',1554,1555),
	(2557,2552,NULL,NULL,'cancelAction',1556,1557),
	(2558,2552,NULL,NULL,'downloadAttachment',1558,1559),
	(2559,524,NULL,NULL,'WorkflowSettings',1561,1570),
	(2560,2559,NULL,NULL,'edit',1562,1563),
	(2561,2559,NULL,NULL,'isAuthorized',1564,1565),
	(2562,2559,NULL,NULL,'cancelAction',1566,1567),
	(2563,2559,NULL,NULL,'downloadAttachment',1568,1569),
	(2564,524,NULL,NULL,'WorkflowStageSteps',1571,1588),
	(2565,2564,NULL,NULL,'delete',1572,1573),
	(2566,2564,NULL,NULL,'add',1574,1575),
	(2567,2564,NULL,NULL,'edit',1576,1577),
	(2568,2564,NULL,NULL,'addCondition',1578,1579),
	(2569,2564,NULL,NULL,'addConditionValue',1580,1581),
	(2570,2564,NULL,NULL,'isAuthorized',1582,1583),
	(2571,2564,NULL,NULL,'cancelAction',1584,1585),
	(2572,2564,NULL,NULL,'downloadAttachment',1586,1587),
	(2573,524,NULL,NULL,'WorkflowStages',1589,1604),
	(2574,2573,NULL,NULL,'index',1590,1591),
	(2575,2573,NULL,NULL,'delete',1592,1593),
	(2576,2573,NULL,NULL,'add',1594,1595),
	(2577,2573,NULL,NULL,'edit',1596,1597),
	(2578,2573,NULL,NULL,'isAuthorized',1598,1599),
	(2579,2573,NULL,NULL,'cancelAction',1600,1601),
	(2580,2573,NULL,NULL,'downloadAttachment',1602,1603);
ALTER TABLE `acos` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `advanced_filters` WRITE;
ALTER TABLE `advanced_filters` DISABLE KEYS;
ALTER TABLE `advanced_filters` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `advanced_filter_crons` WRITE;
ALTER TABLE `advanced_filter_crons` DISABLE KEYS;
ALTER TABLE `advanced_filter_crons` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `advanced_filter_cron_result_items` WRITE;
ALTER TABLE `advanced_filter_cron_result_items` DISABLE KEYS;
ALTER TABLE `advanced_filter_cron_result_items` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `advanced_filter_user_settings` WRITE;
ALTER TABLE `advanced_filter_user_settings` DISABLE KEYS;
ALTER TABLE `advanced_filter_user_settings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `advanced_filter_values` WRITE;
ALTER TABLE `advanced_filter_values` DISABLE KEYS;
ALTER TABLE `advanced_filter_values` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `aros` WRITE;
ALTER TABLE `aros` DISABLE KEYS;
INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES 
	(1,NULL,'Group',10,NULL,1,20),
	(2,11,'User',1,NULL,2,5),
	(3,NULL,'Group',11,NULL,21,24),
	(4,NULL,'Group',12,NULL,25,28),
	(5,NULL,'Group',13,NULL,29,32),
	(6,2,'CustomRolesUser',1,NULL,3,4),
	(7,1,'CustomRolesGroup',1,NULL,18,19),
	(8,3,'CustomRolesGroup',2,NULL,22,23),
	(9,4,'CustomRolesGroup',3,NULL,26,27),
	(10,5,'CustomRolesGroup',4,NULL,30,31);
ALTER TABLE `aros` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `aros_acos` WRITE;
ALTER TABLE `aros_acos` DISABLE KEYS;
INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES 
	(1,1,1,'1','1','1','1'),
	(3,4,263,'1','1','1','1'),
	(4,4,264,'1','1','1','1'),
	(5,3,144,'1','1','1','1'),
	(7,3,3,'1','1','1','1'),
	(9,5,1,'1','1','1','1'),
	(10,5,473,'-1','-1','-1','-1'),
	(11,5,469,'-1','-1','-1','-1'),
	(12,5,470,'-1','-1','-1','-1'),
	(13,5,466,'-1','-1','-1','-1'),
	(14,5,464,'-1','-1','-1','-1'),
	(15,5,463,'-1','-1','-1','-1'),
	(16,5,872,'-1','-1','-1','-1'),
	(17,5,465,'-1','-1','-1','-1'),
	(18,5,468,'-1','-1','-1','-1'),
	(19,5,471,'-1','-1','-1','-1'),
	(20,5,472,'-1','-1','-1','-1'),
	(21,5,467,'-1','-1','-1','-1'),
	(22,3,122,'1','1','1','1'),
	(23,3,123,'1','1','1','1'),
	(24,3,125,'1','1','1','1'),
	(25,3,36,'1','1','1','1'),
	(26,3,37,'1','1','1','1'),
	(27,3,38,'1','1','1','1'),
	(29,4,696,'1','1','1','1'),
	(30,3,696,'1','1','1','1'),
	(31,4,514,'1','1','1','1'),
	(32,3,514,'1','1','1','1'),
	(33,3,512,'1','1','1','1'),
	(34,4,512,'1','1','1','1'),
	(35,4,513,'1','1','1','1'),
	(36,3,513,'1','1','1','1'),
	(37,4,515,'1','1','1','1'),
	(38,3,515,'1','1','1','1'),
	(39,3,4,'1','1','1','1'),
	(40,3,152,'1','1','1','1'),
	(41,1,2104,'1','1','1','1'),
	(42,3,2105,'1','0','0','0'),
	(43,4,2105,'1','0','0','0'),
	(44,5,2105,'1','0','0','0');
ALTER TABLE `aros_acos` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `asset_classification_types` WRITE;
ALTER TABLE `asset_classification_types` DISABLE KEYS;
ALTER TABLE `asset_classification_types` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `asset_classifications` WRITE;
ALTER TABLE `asset_classifications` DISABLE KEYS;
ALTER TABLE `asset_classifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `asset_classifications_assets` WRITE;
ALTER TABLE `asset_classifications_assets` DISABLE KEYS;
ALTER TABLE `asset_classifications_assets` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `asset_labels` WRITE;
ALTER TABLE `asset_labels` DISABLE KEYS;
ALTER TABLE `asset_labels` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `asset_media_types` WRITE;
ALTER TABLE `asset_media_types` DISABLE KEYS;
INSERT INTO `asset_media_types` (`id`, `name`, `editable`, `created`, `modified`) VALUES 
	(1,'Data Asset',0,NULL,NULL),
	(2,'Facilities',0,NULL,NULL),
	(3,'People',0,NULL,NULL),
	(4,'Hardware',0,NULL,NULL),
	(5,'Software',0,NULL,NULL),
	(6,'IT Service',0,NULL,NULL),
	(7,'Network',0,NULL,NULL),
	(8,'Financial',0,NULL,NULL);
ALTER TABLE `asset_media_types` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `asset_media_types_threats` WRITE;
ALTER TABLE `asset_media_types_threats` DISABLE KEYS;
INSERT INTO `asset_media_types_threats` (`id`, `asset_media_type_id`, `threat_id`) VALUES 
	(1,1,6),
	(2,1,7),
	(3,1,10),
	(4,1,16),
	(5,1,27),
	(6,2,2),
	(7,2,3),
	(8,2,18),
	(9,2,19),
	(10,2,20),
	(12,2,30),
	(13,2,31),
	(14,2,32),
	(15,3,1),
	(16,3,2),
	(17,3,3),
	(18,3,4),
	(19,3,5),
	(20,3,6),
	(21,3,7),
	(22,3,13),
	(23,3,14),
	(24,3,15),
	(25,3,16),
	(26,3,17),
	(27,3,21),
	(28,3,26),
	(29,3,27),
	(30,3,30),
	(31,3,32),
	(32,3,33),
	(33,3,34),
	(34,3,35),
	(35,4,4),
	(36,4,5),
	(37,4,14),
	(38,4,15),
	(39,5,8),
	(40,5,9),
	(41,5,10),
	(42,5,14),
	(43,5,15),
	(44,5,21),
	(45,5,22),
	(46,5,23),
	(47,5,33),
	(48,6,8),
	(49,6,9),
	(50,6,10),
	(51,6,13),
	(52,6,14),
	(53,6,15),
	(54,6,21),
	(55,6,22),
	(56,6,23),
	(57,6,26),
	(58,6,30),
	(59,6,33),
	(60,7,8),
	(61,7,9),
	(62,7,10),
	(63,7,11),
	(64,7,12),
	(65,7,14),
	(66,7,15),
	(67,7,21),
	(68,7,22),
	(69,7,24),
	(70,7,25),
	(71,7,26),
	(72,8,16),
	(73,8,27);
ALTER TABLE `asset_media_types_threats` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `asset_media_types_vulnerabilities` WRITE;
ALTER TABLE `asset_media_types_vulnerabilities` DISABLE KEYS;
INSERT INTO `asset_media_types_vulnerabilities` (`id`, `asset_media_type_id`, `vulnerability_id`) VALUES 
	(1,1,2),
	(2,1,3),
	(3,3,1),
	(4,3,3),
	(5,5,2),
	(6,5,3),
	(7,6,2),
	(8,6,3),
	(9,7,3),
	(10,8,3),
	(11,8,2);
ALTER TABLE `asset_media_types_vulnerabilities` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `assets` WRITE;
ALTER TABLE `assets` DISABLE KEYS;
ALTER TABLE `assets` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `assets_business_units` WRITE;
ALTER TABLE `assets_business_units` DISABLE KEYS;
ALTER TABLE `assets_business_units` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `assets_compliance_managements` WRITE;
ALTER TABLE `assets_compliance_managements` DISABLE KEYS;
ALTER TABLE `assets_compliance_managements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `assets_legals` WRITE;
ALTER TABLE `assets_legals` DISABLE KEYS;
ALTER TABLE `assets_legals` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `assets_policy_exceptions` WRITE;
ALTER TABLE `assets_policy_exceptions` DISABLE KEYS;
ALTER TABLE `assets_policy_exceptions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `assets_related` WRITE;
ALTER TABLE `assets_related` DISABLE KEYS;
ALTER TABLE `assets_related` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `assets_risks` WRITE;
ALTER TABLE `assets_risks` DISABLE KEYS;
ALTER TABLE `assets_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `assets_security_incidents` WRITE;
ALTER TABLE `assets_security_incidents` DISABLE KEYS;
ALTER TABLE `assets_security_incidents` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `assets_third_party_risks` WRITE;
ALTER TABLE `assets_third_party_risks` DISABLE KEYS;
ALTER TABLE `assets_third_party_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `attachments` WRITE;
ALTER TABLE `attachments` DISABLE KEYS;
ALTER TABLE `attachments` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `audits` WRITE;
ALTER TABLE `audits` DISABLE KEYS;
INSERT INTO `audits` (`id`, `version`, `event`, `model`, `entity_id`, `request_id`, `json_object`, `description`, `source_id`, `restore_id`, `created`) VALUES 
	('5ab1170c-24a8-4ece-827b-45d8e5196b4e',1,'CREATE','DashboardKpi','1','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"1","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:32","modified":"2018-03-20 15:13:32","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:32'),
	('5ab1170d-1290-422e-8365-469ae5196b4e',2,'EDIT','DashboardKpi','3','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"3","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:33","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170d-31d0-463c-9117-44f0e5196b4e',3,'EDIT','DashboardKpi','3','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"3","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:33","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170d-324c-4359-b6a9-4692e5196b4e',2,'EDIT','DashboardKpi','4','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"4","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:33","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170d-3470-4cb2-97bd-4c5be5196b4e',1,'CREATE','DashboardKpi','4','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"4","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:33","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170d-42e8-4693-9b07-4c80e5196b4e',1,'CREATE','DashboardKpi','5','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"5","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:33","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170d-475c-4e9b-9e15-4508e5196b4e',3,'EDIT','DashboardKpi','2','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"2","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:33","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170d-60cc-447d-be66-4759e5196b4e',1,'CREATE','DashboardKpi','3','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"3","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:33","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170d-7700-4756-88d4-4297e5196b4e',2,'EDIT','DashboardKpi','1','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"1","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:32","modified":"2018-03-20 15:13:33","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170d-8ac8-4e69-837d-4e2ae5196b4e',3,'EDIT','DashboardKpi','4','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"4","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:33","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170d-93ec-4112-9757-44d9e5196b4e',3,'EDIT','DashboardKpi','1','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"1","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:32","modified":"2018-03-20 15:13:33","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170d-c298-494c-b4eb-422fe5196b4e',1,'CREATE','DashboardKpi','2','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"2","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:33","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170d-d728-479b-961a-4699e5196b4e',2,'EDIT','DashboardKpi','2','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"2","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:33","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:33'),
	('5ab1170e-0790-44df-b61e-4f92e5196b4e',2,'EDIT','DashboardKpi','6','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"6","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-0e44-4020-b2f8-4fe5e5196b4e',1,'CREATE','DashboardKpi','10','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"10","class_name":"CustomQueryKpi","title":"Current Total Risk Score","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"CustomQuery\\":\\"total_risk_score\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-1548-46cd-8933-4d96e5196b4e',1,'CREATE','DashboardKpi','9','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"9","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-3a10-4d93-a8e6-4019e5196b4e',3,'EDIT','DashboardKpi','10','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"10","class_name":"CustomQueryKpi","title":"Current Total Risk Score","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_risk_score\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-4110-4eca-9dd8-4d76e5196b4e',3,'EDIT','DashboardKpi','7','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"7","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Asset Risk Management","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-43ec-4654-9f9e-4bf8e5196b4e',1,'CREATE','DashboardKpi','8','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"8","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-4df0-4eb2-9016-4adee5196b4e',2,'EDIT','DashboardKpi','9','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"9","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-50ec-41b2-a8d5-49e2e5196b4e',1,'CREATE','DashboardKpi','7','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"7","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Asset Risk Management","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-5228-47fb-925b-4956e5196b4e',2,'EDIT','DashboardKpi','7','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"7","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Asset Risk Management","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-6770-46b1-8b31-4dcce5196b4e',3,'EDIT','DashboardKpi','5','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"5","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:34","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-7060-43bb-8dc0-4e46e5196b4e',2,'EDIT','DashboardKpi','8','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"8","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-7314-425d-9d64-41bee5196b4e',1,'CREATE','DashboardKpi','12','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"12","class_name":"Dashboard.DashboardKpiObject","title":"Asset Risk Management created during the past two weeks","model":"Risk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-8764-47fc-b59b-43e1e5196b4e',1,'CREATE','DashboardKpi','11','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"11","class_name":"CustomQueryKpi","title":"Current Total Residual Score","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"CustomQuery\\":\\"total_residual_score\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-8b14-4222-94b5-47c3e5196b4e',2,'EDIT','DashboardKpi','10','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"10","class_name":"CustomQueryKpi","title":"Current Total Risk Score","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_risk_score\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-8e9c-4e16-9198-4072e5196b4e',2,'EDIT','DashboardKpi','5','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"5","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:33","modified":"2018-03-20 15:13:34","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-9210-478a-b2b4-4433e5196b4e',1,'CREATE','DashboardKpi','6','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"6","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-9b54-4dd3-bd79-4388e5196b4e',3,'EDIT','DashboardKpi','8','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"8","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-bd4c-4581-8553-4459e5196b4e',3,'EDIT','DashboardKpi','9','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"9","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-cf7c-42b0-bb5c-4a0ae5196b4e',2,'EDIT','DashboardKpi','11','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"11","class_name":"CustomQueryKpi","title":"Current Total Residual Score","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_residual_score\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-eb5c-4289-b55c-42bee5196b4e',3,'EDIT','DashboardKpi','6','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"6","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"Risk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170e-fc78-4517-8212-425ce5196b4e',3,'EDIT','DashboardKpi','11','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"11","class_name":"CustomQueryKpi","title":"Current Total Residual Score","model":"Risk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_residual_score\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:34","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:34'),
	('5ab1170f-0294-4fe4-b030-46f6e5196b4e',1,'CREATE','DashboardKpi','14','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"14","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-05b0-4446-adb3-4a81e5196b4e',2,'EDIT','DashboardKpi','17','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"17","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-1430-4f91-ae62-4246e5196b4e',2,'EDIT','DashboardKpi','18','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"18","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-1558-4c1a-98c4-4f35e5196b4e',3,'EDIT','DashboardKpi','16','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"16","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-523c-4964-abc8-4a39e5196b4e',3,'EDIT','DashboardKpi','18','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"18","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-57e0-4782-b65b-4fb0e5196b4e',1,'CREATE','DashboardKpi','18','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"18","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-7294-4fc5-ac7e-4999e5196b4e',3,'EDIT','DashboardKpi','12','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"12","class_name":"Dashboard.DashboardKpiObject","title":"Asset Risk Management created during the past two weeks","model":"Risk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:35","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-7b54-4d7f-b7e8-420fe5196b4e',1,'CREATE','DashboardKpi','13','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"13","class_name":"Dashboard.DashboardKpiObject","title":"Asset Risk Management deleted during the past two weeks","model":"Risk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-7dc4-4d16-b237-4fcae5196b4e',2,'EDIT','DashboardKpi','13','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"13","class_name":"Dashboard.DashboardKpiObject","title":"Asset Risk Management deleted during the past two weeks","model":"Risk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-8440-41bd-91c7-4422e5196b4e',3,'EDIT','DashboardKpi','14','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"14","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e',1,'CREATE','DashboardKpi','17','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"17","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-9b24-49d8-ad58-4cbae5196b4e',1,'CREATE','DashboardKpi','19','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"19","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-aad0-4c9e-a6bc-4753e5196b4e',2,'EDIT','DashboardKpi','14','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"14","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-bb5c-4e99-a20c-4acde5196b4e',1,'CREATE','DashboardKpi','16','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"16","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-be04-4793-9a04-4e7ce5196b4e',2,'EDIT','DashboardKpi','19','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"19","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-be38-42c7-96c6-4690e5196b4e',2,'EDIT','DashboardKpi','15','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"15","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-cbc0-4844-9a39-418ae5196b4e',3,'EDIT','DashboardKpi','15','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"15","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-cbdc-412f-899e-49c8e5196b4e',2,'EDIT','DashboardKpi','12','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"12","class_name":"Dashboard.DashboardKpiObject","title":"Asset Risk Management created during the past two weeks","model":"Risk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:34","modified":"2018-03-20 15:13:35","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-df10-4f6e-a565-4bd2e5196b4e',3,'EDIT','DashboardKpi','13','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"13","class_name":"Dashboard.DashboardKpiObject","title":"Asset Risk Management deleted during the past two weeks","model":"Risk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-e9ac-4f4b-83b3-4f87e5196b4e',2,'EDIT','DashboardKpi','16','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"16","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-ef38-4ab6-9c2e-4fb9e5196b4e',3,'EDIT','DashboardKpi','17','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"17","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab1170f-ff7c-442a-bd19-44f3e5196b4e',1,'CREATE','DashboardKpi','15','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"15","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:35","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:35'),
	('5ab11710-0a40-4ba9-bc98-484de5196b4e',3,'EDIT','DashboardKpi','19','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"19","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"BusinessContinuity","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:35","modified":"2018-03-20 15:13:36","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-0ae0-40f1-b062-4db8e5196b4e',1,'CREATE','DashboardKpi','23','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"23","class_name":"CustomQueryKpi","title":"Current Total Risk Score","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"CustomQuery\\":\\"total_risk_score\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-0fec-49f4-a48c-45c1e5196b4e',2,'EDIT','DashboardKpi','24','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"24","class_name":"CustomQueryKpi","title":"Current Total Residual Score","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_residual_score\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-1ae4-4c3e-a366-4f23e5196b4e',3,'EDIT','DashboardKpi','23','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"23","class_name":"CustomQueryKpi","title":"Current Total Risk Score","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_risk_score\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-2554-432f-9c80-4ac9e5196b4e',3,'EDIT','DashboardKpi','24','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"24","class_name":"CustomQueryKpi","title":"Current Total Residual Score","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_residual_score\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-25bc-4403-bed6-4f3ee5196b4e',1,'CREATE','DashboardKpi','21','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"21","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-2fc8-43e7-84d8-4f50e5196b4e',3,'EDIT','DashboardKpi','28','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"28","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-3d0c-42a6-870a-4816e5196b4e',2,'EDIT','DashboardKpi','29','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"29","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-400c-4a1f-a0ad-4be5e5196b4e',1,'CREATE','DashboardKpi','20','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"20","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Business Impact Analysis","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-48a4-42df-95ed-4b5ce5196b4e',1,'CREATE','DashboardKpi','29','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"29","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-552c-4a36-9348-49f5e5196b4e',2,'EDIT','DashboardKpi','23','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"23","class_name":"CustomQueryKpi","title":"Current Total Risk Score","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_risk_score\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-58a0-46c5-b53d-4618e5196b4e',3,'EDIT','DashboardKpi','26','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"26","class_name":"Dashboard.DashboardKpiObject","title":"Business Impact Analysis deleted during the past two weeks","model":"BusinessContinuity","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-5ec8-4388-b5f0-4123e5196b4e',1,'CREATE','DashboardKpi','25','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"25","class_name":"Dashboard.DashboardKpiObject","title":"Business Impact Analysis created during the past two weeks","model":"BusinessContinuity","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-6578-4f10-b07b-4175e5196b4e',2,'EDIT','DashboardKpi','22','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"22","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-7af0-4234-bfa7-40bae5196b4e',1,'CREATE','DashboardKpi','30','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"30","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-7e44-4e3c-9015-444ce5196b4e',1,'CREATE','DashboardKpi','22','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"22","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-8a80-4f5e-8e7b-4615e5196b4e',2,'EDIT','DashboardKpi','27','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"27","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-8db4-44f7-8877-460ee5196b4e',2,'EDIT','DashboardKpi','25','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"25","class_name":"Dashboard.DashboardKpiObject","title":"Business Impact Analysis created during the past two weeks","model":"BusinessContinuity","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-a434-4754-82a9-4cdae5196b4e',1,'CREATE','DashboardKpi','26','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"26","class_name":"Dashboard.DashboardKpiObject","title":"Business Impact Analysis deleted during the past two weeks","model":"BusinessContinuity","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-aa08-4882-b274-4e18e5196b4e',1,'CREATE','DashboardKpi','24','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"24","class_name":"CustomQueryKpi","title":"Current Total Residual Score","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"CustomQuery\\":\\"total_residual_score\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-aa44-4775-a057-42bde5196b4e',1,'CREATE','DashboardKpi','27','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"27","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-b204-42e3-bab8-4053e5196b4e',3,'EDIT','DashboardKpi','27','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"27","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-b24c-4c4a-a10e-4830e5196b4e',2,'EDIT','DashboardKpi','20','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"20","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Business Impact Analysis","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-bcd0-48c2-a024-4a59e5196b4e',2,'EDIT','DashboardKpi','21','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"21","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-c9b4-49db-a456-4a95e5196b4e',2,'EDIT','DashboardKpi','26','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"26","class_name":"Dashboard.DashboardKpiObject","title":"Business Impact Analysis deleted during the past two weeks","model":"BusinessContinuity","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-cfe8-47f7-9aaa-430be5196b4e',3,'EDIT','DashboardKpi','20','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"20","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Business Impact Analysis","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-d920-4ae3-8208-493ce5196b4e',3,'EDIT','DashboardKpi','22','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"22","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-df94-4b98-8f9b-4760e5196b4e',1,'CREATE','DashboardKpi','28','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"28","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-e148-4f13-bf14-4a58e5196b4e',3,'EDIT','DashboardKpi','29','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"29","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-f36c-4fbf-97cb-4ba0e5196b4e',2,'EDIT','DashboardKpi','28','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"28","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-f778-4f7d-8f2b-4401e5196b4e',3,'EDIT','DashboardKpi','21','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"21","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"BusinessContinuity","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11710-fae4-4f14-8d9b-465de5196b4e',3,'EDIT','DashboardKpi','25','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"25","class_name":"Dashboard.DashboardKpiObject","title":"Business Impact Analysis created during the past two weeks","model":"BusinessContinuity","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:36'),
	('5ab11711-10b8-4a92-bfea-45ede5196b4e',2,'EDIT','DashboardKpi','34','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"34","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-23ac-4e61-b567-45b6e5196b4e',1,'CREATE','DashboardKpi','39','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"39","class_name":"Dashboard.DashboardKpiObject","title":"Third Party Risk Management deleted during the past two weeks","model":"ThirdPartyRisk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-2470-493d-bbb9-4951e5196b4e',1,'CREATE','DashboardKpi','31','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"31","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-31d8-464f-a34e-4cdde5196b4e',3,'EDIT','DashboardKpi','32','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"32","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-3e0c-442c-ba99-4ec2e5196b4e',1,'CREATE','DashboardKpi','35','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"35","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-5a50-41ef-bb48-4823e5196b4e',3,'EDIT','DashboardKpi','31','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"31","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-5e28-4604-84e5-4975e5196b4e',1,'CREATE','DashboardKpi','38','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"38","class_name":"Dashboard.DashboardKpiObject","title":"Third Party Risk Management created during the past two weeks","model":"ThirdPartyRisk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-6118-439e-8035-414ae5196b4e',3,'EDIT','DashboardKpi','33','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"33","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Third Party Risk Management","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-65bc-4ef6-9eb5-4beae5196b4e',1,'CREATE','DashboardKpi','36','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"36","class_name":"CustomQueryKpi","title":"Current Total Risk Score","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"CustomQuery\\":\\"total_risk_score\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-6768-417e-893c-4419e5196b4e',3,'EDIT','DashboardKpi','39','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"39","class_name":"Dashboard.DashboardKpiObject","title":"Third Party Risk Management deleted during the past two weeks","model":"ThirdPartyRisk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-7654-4d16-9beb-486fe5196b4e',1,'CREATE','DashboardKpi','33','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"33","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Third Party Risk Management","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-7c84-43a2-aa9a-414ee5196b4e',2,'EDIT','DashboardKpi','31','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"31","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-7ce4-42a0-b8cc-49c6e5196b4e',2,'EDIT','DashboardKpi','35','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"35","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-8d7c-49d5-bac1-46a1e5196b4e',2,'EDIT','DashboardKpi','39','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"39","class_name":"Dashboard.DashboardKpiObject","title":"Third Party Risk Management deleted during the past two weeks","model":"ThirdPartyRisk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-977c-40e8-9c7a-43d9e5196b4e',1,'CREATE','DashboardKpi','32','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"32","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-9f08-4bc9-833c-46a1e5196b4e',2,'EDIT','DashboardKpi','37','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"37","class_name":"CustomQueryKpi","title":"Current Total Residual Score","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_residual_score\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-aac8-4bf0-b21d-49c8e5196b4e',1,'CREATE','DashboardKpi','34','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"34","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-af38-4f38-b0ef-4c18e5196b4e',3,'EDIT','DashboardKpi','38','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"38","class_name":"Dashboard.DashboardKpiObject","title":"Third Party Risk Management created during the past two weeks","model":"ThirdPartyRisk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-b10c-49f2-aecb-4484e5196b4e',2,'EDIT','DashboardKpi','32','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"32","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-be1c-4dc7-97d2-488ae5196b4e',1,'CREATE','DashboardKpi','37','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"37","class_name":"CustomQueryKpi","title":"Current Total Residual Score","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"CustomQuery\\":\\"total_residual_score\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-c15c-4491-957f-4aa9e5196b4e',3,'EDIT','DashboardKpi','34','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"34","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-c878-4914-9f48-487ee5196b4e',2,'EDIT','DashboardKpi','33','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"33","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Third Party Risk Management","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-cd08-45df-998e-409be5196b4e',2,'EDIT','DashboardKpi','36','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"36","class_name":"CustomQueryKpi","title":"Current Total Risk Score","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_risk_score\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-cde0-4a9a-adc9-4cb3e5196b4e',3,'EDIT','DashboardKpi','36','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"36","class_name":"CustomQueryKpi","title":"Current Total Risk Score","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_risk_score\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-d9e8-48a1-8385-4f00e5196b4e',3,'EDIT','DashboardKpi','37','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"37","class_name":"CustomQueryKpi","title":"Current Total Residual Score","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"CustomQuery\\":\\"total_residual_score\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-e278-46fe-97b2-4bc8e5196b4e',2,'EDIT','DashboardKpi','30','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"30","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:36","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-ec44-4728-8d3d-475ee5196b4e',2,'EDIT','DashboardKpi','38','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"38","class_name":"Dashboard.DashboardKpiObject","title":"Third Party Risk Management created during the past two weeks","model":"ThirdPartyRisk","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-f598-4681-b059-486fe5196b4e',1,'CREATE','DashboardKpi','40','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"40","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-f630-4193-81da-4a38e5196b4e',3,'EDIT','DashboardKpi','35','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"35","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"ThirdPartyRisk","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:37","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11711-f634-4d7f-9751-487ae5196b4e',3,'EDIT','DashboardKpi','30','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"30","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ThirdPartyRisk","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Stakeholder\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:36","modified":"2018-03-20 15:13:37","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:37'),
	('5ab11712-1ce4-476c-9c7e-45aee5196b4e',1,'CREATE','DashboardKpi','41','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"41","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"expired\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-1e44-4165-888d-4a9fe5196b4e',3,'EDIT','DashboardKpi','40','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"40","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:38","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-2fd0-4240-8015-4198e5196b4e',2,'EDIT','DashboardKpi','42','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"42","class_name":"Visualisation.VisualizedKpi","title":"Coming Deadline (14 Days)","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"comming_dates\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-4ad4-4cf8-8ab6-4ccfe5196b4e',2,'EDIT','DashboardKpi','48','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"48","class_name":"Dashboard.DashboardKpiObject","title":"Projects created during the past two weeks","model":"Project","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-4ecc-43aa-af6f-4463e5196b4e',1,'CREATE','DashboardKpi','42','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"42","class_name":"Visualisation.VisualizedKpi","title":"Coming Deadline (14 Days)","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"comming_dates\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-50bc-48e4-a58d-459de5196b4e',2,'EDIT','DashboardKpi','47','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"47","class_name":"Dashboard.DashboardKpiObject","title":"Project with Expired Tasks","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"expired_tasks\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-5388-4b8f-a9d0-4420e5196b4e',3,'EDIT','DashboardKpi','46','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"46","class_name":"Dashboard.DashboardKpiObject","title":"Coming Deadline (14 Days)","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"comming_dates\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-5eb4-4309-ac42-4ae2e5196b4e',2,'EDIT','DashboardKpi','44','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"44","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Projects","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-73ac-4936-8b2e-41bde5196b4e',2,'EDIT','DashboardKpi','41','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"41","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"expired\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-7838-4a3f-8d55-4defe5196b4e',3,'EDIT','DashboardKpi','45','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"45","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"expired\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-8118-4c9f-91e5-4463e5196b4e',3,'EDIT','DashboardKpi','47','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"47","class_name":"Dashboard.DashboardKpiObject","title":"Project with Expired Tasks","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"expired_tasks\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-828c-4979-a86d-4bd7e5196b4e',3,'EDIT','DashboardKpi','48','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"48","class_name":"Dashboard.DashboardKpiObject","title":"Projects created during the past two weeks","model":"Project","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-82f8-44f8-8979-4f12e5196b4e',3,'EDIT','DashboardKpi','49','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"49","class_name":"Dashboard.DashboardKpiObject","title":"Projects deleted during the past two weeks","model":"Project","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-93e4-4586-b10e-4297e5196b4e',3,'EDIT','DashboardKpi','44','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"44","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Projects","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-a018-428f-ae88-4a40e5196b4e',2,'EDIT','DashboardKpi','46','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"46","class_name":"Dashboard.DashboardKpiObject","title":"Coming Deadline (14 Days)","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"comming_dates\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-ab6c-4f13-aa03-43ede5196b4e',1,'CREATE','DashboardKpi','47','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"47","class_name":"Dashboard.DashboardKpiObject","title":"Project with Expired Tasks","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"expired_tasks\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-ace8-4a21-bd28-4bf7e5196b4e',2,'EDIT','DashboardKpi','43','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"43","class_name":"Visualisation.VisualizedKpi","title":"Project with Expired Tasks","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"expired_tasks\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-ad84-4cb3-8c86-4567e5196b4e',1,'CREATE','DashboardKpi','48','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"48","class_name":"Dashboard.DashboardKpiObject","title":"Projects created during the past two weeks","model":"Project","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-c084-499e-bb0a-4979e5196b4e',1,'CREATE','DashboardKpi','49','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"49","class_name":"Dashboard.DashboardKpiObject","title":"Projects deleted during the past two weeks","model":"Project","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-d264-4a7e-bee5-4dece5196b4e',3,'EDIT','DashboardKpi','41','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"41","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"expired\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-d738-450b-a047-45cfe5196b4e',1,'CREATE','DashboardKpi','45','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"45","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"expired\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-dd00-45d9-8b49-45bbe5196b4e',1,'CREATE','DashboardKpi','46','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"46","class_name":"Dashboard.DashboardKpiObject","title":"Coming Deadline (14 Days)","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"comming_dates\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-e1e4-48aa-b276-4c5ee5196b4e',1,'CREATE','DashboardKpi','44','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"44","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Projects","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-e5d8-413e-86f3-49aee5196b4e',2,'EDIT','DashboardKpi','40','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"40","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:37","modified":"2018-03-20 15:13:38","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-e840-4a96-ae31-4143e5196b4e',1,'CREATE','DashboardKpi','43','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"43","class_name":"Visualisation.VisualizedKpi","title":"Project with Expired Tasks","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"expired_tasks\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-eb94-42b7-974f-43d7e5196b4e',3,'EDIT','DashboardKpi','43','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"43","class_name":"Visualisation.VisualizedKpi","title":"Project with Expired Tasks","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"expired_tasks\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-f75c-4ebd-a783-4ff2e5196b4e',2,'EDIT','DashboardKpi','45','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"45","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"Project","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"expired\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-f7e0-4851-b418-4cbbe5196b4e',3,'EDIT','DashboardKpi','42','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"42","class_name":"Visualisation.VisualizedKpi","title":"Coming Deadline (14 Days)","model":"Project","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"comming_dates\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11712-f9d4-4eae-a779-443be5196b4e',2,'EDIT','DashboardKpi','49','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"49","class_name":"Dashboard.DashboardKpiObject","title":"Projects deleted during the past two weeks","model":"Project","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:38'),
	('5ab11713-29cc-4802-a98c-4ef0e5196b4e',3,'EDIT','DashboardKpi','50','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"50","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:39","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:39'),
	('5ab11713-46e4-4218-8489-4bafe5196b4e',1,'CREATE','DashboardKpi','51','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"51","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:39","modified":"2018-03-20 15:13:39","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:39'),
	('5ab11713-4c44-4828-8571-48f7e5196b4e',2,'EDIT','DashboardKpi','50','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"50","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:39","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:39'),
	('5ab11713-6394-4de8-8706-4f67e5196b4e',1,'CREATE','DashboardKpi','50','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"50","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:38","modified":"2018-03-20 15:13:38","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:39'),
	('5ab11713-794c-41d0-a37e-4fd4e5196b4e',3,'EDIT','DashboardKpi','51','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"51","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:39","modified":"2018-03-20 15:13:39","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:39'),
	('5ab11713-8520-4d82-a866-4c82e5196b4e',1,'CREATE','DashboardKpi','53','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"53","class_name":"Visualisation.VisualizedKpi","title":"Missing Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"missing_audits\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:39","modified":"2018-03-20 15:13:39","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:39'),
	('5ab11713-bd4c-4777-8560-4df9e5196b4e',2,'EDIT','DashboardKpi','52','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"52","class_name":"Visualisation.VisualizedKpi","title":"Missing Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missing_audits\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:39","modified":"2018-03-20 15:13:39","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:39'),
	('5ab11713-bedc-40ad-8401-457ee5196b4e',3,'EDIT','DashboardKpi','52','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"52","class_name":"Visualisation.VisualizedKpi","title":"Missing Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missing_audits\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:39","modified":"2018-03-20 15:13:39","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:39'),
	('5ab11713-f2ec-480d-8559-42ede5196b4e',2,'EDIT','DashboardKpi','51','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"51","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:39","modified":"2018-03-20 15:13:39","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:39'),
	('5ab11713-f348-4c2c-b056-4a9be5196b4e',1,'CREATE','DashboardKpi','52','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"52","class_name":"Visualisation.VisualizedKpi","title":"Missing Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"missing_audits\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:39","modified":"2018-03-20 15:13:39","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:39'),
	('5ab11714-1350-4683-9f67-4051e5196b4e',3,'EDIT','DashboardKpi','54','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"54","class_name":"Visualisation.VisualizedKpi","title":"Failed Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"failed_audits\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-21d4-4110-b8e7-4837e5196b4e',2,'EDIT','DashboardKpi','55','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"55","class_name":"Visualisation.VisualizedKpi","title":"Failed Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"failed_audits\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-3940-4263-a496-4448e5196b4e',2,'EDIT','DashboardKpi','53','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"53","class_name":"Visualisation.VisualizedKpi","title":"Missing Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missing_audits\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:39","modified":"2018-03-20 15:13:40","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-5138-4007-827a-4c42e5196b4e',2,'EDIT','DashboardKpi','56','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"56","class_name":"Visualisation.VisualizedKpi","title":"Issues","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"issue\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-5214-4478-8255-4852e5196b4e',1,'CREATE','DashboardKpi','57','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"57","class_name":"Visualisation.VisualizedKpi","title":"Issues","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"issue\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-6aa0-401c-9bdd-4be8e5196b4e',1,'CREATE','DashboardKpi','56','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"56","class_name":"Visualisation.VisualizedKpi","title":"Issues","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"issue\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-7a60-48c6-86c3-415ce5196b4e',3,'EDIT','DashboardKpi','55','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"55","class_name":"Visualisation.VisualizedKpi","title":"Failed Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"failed_audits\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-82c4-4f4e-b7ba-4667e5196b4e',1,'CREATE','DashboardKpi','54','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"54","class_name":"Visualisation.VisualizedKpi","title":"Failed Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"failed_audits\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-881c-4c3c-aa37-4a9fe5196b4e',3,'EDIT','DashboardKpi','53','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"53","class_name":"Visualisation.VisualizedKpi","title":"Missing Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missing_audits\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:39","modified":"2018-03-20 15:13:40","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-c200-415f-b918-4cece5196b4e',3,'EDIT','DashboardKpi','57','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"57","class_name":"Visualisation.VisualizedKpi","title":"Issues","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"issue\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-c33c-4ac5-99a6-4bd4e5196b4e',3,'EDIT','DashboardKpi','56','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"56","class_name":"Visualisation.VisualizedKpi","title":"Issues","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"issue\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-ef9c-459c-bd8a-4a25e5196b4e',1,'CREATE','DashboardKpi','55','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"55","class_name":"Visualisation.VisualizedKpi","title":"Failed Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"failed_audits\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-fba8-435b-b256-40bce5196b4e',2,'EDIT','DashboardKpi','54','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"54","class_name":"Visualisation.VisualizedKpi","title":"Failed Audits","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"failed_audits\\",\\"CustomRoles.CustomRole\\":\\"ServiceOwner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11714-fee0-4fce-8a25-4da5e5196b4e',2,'EDIT','DashboardKpi','57','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"57","class_name":"Visualisation.VisualizedKpi","title":"Issues","model":"SecurityService","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"issue\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:40'),
	('5ab11715-0fd0-4952-a8a4-4f93e5196b4e',1,'CREATE','DashboardKpi','65','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"65","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-124c-4605-95f2-4865e5196b4e',2,'EDIT','DashboardKpi','62','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"62","class_name":"Dashboard.DashboardKpiObject","title":"Security Services created during the past two weeks","model":"SecurityService","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-1274-40ae-a80f-46cfe5196b4e',1,'CREATE','DashboardKpi','63','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"63","class_name":"Dashboard.DashboardKpiObject","title":"Security Services deleted during the past two weeks","model":"SecurityService","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-194c-493f-a42c-45e7e5196b4e',2,'EDIT','DashboardKpi','60','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"60","class_name":"Dashboard.DashboardKpiObject","title":"Failed Audits","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"failed_audits\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-1eb0-40da-9bd5-4474e5196b4e',1,'CREATE','DashboardKpi','59','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"59","class_name":"Dashboard.DashboardKpiObject","title":"Missing Audits","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"missing_audits\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-2ca0-4073-b792-47a7e5196b4e',3,'EDIT','DashboardKpi','64','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"64","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-331c-48df-a70a-4ea3e5196b4e',3,'EDIT','DashboardKpi','63','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"63","class_name":"Dashboard.DashboardKpiObject","title":"Security Services deleted during the past two weeks","model":"SecurityService","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-357c-48eb-9d83-4194e5196b4e',3,'EDIT','DashboardKpi','62','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"62","class_name":"Dashboard.DashboardKpiObject","title":"Security Services created during the past two weeks","model":"SecurityService","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-6938-422f-bcd3-4b1ce5196b4e',2,'EDIT','DashboardKpi','59','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"59","class_name":"Dashboard.DashboardKpiObject","title":"Missing Audits","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"missing_audits\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-7f7c-49ea-836c-4048e5196b4e',2,'EDIT','DashboardKpi','64','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"64","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-80d4-4e55-9f0f-4630e5196b4e',3,'EDIT','DashboardKpi','58','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"58","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Security Services","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:41","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-85dc-43fb-9835-4049e5196b4e',3,'EDIT','DashboardKpi','60','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"60","class_name":"Dashboard.DashboardKpiObject","title":"Failed Audits","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"failed_audits\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-8d74-4b8e-9ef4-45d1e5196b4e',1,'CREATE','DashboardKpi','64','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"64","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-96f0-40a3-bcc1-4e4de5196b4e',1,'CREATE','DashboardKpi','58','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"58","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Security Services","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:40","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-9710-4276-8881-405fe5196b4e',2,'EDIT','DashboardKpi','63','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"63","class_name":"Dashboard.DashboardKpiObject","title":"Security Services deleted during the past two weeks","model":"SecurityService","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-9fa4-4cc1-b653-4a59e5196b4e',1,'CREATE','DashboardKpi','60','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"60","class_name":"Dashboard.DashboardKpiObject","title":"Failed Audits","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"failed_audits\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-ca58-4190-9aa0-4c96e5196b4e',3,'EDIT','DashboardKpi','61','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"61","class_name":"Dashboard.DashboardKpiObject","title":"Issues","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"issue\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-de70-4591-9bd1-4fefe5196b4e',2,'EDIT','DashboardKpi','58','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"58","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Security Services","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:40","modified":"2018-03-20 15:13:41","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-df34-4af3-8a18-4460e5196b4e',1,'CREATE','DashboardKpi','61','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"61","class_name":"Dashboard.DashboardKpiObject","title":"Issues","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"issue\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-ede0-48d7-9e78-45cee5196b4e',3,'EDIT','DashboardKpi','59','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"59","class_name":"Dashboard.DashboardKpiObject","title":"Missing Audits","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"missing_audits\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-f14c-45c2-996b-47cbe5196b4e',1,'CREATE','DashboardKpi','62','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"62","class_name":"Dashboard.DashboardKpiObject","title":"Security Services created during the past two weeks","model":"SecurityService","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11715-f62c-4b3f-bc7b-461fe5196b4e',2,'EDIT','DashboardKpi','61','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"61","class_name":"Dashboard.DashboardKpiObject","title":"Issues","model":"SecurityService","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"issue\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:41","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:41'),
	('5ab11716-0824-4670-8c7d-45a6e5196b4e',3,'EDIT','DashboardKpi','67','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"67","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-0c34-4306-9f1f-4184e5196b4e',2,'EDIT','DashboardKpi','68','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"68","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-13f8-400b-898b-4c20e5196b4e',1,'CREATE','DashboardKpi','70','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"70","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Security Policies","model":"SecurityPolicy","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-277c-4382-b162-4c8ae5196b4e',1,'CREATE','DashboardKpi','66','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"66","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-2b00-486e-bf34-4be7e5196b4e',1,'CREATE','DashboardKpi','68','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"68","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-6cc0-4d08-bfb4-44a9e5196b4e',2,'EDIT','DashboardKpi','65','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"65","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:42","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-7164-489b-970e-4033e5196b4e',3,'EDIT','DashboardKpi','69','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"69","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-72b4-46c8-9850-4853e5196b4e',2,'EDIT','DashboardKpi','71','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"71","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"SecurityPolicy","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-7aa8-4ffe-adb7-4fcbe5196b4e',3,'EDIT','DashboardKpi','68','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"68","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-818c-41ca-b979-45a0e5196b4e',1,'CREATE','DashboardKpi','67','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"67","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-9b5c-4239-b102-400fe5196b4e',2,'EDIT','DashboardKpi','69','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"69","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-a16c-40e9-a854-472ae5196b4e',2,'EDIT','DashboardKpi','66','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"66","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-a35c-48d9-9a84-4bace5196b4e',1,'CREATE','DashboardKpi','69','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"69","class_name":"Visualisation.VisualizedKpi","title":"Coming Reviews (14 Days)","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"missed_reviews\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-a370-462d-9a2d-42f9e5196b4e',2,'EDIT','DashboardKpi','67','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"67","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-a7c8-4984-8ae1-434ce5196b4e',2,'EDIT','DashboardKpi','70','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"70","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Security Policies","model":"SecurityPolicy","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-a840-438b-a21d-4809e5196b4e',1,'CREATE','DashboardKpi','71','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"71","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"SecurityPolicy","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-b640-4b8a-83ae-4112e5196b4e',1,'CREATE','DashboardKpi','72','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"72","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"SecurityPolicy","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-c274-4baa-877c-4017e5196b4e',3,'EDIT','DashboardKpi','71','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"71","class_name":"Dashboard.DashboardKpiObject","title":"Expired","model":"SecurityPolicy","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"next_reviews\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-c2e4-4625-84c7-4d0be5196b4e',3,'EDIT','DashboardKpi','65','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"65","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:41","modified":"2018-03-20 15:13:42","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-df58-4020-a99d-4d82e5196b4e',3,'EDIT','DashboardKpi','66','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"66","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"SecurityPolicy","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"next_reviews\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11716-e594-47a7-a0af-4bb6e5196b4e',3,'EDIT','DashboardKpi','70','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"70","class_name":"Dashboard.DashboardKpiObject","title":"Total number of Security Policies","model":"SecurityPolicy","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:42","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:42'),
	('5ab11717-0614-4506-8838-42e3e5196b4e',1,'CREATE','DashboardKpi','75','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"75","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-062c-4b9e-886a-4dcee5196b4e',1,'CREATE','DashboardKpi','76','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"76","class_name":"Visualisation.VisualizedKpi","title":"Open","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"open\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-0fc4-4934-957d-4bd5e5196b4e',1,'CREATE','DashboardKpi','77','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"77","class_name":"Visualisation.VisualizedKpi","title":"Closed","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"closed\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-0fd8-4c19-9d87-4457e5196b4e',1,'CREATE','DashboardKpi','73','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"73","class_name":"Dashboard.DashboardKpiObject","title":"Security Policies created during the past two weeks","model":"SecurityPolicy","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-3334-49c6-a3e6-4252e5196b4e',2,'EDIT','DashboardKpi','77','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"77","class_name":"Visualisation.VisualizedKpi","title":"Closed","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"closed\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-34ec-4673-a738-4425e5196b4e',2,'EDIT','DashboardKpi','75','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"75","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-36d8-4e0e-839f-4736e5196b4e',3,'EDIT','DashboardKpi','72','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"72","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"SecurityPolicy","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:43","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-5234-48c9-84b3-44c6e5196b4e',2,'EDIT','DashboardKpi','76','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"76","class_name":"Visualisation.VisualizedKpi","title":"Open","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"open\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-5ee4-47b1-b6b1-43fae5196b4e',3,'EDIT','DashboardKpi','77','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"77","class_name":"Visualisation.VisualizedKpi","title":"Closed","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"closed\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-663c-4945-ad65-43a9e5196b4e',3,'EDIT','DashboardKpi','76','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"76","class_name":"Visualisation.VisualizedKpi","title":"Open","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"open\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-8370-4354-91f9-4150e5196b4e',3,'EDIT','DashboardKpi','75','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"75","class_name":"Visualisation.VisualizedKpi","title":"Total","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"total\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-8aa0-45e9-8d0a-46b4e5196b4e',3,'EDIT','DashboardKpi','74','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"74","class_name":"Dashboard.DashboardKpiObject","title":"Security Policies deleted during the past two weeks","model":"SecurityPolicy","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-9238-45d1-b616-4a84e5196b4e',2,'EDIT','DashboardKpi','74','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"74","class_name":"Dashboard.DashboardKpiObject","title":"Security Policies deleted during the past two weeks","model":"SecurityPolicy","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-c218-4edf-9e33-4139e5196b4e',2,'EDIT','DashboardKpi','73','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"73","class_name":"Dashboard.DashboardKpiObject","title":"Security Policies created during the past two weeks","model":"SecurityPolicy","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-ec60-4f50-8944-43d0e5196b4e',3,'EDIT','DashboardKpi','73','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"73","class_name":"Dashboard.DashboardKpiObject","title":"Security Policies created during the past two weeks","model":"SecurityPolicy","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-ed54-4ac3-bde9-46a3e5196b4e',2,'EDIT','DashboardKpi','72','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"72","class_name":"Dashboard.DashboardKpiObject","title":"Coming Reviews (14 Days)","model":"SecurityPolicy","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"missed_reviews\\"}","created":"2018-03-20 15:13:42","modified":"2018-03-20 15:13:43","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11717-fd10-4b75-83ec-49c1e5196b4e',1,'CREATE','DashboardKpi','74','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"74","class_name":"Dashboard.DashboardKpiObject","title":"Security Policies deleted during the past two weeks","model":"SecurityPolicy","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:43","modified":"2018-03-20 15:13:43","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:43'),
	('5ab11718-00c4-4ce0-9423-4a53e5196b4e',3,'EDIT','DashboardKpi','84','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"84","class_name":"Dashboard.DashboardKpiObject","title":"Security Incidents deleted during the past two weeks","model":"SecurityIncident","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-0fac-473c-8a0d-4087e5196b4e',2,'EDIT','DashboardKpi','84','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"84","class_name":"Dashboard.DashboardKpiObject","title":"Security Incidents deleted during the past two weeks","model":"SecurityIncident","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-119c-4de1-a681-4184e5196b4e',1,'CREATE','DashboardKpi','83','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"83","class_name":"Dashboard.DashboardKpiObject","title":"Security Incidents created during the past two weeks","model":"SecurityIncident","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-15c8-49df-9b26-490ae5196b4e',2,'EDIT','DashboardKpi','82','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"82","class_name":"Dashboard.DashboardKpiObject","title":"Incomplete Lifecycle","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"incomplete_stage\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-4150-4ceb-8b0b-4ee1e5196b4e',3,'EDIT','DashboardKpi','82','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"82","class_name":"Dashboard.DashboardKpiObject","title":"Incomplete Lifecycle","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"incomplete_stage\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-5be0-4d9b-8cfb-4bd5e5196b4e',3,'EDIT','DashboardKpi','78','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"78","class_name":"Visualisation.VisualizedKpi","title":"Incomplete Lifecycle","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"incomplete_stage\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-5ca0-40c4-a707-4ad7e5196b4e',1,'CREATE','DashboardKpi','85','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"85","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"expired\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-631c-48b0-a685-4039e5196b4e',3,'EDIT','DashboardKpi','79','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"79","class_name":"Dashboard.DashboardKpiObject","title":"Total","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-71d4-4a45-ab15-49dde5196b4e',1,'CREATE','DashboardKpi','84','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"84","class_name":"Dashboard.DashboardKpiObject","title":"Security Incidents deleted during the past two weeks","model":"SecurityIncident","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"DynamicFilter\\":\\"recently_deleted\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-76d4-455a-a2fc-4880e5196b4e',2,'EDIT','DashboardKpi','81','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"81","class_name":"Dashboard.DashboardKpiObject","title":"Closed","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"closed\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-78a4-4350-836f-4754e5196b4e',2,'EDIT','DashboardKpi','83','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"83","class_name":"Dashboard.DashboardKpiObject","title":"Security Incidents created during the past two weeks","model":"SecurityIncident","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-8790-498f-9b99-4fb5e5196b4e',2,'EDIT','DashboardKpi','80','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"80","class_name":"Dashboard.DashboardKpiObject","title":"Open","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"open\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-8f8c-4593-bf2c-49f2e5196b4e',3,'EDIT','DashboardKpi','80','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"80","class_name":"Dashboard.DashboardKpiObject","title":"Open","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"open\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-9110-499b-88fc-4af5e5196b4e',3,'EDIT','DashboardKpi','81','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"81","class_name":"Dashboard.DashboardKpiObject","title":"Closed","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"closed\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-a060-4321-b3ff-4570e5196b4e',2,'EDIT','DashboardKpi','79','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"79","class_name":"Dashboard.DashboardKpiObject","title":"Total","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-ac60-4121-ada5-4789e5196b4e',1,'CREATE','DashboardKpi','78','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"78","class_name":"Visualisation.VisualizedKpi","title":"Incomplete Lifecycle","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"incomplete_stage\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-afac-49b1-9b32-4cf8e5196b4e',1,'CREATE','DashboardKpi','79','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"79","class_name":"Dashboard.DashboardKpiObject","title":"Total","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"total\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-b9fc-4c28-8822-488fe5196b4e',1,'CREATE','DashboardKpi','82','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"82","class_name":"Dashboard.DashboardKpiObject","title":"Incomplete Lifecycle","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"incomplete_stage\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-e054-40c3-af4d-4a9be5196b4e',2,'EDIT','DashboardKpi','78','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"78","class_name":"Visualisation.VisualizedKpi","title":"Incomplete Lifecycle","model":"SecurityIncident","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"incomplete_stage\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-e3d8-45bb-9b34-4dcae5196b4e',1,'CREATE','DashboardKpi','80','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"80","class_name":"Dashboard.DashboardKpiObject","title":"Open","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"open\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-f080-43a7-b019-4926e5196b4e',3,'EDIT','DashboardKpi','83','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"83","class_name":"Dashboard.DashboardKpiObject","title":"Security Incidents created during the past two weeks","model":"SecurityIncident","type":"1","category":"1","owner_id":null,"dashboard_kpi_attribute_count":"1","json":"{\\"DynamicFilter\\":\\"recently_created\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11718-f570-4073-b3a8-4c79e5196b4e',1,'CREATE','DashboardKpi','81','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"81","class_name":"Dashboard.DashboardKpiObject","title":"Closed","model":"SecurityIncident","type":"1","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"Admin\\":\\"closed\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:44","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:44'),
	('5ab11719-0500-417f-b5e1-4de6e5196b4e',3,'EDIT','DashboardKpi','85','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"85","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"expired\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:45","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:45'),
	('5ab11719-36b8-4a32-b614-414ae5196b4e',2,'EDIT','DashboardKpi','85','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"85","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"expired\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:44","modified":"2018-03-20 15:13:45","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:45'),
	('5ab11719-3ba4-43b9-8d7f-45d9e5196b4e',1,'CREATE','DashboardKpi','86','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"86","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"expired\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:45","modified":"2018-03-20 15:13:45","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:45'),
	('5ab11719-4720-4d7d-a45b-4c08e5196b4e',3,'EDIT','DashboardKpi','86','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"86","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"expired\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:45","modified":"2018-03-20 15:13:45","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:45'),
	('5ab11719-fc00-41c4-9aaf-464be5196b4e',2,'EDIT','DashboardKpi','86','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"86","class_name":"Visualisation.VisualizedKpi","title":"Expired","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"expired\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:45","modified":"2018-03-20 15:13:45","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:45'),
	('5ab1171a-052c-4d10-98bb-4698e5196b4e',1,'CREATE','DashboardKpi','89','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"89","class_name":"Visualisation.VisualizedKpi","title":"Closed","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"closed\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:46'),
	('5ab1171a-398c-4251-84c9-4208e5196b4e',1,'CREATE','DashboardKpi','87','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"87","class_name":"Visualisation.VisualizedKpi","title":"Open","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"open\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:46'),
	('5ab1171a-3ca4-48dd-8682-4b8ce5196b4e',2,'EDIT','DashboardKpi','87','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"87","class_name":"Visualisation.VisualizedKpi","title":"Open","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"open\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:46'),
	('5ab1171a-6228-4432-a40c-4160e5196b4e',1,'CREATE','DashboardKpi','90','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"90","class_name":"Visualisation.VisualizedKpi","title":"Closed","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"closed\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:46'),
	('5ab1171a-705c-4605-8163-45dae5196b4e',3,'EDIT','DashboardKpi','87','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"87","class_name":"Visualisation.VisualizedKpi","title":"Open","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"open\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:46'),
	('5ab1171a-7d9c-4722-9b27-44dce5196b4e',2,'EDIT','DashboardKpi','89','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"89","class_name":"Visualisation.VisualizedKpi","title":"Closed","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"closed\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:46'),
	('5ab1171a-97a4-43c2-8bdb-45fee5196b4e',2,'EDIT','DashboardKpi','90','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"90","class_name":"Visualisation.VisualizedKpi","title":"Closed","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"closed\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:46'),
	('5ab1171a-9a78-4bd6-b526-42b2e5196b4e',1,'CREATE','DashboardKpi','88','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"88","class_name":"Visualisation.VisualizedKpi","title":"Open","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"0","json":"{\\"User\\":\\"open\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":null,"status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:46'),
	('5ab1171a-ae9c-4f71-8caf-476fe5196b4e',2,'EDIT','DashboardKpi','88','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"88","class_name":"Visualisation.VisualizedKpi","title":"Open","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"open\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":"0","status":"0"}}',NULL,NULL,NULL,'2018-03-20 15:13:46'),
	('5ab1171a-c060-4931-9434-43a2e5196b4e',3,'EDIT','DashboardKpi','90','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"90","class_name":"Visualisation.VisualizedKpi","title":"Closed","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"closed\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:46'),
	('5ab1171a-ccf4-4c65-9b61-49d4e5196b4e',3,'EDIT','DashboardKpi','88','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"88","class_name":"Visualisation.VisualizedKpi","title":"Open","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"open\\",\\"CustomRoles.CustomRole\\":\\"Collaborator\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:46'),
	('5ab1171a-da3c-4c7d-89d7-4a2ee5196b4e',3,'EDIT','DashboardKpi','89','5ab1170c-fea8-415e-9b89-4c9ce5196b4e','{"DashboardKpi":{"id":"89","class_name":"Visualisation.VisualizedKpi","title":"Closed","model":"ComplianceAnalysisFinding","type":"0","category":"0","owner_id":null,"dashboard_kpi_attribute_count":"3","json":"{\\"User\\":\\"closed\\",\\"CustomRoles.CustomRole\\":\\"Owner\\",\\"CustomRoles.CustomUser\\":\\"1\\"}","created":"2018-03-20 15:13:46","modified":"2018-03-20 15:13:46","value":"0","status":"1"}}',NULL,NULL,NULL,'2018-03-20 15:13:46');
ALTER TABLE `audits` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `audit_deltas` WRITE;
ALTER TABLE `audit_deltas` DISABLE KEYS;
INSERT INTO `audit_deltas` (`id`, `audit_id`, `property_name`, `old_value`, `new_value`) VALUES 
	('5ab1170c-2728-4fde-b011-4c38e5196b4e','5ab1170c-24a8-4ece-827b-45d8e5196b4e','title','','Total'),
	('5ab1170c-292c-4f8f-b943-40c0e5196b4e','5ab1170c-24a8-4ece-827b-45d8e5196b4e','id','','1'),
	('5ab1170c-2a50-4652-bff5-42b4e5196b4e','5ab1170c-24a8-4ece-827b-45d8e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170c-8214-4c2a-941e-4450e5196b4e','5ab1170c-24a8-4ece-827b-45d8e5196b4e','model','','Risk'),
	('5ab1170d-0138-4ae7-b42d-437fe5196b4e','5ab1170d-3470-4cb2-97bd-4c5be5196b4e','json','','{"User":"next_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}'),
	('5ab1170d-0ef8-42bc-9101-4fbce5196b4e','5ab1170d-60cc-447d-be66-4759e5196b4e','owner_id','',NULL),
	('5ab1170d-0ffc-4756-96ad-4a1de5196b4e','5ab1170d-3470-4cb2-97bd-4c5be5196b4e','owner_id','',NULL),
	('5ab1170d-12cc-4a4d-aebc-4424e5196b4e','5ab1170d-60cc-447d-be66-4759e5196b4e','type','','0'),
	('5ab1170d-1664-4986-a20e-449ee5196b4e','5ab1170d-c298-494c-b4eb-422fe5196b4e','id','','2'),
	('5ab1170d-1a4c-40df-9837-4903e5196b4e','5ab1170d-42e8-4693-9b07-4c80e5196b4e','owner_id','',NULL),
	('5ab1170d-1bf8-4020-8787-4d11e5196b4e','5ab1170d-60cc-447d-be66-4759e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170d-1dd4-4a60-bb6d-444fe5196b4e','5ab1170d-3470-4cb2-97bd-4c5be5196b4e','id','','4'),
	('5ab1170d-2280-41e6-9762-4b7ce5196b4e','5ab1170d-3470-4cb2-97bd-4c5be5196b4e','title','','Expired'),
	('5ab1170d-2484-4729-8963-4312e5196b4e','5ab1170c-24a8-4ece-827b-45d8e5196b4e','category','','0'),
	('5ab1170d-2834-412a-aabf-48f3e5196b4e','5ab1170d-c298-494c-b4eb-422fe5196b4e','status','','0'),
	('5ab1170d-28b8-4c8d-9c20-4ea4e5196b4e','5ab1170d-3470-4cb2-97bd-4c5be5196b4e','model','','Risk'),
	('5ab1170d-2d14-4db3-b3ba-4971e5196b4e','5ab1170d-c298-494c-b4eb-422fe5196b4e','title','','Total'),
	('5ab1170d-3c4c-4134-b180-489ae5196b4e','5ab1170d-42e8-4693-9b07-4c80e5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab1170d-3e80-4f22-a842-49a3e5196b4e','5ab1170d-42e8-4693-9b07-4c80e5196b4e','json','','{"User":"missed_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab1170d-4270-4ab5-bda4-468be5196b4e','5ab1170d-60cc-447d-be66-4759e5196b4e','status','','0'),
	('5ab1170d-434c-49b7-8b88-4464e5196b4e','5ab1170d-42e8-4693-9b07-4c80e5196b4e','value','',NULL),
	('5ab1170d-44ec-4e87-bcad-4331e5196b4e','5ab1170d-31d0-463c-9117-44f0e5196b4e','status','0','1'),
	('5ab1170d-4790-45a5-b8d9-4b3ae5196b4e','5ab1170d-3470-4cb2-97bd-4c5be5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170d-4ad4-4b7e-b98a-4eb7e5196b4e','5ab1170d-7700-4756-88d4-4297e5196b4e','value',NULL,'0'),
	('5ab1170d-4c68-457b-9f70-4b60e5196b4e','5ab1170d-475c-4e9b-9e15-4508e5196b4e','status','0','1'),
	('5ab1170d-5470-4d51-aeec-49bbe5196b4e','5ab1170d-3470-4cb2-97bd-4c5be5196b4e','type','','0'),
	('5ab1170d-5734-43a1-ba47-4f87e5196b4e','5ab1170d-c298-494c-b4eb-422fe5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170d-57c0-4077-9acf-4583e5196b4e','5ab1170d-42e8-4693-9b07-4c80e5196b4e','model','','Risk'),
	('5ab1170d-59bc-4857-86ba-41f7e5196b4e','5ab1170d-60cc-447d-be66-4759e5196b4e','id','','3'),
	('5ab1170d-60d4-4598-9a85-4e15e5196b4e','5ab1170d-c298-494c-b4eb-422fe5196b4e','category','','0'),
	('5ab1170d-62b4-43ae-bd48-4e96e5196b4e','5ab1170d-c298-494c-b4eb-422fe5196b4e','model','','Risk'),
	('5ab1170d-69e4-4a65-ab1a-4d1ee5196b4e','5ab1170d-1290-422e-8365-469ae5196b4e','value',NULL,'0'),
	('5ab1170d-6bf8-41d0-b30e-4b6de5196b4e','5ab1170c-24a8-4ece-827b-45d8e5196b4e','owner_id','',NULL),
	('5ab1170d-6fa8-49a2-a7ae-4583e5196b4e','5ab1170d-42e8-4693-9b07-4c80e5196b4e','type','','0'),
	('5ab1170d-6fac-44db-b521-4817e5196b4e','5ab1170d-60cc-447d-be66-4759e5196b4e','json','','{"User":"next_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab1170d-707c-43a5-ba8d-4d8ae5196b4e','5ab1170d-c298-494c-b4eb-422fe5196b4e','type','','0'),
	('5ab1170d-75a8-4e29-a0d9-47d5e5196b4e','5ab1170d-8ac8-4e69-837d-4e2ae5196b4e','status','0','1'),
	('5ab1170d-7a1c-4456-b55a-4264e5196b4e','5ab1170d-93ec-4112-9757-44d9e5196b4e','status','0','1'),
	('5ab1170d-8578-4aaa-a80b-48eae5196b4e','5ab1170d-324c-4359-b6a9-4692e5196b4e','value',NULL,'0'),
	('5ab1170d-8abc-426b-94b3-4395e5196b4e','5ab1170d-60cc-447d-be66-4759e5196b4e','value','',NULL),
	('5ab1170d-8cfc-4c54-a8f7-487ae5196b4e','5ab1170d-60cc-447d-be66-4759e5196b4e','title','','Expired'),
	('5ab1170d-8ddc-4718-bc08-4a35e5196b4e','5ab1170d-c298-494c-b4eb-422fe5196b4e','owner_id','',NULL),
	('5ab1170d-93c4-4aaf-b426-4192e5196b4e','5ab1170d-3470-4cb2-97bd-4c5be5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170d-93d4-49ee-85d8-4c73e5196b4e','5ab1170d-42e8-4693-9b07-4c80e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170d-9dd0-4f5f-8051-4914e5196b4e','5ab1170d-60cc-447d-be66-4759e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170d-9e9c-4be1-add7-46f9e5196b4e','5ab1170d-3470-4cb2-97bd-4c5be5196b4e','value','',NULL),
	('5ab1170d-a94c-4a85-99ae-41b3e5196b4e','5ab1170d-60cc-447d-be66-4759e5196b4e','category','','0'),
	('5ab1170d-b4bc-4945-a9a1-4706e5196b4e','5ab1170c-24a8-4ece-827b-45d8e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170d-b7c4-4b7e-91a4-46b4e5196b4e','5ab1170d-c298-494c-b4eb-422fe5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170d-b9d8-4c39-b081-46ece5196b4e','5ab1170d-42e8-4693-9b07-4c80e5196b4e','category','','0'),
	('5ab1170d-c3b0-465d-a43e-454ce5196b4e','5ab1170d-3470-4cb2-97bd-4c5be5196b4e','status','','0'),
	('5ab1170d-ce00-45f0-87b5-4a3ee5196b4e','5ab1170d-d728-479b-961a-4699e5196b4e','value',NULL,'0'),
	('5ab1170d-cfcc-41b1-8765-49e4e5196b4e','5ab1170c-24a8-4ece-827b-45d8e5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab1170d-d62c-45d4-969d-461ae5196b4e','5ab1170c-24a8-4ece-827b-45d8e5196b4e','type','','0'),
	('5ab1170d-dc04-45f0-99d7-4fcde5196b4e','5ab1170c-24a8-4ece-827b-45d8e5196b4e','value','',NULL),
	('5ab1170d-dc20-4c14-8a33-4147e5196b4e','5ab1170d-42e8-4693-9b07-4c80e5196b4e','id','','5'),
	('5ab1170d-e198-47c3-8baa-47dae5196b4e','5ab1170c-24a8-4ece-827b-45d8e5196b4e','status','','0'),
	('5ab1170d-e5b0-46ef-b5ce-4e90e5196b4e','5ab1170d-c298-494c-b4eb-422fe5196b4e','value','',NULL),
	('5ab1170d-e724-4db7-9f15-46d0e5196b4e','5ab1170d-c298-494c-b4eb-422fe5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}'),
	('5ab1170d-ea10-4b46-84c6-4eabe5196b4e','5ab1170d-60cc-447d-be66-4759e5196b4e','model','','Risk'),
	('5ab1170d-f5ec-4db6-9f95-44c1e5196b4e','5ab1170d-42e8-4693-9b07-4c80e5196b4e','status','','0'),
	('5ab1170d-fb10-4468-b11f-4d10e5196b4e','5ab1170d-3470-4cb2-97bd-4c5be5196b4e','category','','0'),
	('5ab1170d-fdf0-4ba2-8808-4dd3e5196b4e','5ab1170d-42e8-4693-9b07-4c80e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170e-01b4-4380-801f-487ae5196b4e','5ab1170e-9210-478a-b2b4-4433e5196b4e','owner_id','',NULL),
	('5ab1170e-057c-4e63-bc32-4a6de5196b4e','5ab1170e-50ec-41b2-a8d5-49e2e5196b4e','category','','0'),
	('5ab1170e-0844-46fa-bc67-4abde5196b4e','5ab1170e-7314-425d-9d64-41bee5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170e-0b24-47e9-8972-422de5196b4e','5ab1170e-0e44-4020-b2f8-4fe5e5196b4e','owner_id','',NULL),
	('5ab1170e-0d44-4441-9001-4089e5196b4e','5ab1170e-0e44-4020-b2f8-4fe5e5196b4e','category','','0'),
	('5ab1170e-10b8-4734-ad9d-4806e5196b4e','5ab1170e-1548-46cd-8933-4d96e5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab1170e-15a0-4f52-ae0e-49bae5196b4e','5ab1170e-50ec-41b2-a8d5-49e2e5196b4e','value','',NULL),
	('5ab1170e-166c-42a4-870e-47afe5196b4e','5ab1170e-0e44-4020-b2f8-4fe5e5196b4e','type','','1'),
	('5ab1170e-178c-4818-9fca-4e1de5196b4e','5ab1170e-8e9c-4e16-9198-4072e5196b4e','value',NULL,'0'),
	('5ab1170e-1c00-4109-a8a7-4895e5196b4e','5ab1170e-8764-47fc-b59b-43e1e5196b4e','category','','0'),
	('5ab1170e-1de0-4817-a806-43b3e5196b4e','5ab1170e-0e44-4020-b2f8-4fe5e5196b4e','model','','Risk'),
	('5ab1170e-1e30-4eea-bfe5-4145e5196b4e','5ab1170e-0790-44df-b61e-4f92e5196b4e','value',NULL,'0'),
	('5ab1170e-1f80-48d6-8f79-4f44e5196b4e','5ab1170e-50ec-41b2-a8d5-49e2e5196b4e','status','','0'),
	('5ab1170e-2920-4eed-a4c7-4aade5196b4e','5ab1170e-0e44-4020-b2f8-4fe5e5196b4e','class_name','','CustomQueryKpi'),
	('5ab1170e-2f30-4e43-a30e-408ee5196b4e','5ab1170e-8764-47fc-b59b-43e1e5196b4e','json','','{"CustomQuery":"total_residual_score"}'),
	('5ab1170e-3398-458e-bac8-43cae5196b4e','5ab1170e-8764-47fc-b59b-43e1e5196b4e','value','',NULL),
	('5ab1170e-3704-4f4c-b621-4692e5196b4e','5ab1170e-50ec-41b2-a8d5-49e2e5196b4e','type','','1'),
	('5ab1170e-3a74-4967-8342-4a2ce5196b4e','5ab1170e-43ec-4654-9f9e-4bf8e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab1170e-3bb4-43c8-93c1-4308e5196b4e','5ab1170e-9b54-4dd3-bd79-4388e5196b4e','status','0','1'),
	('5ab1170e-3f80-4396-9b39-45aae5196b4e','5ab1170e-0e44-4020-b2f8-4fe5e5196b4e','title','','Current Total Risk Score'),
	('5ab1170e-4260-4f06-ae7a-45c2e5196b4e','5ab1170e-1548-46cd-8933-4d96e5196b4e','type','','1'),
	('5ab1170e-4ad8-4ffd-9d7e-43bde5196b4e','5ab1170e-1548-46cd-8933-4d96e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170e-4c98-428a-a42c-4026e5196b4e','5ab1170e-8764-47fc-b59b-43e1e5196b4e','id','','11'),
	('5ab1170e-5084-4823-ac3c-4893e5196b4e','5ab1170e-9210-478a-b2b4-4433e5196b4e','status','','0'),
	('5ab1170e-512c-4b17-827c-4536e5196b4e','5ab1170e-50ec-41b2-a8d5-49e2e5196b4e','json','','{"Admin":"total"}'),
	('5ab1170e-529c-4c6b-9a67-462ce5196b4e','5ab1170e-1548-46cd-8933-4d96e5196b4e','category','','0'),
	('5ab1170e-541c-4daa-a2ce-4e58e5196b4e','5ab1170e-50ec-41b2-a8d5-49e2e5196b4e','owner_id','',NULL),
	('5ab1170e-5570-424e-8f32-4399e5196b4e','5ab1170e-6770-46b1-8b31-4dcce5196b4e','status','0','1'),
	('5ab1170e-559c-4aa2-a164-4723e5196b4e','5ab1170e-9210-478a-b2b4-4433e5196b4e','type','','0'),
	('5ab1170e-5870-4c87-82d0-4a37e5196b4e','5ab1170e-cf7c-42b0-bb5c-4a0ae5196b4e','value',NULL,'0'),
	('5ab1170e-5d34-4ab9-b7ad-40b8e5196b4e','5ab1170e-43ec-4654-9f9e-4bf8e5196b4e','type','','1'),
	('5ab1170e-6008-47ef-bbaa-40ede5196b4e','5ab1170e-9210-478a-b2b4-4433e5196b4e','id','','6'),
	('5ab1170e-6494-4528-8a60-47fee5196b4e','5ab1170e-43ec-4654-9f9e-4bf8e5196b4e','status','','0'),
	('5ab1170e-66a0-4f7b-9b24-4dd0e5196b4e','5ab1170e-7314-425d-9d64-41bee5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab1170e-6d14-4bc8-8e64-4791e5196b4e','5ab1170e-8b14-4222-94b5-47c3e5196b4e','value',NULL,'0'),
	('5ab1170e-7ce4-4317-a568-446ae5196b4e','5ab1170e-7314-425d-9d64-41bee5196b4e','json','','{"DynamicFilter":"recently_created"}'),
	('5ab1170e-82d4-4581-9b24-4fe5e5196b4e','5ab1170e-43ec-4654-9f9e-4bf8e5196b4e','value','',NULL),
	('5ab1170e-82f0-48ee-bd0e-435ee5196b4e','5ab1170e-43ec-4654-9f9e-4bf8e5196b4e','title','','Expired'),
	('5ab1170e-83c8-4798-99a8-4c55e5196b4e','5ab1170e-5228-47fb-925b-4956e5196b4e','value',NULL,'0'),
	('5ab1170e-88f8-4db7-84ae-45f4e5196b4e','5ab1170e-9210-478a-b2b4-4433e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170e-8988-4241-8d21-4ff8e5196b4e','5ab1170e-4df0-4eb2-9016-4adee5196b4e','value',NULL,'0'),
	('5ab1170e-8998-442d-a64b-40f4e5196b4e','5ab1170e-0e44-4020-b2f8-4fe5e5196b4e','status','','0'),
	('5ab1170e-8c30-4238-b712-475be5196b4e','5ab1170e-1548-46cd-8933-4d96e5196b4e','json','','{"Admin":"missed_reviews"}'),
	('5ab1170e-8cb0-427c-8e8f-4055e5196b4e','5ab1170e-43ec-4654-9f9e-4bf8e5196b4e','category','','0'),
	('5ab1170e-8ccc-4537-8484-4d4de5196b4e','5ab1170e-9210-478a-b2b4-4433e5196b4e','category','','0'),
	('5ab1170e-8e80-4e3d-bf51-4436e5196b4e','5ab1170e-0e44-4020-b2f8-4fe5e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170e-8f64-4a85-a47b-4a1ce5196b4e','5ab1170e-43ec-4654-9f9e-4bf8e5196b4e','id','','8'),
	('5ab1170e-91cc-4e8e-9769-4fa7e5196b4e','5ab1170e-50ec-41b2-a8d5-49e2e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170e-91f0-474b-8fe5-44e8e5196b4e','5ab1170e-8764-47fc-b59b-43e1e5196b4e','owner_id','',NULL),
	('5ab1170e-93e8-42cf-907d-4c02e5196b4e','5ab1170e-50ec-41b2-a8d5-49e2e5196b4e','title','','Total number of Asset Risk Management'),
	('5ab1170e-96c0-4495-b1c1-4af6e5196b4e','5ab1170e-8764-47fc-b59b-43e1e5196b4e','class_name','','CustomQueryKpi'),
	('5ab1170e-96f0-42c6-a3af-4f06e5196b4e','5ab1170e-43ec-4654-9f9e-4bf8e5196b4e','model','','Risk'),
	('5ab1170e-9db8-44ce-b57d-4231e5196b4e','5ab1170e-8764-47fc-b59b-43e1e5196b4e','type','','1'),
	('5ab1170e-a1d0-4601-9e4a-40cfe5196b4e','5ab1170e-1548-46cd-8933-4d96e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab1170e-a508-4619-8839-45f4e5196b4e','5ab1170e-3a10-4d93-a8e6-4019e5196b4e','status','0','1'),
	('5ab1170e-a54c-4926-9248-48b5e5196b4e','5ab1170e-7314-425d-9d64-41bee5196b4e','id','','12'),
	('5ab1170e-a7d0-491a-8ee8-4a4fe5196b4e','5ab1170e-9210-478a-b2b4-4433e5196b4e','model','','Risk'),
	('5ab1170e-a864-4337-9dd3-4407e5196b4e','5ab1170e-7060-43bb-8dc0-4e46e5196b4e','value',NULL,'0'),
	('5ab1170e-ab5c-4d83-b091-47e2e5196b4e','5ab1170e-1548-46cd-8933-4d96e5196b4e','id','','9'),
	('5ab1170e-ab7c-4916-922c-4ba7e5196b4e','5ab1170e-7314-425d-9d64-41bee5196b4e','value','',NULL),
	('5ab1170e-ae18-496c-a8be-4388e5196b4e','5ab1170e-43ec-4654-9f9e-4bf8e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170e-b144-4b89-830f-48f9e5196b4e','5ab1170e-1548-46cd-8933-4d96e5196b4e','value','',NULL),
	('5ab1170e-b5c8-405c-ab6b-4768e5196b4e','5ab1170e-1548-46cd-8933-4d96e5196b4e','model','','Risk'),
	('5ab1170e-b780-474d-b936-42cde5196b4e','5ab1170e-9210-478a-b2b4-4433e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170e-b94c-4e64-baf4-47fae5196b4e','5ab1170e-8764-47fc-b59b-43e1e5196b4e','status','','0'),
	('5ab1170e-b9c8-434b-a73a-41c9e5196b4e','5ab1170e-7314-425d-9d64-41bee5196b4e','owner_id','',NULL),
	('5ab1170e-ba14-4122-a9d3-4e6ce5196b4e','5ab1170e-0e44-4020-b2f8-4fe5e5196b4e','json','','{"CustomQuery":"total_risk_score"}'),
	('5ab1170e-bae8-4cab-b377-4d93e5196b4e','5ab1170e-8764-47fc-b59b-43e1e5196b4e','model','','Risk'),
	('5ab1170e-bba8-4a7b-9bd3-4a1ee5196b4e','5ab1170e-4110-4eca-9dd8-4d76e5196b4e','status','0','1'),
	('5ab1170e-bfb0-47ee-a6ea-4801e5196b4e','5ab1170e-1548-46cd-8933-4d96e5196b4e','status','','0'),
	('5ab1170e-c44c-49a7-b465-4660e5196b4e','5ab1170e-bd4c-4581-8553-4459e5196b4e','status','0','1'),
	('5ab1170e-c4e0-4234-a9f2-453ee5196b4e','5ab1170e-7314-425d-9d64-41bee5196b4e','title','','Asset Risk Management created during the past two weeks'),
	('5ab1170e-c53c-44fd-87c4-4274e5196b4e','5ab1170e-9210-478a-b2b4-4433e5196b4e','json','','{"User":"missed_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}'),
	('5ab1170e-c664-4588-873b-40b8e5196b4e','5ab1170e-8764-47fc-b59b-43e1e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170e-c75c-4d44-9b67-4da7e5196b4e','5ab1170e-50ec-41b2-a8d5-49e2e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab1170e-cbfc-4262-8696-43dde5196b4e','5ab1170e-8764-47fc-b59b-43e1e5196b4e','title','','Current Total Residual Score'),
	('5ab1170e-cda4-44e5-a326-434ae5196b4e','5ab1170e-eb5c-4289-b55c-42bee5196b4e','status','0','1'),
	('5ab1170e-d02c-40f7-97ba-4d45e5196b4e','5ab1170e-0e44-4020-b2f8-4fe5e5196b4e','value','',NULL),
	('5ab1170e-d7a8-4c11-a774-429ae5196b4e','5ab1170e-50ec-41b2-a8d5-49e2e5196b4e','id','','7'),
	('5ab1170e-d83c-4b6f-9747-4330e5196b4e','5ab1170e-43ec-4654-9f9e-4bf8e5196b4e','owner_id','',NULL),
	('5ab1170e-de04-429c-b2fc-46b8e5196b4e','5ab1170e-0e44-4020-b2f8-4fe5e5196b4e','id','','10'),
	('5ab1170e-e5a8-4f34-a43a-45fbe5196b4e','5ab1170e-50ec-41b2-a8d5-49e2e5196b4e','model','','Risk'),
	('5ab1170e-e6f4-41ce-a433-43cbe5196b4e','5ab1170e-fc78-4517-8212-425ce5196b4e','status','0','1'),
	('5ab1170e-ef98-41f2-a27a-47c1e5196b4e','5ab1170e-43ec-4654-9f9e-4bf8e5196b4e','json','','{"Admin":"next_reviews"}'),
	('5ab1170e-f48c-4475-819b-481ce5196b4e','5ab1170e-7314-425d-9d64-41bee5196b4e','category','','1'),
	('5ab1170e-f530-4078-b12f-4119e5196b4e','5ab1170e-1548-46cd-8933-4d96e5196b4e','owner_id','',NULL),
	('5ab1170e-fb1c-4e53-b23e-44b2e5196b4e','5ab1170e-7314-425d-9d64-41bee5196b4e','type','','1'),
	('5ab1170e-fd64-45a6-82ef-4b57e5196b4e','5ab1170e-9210-478a-b2b4-4433e5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab1170e-ff8c-48b9-8899-40ede5196b4e','5ab1170e-9210-478a-b2b4-4433e5196b4e','value','',NULL),
	('5ab1170e-ffd4-4f53-b456-4ea8e5196b4e','5ab1170e-7314-425d-9d64-41bee5196b4e','model','','Risk'),
	('5ab1170f-0070-4e2e-8c1d-447ae5196b4e','5ab1170f-aad0-4c9e-a6bc-4753e5196b4e','value',NULL,'0'),
	('5ab1170f-0674-4b60-b60e-4681e5196b4e','5ab1170f-bb5c-4e99-a20c-4acde5196b4e','type','','0'),
	('5ab1170f-07c8-4b8c-8ac5-4dc0e5196b4e','5ab1170f-7b54-4d7f-b7e8-420fe5196b4e','json','','{"DynamicFilter":"recently_deleted"}'),
	('5ab1170f-0a7c-4c67-95ff-4714e5196b4e','5ab1170f-0294-4fe4-b030-46f6e5196b4e','model','','BusinessContinuity'),
	('5ab1170f-0c70-4606-884c-4f35e5196b4e','5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170f-0d98-46cd-8e59-435ae5196b4e','5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e','type','','0'),
	('5ab1170f-12e4-47f2-97b4-4c50e5196b4e','5ab1170f-0294-4fe4-b030-46f6e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170f-1410-4500-b035-4164e5196b4e','5ab1170f-ff7c-442a-bd19-44f3e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170f-15a4-472e-98bf-4192e5196b4e','5ab1170f-e9ac-4f4b-83b3-4f87e5196b4e','value',NULL,'0'),
	('5ab1170f-1918-4b2d-b11a-4cffe5196b4e','5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e','title','','Expired'),
	('5ab1170f-1a2c-4dd2-9bfe-4e56e5196b4e','5ab1170f-0294-4fe4-b030-46f6e5196b4e','value','',NULL),
	('5ab1170f-1cf8-4842-a29c-4928e5196b4e','5ab1170f-7dc4-4d16-b237-4fcae5196b4e','value',NULL,'0'),
	('5ab1170f-1edc-442b-93c0-4c18e5196b4e','5ab1170f-be04-4793-9a04-4e7ce5196b4e','value',NULL,'0'),
	('5ab1170f-21cc-43c2-9697-4135e5196b4e','5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e','model','','BusinessContinuity'),
	('5ab1170f-2388-4f5c-b9b2-4b6ce5196b4e','5ab1170f-0294-4fe4-b030-46f6e5196b4e','owner_id','',NULL),
	('5ab1170f-245c-4d4a-8720-4d7ae5196b4e','5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e','category','','0'),
	('5ab1170f-2480-4c8c-a0d5-4b35e5196b4e','5ab1170f-9b24-49d8-ad58-4cbae5196b4e','value','',NULL),
	('5ab1170f-2558-498e-a9d9-4c73e5196b4e','5ab1170f-bb5c-4e99-a20c-4acde5196b4e','status','','0'),
	('5ab1170f-27a8-4b51-8537-4ee2e5196b4e','5ab1170f-be38-42c7-96c6-4690e5196b4e','value',NULL,'0'),
	('5ab1170f-3524-45fa-b296-46d6e5196b4e','5ab1170f-57e0-4782-b65b-4fb0e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170f-3584-4238-a2d5-42d1e5196b4e','5ab1170f-9b24-49d8-ad58-4cbae5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170f-36a4-4603-91a5-4779e5196b4e','5ab1170f-57e0-4782-b65b-4fb0e5196b4e','id','','18'),
	('5ab1170f-3740-439d-bd1a-4284e5196b4e','5ab1170f-523c-4964-abc8-4a39e5196b4e','status','0','1'),
	('5ab1170f-3958-40ff-92ff-4ff8e5196b4e','5ab1170f-57e0-4782-b65b-4fb0e5196b4e','status','','0'),
	('5ab1170f-3988-4ea7-b203-40b5e5196b4e','5ab1170e-7314-425d-9d64-41bee5196b4e','status','','0'),
	('5ab1170f-3bfc-4c73-aefd-42c3e5196b4e','5ab1170f-9b24-49d8-ad58-4cbae5196b4e','model','','BusinessContinuity'),
	('5ab1170f-3cac-4baf-b7cf-4541e5196b4e','5ab1170f-ff7c-442a-bd19-44f3e5196b4e','category','','0'),
	('5ab1170f-3ffc-46e0-a78c-469fe5196b4e','5ab1170f-7294-4fc5-ac7e-4999e5196b4e','status','0','1'),
	('5ab1170f-402c-46b4-b686-4ac3e5196b4e','5ab1170f-9b24-49d8-ad58-4cbae5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab1170f-4120-4c89-a402-4defe5196b4e','5ab1170f-9b24-49d8-ad58-4cbae5196b4e','status','','0'),
	('5ab1170f-41e8-40fb-a580-4fe4e5196b4e','5ab1170f-57e0-4782-b65b-4fb0e5196b4e','model','','BusinessContinuity'),
	('5ab1170f-45a8-429e-b0ff-4f7be5196b4e','5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e','value','',NULL),
	('5ab1170f-4614-4bbf-b6ba-4f64e5196b4e','5ab1170f-bb5c-4e99-a20c-4acde5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170f-4850-4023-b220-466fe5196b4e','5ab1170f-ff7c-442a-bd19-44f3e5196b4e','value','',NULL),
	('5ab1170f-4dcc-437f-946f-4034e5196b4e','5ab1170f-57e0-4782-b65b-4fb0e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170f-5040-4539-b184-4253e5196b4e','5ab1170f-57e0-4782-b65b-4fb0e5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab1170f-5070-487d-b27c-459be5196b4e','5ab1170f-ff7c-442a-bd19-44f3e5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}'),
	('5ab1170f-544c-4d9f-8492-48c7e5196b4e','5ab1170f-bb5c-4e99-a20c-4acde5196b4e','category','','0'),
	('5ab1170f-55e0-445d-b3af-4400e5196b4e','5ab1170f-9b24-49d8-ad58-4cbae5196b4e','type','','0'),
	('5ab1170f-55f4-4873-9095-4ad1e5196b4e','5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e','id','','17'),
	('5ab1170f-598c-4bca-8d1d-4926e5196b4e','5ab1170f-05b0-4446-adb3-4a81e5196b4e','value',NULL,'0'),
	('5ab1170f-5bb4-4499-a0a0-49e9e5196b4e','5ab1170f-bb5c-4e99-a20c-4acde5196b4e','value','',NULL),
	('5ab1170f-60b8-4cb0-b349-4e4ae5196b4e','5ab1170f-7b54-4d7f-b7e8-420fe5196b4e','type','','1'),
	('5ab1170f-621c-417d-80ef-4437e5196b4e','5ab1170f-ff7c-442a-bd19-44f3e5196b4e','owner_id','',NULL),
	('5ab1170f-635c-47a7-b536-404ae5196b4e','5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e','owner_id','',NULL),
	('5ab1170f-6ac0-43da-8631-4f1ce5196b4e','5ab1170f-bb5c-4e99-a20c-4acde5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170f-6b24-4b65-b4d5-4747e5196b4e','5ab1170f-0294-4fe4-b030-46f6e5196b4e','status','','0'),
	('5ab1170f-6c90-4c05-92bb-431ce5196b4e','5ab1170f-0294-4fe4-b030-46f6e5196b4e','title','','Total'),
	('5ab1170f-6d00-49e4-8436-40dfe5196b4e','5ab1170f-0294-4fe4-b030-46f6e5196b4e','category','','0'),
	('5ab1170f-6fec-45e8-abb2-4737e5196b4e','5ab1170f-1430-4f91-ae62-4246e5196b4e','value',NULL,'0'),
	('5ab1170f-70d8-4003-91c3-4f65e5196b4e','5ab1170f-0294-4fe4-b030-46f6e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170f-7998-4132-8c9f-4c54e5196b4e','5ab1170f-7b54-4d7f-b7e8-420fe5196b4e','id','','13'),
	('5ab1170f-8220-4193-ae10-4b12e5196b4e','5ab1170f-9b24-49d8-ad58-4cbae5196b4e','category','','0'),
	('5ab1170f-8660-4b39-8e28-4ceae5196b4e','5ab1170f-57e0-4782-b65b-4fb0e5196b4e','type','','0'),
	('5ab1170f-8994-402b-9ca1-41d0e5196b4e','5ab1170f-1558-4c1a-98c4-4f35e5196b4e','status','0','1'),
	('5ab1170f-8b9c-4484-89bc-4c61e5196b4e','5ab1170f-7b54-4d7f-b7e8-420fe5196b4e','status','','0'),
	('5ab1170f-8e28-4039-806d-431de5196b4e','5ab1170f-ff7c-442a-bd19-44f3e5196b4e','model','','BusinessContinuity'),
	('5ab1170f-8e5c-4479-b565-40b5e5196b4e','5ab1170f-0294-4fe4-b030-46f6e5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab1170f-9bec-4042-bfb0-47f1e5196b4e','5ab1170f-7b54-4d7f-b7e8-420fe5196b4e','category','','1'),
	('5ab1170f-9e58-42b5-a368-48c2e5196b4e','5ab1170f-ff7c-442a-bd19-44f3e5196b4e','title','','Total'),
	('5ab1170f-a044-43fd-a726-44fce5196b4e','5ab1170f-57e0-4782-b65b-4fb0e5196b4e','category','','0'),
	('5ab1170f-a1f4-461f-8856-41e8e5196b4e','5ab1170f-bb5c-4e99-a20c-4acde5196b4e','model','','BusinessContinuity'),
	('5ab1170f-a2b4-48bf-9aa6-4351e5196b4e','5ab1170f-ff7c-442a-bd19-44f3e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170f-a588-4123-8dee-4c16e5196b4e','5ab1170f-7b54-4d7f-b7e8-420fe5196b4e','title','','Asset Risk Management deleted during the past two weeks'),
	('5ab1170f-a640-4c97-b653-48b6e5196b4e','5ab1170f-57e0-4782-b65b-4fb0e5196b4e','json','','{"User":"missed_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab1170f-a6f8-4dbe-8d5f-4326e5196b4e','5ab1170f-cbdc-412f-899e-49c8e5196b4e','value',NULL,'0'),
	('5ab1170f-ab7c-4bef-af88-41bde5196b4e','5ab1170f-7b54-4d7f-b7e8-420fe5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170f-acdc-404d-8d5a-4e76e5196b4e','5ab1170f-9b24-49d8-ad58-4cbae5196b4e','json','','{"User":"missed_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}'),
	('5ab1170f-b0a0-491d-b7c8-451ce5196b4e','5ab1170f-0294-4fe4-b030-46f6e5196b4e','id','','14'),
	('5ab1170f-b57c-4d40-af59-4b3fe5196b4e','5ab1170f-df10-4f6e-a565-4bd2e5196b4e','status','0','1'),
	('5ab1170f-b850-434f-a0fa-44d3e5196b4e','5ab1170f-bb5c-4e99-a20c-4acde5196b4e','json','','{"User":"next_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab1170f-bb3c-4aa7-8dce-4fa0e5196b4e','5ab1170f-ef38-4ab6-9c2e-4fb9e5196b4e','status','0','1'),
	('5ab1170f-bf10-45f4-b979-4edce5196b4e','5ab1170f-0294-4fe4-b030-46f6e5196b4e','type','','0'),
	('5ab1170f-c164-45dc-862e-4b31e5196b4e','5ab1170f-7b54-4d7f-b7e8-420fe5196b4e','owner_id','',NULL),
	('5ab1170f-c438-4748-bc75-4277e5196b4e','5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1170f-c95c-4482-b912-4bfbe5196b4e','5ab1170f-ff7c-442a-bd19-44f3e5196b4e','type','','0'),
	('5ab1170f-d2e4-4417-9c0d-4e35e5196b4e','5ab1170f-57e0-4782-b65b-4fb0e5196b4e','value','',NULL),
	('5ab1170f-d764-4065-b5dd-43f1e5196b4e','5ab1170f-ff7c-442a-bd19-44f3e5196b4e','status','','0'),
	('5ab1170f-de50-4705-bcce-4a81e5196b4e','5ab1170f-8440-41bd-91c7-4422e5196b4e','status','0','1'),
	('5ab1170f-e664-4c57-933a-41d1e5196b4e','5ab1170f-cbc0-4844-9a39-418ae5196b4e','status','0','1'),
	('5ab1170f-e6fc-4a40-a6df-44cde5196b4e','5ab1170f-9b24-49d8-ad58-4cbae5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1170f-eb6c-4ba1-a5e6-4adee5196b4e','5ab1170f-7b54-4d7f-b7e8-420fe5196b4e','model','','Risk'),
	('5ab1170f-f078-49c2-802f-47f4e5196b4e','5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e','status','','0'),
	('5ab1170f-f10c-4a17-a229-40f4e5196b4e','5ab1170f-bb5c-4e99-a20c-4acde5196b4e','owner_id','',NULL),
	('5ab1170f-f12c-4bc3-a929-4376e5196b4e','5ab1170f-9b24-49d8-ad58-4cbae5196b4e','owner_id','',NULL),
	('5ab1170f-f158-42ac-8f7e-40fbe5196b4e','5ab1170f-bb5c-4e99-a20c-4acde5196b4e','id','','16'),
	('5ab1170f-f62c-4cf9-bd8a-4f3fe5196b4e','5ab1170f-7b54-4d7f-b7e8-420fe5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab1170f-f664-4c9e-b2c5-40bae5196b4e','5ab1170f-85ec-4fcc-a4dd-4b93e5196b4e','json','','{"User":"next_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}'),
	('5ab1170f-f6c0-4076-9560-4723e5196b4e','5ab1170f-bb5c-4e99-a20c-4acde5196b4e','title','','Expired'),
	('5ab1170f-f7b0-4c69-8639-4458e5196b4e','5ab1170f-9b24-49d8-ad58-4cbae5196b4e','id','','19'),
	('5ab1170f-f90c-4913-a819-48d6e5196b4e','5ab1170f-57e0-4782-b65b-4fb0e5196b4e','owner_id','',NULL),
	('5ab1170f-fb1c-442b-86d4-4c4fe5196b4e','5ab1170f-7b54-4d7f-b7e8-420fe5196b4e','value','',NULL),
	('5ab1170f-fe24-4b1d-a108-45a6e5196b4e','5ab1170f-ff7c-442a-bd19-44f3e5196b4e','id','','15'),
	('5ab11710-0094-4ca0-a32e-4936e5196b4e','5ab11710-25bc-4403-bed6-4f3ee5196b4e','owner_id','',NULL),
	('5ab11710-00ec-4a75-8394-4524e5196b4e','5ab11710-48a4-42df-95ed-4b5ce5196b4e','model','','ThirdPartyRisk'),
	('5ab11710-0370-4193-a20d-4bace5196b4e','5ab11710-48a4-42df-95ed-4b5ce5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11710-05f4-4f28-9663-45e6e5196b4e','5ab11710-a434-4754-82a9-4cdae5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11710-0678-4889-b6f3-47a5e5196b4e','5ab11710-aa44-4775-a057-42bde5196b4e','title','','Total'),
	('5ab11710-0834-464b-912b-40f5e5196b4e','5ab11710-b24c-4c4a-a10e-4830e5196b4e','value',NULL,'0'),
	('5ab11710-08a4-4f81-87f1-42c2e5196b4e','5ab11710-0ae0-40f1-b062-4db8e5196b4e','status','','0'),
	('5ab11710-08f8-40ce-87d7-48b1e5196b4e','5ab11710-5ec8-4388-b5f0-4123e5196b4e','category','','1'),
	('5ab11710-0a40-4385-bde9-4952e5196b4e','5ab11710-2fc8-43e7-84d8-4f50e5196b4e','status','0','1'),
	('5ab11710-0af8-47e5-bcaa-4ff7e5196b4e','5ab11710-48a4-42df-95ed-4b5ce5196b4e','title','','Expired'),
	('5ab11710-0d14-487e-9ff2-424ce5196b4e','5ab11710-2554-432f-9c80-4ac9e5196b4e','status','0','1'),
	('5ab11710-0dc8-45f6-878e-4a0ce5196b4e','5ab11710-7af0-4234-bfa7-40bae5196b4e','id','','30'),
	('5ab11710-0f90-49e4-a6bc-44ece5196b4e','5ab11710-0ae0-40f1-b062-4db8e5196b4e','value','',NULL),
	('5ab11710-1004-492a-a3d6-4903e5196b4e','5ab11710-0ae0-40f1-b062-4db8e5196b4e','owner_id','',NULL),
	('5ab11710-1158-4cb9-850e-4dafe5196b4e','5ab11710-3d0c-42a6-870a-4816e5196b4e','value',NULL,'0'),
	('5ab11710-12c4-4834-9d60-4080e5196b4e','5ab11710-48a4-42df-95ed-4b5ce5196b4e','value','',NULL),
	('5ab11710-14a0-4761-a88a-402ee5196b4e','5ab11710-aa44-4775-a057-42bde5196b4e','status','','0'),
	('5ab11710-180c-45db-b3bc-4617e5196b4e','5ab11710-0ae0-40f1-b062-4db8e5196b4e','json','','{"CustomQuery":"total_risk_score"}'),
	('5ab11710-1820-45a5-9a55-487ee5196b4e','5ab11710-7af0-4234-bfa7-40bae5196b4e','model','','ThirdPartyRisk'),
	('5ab11710-1bc8-4e9a-b4c2-43b8e5196b4e','5ab11710-aa08-4882-b274-4e18e5196b4e','class_name','','CustomQueryKpi'),
	('5ab11710-1c9c-4204-ae30-4839e5196b4e','5ab11710-6578-4f10-b07b-4175e5196b4e','value',NULL,'0'),
	('5ab11710-1ce4-4c47-9502-407ae5196b4e','5ab11710-a434-4754-82a9-4cdae5196b4e','owner_id','',NULL),
	('5ab11710-1ce4-4c6c-a00b-4fb5e5196b4e','5ab11710-aa44-4775-a057-42bde5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11710-1f84-4387-a5c6-400ce5196b4e','5ab11710-5ec8-4388-b5f0-4123e5196b4e','type','','1'),
	('5ab11710-1f8c-4840-b8de-4b06e5196b4e','5ab11710-48a4-42df-95ed-4b5ce5196b4e','json','','{"User":"next_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11710-1fd8-4dde-b476-4a93e5196b4e','5ab11710-aa44-4775-a057-42bde5196b4e','value','',NULL),
	('5ab11710-2048-4a93-ae33-4e88e5196b4e','5ab11710-48a4-42df-95ed-4b5ce5196b4e','type','','0'),
	('5ab11710-21b0-4774-b6c2-42fae5196b4e','5ab11710-aa44-4775-a057-42bde5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11710-21c4-4a9a-9144-423fe5196b4e','5ab11710-25bc-4403-bed6-4f3ee5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11710-2308-4bb5-ba96-4136e5196b4e','5ab11710-7af0-4234-bfa7-40bae5196b4e','type','','0'),
	('5ab11710-2470-459a-befb-4be0e5196b4e','5ab11710-0ae0-40f1-b062-4db8e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11710-25c4-41eb-9fa9-4d41e5196b4e','5ab11710-aa44-4775-a057-42bde5196b4e','owner_id','',NULL),
	('5ab11710-2718-4a52-98e1-494ee5196b4e','5ab11710-f778-4f7d-8f2b-4401e5196b4e','status','0','1'),
	('5ab11710-2900-4929-a0e8-491fe5196b4e','5ab11710-aa08-4882-b274-4e18e5196b4e','type','','1'),
	('5ab11710-2960-47a2-81e4-4abae5196b4e','5ab11710-c9b4-49db-a456-4a95e5196b4e','value',NULL,'0'),
	('5ab11710-2b8c-4bac-adb3-4b53e5196b4e','5ab11710-48a4-42df-95ed-4b5ce5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11710-2c20-4596-add8-4d1ce5196b4e','5ab11710-48a4-42df-95ed-4b5ce5196b4e','status','','0'),
	('5ab11710-2cb0-4bc4-98c5-4749e5196b4e','5ab11710-aa44-4775-a057-42bde5196b4e','category','','0'),
	('5ab11710-3270-4903-8395-4772e5196b4e','5ab11710-aa44-4775-a057-42bde5196b4e','type','','0'),
	('5ab11710-32a0-4056-92ba-4800e5196b4e','5ab11710-552c-4a36-9348-49f5e5196b4e','value',NULL,'0'),
	('5ab11710-32a8-4a93-9260-4733e5196b4e','5ab11710-aa08-4882-b274-4e18e5196b4e','model','','BusinessContinuity'),
	('5ab11710-32b8-4465-9357-40a9e5196b4e','5ab11710-aa44-4775-a057-42bde5196b4e','model','','ThirdPartyRisk'),
	('5ab11710-3370-45fb-ac6d-49d3e5196b4e','5ab11710-a434-4754-82a9-4cdae5196b4e','category','','1'),
	('5ab11710-3674-4422-a556-4803e5196b4e','5ab11710-5ec8-4388-b5f0-4123e5196b4e','model','','BusinessContinuity'),
	('5ab11710-3930-45c0-be8e-4795e5196b4e','5ab11710-aa08-4882-b274-4e18e5196b4e','title','','Current Total Residual Score'),
	('5ab11710-3a48-4d40-aae9-49bee5196b4e','5ab11710-48a4-42df-95ed-4b5ce5196b4e','owner_id','',NULL),
	('5ab11710-3d38-418a-8047-4878e5196b4e','5ab11710-25bc-4403-bed6-4f3ee5196b4e','title','','Expired'),
	('5ab11710-3d4c-4871-9f0e-46bde5196b4e','5ab11710-48a4-42df-95ed-4b5ce5196b4e','category','','0'),
	('5ab11710-3e08-4093-98b1-4b78e5196b4e','5ab11710-58a0-46c5-b53d-4618e5196b4e','status','0','1'),
	('5ab11710-3e90-4616-afcf-4340e5196b4e','5ab11710-25bc-4403-bed6-4f3ee5196b4e','status','','0'),
	('5ab11710-4058-49a3-bd41-4f8be5196b4e','5ab11710-aa44-4775-a057-42bde5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11710-4224-4841-8184-4d0de5196b4e','5ab11710-0ae0-40f1-b062-4db8e5196b4e','title','','Current Total Risk Score'),
	('5ab11710-4688-409a-a5db-4e9ae5196b4e','5ab11710-5ec8-4388-b5f0-4123e5196b4e','id','','25'),
	('5ab11710-4828-4654-af4c-421de5196b4e','5ab11710-7e44-4e3c-9015-444ce5196b4e','status','','0'),
	('5ab11710-4934-4d03-920a-4c73e5196b4e','5ab11710-a434-4754-82a9-4cdae5196b4e','type','','1'),
	('5ab11710-4c9c-4cfa-a789-48f2e5196b4e','5ab11710-5ec8-4388-b5f0-4123e5196b4e','title','','Business Impact Analysis created during the past two weeks'),
	('5ab11710-4d0c-423d-b16a-47a4e5196b4e','5ab11710-aa08-4882-b274-4e18e5196b4e','category','','0'),
	('5ab11710-4e0c-4bc7-8496-4599e5196b4e','5ab11710-7e44-4e3c-9015-444ce5196b4e','id','','22'),
	('5ab11710-4ffc-4cee-ae9a-4b52e5196b4e','5ab11710-7e44-4e3c-9015-444ce5196b4e','model','','BusinessContinuity'),
	('5ab11710-5014-4052-aeb9-4ea3e5196b4e','5ab11710-25bc-4403-bed6-4f3ee5196b4e','model','','BusinessContinuity'),
	('5ab11710-55b0-45bb-b7db-47e6e5196b4e','5ab11710-7af0-4234-bfa7-40bae5196b4e','status','','0'),
	('5ab11710-589c-4227-8905-43ebe5196b4e','5ab11710-5ec8-4388-b5f0-4123e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11710-5bd8-4233-adb4-4516e5196b4e','5ab11710-a434-4754-82a9-4cdae5196b4e','model','','BusinessContinuity'),
	('5ab11710-5cc0-462b-bc21-4c10e5196b4e','5ab11710-7e44-4e3c-9015-444ce5196b4e','value','',NULL),
	('5ab11710-5e3c-4c7d-9594-4affe5196b4e','5ab11710-0ae0-40f1-b062-4db8e5196b4e','model','','BusinessContinuity'),
	('5ab11710-5ed4-4821-9240-4596e5196b4e','5ab11710-25bc-4403-bed6-4f3ee5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11710-646c-46ba-b413-4c0ce5196b4e','5ab11710-0ae0-40f1-b062-4db8e5196b4e','category','','0'),
	('5ab11710-6870-45fd-a6d3-4f02e5196b4e','5ab11710-7e44-4e3c-9015-444ce5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab11710-6b98-415a-b5cd-4b46e5196b4e','5ab11710-df94-4b98-8f9b-4760e5196b4e','value','',NULL),
	('5ab11710-6c24-466c-a651-4ebfe5196b4e','5ab11710-a434-4754-82a9-4cdae5196b4e','title','','Business Impact Analysis deleted during the past two weeks'),
	('5ab11710-6ca0-4bb3-91b5-4e50e5196b4e','5ab11710-7af0-4234-bfa7-40bae5196b4e','value','',NULL),
	('5ab11710-6cbc-4577-9e36-4bfde5196b4e','5ab11710-5ec8-4388-b5f0-4123e5196b4e','status','','0'),
	('5ab11710-6e38-4b07-ad45-4532e5196b4e','5ab11710-7e44-4e3c-9015-444ce5196b4e','json','','{"Admin":"missed_reviews"}'),
	('5ab11710-6f20-41af-8793-43f3e5196b4e','5ab11710-8a80-4f5e-8e7b-4615e5196b4e','value',NULL,'0'),
	('5ab11710-7050-4b46-9ef0-45cee5196b4e','5ab11710-5ec8-4388-b5f0-4123e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11710-718c-49de-a206-4dbbe5196b4e','5ab11710-8db4-44f7-8877-460ee5196b4e','value',NULL,'0'),
	('5ab11710-7218-4ee2-87db-45cbe5196b4e','5ab11710-7e44-4e3c-9015-444ce5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11710-7288-4ac9-8421-4573e5196b4e','5ab11710-400c-4a1f-a0ad-4be5e5196b4e','owner_id','',NULL),
	('5ab11710-7334-43ab-8e34-409be5196b4e','5ab11710-df94-4b98-8f9b-4760e5196b4e','id','','28'),
	('5ab11710-754c-4431-8318-4d73e5196b4e','5ab11710-aa08-4882-b274-4e18e5196b4e','json','','{"CustomQuery":"total_residual_score"}'),
	('5ab11710-77e0-4dc4-93f2-471ce5196b4e','5ab11710-400c-4a1f-a0ad-4be5e5196b4e','id','','20'),
	('5ab11710-7a74-4e32-89eb-400ae5196b4e','5ab11710-400c-4a1f-a0ad-4be5e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11710-7b44-492a-8730-488ce5196b4e','5ab11710-a434-4754-82a9-4cdae5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11710-7c54-4e26-91c3-445be5196b4e','5ab11710-a434-4754-82a9-4cdae5196b4e','id','','26'),
	('5ab11710-7e20-4690-87bd-4beee5196b4e','5ab11710-7e44-4e3c-9015-444ce5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11710-7ed8-4244-9235-454ae5196b4e','5ab11710-df94-4b98-8f9b-4760e5196b4e','status','','0'),
	('5ab11710-7f04-4865-bd7f-420be5196b4e','5ab11710-df94-4b98-8f9b-4760e5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}'),
	('5ab11710-7ff4-441c-b5df-4bdfe5196b4e','5ab11710-7af0-4234-bfa7-40bae5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11710-8138-4c01-b3e8-4367e5196b4e','5ab11710-7af0-4234-bfa7-40bae5196b4e','json','','{"User":"next_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}'),
	('5ab11710-83ac-4ec0-9b69-44f2e5196b4e','5ab11710-5ec8-4388-b5f0-4123e5196b4e','value','',NULL),
	('5ab11710-843c-4172-b86a-41cae5196b4e','5ab11710-aa08-4882-b274-4e18e5196b4e','owner_id','',NULL),
	('5ab11710-8f98-4306-a5cc-48b7e5196b4e','5ab11710-7e44-4e3c-9015-444ce5196b4e','owner_id','',NULL),
	('5ab11710-9100-453d-b77d-4f54e5196b4e','5ab11710-aa08-4882-b274-4e18e5196b4e','id','','24'),
	('5ab11710-9338-4077-b617-4680e5196b4e','5ab11710-df94-4b98-8f9b-4760e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11710-9508-4a34-9c72-43ffe5196b4e','5ab11710-7af0-4234-bfa7-40bae5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11710-9650-4368-94e8-4cbfe5196b4e','5ab11710-5ec8-4388-b5f0-4123e5196b4e','json','','{"DynamicFilter":"recently_created"}'),
	('5ab11710-971c-462c-8b7d-4978e5196b4e','5ab11710-aa08-4882-b274-4e18e5196b4e','status','','0'),
	('5ab11710-9954-4b48-96b1-4face5196b4e','5ab11710-25bc-4403-bed6-4f3ee5196b4e','id','','21'),
	('5ab11710-9b74-4163-b464-4039e5196b4e','5ab11710-7af0-4234-bfa7-40bae5196b4e','category','','0'),
	('5ab11710-9db8-424f-bf60-4a53e5196b4e','5ab11710-400c-4a1f-a0ad-4be5e5196b4e','json','','{"Admin":"total"}'),
	('5ab11710-9df0-465e-8ab7-43ede5196b4e','5ab11710-7e44-4e3c-9015-444ce5196b4e','category','','0'),
	('5ab11710-9f0c-45d0-af4a-4b82e5196b4e','5ab11710-400c-4a1f-a0ad-4be5e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11710-9f48-4016-94f4-472fe5196b4e','5ab11710-d920-4ae3-8208-493ce5196b4e','status','0','1'),
	('5ab11710-a138-4802-9485-4fcfe5196b4e','5ab11710-400c-4a1f-a0ad-4be5e5196b4e','model','','BusinessContinuity'),
	('5ab11710-a248-4e69-ab40-4861e5196b4e','5ab11710-400c-4a1f-a0ad-4be5e5196b4e','title','','Total number of Business Impact Analysis'),
	('5ab11710-a4ac-4849-9941-476ae5196b4e','5ab11710-aa08-4882-b274-4e18e5196b4e','value','',NULL),
	('5ab11710-a554-4d68-ab68-4d30e5196b4e','5ab11710-7af0-4234-bfa7-40bae5196b4e','owner_id','',NULL),
	('5ab11710-a860-4e0e-a13e-4d2ce5196b4e','5ab11710-7e44-4e3c-9015-444ce5196b4e','type','','1'),
	('5ab11710-a9c4-42d1-a918-4a6fe5196b4e','5ab11710-df94-4b98-8f9b-4760e5196b4e','owner_id','',NULL),
	('5ab11710-a9c8-43c7-9c96-4fd5e5196b4e','5ab11710-e148-4f13-bf14-4a58e5196b4e','status','0','1'),
	('5ab11710-b128-49b8-9a97-4b7de5196b4e','5ab11710-0ae0-40f1-b062-4db8e5196b4e','type','','1'),
	('5ab11710-b2d4-47aa-b5a5-4c77e5196b4e','5ab11710-aa44-4775-a057-42bde5196b4e','id','','27'),
	('5ab11710-b2dc-43d5-9a0e-4820e5196b4e','5ab11710-48a4-42df-95ed-4b5ce5196b4e','id','','29'),
	('5ab11710-b9ac-49a6-b76b-47bfe5196b4e','5ab11710-df94-4b98-8f9b-4760e5196b4e','category','','0'),
	('5ab11710-c224-4a36-ac65-4f42e5196b4e','5ab11710-400c-4a1f-a0ad-4be5e5196b4e','type','','1'),
	('5ab11710-c764-4707-a5dd-4d68e5196b4e','5ab11710-a434-4754-82a9-4cdae5196b4e','status','','0'),
	('5ab11710-c880-4e5d-9e54-4745e5196b4e','5ab11710-25bc-4403-bed6-4f3ee5196b4e','type','','1'),
	('5ab11710-cd7c-4c65-a306-488ce5196b4e','5ab11710-df94-4b98-8f9b-4760e5196b4e','type','','0'),
	('5ab11710-cd98-4b9c-aa66-457ce5196b4e','5ab11710-25bc-4403-bed6-4f3ee5196b4e','json','','{"Admin":"next_reviews"}'),
	('5ab11710-d0d4-4c56-8760-48d8e5196b4e','5ab11710-400c-4a1f-a0ad-4be5e5196b4e','status','','0'),
	('5ab11710-d2f4-4ae9-833d-40cde5196b4e','5ab11710-400c-4a1f-a0ad-4be5e5196b4e','value','',NULL),
	('5ab11710-d3cc-4792-8b13-408ae5196b4e','5ab11710-fae4-4f14-8d9b-465de5196b4e','status','0','1'),
	('5ab11710-d500-4f5f-b9ce-4f2de5196b4e','5ab11710-b204-42e3-bab8-4053e5196b4e','status','0','1'),
	('5ab11710-d78c-48d1-ba36-4179e5196b4e','5ab11710-aa08-4882-b274-4e18e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11710-db58-4263-94c9-4164e5196b4e','5ab11710-0a40-4ba9-bc98-484de5196b4e','status','0','1'),
	('5ab11710-dd28-4dae-bfbb-4b93e5196b4e','5ab11710-a434-4754-82a9-4cdae5196b4e','value','',NULL),
	('5ab11710-de2c-4577-8796-4bd7e5196b4e','5ab11710-df94-4b98-8f9b-4760e5196b4e','model','','ThirdPartyRisk'),
	('5ab11710-e16c-4efa-90f8-4be7e5196b4e','5ab11710-f36c-4fbf-97cb-4ba0e5196b4e','value',NULL,'0'),
	('5ab11710-e310-471e-9e4e-4123e5196b4e','5ab11710-400c-4a1f-a0ad-4be5e5196b4e','category','','0'),
	('5ab11710-e36c-45a3-af2d-4825e5196b4e','5ab11710-df94-4b98-8f9b-4760e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11710-e6b0-4e4e-bfd5-4a71e5196b4e','5ab11710-25bc-4403-bed6-4f3ee5196b4e','category','','0'),
	('5ab11710-e6e8-4cc4-9329-4b4ae5196b4e','5ab11710-1ae4-4c3e-a366-4f23e5196b4e','status','0','1'),
	('5ab11710-e9ec-402e-9eba-45f5e5196b4e','5ab11710-7af0-4234-bfa7-40bae5196b4e','title','','Expired'),
	('5ab11710-ebf4-402f-bb1b-49b8e5196b4e','5ab11710-0fec-49f4-a48c-45c1e5196b4e','value',NULL,'0'),
	('5ab11710-ec84-4f71-83ca-4089e5196b4e','5ab11710-df94-4b98-8f9b-4760e5196b4e','title','','Total'),
	('5ab11710-ee00-46a6-84ba-4781e5196b4e','5ab11710-bcd0-48c2-a024-4a59e5196b4e','value',NULL,'0'),
	('5ab11710-ef4c-4a51-858c-4d7be5196b4e','5ab11710-5ec8-4388-b5f0-4123e5196b4e','owner_id','',NULL),
	('5ab11710-f030-4847-86fd-40c7e5196b4e','5ab11710-a434-4754-82a9-4cdae5196b4e','json','','{"DynamicFilter":"recently_deleted"}'),
	('5ab11710-f2a4-453d-b7ce-40b2e5196b4e','5ab11710-cfe8-47f7-9aaa-430be5196b4e','status','0','1'),
	('5ab11710-f3cc-4591-996c-4ad8e5196b4e','5ab11710-0ae0-40f1-b062-4db8e5196b4e','id','','23'),
	('5ab11710-f43c-480d-b091-4634e5196b4e','5ab11710-25bc-4403-bed6-4f3ee5196b4e','value','',NULL),
	('5ab11710-f834-488b-9d05-455ee5196b4e','5ab11710-0ae0-40f1-b062-4db8e5196b4e','class_name','','CustomQueryKpi'),
	('5ab11711-0148-4247-8209-424ae5196b4e','5ab11711-65bc-4ef6-9eb5-4beae5196b4e','class_name','','CustomQueryKpi'),
	('5ab11711-0390-411a-a57f-4a69e5196b4e','5ab11711-65bc-4ef6-9eb5-4beae5196b4e','type','','1'),
	('5ab11711-0400-40c1-93d6-4c75e5196b4e','5ab11711-977c-40e8-9c7a-43d9e5196b4e','category','','0'),
	('5ab11711-0630-47c0-b302-4732e5196b4e','5ab11711-65bc-4ef6-9eb5-4beae5196b4e','model','','ThirdPartyRisk'),
	('5ab11711-0740-426f-b45f-4793e5196b4e','5ab11711-65bc-4ef6-9eb5-4beae5196b4e','title','','Current Total Risk Score'),
	('5ab11711-0764-471e-83bd-4a32e5196b4e','5ab11711-977c-40e8-9c7a-43d9e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11711-0830-44a5-a4d1-4a05e5196b4e','5ab11711-65bc-4ef6-9eb5-4beae5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11711-0a1c-49a7-9534-427fe5196b4e','5ab11711-7654-4d16-9beb-486fe5196b4e','title','','Total number of Third Party Risk Management'),
	('5ab11711-0a54-4c2e-b883-4cfce5196b4e','5ab11711-cd08-45df-998e-409be5196b4e','value',NULL,'0'),
	('5ab11711-11e0-4590-9a29-4085e5196b4e','5ab11711-5e28-4604-84e5-4975e5196b4e','json','','{"DynamicFilter":"recently_created"}'),
	('5ab11711-1244-4440-8579-4f0fe5196b4e','5ab11711-3e0c-442c-ba99-4ec2e5196b4e','value','',NULL),
	('5ab11711-1608-416b-b29a-4ef2e5196b4e','5ab11711-7654-4d16-9beb-486fe5196b4e','status','','0'),
	('5ab11711-1734-4751-a94a-430de5196b4e','5ab11711-7654-4d16-9beb-486fe5196b4e','id','','33'),
	('5ab11711-1890-4619-b0ac-4302e5196b4e','5ab11711-be1c-4dc7-97d2-488ae5196b4e','id','','37'),
	('5ab11711-197c-4ef5-8385-4d76e5196b4e','5ab11711-65bc-4ef6-9eb5-4beae5196b4e','json','','{"CustomQuery":"total_risk_score"}'),
	('5ab11711-1a60-4736-a6f3-4214e5196b4e','5ab11711-65bc-4ef6-9eb5-4beae5196b4e','value','',NULL),
	('5ab11711-1e04-48d0-91bc-47ede5196b4e','5ab11711-aac8-4bf0-b21d-49c8e5196b4e','category','','0'),
	('5ab11711-1e4c-472a-b745-4e7ae5196b4e','5ab11711-3e0c-442c-ba99-4ec2e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11711-2098-416e-bff3-4208e5196b4e','5ab11711-3e0c-442c-ba99-4ec2e5196b4e','type','','1'),
	('5ab11711-2314-4b82-8778-4d8fe5196b4e','5ab11711-65bc-4ef6-9eb5-4beae5196b4e','status','','0'),
	('5ab11711-2824-4a82-9b55-4e5de5196b4e','5ab11711-23ac-4e61-b567-45b6e5196b4e','owner_id','',NULL),
	('5ab11711-29f4-43bb-b4ac-4995e5196b4e','5ab11711-d9e8-48a1-8385-4f00e5196b4e','status','0','1'),
	('5ab11711-2b14-4b97-9d0e-4f96e5196b4e','5ab11711-7654-4d16-9beb-486fe5196b4e','owner_id','',NULL),
	('5ab11711-2b50-4bb7-8684-43c4e5196b4e','5ab11711-5e28-4604-84e5-4975e5196b4e','status','','0'),
	('5ab11711-2dc0-4474-bd21-4898e5196b4e','5ab11711-7654-4d16-9beb-486fe5196b4e','value','',NULL),
	('5ab11711-2e20-48b5-bdc3-4132e5196b4e','5ab11711-5e28-4604-84e5-4975e5196b4e','type','','1'),
	('5ab11711-335c-44b9-8b66-4000e5196b4e','5ab11711-2470-493d-bbb9-4951e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11711-35c4-423c-b12a-439ce5196b4e','5ab11711-6768-417e-893c-4419e5196b4e','status','0','1'),
	('5ab11711-3778-472e-ab33-48fde5196b4e','5ab11711-5e28-4604-84e5-4975e5196b4e','category','','1'),
	('5ab11711-3b00-4be3-ad64-4580e5196b4e','5ab11711-ec44-4728-8d3d-475ee5196b4e','value',NULL,'0'),
	('5ab11711-3b50-4bf7-8c35-4471e5196b4e','5ab11711-7654-4d16-9beb-486fe5196b4e','json','','{"Admin":"total"}'),
	('5ab11711-3c7c-4ce8-9555-4268e5196b4e','5ab11711-977c-40e8-9c7a-43d9e5196b4e','json','','{"User":"missed_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}'),
	('5ab11711-3d80-47e4-9371-4a47e5196b4e','5ab11711-aac8-4bf0-b21d-49c8e5196b4e','status','','0'),
	('5ab11711-3fe4-48c9-8b3d-4085e5196b4e','5ab11711-7654-4d16-9beb-486fe5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11711-404c-4ba1-96e4-40dee5196b4e','5ab11711-2470-493d-bbb9-4951e5196b4e','type','','0'),
	('5ab11711-4090-4141-a823-4f5de5196b4e','5ab11711-2470-493d-bbb9-4951e5196b4e','status','','0'),
	('5ab11711-418c-4d9f-a39d-4553e5196b4e','5ab11711-5e28-4604-84e5-4975e5196b4e','model','','ThirdPartyRisk'),
	('5ab11711-43c8-4545-b51e-4b33e5196b4e','5ab11711-23ac-4e61-b567-45b6e5196b4e','value','',NULL),
	('5ab11711-44e4-4ca3-842b-4fbce5196b4e','5ab11711-aac8-4bf0-b21d-49c8e5196b4e','model','','ThirdPartyRisk'),
	('5ab11711-4598-47f6-8632-434be5196b4e','5ab11711-be1c-4dc7-97d2-488ae5196b4e','status','','0'),
	('5ab11711-4974-4bf8-b284-4903e5196b4e','5ab11711-3e0c-442c-ba99-4ec2e5196b4e','status','','0'),
	('5ab11711-4a04-4c57-a538-4e0fe5196b4e','5ab11711-6118-439e-8035-414ae5196b4e','status','0','1'),
	('5ab11711-4abc-4524-b8a0-41a7e5196b4e','5ab11711-3e0c-442c-ba99-4ec2e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11711-4b34-4a23-a6c1-4814e5196b4e','5ab11711-5e28-4604-84e5-4975e5196b4e','title','','Third Party Risk Management created during the past two weeks'),
	('5ab11711-4c90-4ba4-9041-4a8fe5196b4e','5ab11711-2470-493d-bbb9-4951e5196b4e','value','',NULL),
	('5ab11711-4e34-4855-a7c3-4a0ce5196b4e','5ab11711-c15c-4491-957f-4aa9e5196b4e','status','0','1'),
	('5ab11711-4f00-4bfc-a6ef-4fede5196b4e','5ab11711-5e28-4604-84e5-4975e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11711-4fb0-4d5e-b0eb-4837e5196b4e','5ab11711-977c-40e8-9c7a-43d9e5196b4e','owner_id','',NULL),
	('5ab11711-5010-4519-9635-4b1de5196b4e','5ab11711-5e28-4604-84e5-4975e5196b4e','id','','38'),
	('5ab11711-5318-4600-abcc-479ae5196b4e','5ab11711-2470-493d-bbb9-4951e5196b4e','json','','{"User":"missed_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11711-5794-4a63-9934-4bd9e5196b4e','5ab11711-977c-40e8-9c7a-43d9e5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab11711-57d8-4b79-8744-40c0e5196b4e','5ab11711-be1c-4dc7-97d2-488ae5196b4e','value','',NULL),
	('5ab11711-58e8-4d6c-8228-4bb1e5196b4e','5ab11711-3e0c-442c-ba99-4ec2e5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab11711-5b1c-4654-b9ba-4e0be5196b4e','5ab11711-f598-4681-b059-486fe5196b4e','type','','0'),
	('5ab11711-5b6c-4734-87bc-4ccbe5196b4e','5ab11711-af38-4f38-b0ef-4c18e5196b4e','status','0','1'),
	('5ab11711-5cc0-4b97-a2ac-47ece5196b4e','5ab11711-2470-493d-bbb9-4951e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11711-5cfc-4fe4-9afe-4126e5196b4e','5ab11711-be1c-4dc7-97d2-488ae5196b4e','owner_id','',NULL),
	('5ab11711-5dd0-45fe-98d8-456de5196b4e','5ab11711-7654-4d16-9beb-486fe5196b4e','model','','ThirdPartyRisk'),
	('5ab11711-61c8-4fba-aa86-4df3e5196b4e','5ab11711-5e28-4604-84e5-4975e5196b4e','value','',NULL),
	('5ab11711-62ac-4d00-bdeb-4f8ae5196b4e','5ab11711-be1c-4dc7-97d2-488ae5196b4e','json','','{"CustomQuery":"total_residual_score"}'),
	('5ab11711-632c-4363-88e7-4602e5196b4e','5ab11711-3e0c-442c-ba99-4ec2e5196b4e','model','','ThirdPartyRisk'),
	('5ab11711-6668-47f2-88d5-46f0e5196b4e','5ab11711-2470-493d-bbb9-4951e5196b4e','owner_id','',NULL),
	('5ab11711-6908-4b01-a5aa-40e8e5196b4e','5ab11711-2470-493d-bbb9-4951e5196b4e','category','','0'),
	('5ab11711-6f40-4841-ac5f-4e36e5196b4e','5ab11711-f630-4193-81da-4a38e5196b4e','status','0','1'),
	('5ab11711-7194-4878-b564-424ce5196b4e','5ab11711-aac8-4bf0-b21d-49c8e5196b4e','id','','34'),
	('5ab11711-7320-4786-9b88-4b4ce5196b4e','5ab11711-3e0c-442c-ba99-4ec2e5196b4e','category','','0'),
	('5ab11711-7550-4ad4-8504-46c0e5196b4e','5ab11711-977c-40e8-9c7a-43d9e5196b4e','type','','0'),
	('5ab11711-7618-447a-b801-4e1de5196b4e','5ab11711-be1c-4dc7-97d2-488ae5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11711-77d4-4df5-8796-4463e5196b4e','5ab11711-c878-4914-9f48-487ee5196b4e','value',NULL,'0'),
	('5ab11711-7d04-493e-b04a-4715e5196b4e','5ab11711-977c-40e8-9c7a-43d9e5196b4e','status','','0'),
	('5ab11711-7e7c-4cb9-b314-481be5196b4e','5ab11711-aac8-4bf0-b21d-49c8e5196b4e','title','','Expired'),
	('5ab11711-8168-40bd-a51e-4ccbe5196b4e','5ab11711-977c-40e8-9c7a-43d9e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11711-838c-4b7f-a851-4de8e5196b4e','5ab11711-2470-493d-bbb9-4951e5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab11711-86a4-43dd-8d16-4a56e5196b4e','5ab11711-7c84-43a2-aa9a-414ee5196b4e','value',NULL,'0'),
	('5ab11711-8b24-4db4-a589-42cae5196b4e','5ab11711-f598-4681-b059-486fe5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11711-8b94-4671-b41c-4262e5196b4e','5ab11711-e278-46fe-97b2-4bc8e5196b4e','value',NULL,'0'),
	('5ab11711-8cb8-483b-967f-4c68e5196b4e','5ab11711-23ac-4e61-b567-45b6e5196b4e','id','','39'),
	('5ab11711-8fc0-45cf-9d06-439ee5196b4e','5ab11711-977c-40e8-9c7a-43d9e5196b4e','id','','32'),
	('5ab11711-8fcc-4374-a065-441ee5196b4e','5ab11711-977c-40e8-9c7a-43d9e5196b4e','value','',NULL),
	('5ab11711-9020-44da-8226-47c7e5196b4e','5ab11711-cde0-4a9a-adc9-4cb3e5196b4e','status','0','1'),
	('5ab11711-9038-48a5-b69f-4a4ce5196b4e','5ab11711-23ac-4e61-b567-45b6e5196b4e','category','','1'),
	('5ab11711-9948-4b8b-a9c5-458be5196b4e','5ab11711-3e0c-442c-ba99-4ec2e5196b4e','id','','35'),
	('5ab11711-9a8c-45da-9b83-45dae5196b4e','5ab11711-7ce4-42a0-b8cc-49c6e5196b4e','value',NULL,'0'),
	('5ab11711-9f64-4147-afd3-4fb3e5196b4e','5ab11711-23ac-4e61-b567-45b6e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11711-a374-47e6-9716-44d1e5196b4e','5ab11711-23ac-4e61-b567-45b6e5196b4e','status','','0'),
	('5ab11711-acdc-488d-81c3-4c66e5196b4e','5ab11711-f598-4681-b059-486fe5196b4e','category','','0'),
	('5ab11711-b2f0-4268-bcb9-42aae5196b4e','5ab11711-f598-4681-b059-486fe5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11711-b47c-4003-8041-4406e5196b4e','5ab11711-aac8-4bf0-b21d-49c8e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11711-b5c0-499b-a6a9-48aee5196b4e','5ab11711-be1c-4dc7-97d2-488ae5196b4e','model','','ThirdPartyRisk'),
	('5ab11711-b838-45dd-92b2-4a3de5196b4e','5ab11711-aac8-4bf0-b21d-49c8e5196b4e','json','','{"Admin":"next_reviews"}'),
	('5ab11711-b8b0-455c-bc27-408de5196b4e','5ab11711-31d8-464f-a34e-4cdde5196b4e','status','0','1'),
	('5ab11711-badc-421c-a034-42c6e5196b4e','5ab11711-f598-4681-b059-486fe5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11711-bbd0-4083-b9b5-4a54e5196b4e','5ab11711-f598-4681-b059-486fe5196b4e','owner_id','',NULL),
	('5ab11711-bcd0-4087-9378-4826e5196b4e','5ab11711-7654-4d16-9beb-486fe5196b4e','category','','0'),
	('5ab11711-bd7c-421a-a6f5-42a1e5196b4e','5ab11711-be1c-4dc7-97d2-488ae5196b4e','title','','Current Total Residual Score'),
	('5ab11711-be48-44db-aa8d-4987e5196b4e','5ab11711-be1c-4dc7-97d2-488ae5196b4e','category','','0'),
	('5ab11711-c188-4f01-9238-4c14e5196b4e','5ab11711-7654-4d16-9beb-486fe5196b4e','type','','1'),
	('5ab11711-c340-4f32-85d2-4a34e5196b4e','5ab11711-3e0c-442c-ba99-4ec2e5196b4e','json','','{"Admin":"missed_reviews"}'),
	('5ab11711-c9c0-4619-bad6-47ebe5196b4e','5ab11711-f598-4681-b059-486fe5196b4e','title','','Total'),
	('5ab11711-c9dc-45f5-911f-4742e5196b4e','5ab11711-977c-40e8-9c7a-43d9e5196b4e','model','','ThirdPartyRisk'),
	('5ab11711-ca14-4156-ad10-4deee5196b4e','5ab11711-9f08-4bc9-833c-46a1e5196b4e','value',NULL,'0'),
	('5ab11711-cbb8-4269-9689-4ac3e5196b4e','5ab11711-23ac-4e61-b567-45b6e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11711-cf6c-48c3-a9df-47b2e5196b4e','5ab11711-23ac-4e61-b567-45b6e5196b4e','model','','ThirdPartyRisk'),
	('5ab11711-d874-4446-85b7-4c10e5196b4e','5ab11711-aac8-4bf0-b21d-49c8e5196b4e','owner_id','',NULL),
	('5ab11711-d884-47de-9b4f-40b5e5196b4e','5ab11711-f598-4681-b059-486fe5196b4e','id','','40'),
	('5ab11711-d930-4009-be9e-4f74e5196b4e','5ab11711-3e0c-442c-ba99-4ec2e5196b4e','owner_id','',NULL),
	('5ab11711-da70-474c-8724-4d9be5196b4e','5ab11711-be1c-4dc7-97d2-488ae5196b4e','type','','1'),
	('5ab11711-dac4-4037-addd-43b9e5196b4e','5ab11711-2470-493d-bbb9-4951e5196b4e','model','','ThirdPartyRisk'),
	('5ab11711-dc10-4084-b44e-4fe7e5196b4e','5ab11711-65bc-4ef6-9eb5-4beae5196b4e','id','','36'),
	('5ab11711-dcd0-4b2b-9286-4c2ee5196b4e','5ab11711-5e28-4604-84e5-4975e5196b4e','owner_id','',NULL),
	('5ab11711-e400-4c51-b1c1-4687e5196b4e','5ab11711-7654-4d16-9beb-486fe5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11711-e430-4929-aa2c-48e3e5196b4e','5ab11711-be1c-4dc7-97d2-488ae5196b4e','class_name','','CustomQueryKpi'),
	('5ab11711-e5b8-406e-b624-4ccce5196b4e','5ab11711-23ac-4e61-b567-45b6e5196b4e','title','','Third Party Risk Management deleted during the past two weeks'),
	('5ab11711-ea18-416a-9813-4c62e5196b4e','5ab11711-23ac-4e61-b567-45b6e5196b4e','type','','1'),
	('5ab11711-ebec-4788-9e75-4f46e5196b4e','5ab11711-23ac-4e61-b567-45b6e5196b4e','json','','{"DynamicFilter":"recently_deleted"}'),
	('5ab11711-ef88-4c37-8def-4cd6e5196b4e','5ab11711-5e28-4604-84e5-4975e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11711-f024-414b-9470-4882e5196b4e','5ab11711-f598-4681-b059-486fe5196b4e','model','','Project'),
	('5ab11711-f2f0-48a8-8b42-4d62e5196b4e','5ab11711-aac8-4bf0-b21d-49c8e5196b4e','type','','1'),
	('5ab11711-f3cc-4ab7-8519-44c9e5196b4e','5ab11711-8d7c-49d5-bac1-46a1e5196b4e','value',NULL,'0'),
	('5ab11711-f584-48b6-b778-4f41e5196b4e','5ab11711-aac8-4bf0-b21d-49c8e5196b4e','value','',NULL),
	('5ab11711-f700-4746-85a5-4808e5196b4e','5ab11711-aac8-4bf0-b21d-49c8e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11711-f87c-4670-bf4d-4a38e5196b4e','5ab11711-5a50-41ef-bb48-4823e5196b4e','status','0','1'),
	('5ab11711-f970-42db-8a25-4a39e5196b4e','5ab11711-2470-493d-bbb9-4951e5196b4e','id','','31'),
	('5ab11711-f9b0-4c8d-8931-4491e5196b4e','5ab11711-f634-4d7f-9751-487ae5196b4e','status','0','1'),
	('5ab11711-fa40-406e-8b3f-4722e5196b4e','5ab11711-10b8-4a92-bfea-45ede5196b4e','value',NULL,'0'),
	('5ab11711-fb94-4fcc-9f97-4475e5196b4e','5ab11711-65bc-4ef6-9eb5-4beae5196b4e','owner_id','',NULL),
	('5ab11711-fe14-434d-8096-4af6e5196b4e','5ab11711-b10c-49f2-aecb-4484e5196b4e','value',NULL,'0'),
	('5ab11711-fe34-4464-a0a6-4d23e5196b4e','5ab11711-65bc-4ef6-9eb5-4beae5196b4e','category','','0'),
	('5ab11712-00c4-44a1-9608-4312e5196b4e','5ab11712-c084-499e-bb0a-4979e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11712-03ac-42c9-8e7c-4461e5196b4e','5ab11712-d738-450b-a047-45cfe5196b4e','json','','{"Admin":"expired"}'),
	('5ab11712-07c0-468f-929a-411ae5196b4e','5ab11712-c084-499e-bb0a-4979e5196b4e','value','',NULL),
	('5ab11712-07f4-43d1-a9e4-41c0e5196b4e','5ab11712-ad84-4cb3-8c86-4567e5196b4e','type','','1'),
	('5ab11712-08f0-4d4d-b2cf-4499e5196b4e','5ab11712-1ce4-476c-9c7e-45aee5196b4e','id','','41'),
	('5ab11712-09a4-4033-852b-4fe1e5196b4e','5ab11712-73ac-4936-8b2e-41bde5196b4e','value',NULL,'0'),
	('5ab11712-0ed0-4f7e-a5f2-4ebfe5196b4e','5ab11712-e840-4a96-ae31-4143e5196b4e','value','',NULL),
	('5ab11712-103c-4908-992c-423ee5196b4e','5ab11712-c084-499e-bb0a-4979e5196b4e','json','','{"DynamicFilter":"recently_deleted"}'),
	('5ab11712-12c8-4830-92f7-4de8e5196b4e','5ab11712-ad84-4cb3-8c86-4567e5196b4e','model','','Project'),
	('5ab11712-13cc-4ec0-b61e-4531e5196b4e','5ab11712-d738-450b-a047-45cfe5196b4e','value','',NULL),
	('5ab11712-1784-4625-98c7-4afee5196b4e','5ab11712-ad84-4cb3-8c86-4567e5196b4e','owner_id','',NULL),
	('5ab11712-19e4-4923-a0ba-4b50e5196b4e','5ab11712-c084-499e-bb0a-4979e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11712-1d38-4c02-bbf3-485de5196b4e','5ab11712-f75c-4ebd-a783-4ff2e5196b4e','value',NULL,'0'),
	('5ab11712-1e34-4c2a-b8d5-4b23e5196b4e','5ab11712-2fd0-4240-8015-4198e5196b4e','value',NULL,'0'),
	('5ab11712-1fbc-461f-af43-43c4e5196b4e','5ab11712-e840-4a96-ae31-4143e5196b4e','type','','0'),
	('5ab11712-224c-4b52-9b9a-4182e5196b4e','5ab11712-d738-450b-a047-45cfe5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11712-22bc-49d7-adeb-47bae5196b4e','5ab11711-f598-4681-b059-486fe5196b4e','status','','0'),
	('5ab11712-2460-4c53-850b-421ce5196b4e','5ab11712-c084-499e-bb0a-4979e5196b4e','status','','0'),
	('5ab11712-2644-4166-9938-454ae5196b4e','5ab11712-ab6c-4f13-aa03-43ede5196b4e','title','','Project with Expired Tasks'),
	('5ab11712-26c8-4be4-ad7c-492fe5196b4e','5ab11712-e1e4-48aa-b276-4c5ee5196b4e','json','','{"Admin":"total"}'),
	('5ab11712-279c-4045-89fe-464ae5196b4e','5ab11712-ab6c-4f13-aa03-43ede5196b4e','id','','47'),
	('5ab11712-2994-43dc-9c05-4488e5196b4e','5ab11712-ab6c-4f13-aa03-43ede5196b4e','category','','0'),
	('5ab11712-2adc-4aad-9a11-46cce5196b4e','5ab11712-c084-499e-bb0a-4979e5196b4e','category','','1'),
	('5ab11712-2d50-40ae-b3f3-45f2e5196b4e','5ab11712-c084-499e-bb0a-4979e5196b4e','owner_id','',NULL),
	('5ab11712-2e58-41cd-83ca-469be5196b4e','5ab11712-4ecc-43aa-af6f-4463e5196b4e','id','','42'),
	('5ab11712-3364-46be-a889-45a6e5196b4e','5ab11712-dd00-45d9-8b49-45bbe5196b4e','json','','{"Admin":"comming_dates"}'),
	('5ab11712-3690-4d7e-9d62-41f0e5196b4e','5ab11712-ab6c-4f13-aa03-43ede5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11712-37a4-4d69-9ebe-4a5be5196b4e','5ab11712-dd00-45d9-8b49-45bbe5196b4e','type','','1'),
	('5ab11712-3854-4f8c-ab26-4f75e5196b4e','5ab11712-e840-4a96-ae31-4143e5196b4e','status','','0'),
	('5ab11712-3ac4-4496-94b0-4316e5196b4e','5ab11712-d264-4a7e-bee5-4dece5196b4e','status','0','1'),
	('5ab11712-3b1c-46b3-bdfe-46f7e5196b4e','5ab11712-ad84-4cb3-8c86-4567e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11712-3bc0-4df1-9abc-4214e5196b4e','5ab11712-d738-450b-a047-45cfe5196b4e','status','','0'),
	('5ab11712-3f58-4660-9106-4b48e5196b4e','5ab11712-1ce4-476c-9c7e-45aee5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11712-3fbc-4c99-a6b0-4318e5196b4e','5ab11712-e1e4-48aa-b276-4c5ee5196b4e','category','','0'),
	('5ab11712-4284-49fe-b723-4931e5196b4e','5ab11712-dd00-45d9-8b49-45bbe5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11712-493c-4bfe-95ee-4146e5196b4e','5ab11712-4ad4-4cf8-8ab6-4ccfe5196b4e','value',NULL,'0'),
	('5ab11712-4d84-4d41-983c-4e54e5196b4e','5ab11712-e1e4-48aa-b276-4c5ee5196b4e','owner_id','',NULL),
	('5ab11712-5014-42a0-becb-4f0ae5196b4e','5ab11712-dd00-45d9-8b49-45bbe5196b4e','owner_id','',NULL),
	('5ab11712-5104-42ba-b791-431ee5196b4e','5ab11712-5388-4b8f-a9d0-4420e5196b4e','status','0','1'),
	('5ab11712-5108-4d21-9b07-4d93e5196b4e','5ab11712-4ecc-43aa-af6f-4463e5196b4e','owner_id','',NULL),
	('5ab11712-5200-43ec-887b-432be5196b4e','5ab11712-ab6c-4f13-aa03-43ede5196b4e','json','','{"Admin":"expired_tasks"}'),
	('5ab11712-537c-475b-805a-45afe5196b4e','5ab11712-dd00-45d9-8b49-45bbe5196b4e','category','','0'),
	('5ab11712-541c-4c58-a1b3-445ce5196b4e','5ab11712-1ce4-476c-9c7e-45aee5196b4e','value','',NULL),
	('5ab11712-542c-4e1a-8f19-4cace5196b4e','5ab11712-ad84-4cb3-8c86-4567e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11712-5544-43ae-b273-42aae5196b4e','5ab11712-4ecc-43aa-af6f-4463e5196b4e','json','','{"User":"comming_dates","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11712-5888-427f-8bdb-44ebe5196b4e','5ab11712-1ce4-476c-9c7e-45aee5196b4e','type','','0'),
	('5ab11712-59f0-472a-8f5b-4b7be5196b4e','5ab11712-e5d8-413e-86f3-49aee5196b4e','value',NULL,'0'),
	('5ab11712-59f0-4972-86f3-438be5196b4e','5ab11712-c084-499e-bb0a-4979e5196b4e','title','','Projects deleted during the past two weeks'),
	('5ab11712-5a24-4318-8b1e-4e9ce5196b4e','5ab11712-828c-4979-a86d-4bd7e5196b4e','status','0','1'),
	('5ab11712-5cdc-479c-8925-4eb7e5196b4e','5ab11712-e1e4-48aa-b276-4c5ee5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11712-5e50-4157-97ac-4b4ae5196b4e','5ab11712-1ce4-476c-9c7e-45aee5196b4e','title','','Expired'),
	('5ab11712-6018-4f56-b8e4-473fe5196b4e','5ab11712-5eb4-4309-ac42-4ae2e5196b4e','value',NULL,'0'),
	('5ab11712-62e0-4372-a625-4d94e5196b4e','5ab11712-f9d4-4eae-a779-443be5196b4e','value',NULL,'0'),
	('5ab11712-63d0-44b5-ba47-46c6e5196b4e','5ab11712-c084-499e-bb0a-4979e5196b4e','model','','Project'),
	('5ab11712-67bc-4d5a-83e6-4518e5196b4e','5ab11712-e1e4-48aa-b276-4c5ee5196b4e','value','',NULL),
	('5ab11712-6874-4f75-90d0-408fe5196b4e','5ab11712-c084-499e-bb0a-4979e5196b4e','id','','49'),
	('5ab11712-698c-40c7-9802-420ee5196b4e','5ab11712-82f8-44f8-8979-4f12e5196b4e','status','0','1'),
	('5ab11712-6af4-4143-bcea-45f9e5196b4e','5ab11712-c084-499e-bb0a-4979e5196b4e','type','','1'),
	('5ab11712-7960-4223-9158-4f70e5196b4e','5ab11712-ab6c-4f13-aa03-43ede5196b4e','owner_id','',NULL),
	('5ab11712-79d0-4ca3-93f7-44fae5196b4e','5ab11712-dd00-45d9-8b49-45bbe5196b4e','value','',NULL),
	('5ab11712-7d34-4de9-81fb-4ecde5196b4e','5ab11712-93e4-4586-b10e-4297e5196b4e','status','0','1'),
	('5ab11712-7e80-4b79-b4b7-4203e5196b4e','5ab11712-4ecc-43aa-af6f-4463e5196b4e','status','','0'),
	('5ab11712-8120-4e1d-82d8-4c63e5196b4e','5ab11712-4ecc-43aa-af6f-4463e5196b4e','value','',NULL),
	('5ab11712-8278-455e-8bda-44b0e5196b4e','5ab11712-ab6c-4f13-aa03-43ede5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11712-83e0-4449-8816-47b7e5196b4e','5ab11712-50bc-48e4-a58d-459de5196b4e','value',NULL,'0'),
	('5ab11712-8ad4-4757-a92d-4f39e5196b4e','5ab11712-8118-4c9f-91e5-4463e5196b4e','status','0','1'),
	('5ab11712-8cfc-4ee7-a9c3-4ebfe5196b4e','5ab11712-4ecc-43aa-af6f-4463e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11712-8ee8-49ce-9e98-4669e5196b4e','5ab11712-e1e4-48aa-b276-4c5ee5196b4e','status','','0'),
	('5ab11712-90f4-4e59-9d65-42ebe5196b4e','5ab11712-d738-450b-a047-45cfe5196b4e','title','','Expired'),
	('5ab11712-9824-4354-ac71-451ae5196b4e','5ab11712-1ce4-476c-9c7e-45aee5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11712-9a44-4806-aad3-42bfe5196b4e','5ab11712-eb94-42b7-974f-43d7e5196b4e','status','0','1'),
	('5ab11712-9ac8-418d-ba4e-4025e5196b4e','5ab11712-ab6c-4f13-aa03-43ede5196b4e','model','','Project'),
	('5ab11712-9d84-486d-befb-46afe5196b4e','5ab11712-4ecc-43aa-af6f-4463e5196b4e','category','','0'),
	('5ab11712-9f74-4605-86d1-44c9e5196b4e','5ab11712-ace8-4a21-bd28-4bf7e5196b4e','value',NULL,'0'),
	('5ab11712-9f74-4d29-a603-4103e5196b4e','5ab11712-ab6c-4f13-aa03-43ede5196b4e','value','',NULL),
	('5ab11712-a0b0-402a-9bd5-4055e5196b4e','5ab11712-4ecc-43aa-af6f-4463e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11712-a430-4e41-a4ea-4849e5196b4e','5ab11712-e1e4-48aa-b276-4c5ee5196b4e','model','','Project'),
	('5ab11712-a4c0-4d97-af00-4186e5196b4e','5ab11712-1ce4-476c-9c7e-45aee5196b4e','json','','{"User":"expired","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11712-a5fc-4654-992b-4cf2e5196b4e','5ab11712-1ce4-476c-9c7e-45aee5196b4e','category','','0'),
	('5ab11712-abd8-4b68-9def-4ca1e5196b4e','5ab11712-d738-450b-a047-45cfe5196b4e','category','','0'),
	('5ab11712-b020-447d-8858-4b7de5196b4e','5ab11712-d738-450b-a047-45cfe5196b4e','id','','45'),
	('5ab11712-b234-44a4-8cb4-45b5e5196b4e','5ab11712-1ce4-476c-9c7e-45aee5196b4e','owner_id','',NULL),
	('5ab11712-b450-46c5-b070-4749e5196b4e','5ab11712-1ce4-476c-9c7e-45aee5196b4e','status','','0'),
	('5ab11712-b4fc-42d9-b53c-4478e5196b4e','5ab11712-1e44-4165-888d-4a9fe5196b4e','status','0','1'),
	('5ab11712-b648-4ee5-8a6e-40a5e5196b4e','5ab11712-d738-450b-a047-45cfe5196b4e','type','','1'),
	('5ab11712-ba00-491e-afc0-4f1ee5196b4e','5ab11712-e840-4a96-ae31-4143e5196b4e','category','','0'),
	('5ab11712-ba14-4988-a526-42c5e5196b4e','5ab11712-d738-450b-a047-45cfe5196b4e','model','','Project'),
	('5ab11712-bacc-4efe-972e-4f13e5196b4e','5ab11712-d738-450b-a047-45cfe5196b4e','owner_id','',NULL),
	('5ab11712-bc30-4d38-8323-4558e5196b4e','5ab11712-ad84-4cb3-8c86-4567e5196b4e','json','','{"DynamicFilter":"recently_created"}'),
	('5ab11712-bdc8-431d-be18-4b90e5196b4e','5ab11712-4ecc-43aa-af6f-4463e5196b4e','title','','Coming Deadline (14 Days)'),
	('5ab11712-be70-4042-9d32-48eee5196b4e','5ab11712-ab6c-4f13-aa03-43ede5196b4e','type','','1'),
	('5ab11712-c238-47fc-a901-4c85e5196b4e','5ab11712-e1e4-48aa-b276-4c5ee5196b4e','id','','44'),
	('5ab11712-c830-4b8a-b999-454ce5196b4e','5ab11712-d738-450b-a047-45cfe5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11712-c840-44f3-a81e-451de5196b4e','5ab11712-ad84-4cb3-8c86-4567e5196b4e','category','','1'),
	('5ab11712-c9b0-4a43-85a4-40b2e5196b4e','5ab11712-4ecc-43aa-af6f-4463e5196b4e','type','','0'),
	('5ab11712-ccf4-4935-8cc2-4d1de5196b4e','5ab11712-dd00-45d9-8b49-45bbe5196b4e','id','','46'),
	('5ab11712-cfe8-4a43-935c-4192e5196b4e','5ab11712-dd00-45d9-8b49-45bbe5196b4e','title','','Coming Deadline (14 Days)'),
	('5ab11712-d374-4671-a22b-4706e5196b4e','5ab11712-e840-4a96-ae31-4143e5196b4e','model','','Project'),
	('5ab11712-d5f4-4d62-b9ff-4d8ae5196b4e','5ab11712-1ce4-476c-9c7e-45aee5196b4e','model','','Project'),
	('5ab11712-d8fc-4bd9-8637-4158e5196b4e','5ab11712-e1e4-48aa-b276-4c5ee5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11712-da20-4afa-ae20-40dbe5196b4e','5ab11712-e1e4-48aa-b276-4c5ee5196b4e','title','','Total number of Projects'),
	('5ab11712-dbb8-48a1-b9b1-4cfae5196b4e','5ab11712-e840-4a96-ae31-4143e5196b4e','id','','43'),
	('5ab11712-dc50-4f12-a9a4-47a9e5196b4e','5ab11711-f598-4681-b059-486fe5196b4e','value','',NULL),
	('5ab11712-dde0-4c33-972f-438ce5196b4e','5ab11712-e840-4a96-ae31-4143e5196b4e','json','','{"User":"expired_tasks","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11712-dde4-4e78-b570-4020e5196b4e','5ab11712-e840-4a96-ae31-4143e5196b4e','title','','Project with Expired Tasks'),
	('5ab11712-de48-4174-9d68-4113e5196b4e','5ab11712-ad84-4cb3-8c86-4567e5196b4e','value','',NULL),
	('5ab11712-dea4-4d04-bd20-428ce5196b4e','5ab11712-dd00-45d9-8b49-45bbe5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11712-e45c-40ed-854e-49e6e5196b4e','5ab11712-ad84-4cb3-8c86-4567e5196b4e','status','','0'),
	('5ab11712-e838-4235-bfc6-463de5196b4e','5ab11712-f7e0-4851-b418-4cbbe5196b4e','status','0','1'),
	('5ab11712-e854-4118-b095-46efe5196b4e','5ab11712-e840-4a96-ae31-4143e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11712-e9dc-4e6d-a375-4154e5196b4e','5ab11712-7838-4a3f-8d55-4defe5196b4e','status','0','1'),
	('5ab11712-ec18-4381-be71-45aee5196b4e','5ab11712-a018-428f-ae88-4a40e5196b4e','value',NULL,'0'),
	('5ab11712-ed34-4d56-84cb-458ee5196b4e','5ab11712-e1e4-48aa-b276-4c5ee5196b4e','type','','1'),
	('5ab11712-eeb8-439a-9e8e-438ae5196b4e','5ab11712-ad84-4cb3-8c86-4567e5196b4e','id','','48'),
	('5ab11712-ef84-4228-a801-499be5196b4e','5ab11712-ad84-4cb3-8c86-4567e5196b4e','title','','Projects created during the past two weeks'),
	('5ab11712-f214-4024-8cfd-4ff2e5196b4e','5ab11712-e840-4a96-ae31-4143e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11712-f304-4332-9eb3-4848e5196b4e','5ab11712-ab6c-4f13-aa03-43ede5196b4e','status','','0'),
	('5ab11712-fb60-4f4e-bef7-42afe5196b4e','5ab11712-dd00-45d9-8b49-45bbe5196b4e','model','','Project'),
	('5ab11712-fc1c-4e2b-b42f-4683e5196b4e','5ab11712-4ecc-43aa-af6f-4463e5196b4e','model','','Project'),
	('5ab11712-fdb0-4ba9-971c-4de6e5196b4e','5ab11712-e840-4a96-ae31-4143e5196b4e','owner_id','',NULL),
	('5ab11712-fe58-43ff-9f1e-485ee5196b4e','5ab11712-dd00-45d9-8b49-45bbe5196b4e','status','','0'),
	('5ab11713-02ac-485a-915a-46d5e5196b4e','5ab11713-8520-4d82-a866-4c82e5196b4e','value','',NULL),
	('5ab11713-0518-4219-b2da-4d06e5196b4e','5ab11713-f348-4c2c-b056-4a9be5196b4e','title','','Missing Audits'),
	('5ab11713-09d0-4b7d-983b-4ba8e5196b4e','5ab11713-8520-4d82-a866-4c82e5196b4e','model','','SecurityService'),
	('5ab11713-0dd4-4a1c-95c9-4bafe5196b4e','5ab11713-8520-4d82-a866-4c82e5196b4e','status','','0'),
	('5ab11713-1198-4723-ad6b-466ae5196b4e','5ab11713-46e4-4218-8489-4bafe5196b4e','id','','51'),
	('5ab11713-1984-4b8b-be10-441ce5196b4e','5ab11713-8520-4d82-a866-4c82e5196b4e','category','','0'),
	('5ab11713-1db0-4776-b6aa-47e7e5196b4e','5ab11713-8520-4d82-a866-4c82e5196b4e','json','','{"User":"missing_audits","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}'),
	('5ab11713-1ef0-46d7-83ae-4139e5196b4e','5ab11713-6394-4de8-8706-4f67e5196b4e','value','',NULL),
	('5ab11713-2370-4966-a020-4f53e5196b4e','5ab11713-f348-4c2c-b056-4a9be5196b4e','json','','{"User":"missing_audits","CustomRoles.CustomRole":"ServiceOwner","CustomRoles.CustomUser":"1"}'),
	('5ab11713-3038-479a-bcb4-4ab7e5196b4e','5ab11713-6394-4de8-8706-4f67e5196b4e','category','','0'),
	('5ab11713-3618-4085-a77f-438de5196b4e','5ab11713-46e4-4218-8489-4bafe5196b4e','owner_id','',NULL),
	('5ab11713-362c-451b-8472-4dafe5196b4e','5ab11713-6394-4de8-8706-4f67e5196b4e','title','','Total'),
	('5ab11713-3758-4d46-9212-461be5196b4e','5ab11713-46e4-4218-8489-4bafe5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}'),
	('5ab11713-3c3c-4800-a34e-4b50e5196b4e','5ab11713-29cc-4802-a98c-4ef0e5196b4e','status','0','1'),
	('5ab11713-3f90-4d3e-a708-48d5e5196b4e','5ab11713-f2ec-480d-8559-42ede5196b4e','value',NULL,'0'),
	('5ab11713-4308-4f81-b4ba-4752e5196b4e','5ab11713-f348-4c2c-b056-4a9be5196b4e','model','','SecurityService'),
	('5ab11713-4db0-4f12-a205-4db3e5196b4e','5ab11713-6394-4de8-8706-4f67e5196b4e','type','','0'),
	('5ab11713-6278-4883-94f1-4039e5196b4e','5ab11713-8520-4d82-a866-4c82e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11713-6b48-403e-a323-4a44e5196b4e','5ab11713-46e4-4218-8489-4bafe5196b4e','type','','0'),
	('5ab11713-6c54-4c24-bdae-4586e5196b4e','5ab11713-46e4-4218-8489-4bafe5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11713-6dc4-4098-808b-41cae5196b4e','5ab11713-8520-4d82-a866-4c82e5196b4e','id','','53'),
	('5ab11713-7c34-4e70-b065-49b9e5196b4e','5ab11713-46e4-4218-8489-4bafe5196b4e','value','',NULL),
	('5ab11713-9098-48ea-8427-4b78e5196b4e','5ab11713-bedc-40ad-8401-457ee5196b4e','status','0','1'),
	('5ab11713-9180-4b29-996d-4c5ce5196b4e','5ab11713-6394-4de8-8706-4f67e5196b4e','id','','50'),
	('5ab11713-96bc-4e4e-b882-4211e5196b4e','5ab11713-f348-4c2c-b056-4a9be5196b4e','type','','0'),
	('5ab11713-9b24-40d6-b574-41bbe5196b4e','5ab11713-bd4c-4777-8560-4df9e5196b4e','value',NULL,'0'),
	('5ab11713-9d10-4fc4-b272-4165e5196b4e','5ab11713-4c44-4828-8571-48f7e5196b4e','value',NULL,'0'),
	('5ab11713-a31c-41b3-9ae6-464be5196b4e','5ab11713-f348-4c2c-b056-4a9be5196b4e','category','','0'),
	('5ab11713-a7b0-4816-bab4-4bcde5196b4e','5ab11713-6394-4de8-8706-4f67e5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"ServiceOwner","CustomRoles.CustomUser":"1"}'),
	('5ab11713-a8bc-431f-a949-49dee5196b4e','5ab11713-6394-4de8-8706-4f67e5196b4e','model','','SecurityService'),
	('5ab11713-abe8-4065-b4c5-4a61e5196b4e','5ab11713-794c-41d0-a37e-4fd4e5196b4e','status','0','1'),
	('5ab11713-ae80-47df-bfde-454ae5196b4e','5ab11713-6394-4de8-8706-4f67e5196b4e','owner_id','',NULL),
	('5ab11713-af3c-4183-b8f9-4d1ae5196b4e','5ab11713-f348-4c2c-b056-4a9be5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11713-b274-4147-8021-4f7be5196b4e','5ab11713-f348-4c2c-b056-4a9be5196b4e','status','','0'),
	('5ab11713-b324-4c5b-8538-4429e5196b4e','5ab11713-8520-4d82-a866-4c82e5196b4e','owner_id','',NULL),
	('5ab11713-b4dc-46c6-93b5-4827e5196b4e','5ab11713-6394-4de8-8706-4f67e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11713-bdec-4b08-8a1d-400de5196b4e','5ab11713-8520-4d82-a866-4c82e5196b4e','title','','Missing Audits'),
	('5ab11713-c21c-40c8-bc72-4228e5196b4e','5ab11713-46e4-4218-8489-4bafe5196b4e','category','','0'),
	('5ab11713-c560-42af-aa65-4363e5196b4e','5ab11713-8520-4d82-a866-4c82e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11713-c6a4-480e-a5ef-458fe5196b4e','5ab11713-46e4-4218-8489-4bafe5196b4e','model','','SecurityService'),
	('5ab11713-c710-4397-ad15-4e20e5196b4e','5ab11713-46e4-4218-8489-4bafe5196b4e','title','','Total'),
	('5ab11713-d4b0-444e-b5b8-44cde5196b4e','5ab11713-6394-4de8-8706-4f67e5196b4e','status','','0'),
	('5ab11713-ddbc-4a0f-a24a-48b1e5196b4e','5ab11713-f348-4c2c-b056-4a9be5196b4e','value','',NULL),
	('5ab11713-e0c4-48c1-a523-4ff0e5196b4e','5ab11713-8520-4d82-a866-4c82e5196b4e','type','','0'),
	('5ab11713-e31c-47d9-821e-44dee5196b4e','5ab11713-f348-4c2c-b056-4a9be5196b4e','owner_id','',NULL),
	('5ab11713-f3a8-4125-b30e-4733e5196b4e','5ab11713-6394-4de8-8706-4f67e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11713-f40c-4d17-b48e-4fb9e5196b4e','5ab11713-46e4-4218-8489-4bafe5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11713-f60c-4f03-9462-4a5be5196b4e','5ab11713-46e4-4218-8489-4bafe5196b4e','status','','0'),
	('5ab11713-f80c-4d99-a402-4ee1e5196b4e','5ab11713-f348-4c2c-b056-4a9be5196b4e','id','','52'),
	('5ab11713-fbe4-416b-a27b-405be5196b4e','5ab11713-f348-4c2c-b056-4a9be5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11714-0054-4bf0-a03d-4694e5196b4e','5ab11714-6aa0-401c-9bdd-4be8e5196b4e','model','','SecurityService'),
	('5ab11714-0134-4daa-af54-4774e5196b4e','5ab11714-fba8-435b-b256-40bce5196b4e','value',NULL,'0'),
	('5ab11714-0984-4488-b3d2-4cc7e5196b4e','5ab11714-82c4-4f4e-b7ba-4667e5196b4e','title','','Failed Audits'),
	('5ab11714-1274-4c87-83e9-4f75e5196b4e','5ab11714-82c4-4f4e-b7ba-4667e5196b4e','id','','54'),
	('5ab11714-1848-4bc6-9e54-4babe5196b4e','5ab11714-82c4-4f4e-b7ba-4667e5196b4e','category','','0'),
	('5ab11714-267c-40ee-a824-40e3e5196b4e','5ab11714-5214-4478-8255-4852e5196b4e','title','','Issues'),
	('5ab11714-2ae8-4fbf-af89-4245e5196b4e','5ab11714-5138-4007-827a-4c42e5196b4e','value',NULL,'0'),
	('5ab11714-2e7c-49bf-b9fc-47f8e5196b4e','5ab11714-6aa0-401c-9bdd-4be8e5196b4e','status','','0'),
	('5ab11714-31b4-4412-a897-42a1e5196b4e','5ab11714-5214-4478-8255-4852e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11714-329c-49ec-b504-4322e5196b4e','5ab11714-82c4-4f4e-b7ba-4667e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11714-34b8-40b1-977c-491ae5196b4e','5ab11714-5214-4478-8255-4852e5196b4e','status','','0'),
	('5ab11714-3694-43a0-9c21-4eaae5196b4e','5ab11714-5214-4478-8255-4852e5196b4e','type','','0'),
	('5ab11714-3e30-4cd3-a16f-43cee5196b4e','5ab11714-82c4-4f4e-b7ba-4667e5196b4e','model','','SecurityService'),
	('5ab11714-41bc-46a2-bcc3-4b16e5196b4e','5ab11714-5214-4478-8255-4852e5196b4e','owner_id','',NULL),
	('5ab11714-42d8-4f05-92ec-4161e5196b4e','5ab11714-6aa0-401c-9bdd-4be8e5196b4e','type','','0'),
	('5ab11714-477c-466b-93d5-4b7ae5196b4e','5ab11714-5214-4478-8255-4852e5196b4e','category','','0'),
	('5ab11714-4e0c-4d63-9f14-460fe5196b4e','5ab11714-6aa0-401c-9bdd-4be8e5196b4e','json','','{"User":"issue","CustomRoles.CustomRole":"ServiceOwner","CustomRoles.CustomUser":"1"}'),
	('5ab11714-4ff0-4b64-93d0-472ae5196b4e','5ab11714-1350-4683-9f67-4051e5196b4e','status','0','1'),
	('5ab11714-5894-4ca8-963c-4c87e5196b4e','5ab11714-82c4-4f4e-b7ba-4667e5196b4e','json','','{"User":"failed_audits","CustomRoles.CustomRole":"ServiceOwner","CustomRoles.CustomUser":"1"}'),
	('5ab11714-6468-4a0f-835c-497ee5196b4e','5ab11714-ef9c-459c-bd8a-4a25e5196b4e','json','','{"User":"failed_audits","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}'),
	('5ab11714-65b4-4df4-8487-4933e5196b4e','5ab11714-ef9c-459c-bd8a-4a25e5196b4e','status','','0'),
	('5ab11714-6ab8-487e-b9db-4e7ae5196b4e','5ab11714-3940-4263-a496-4448e5196b4e','value',NULL,'0'),
	('5ab11714-763c-4e70-a08e-4d5ce5196b4e','5ab11714-82c4-4f4e-b7ba-4667e5196b4e','status','','0'),
	('5ab11714-77d8-4f1f-b535-44c0e5196b4e','5ab11714-5214-4478-8255-4852e5196b4e','model','','SecurityService'),
	('5ab11714-852c-45c7-a857-4ecce5196b4e','5ab11714-6aa0-401c-9bdd-4be8e5196b4e','title','','Issues'),
	('5ab11714-99ac-4ac4-ad6a-49abe5196b4e','5ab11714-6aa0-401c-9bdd-4be8e5196b4e','category','','0'),
	('5ab11714-9c34-4680-8e36-47d2e5196b4e','5ab11714-ef9c-459c-bd8a-4a25e5196b4e','id','','55'),
	('5ab11714-9c70-4994-b61c-4564e5196b4e','5ab11714-7a60-48c6-86c3-415ce5196b4e','status','0','1'),
	('5ab11714-9cc4-4649-b035-45cee5196b4e','5ab11714-ef9c-459c-bd8a-4a25e5196b4e','model','','SecurityService'),
	('5ab11714-9d60-448c-961c-40efe5196b4e','5ab11714-ef9c-459c-bd8a-4a25e5196b4e','type','','0'),
	('5ab11714-a08c-49c4-81c7-4aa6e5196b4e','5ab11714-6aa0-401c-9bdd-4be8e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11714-a128-4ea7-9ab1-4560e5196b4e','5ab11714-c200-415f-b918-4cece5196b4e','status','0','1'),
	('5ab11714-a174-42b4-b7be-4067e5196b4e','5ab11714-5214-4478-8255-4852e5196b4e','id','','57'),
	('5ab11714-b0c0-4801-80c6-4bebe5196b4e','5ab11714-5214-4478-8255-4852e5196b4e','json','','{"User":"issue","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}'),
	('5ab11714-b454-48b6-a22d-4dcee5196b4e','5ab11714-ef9c-459c-bd8a-4a25e5196b4e','owner_id','',NULL),
	('5ab11714-ba6c-4646-b5f0-49e7e5196b4e','5ab11714-6aa0-401c-9bdd-4be8e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11714-ba80-4f46-964f-4f28e5196b4e','5ab11714-82c4-4f4e-b7ba-4667e5196b4e','value','',NULL),
	('5ab11714-be10-4025-9344-49c9e5196b4e','5ab11714-5214-4478-8255-4852e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11714-c164-4eaf-a5be-4a26e5196b4e','5ab11714-82c4-4f4e-b7ba-4667e5196b4e','owner_id','',NULL),
	('5ab11714-c230-4888-9d91-4421e5196b4e','5ab11714-82c4-4f4e-b7ba-4667e5196b4e','type','','0'),
	('5ab11714-c300-4b8f-a883-4ecfe5196b4e','5ab11714-ef9c-459c-bd8a-4a25e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11714-c394-43f1-b488-4a7fe5196b4e','5ab11714-6aa0-401c-9bdd-4be8e5196b4e','owner_id','',NULL),
	('5ab11714-c6dc-4523-8fc5-4146e5196b4e','5ab11714-6aa0-401c-9bdd-4be8e5196b4e','value','',NULL),
	('5ab11714-c750-4c13-8abc-4424e5196b4e','5ab11714-ef9c-459c-bd8a-4a25e5196b4e','value','',NULL),
	('5ab11714-ca68-474f-a463-44fae5196b4e','5ab11714-82c4-4f4e-b7ba-4667e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11714-cb28-42f7-9417-41dce5196b4e','5ab11714-ef9c-459c-bd8a-4a25e5196b4e','category','','0'),
	('5ab11714-d53c-4d6e-bb8d-4c98e5196b4e','5ab11714-6aa0-401c-9bdd-4be8e5196b4e','id','','56'),
	('5ab11714-d7e8-4863-a655-4af6e5196b4e','5ab11714-c33c-4ac5-99a6-4bd4e5196b4e','status','0','1'),
	('5ab11714-e8dc-45a0-a137-4918e5196b4e','5ab11714-ef9c-459c-bd8a-4a25e5196b4e','title','','Failed Audits'),
	('5ab11714-ec18-477c-84e4-4f6fe5196b4e','5ab11714-fee0-4fce-8a25-4da5e5196b4e','value',NULL,'0'),
	('5ab11714-ec1c-4f08-812c-4b34e5196b4e','5ab11714-21d4-4110-b8e7-4837e5196b4e','value',NULL,'0'),
	('5ab11714-ecbc-47f4-a7cc-41d0e5196b4e','5ab11714-5214-4478-8255-4852e5196b4e','value','',NULL),
	('5ab11714-f480-449f-a2d9-4285e5196b4e','5ab11714-881c-4c3c-aa37-4a9fe5196b4e','status','0','1'),
	('5ab11714-ff94-4e51-a339-4002e5196b4e','5ab11714-ef9c-459c-bd8a-4a25e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11715-003c-433c-acda-4ef2e5196b4e','5ab11715-96f0-40a3-bcc1-4e4de5196b4e','value','',NULL),
	('5ab11715-0a4c-46b4-b071-4f40e5196b4e','5ab11715-0fd0-4952-a8a4-4f93e5196b4e','owner_id','',NULL),
	('5ab11715-0c3c-472f-a9d5-4b73e5196b4e','5ab11715-df34-4af3-8a18-4460e5196b4e','owner_id','',NULL),
	('5ab11715-0cec-45a3-b2c4-4d73e5196b4e','5ab11715-9fa4-4cc1-b653-4a59e5196b4e','category','','0'),
	('5ab11715-0d0c-4d05-ba9b-409fe5196b4e','5ab11715-1eb0-40da-9bd5-4474e5196b4e','status','','0'),
	('5ab11715-0fcc-4e12-834c-4f46e5196b4e','5ab11715-df34-4af3-8a18-4460e5196b4e','title','','Issues'),
	('5ab11715-142c-4ee5-a9e6-4031e5196b4e','5ab11715-0fd0-4952-a8a4-4f93e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11715-1450-4e33-bcee-4690e5196b4e','5ab11715-96f0-40a3-bcc1-4e4de5196b4e','status','','0'),
	('5ab11715-1b50-4e84-850d-46e4e5196b4e','5ab11715-8d74-4b8e-9ef4-45d1e5196b4e','model','','SecurityPolicy'),
	('5ab11715-1e60-4726-99c3-4383e5196b4e','5ab11715-331c-48df-a70a-4ea3e5196b4e','status','0','1'),
	('5ab11715-1f2c-4b02-ac6e-4a74e5196b4e','5ab11715-96f0-40a3-bcc1-4e4de5196b4e','model','','SecurityService'),
	('5ab11715-20e8-435a-8946-4e12e5196b4e','5ab11715-df34-4af3-8a18-4460e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11715-2354-47d9-94e3-4404e5196b4e','5ab11715-8d74-4b8e-9ef4-45d1e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11715-2384-4a7b-a1a7-41c1e5196b4e','5ab11715-0fd0-4952-a8a4-4f93e5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}'),
	('5ab11715-2728-40b5-ab4b-440ee5196b4e','5ab11715-9fa4-4cc1-b653-4a59e5196b4e','type','','1'),
	('5ab11715-2a30-4b42-9dd1-46ece5196b4e','5ab11715-9fa4-4cc1-b653-4a59e5196b4e','title','','Failed Audits'),
	('5ab11715-2f6c-4300-be4a-4e91e5196b4e','5ab11715-0fd0-4952-a8a4-4f93e5196b4e','id','','65'),
	('5ab11715-30b4-4dd3-8bd6-4c2ce5196b4e','5ab11715-194c-493f-a42c-45e7e5196b4e','value',NULL,'0'),
	('5ab11715-3128-428b-82de-45dfe5196b4e','5ab11715-1eb0-40da-9bd5-4474e5196b4e','value','',NULL),
	('5ab11715-3594-4da8-9a36-4d24e5196b4e','5ab11715-1274-40ae-a80f-46cfe5196b4e','id','','63'),
	('5ab11715-36b0-4345-ac48-4a66e5196b4e','5ab11715-df34-4af3-8a18-4460e5196b4e','model','','SecurityService'),
	('5ab11715-38b8-4a5d-b698-45fce5196b4e','5ab11715-8d74-4b8e-9ef4-45d1e5196b4e','type','','0'),
	('5ab11715-3a40-4bc9-ab9c-4865e5196b4e','5ab11715-0fd0-4952-a8a4-4f93e5196b4e','status','','0'),
	('5ab11715-41c8-456d-8cf0-4c5be5196b4e','5ab11715-1274-40ae-a80f-46cfe5196b4e','category','','1'),
	('5ab11715-47a4-4ce4-848c-4085e5196b4e','5ab11715-1eb0-40da-9bd5-4474e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11715-4980-4489-8cb5-4bb7e5196b4e','5ab11715-1eb0-40da-9bd5-4474e5196b4e','model','','SecurityService'),
	('5ab11715-4cc0-47f5-bbf0-463ae5196b4e','5ab11715-8d74-4b8e-9ef4-45d1e5196b4e','category','','0'),
	('5ab11715-4f78-4ba3-bbf2-4d71e5196b4e','5ab11715-8d74-4b8e-9ef4-45d1e5196b4e','value','',NULL),
	('5ab11715-4ff8-4a5a-a137-4bfce5196b4e','5ab11715-96f0-40a3-bcc1-4e4de5196b4e','type','','1'),
	('5ab11715-563c-4f70-8488-4179e5196b4e','5ab11715-8d74-4b8e-9ef4-45d1e5196b4e','owner_id','',NULL),
	('5ab11715-5720-4227-8274-4c12e5196b4e','5ab11715-8d74-4b8e-9ef4-45d1e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11715-5924-4514-af3c-424ce5196b4e','5ab11715-0fd0-4952-a8a4-4f93e5196b4e','value','',NULL),
	('5ab11715-5bac-456a-a870-479ce5196b4e','5ab11715-0fd0-4952-a8a4-4f93e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11715-5d7c-435f-95f8-4d21e5196b4e','5ab11715-8d74-4b8e-9ef4-45d1e5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11715-5d84-42cb-8007-4d80e5196b4e','5ab11715-96f0-40a3-bcc1-4e4de5196b4e','json','','{"Admin":"total"}'),
	('5ab11715-5e80-43e6-adea-490ae5196b4e','5ab11715-9fa4-4cc1-b653-4a59e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11715-6228-496f-90e4-4d4ae5196b4e','5ab11715-f14c-45c2-996b-47cbe5196b4e','category','','1'),
	('5ab11715-6318-4ff9-9bc6-494ae5196b4e','5ab11715-9fa4-4cc1-b653-4a59e5196b4e','status','','0'),
	('5ab11715-643c-49aa-bc83-4d01e5196b4e','5ab11715-96f0-40a3-bcc1-4e4de5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11715-64bc-433f-a8e8-4655e5196b4e','5ab11715-96f0-40a3-bcc1-4e4de5196b4e','title','','Total number of Security Services'),
	('5ab11715-6544-4f0d-b325-4fcae5196b4e','5ab11715-9fa4-4cc1-b653-4a59e5196b4e','id','','60'),
	('5ab11715-65c8-478d-ac9b-4b52e5196b4e','5ab11715-df34-4af3-8a18-4460e5196b4e','type','','1'),
	('5ab11715-6748-4abc-9604-46b2e5196b4e','5ab11715-9fa4-4cc1-b653-4a59e5196b4e','model','','SecurityService'),
	('5ab11715-6788-4457-be65-48f2e5196b4e','5ab11715-1eb0-40da-9bd5-4474e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11715-6974-42da-9abc-4567e5196b4e','5ab11715-0fd0-4952-a8a4-4f93e5196b4e','title','','Total'),
	('5ab11715-6ac4-4405-a72e-47d4e5196b4e','5ab11715-96f0-40a3-bcc1-4e4de5196b4e','id','','58'),
	('5ab11715-6b08-432a-bc0a-4722e5196b4e','5ab11715-de70-4591-9bd1-4fefe5196b4e','value',NULL,'0'),
	('5ab11715-7160-484b-ae5a-4049e5196b4e','5ab11715-0fd0-4952-a8a4-4f93e5196b4e','model','','SecurityPolicy'),
	('5ab11715-75b4-413a-8b7f-4401e5196b4e','5ab11715-1eb0-40da-9bd5-4474e5196b4e','title','','Missing Audits'),
	('5ab11715-7600-45e1-b07a-412ae5196b4e','5ab11715-df34-4af3-8a18-4460e5196b4e','status','','0'),
	('5ab11715-7700-4689-a71e-4e2ae5196b4e','5ab11715-1274-40ae-a80f-46cfe5196b4e','model','','SecurityService'),
	('5ab11715-7818-4559-a1ef-4212e5196b4e','5ab11715-f14c-45c2-996b-47cbe5196b4e','status','','0'),
	('5ab11715-789c-4d15-bef9-4f83e5196b4e','5ab11715-df34-4af3-8a18-4460e5196b4e','category','','0'),
	('5ab11715-7a8c-45d1-9fe2-4dd4e5196b4e','5ab11715-f14c-45c2-996b-47cbe5196b4e','type','','1'),
	('5ab11715-8248-4a7f-9f56-4e29e5196b4e','5ab11715-df34-4af3-8a18-4460e5196b4e','json','','{"Admin":"issue"}'),
	('5ab11715-8264-4118-bef9-4566e5196b4e','5ab11715-df34-4af3-8a18-4460e5196b4e','value','',NULL),
	('5ab11715-8720-4a26-b669-4c39e5196b4e','5ab11715-1eb0-40da-9bd5-4474e5196b4e','category','','0'),
	('5ab11715-87c0-4516-94e9-45dae5196b4e','5ab11715-f14c-45c2-996b-47cbe5196b4e','model','','SecurityService'),
	('5ab11715-8ce8-462d-b70f-4961e5196b4e','5ab11715-80d4-4e55-9f0f-4630e5196b4e','status','0','1'),
	('5ab11715-8eac-401a-88b8-4f60e5196b4e','5ab11715-85dc-43fb-9835-4049e5196b4e','status','0','1'),
	('5ab11715-90e4-4585-86a3-476ce5196b4e','5ab11715-9710-4276-8881-405fe5196b4e','value',NULL,'0'),
	('5ab11715-9230-412c-8e95-4e4de5196b4e','5ab11715-1eb0-40da-9bd5-4474e5196b4e','json','','{"Admin":"missing_audits"}'),
	('5ab11715-92fc-4a8f-a630-4030e5196b4e','5ab11715-7f7c-49ea-836c-4048e5196b4e','value',NULL,'0'),
	('5ab11715-942c-4aee-a116-4594e5196b4e','5ab11715-f14c-45c2-996b-47cbe5196b4e','json','','{"DynamicFilter":"recently_created"}'),
	('5ab11715-9908-4b91-83e3-468ee5196b4e','5ab11715-f14c-45c2-996b-47cbe5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11715-9dd0-48de-9956-4cd8e5196b4e','5ab11715-96f0-40a3-bcc1-4e4de5196b4e','category','','0'),
	('5ab11715-a2e4-45a0-a71a-4ddfe5196b4e','5ab11715-1274-40ae-a80f-46cfe5196b4e','value','',NULL),
	('5ab11715-a878-4377-a7c3-4edce5196b4e','5ab11715-96f0-40a3-bcc1-4e4de5196b4e','owner_id','',NULL),
	('5ab11715-b0bc-40f4-86e1-4d81e5196b4e','5ab11715-1274-40ae-a80f-46cfe5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11715-b0c8-4ac5-9c16-4806e5196b4e','5ab11715-96f0-40a3-bcc1-4e4de5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11715-b0f0-468d-ac51-4ce6e5196b4e','5ab11715-1274-40ae-a80f-46cfe5196b4e','owner_id','',NULL),
	('5ab11715-b108-4b36-8622-4c0de5196b4e','5ab11715-1274-40ae-a80f-46cfe5196b4e','type','','1'),
	('5ab11715-b504-409c-8e67-4e8be5196b4e','5ab11715-f14c-45c2-996b-47cbe5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11715-b5d4-4c13-97db-4e76e5196b4e','5ab11715-f14c-45c2-996b-47cbe5196b4e','id','','62'),
	('5ab11715-b720-4adb-bdfa-4ae4e5196b4e','5ab11715-9fa4-4cc1-b653-4a59e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11715-b948-4c85-b232-467ce5196b4e','5ab11715-6938-422f-bcd3-4b1ce5196b4e','value',NULL,'0'),
	('5ab11715-bd7c-440a-a935-4da0e5196b4e','5ab11715-1274-40ae-a80f-46cfe5196b4e','json','','{"DynamicFilter":"recently_deleted"}'),
	('5ab11715-bfdc-45ff-a268-4b66e5196b4e','5ab11715-0fd0-4952-a8a4-4f93e5196b4e','type','','0'),
	('5ab11715-c2a4-4af9-a474-437ae5196b4e','5ab11715-1274-40ae-a80f-46cfe5196b4e','status','','0'),
	('5ab11715-c654-4a9e-b2d5-4443e5196b4e','5ab11715-1eb0-40da-9bd5-4474e5196b4e','type','','1'),
	('5ab11715-c78c-44a4-94da-4c46e5196b4e','5ab11715-f14c-45c2-996b-47cbe5196b4e','owner_id','',NULL),
	('5ab11715-c8ec-4288-b577-4552e5196b4e','5ab11715-9fa4-4cc1-b653-4a59e5196b4e','owner_id','',NULL),
	('5ab11715-c940-42c9-b3a9-4180e5196b4e','5ab11715-ca58-4190-9aa0-4c96e5196b4e','status','0','1'),
	('5ab11715-cd04-45dc-845e-44c2e5196b4e','5ab11715-9fa4-4cc1-b653-4a59e5196b4e','json','','{"Admin":"failed_audits"}'),
	('5ab11715-cdd0-4748-8081-49eee5196b4e','5ab11715-ede0-48d7-9e78-45cee5196b4e','status','0','1'),
	('5ab11715-d6d8-4200-bbf5-4ed5e5196b4e','5ab11715-8d74-4b8e-9ef4-45d1e5196b4e','title','','Total'),
	('5ab11715-da04-491b-9e75-4f2ce5196b4e','5ab11715-f62c-4b3f-bc7b-461fe5196b4e','value',NULL,'0'),
	('5ab11715-dd48-41b5-905a-4eaae5196b4e','5ab11715-1eb0-40da-9bd5-4474e5196b4e','id','','59'),
	('5ab11715-e760-4405-b4c9-4ec7e5196b4e','5ab11715-df34-4af3-8a18-4460e5196b4e','id','','61'),
	('5ab11715-eb0c-4d11-b7e1-4ab5e5196b4e','5ab11715-8d74-4b8e-9ef4-45d1e5196b4e','status','','0'),
	('5ab11715-ed00-4e5b-ad95-48e2e5196b4e','5ab11715-1274-40ae-a80f-46cfe5196b4e','title','','Security Services deleted during the past two weeks'),
	('5ab11715-f0b8-4d89-8b3e-4d91e5196b4e','5ab11715-df34-4af3-8a18-4460e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11715-f110-488b-ab66-483ae5196b4e','5ab11715-9fa4-4cc1-b653-4a59e5196b4e','value','',NULL),
	('5ab11715-f1f8-473c-a026-4286e5196b4e','5ab11715-0fd0-4952-a8a4-4f93e5196b4e','category','','0'),
	('5ab11715-f288-427f-84ce-4c03e5196b4e','5ab11715-f14c-45c2-996b-47cbe5196b4e','value','',NULL),
	('5ab11715-f358-4d16-bc3d-4bf7e5196b4e','5ab11715-f14c-45c2-996b-47cbe5196b4e','title','','Security Services created during the past two weeks'),
	('5ab11715-f740-4b11-8dd7-41e5e5196b4e','5ab11715-8d74-4b8e-9ef4-45d1e5196b4e','id','','64'),
	('5ab11715-f908-4796-b380-492de5196b4e','5ab11715-1274-40ae-a80f-46cfe5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11715-fb70-4e63-98bc-434be5196b4e','5ab11715-357c-48eb-9d83-4194e5196b4e','status','0','1'),
	('5ab11715-fcc8-4c1d-a4fe-4958e5196b4e','5ab11715-124c-4605-95f2-4865e5196b4e','value',NULL,'0'),
	('5ab11715-fd50-4ce5-88e8-42e5e5196b4e','5ab11715-1eb0-40da-9bd5-4474e5196b4e','owner_id','',NULL),
	('5ab11715-ffa4-43a9-8992-4644e5196b4e','5ab11715-2ca0-4073-b792-47a7e5196b4e','status','0','1'),
	('5ab11716-0244-443c-b46b-4a77e5196b4e','5ab11716-13f8-400b-898b-4c20e5196b4e','model','','SecurityPolicy'),
	('5ab11716-0364-4d43-9955-456ce5196b4e','5ab11716-277c-4382-b162-4c8ae5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11716-06e4-4ab2-ac93-4bb0e5196b4e','5ab11716-2b00-486e-bf34-4be7e5196b4e','status','','0'),
	('5ab11716-0730-4991-b1da-41c9e5196b4e','5ab11716-0824-4670-8c7d-45a6e5196b4e','status','0','1'),
	('5ab11716-0900-4973-b8d1-48d8e5196b4e','5ab11716-13f8-400b-898b-4c20e5196b4e','id','','70'),
	('5ab11716-0940-4910-871d-4df8e5196b4e','5ab11716-a840-438b-a21d-4809e5196b4e','value','',NULL),
	('5ab11716-0a60-4f34-8cf2-4a7de5196b4e','5ab11716-a840-438b-a21d-4809e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11716-1188-4750-b780-498be5196b4e','5ab11716-a840-438b-a21d-4809e5196b4e','type','','1'),
	('5ab11716-15d0-460f-bcfa-4035e5196b4e','5ab11716-2b00-486e-bf34-4be7e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11716-1610-434c-91bb-4191e5196b4e','5ab11716-277c-4382-b162-4c8ae5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11716-1724-4482-9819-4838e5196b4e','5ab11716-818c-41ca-b979-45a0e5196b4e','category','','0'),
	('5ab11716-1954-4fc2-a74e-4b69e5196b4e','5ab11716-72b4-46c8-9850-4853e5196b4e','value',NULL,'0'),
	('5ab11716-2798-410b-bad8-4f33e5196b4e','5ab11716-c274-4baa-877c-4017e5196b4e','status','0','1'),
	('5ab11716-2b90-4b6a-8414-465de5196b4e','5ab11716-818c-41ca-b979-45a0e5196b4e','owner_id','',NULL),
	('5ab11716-2cfc-4d3c-a27f-4dcce5196b4e','5ab11716-277c-4382-b162-4c8ae5196b4e','value','',NULL),
	('5ab11716-31a4-41df-a8bb-4936e5196b4e','5ab11716-13f8-400b-898b-4c20e5196b4e','type','','1'),
	('5ab11716-3288-4fad-968a-4b07e5196b4e','5ab11716-a35c-48d9-9a84-4bace5196b4e','type','','0'),
	('5ab11716-3444-4915-959a-49a6e5196b4e','5ab11716-818c-41ca-b979-45a0e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11716-39d4-49e8-bc13-47a2e5196b4e','5ab11716-277c-4382-b162-4c8ae5196b4e','title','','Expired'),
	('5ab11716-4018-4cc2-82ef-4741e5196b4e','5ab11716-818c-41ca-b979-45a0e5196b4e','json','','{"User":"next_reviews","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}'),
	('5ab11716-40d0-4e34-93d4-48b4e5196b4e','5ab11716-277c-4382-b162-4c8ae5196b4e','id','','66'),
	('5ab11716-41f4-491b-8629-45f3e5196b4e','5ab11716-7164-489b-970e-4033e5196b4e','status','0','1'),
	('5ab11716-45b8-4894-9e20-4684e5196b4e','5ab11716-2b00-486e-bf34-4be7e5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab11716-4628-4c30-ad0d-42dde5196b4e','5ab11716-b640-4b8a-83ae-4112e5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab11716-4804-4165-8808-486ce5196b4e','5ab11716-818c-41ca-b979-45a0e5196b4e','value','',NULL),
	('5ab11716-4898-48ba-8f19-41c2e5196b4e','5ab11716-a840-438b-a21d-4809e5196b4e','json','','{"Admin":"next_reviews"}'),
	('5ab11716-4ff4-40d2-acb7-4f3fe5196b4e','5ab11716-2b00-486e-bf34-4be7e5196b4e','type','','0'),
	('5ab11716-5470-46c9-8761-41fde5196b4e','5ab11716-0c34-4306-9f1f-4184e5196b4e','value',NULL,'0'),
	('5ab11716-56b4-4d7a-ad57-49b2e5196b4e','5ab11716-277c-4382-b162-4c8ae5196b4e','json','','{"User":"next_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11716-5ba8-47b7-bd3a-4b9ae5196b4e','5ab11716-a35c-48d9-9a84-4bace5196b4e','category','','0'),
	('5ab11716-60e4-4417-8e30-4168e5196b4e','5ab11716-13f8-400b-898b-4c20e5196b4e','json','','{"Admin":"total"}'),
	('5ab11716-61c0-4768-952f-434de5196b4e','5ab11716-13f8-400b-898b-4c20e5196b4e','value','',NULL),
	('5ab11716-6200-4ad1-99d1-422be5196b4e','5ab11716-b640-4b8a-83ae-4112e5196b4e','model','','SecurityPolicy'),
	('5ab11716-6c54-4314-9f62-42b1e5196b4e','5ab11716-6cc0-4d08-bfb4-44a9e5196b4e','value',NULL,'0'),
	('5ab11716-6cf4-4292-89b8-4814e5196b4e','5ab11716-7aa8-4ffe-adb7-4fcbe5196b4e','status','0','1'),
	('5ab11716-6d70-4ee1-aff3-407fe5196b4e','5ab11716-b640-4b8a-83ae-4112e5196b4e','type','','1'),
	('5ab11716-6d90-49ca-a65b-4c8ee5196b4e','5ab11716-c2e4-4625-84c7-4d0be5196b4e','status','0','1'),
	('5ab11716-6dcc-43ca-a496-453fe5196b4e','5ab11716-277c-4382-b162-4c8ae5196b4e','category','','0'),
	('5ab11716-6f80-4492-a579-4842e5196b4e','5ab11716-a840-438b-a21d-4809e5196b4e','category','','0'),
	('5ab11716-7208-4273-b88a-46cfe5196b4e','5ab11716-a35c-48d9-9a84-4bace5196b4e','owner_id','',NULL),
	('5ab11716-7504-4907-8bf3-4390e5196b4e','5ab11716-818c-41ca-b979-45a0e5196b4e','id','','67'),
	('5ab11716-76bc-4604-9378-42d6e5196b4e','5ab11716-13f8-400b-898b-4c20e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11716-7c00-4ef5-9841-426be5196b4e','5ab11716-b640-4b8a-83ae-4112e5196b4e','category','','0'),
	('5ab11716-7d24-4c83-b94f-4779e5196b4e','5ab11716-13f8-400b-898b-4c20e5196b4e','category','','0'),
	('5ab11716-7f08-4fa5-b1d6-4139e5196b4e','5ab11716-a35c-48d9-9a84-4bace5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11716-7fcc-474a-87fc-4be4e5196b4e','5ab11716-a35c-48d9-9a84-4bace5196b4e','title','','Coming Reviews (14 Days)'),
	('5ab11716-8508-4925-a202-4236e5196b4e','5ab11716-2b00-486e-bf34-4be7e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11716-857c-4ae6-b1ac-41cfe5196b4e','5ab11716-b640-4b8a-83ae-4112e5196b4e','owner_id','',NULL),
	('5ab11716-8818-47bf-83c0-417de5196b4e','5ab11716-2b00-486e-bf34-4be7e5196b4e','json','','{"User":"missed_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11716-8968-433f-951d-461de5196b4e','5ab11716-2b00-486e-bf34-4be7e5196b4e','model','','SecurityPolicy'),
	('5ab11716-8b64-4c85-806b-45cfe5196b4e','5ab11716-818c-41ca-b979-45a0e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11716-9000-476f-afea-463de5196b4e','5ab11716-a840-438b-a21d-4809e5196b4e','id','','71'),
	('5ab11716-9098-4824-a493-4b65e5196b4e','5ab11716-a16c-40e9-a854-472ae5196b4e','value',NULL,'0'),
	('5ab11716-915c-4a16-9764-41aae5196b4e','5ab11716-818c-41ca-b979-45a0e5196b4e','title','','Expired'),
	('5ab11716-9310-43cd-b3c9-4562e5196b4e','5ab11716-a35c-48d9-9a84-4bace5196b4e','json','','{"User":"missed_reviews","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}'),
	('5ab11716-94fc-45c3-bfc6-410ee5196b4e','5ab11716-818c-41ca-b979-45a0e5196b4e','model','','SecurityPolicy'),
	('5ab11716-9538-4616-bca4-4670e5196b4e','5ab11716-b640-4b8a-83ae-4112e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11716-9638-4275-884b-4bf3e5196b4e','5ab11716-a840-438b-a21d-4809e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11716-97b0-46bc-8d64-4aace5196b4e','5ab11716-277c-4382-b162-4c8ae5196b4e','model','','SecurityPolicy'),
	('5ab11716-a1a0-40c8-aad8-4fe0e5196b4e','5ab11716-a35c-48d9-9a84-4bace5196b4e','value','',NULL),
	('5ab11716-a3a4-4e16-955b-40a6e5196b4e','5ab11716-e594-47a7-a0af-4bb6e5196b4e','status','0','1'),
	('5ab11716-a5ec-42a6-a37f-4fd4e5196b4e','5ab11716-a840-438b-a21d-4809e5196b4e','title','','Expired'),
	('5ab11716-ac48-4fc5-95da-48b4e5196b4e','5ab11716-a35c-48d9-9a84-4bace5196b4e','status','','0'),
	('5ab11716-ac5c-4e83-8aa4-45cae5196b4e','5ab11716-a35c-48d9-9a84-4bace5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11716-ae60-440b-b578-402de5196b4e','5ab11716-b640-4b8a-83ae-4112e5196b4e','id','','72'),
	('5ab11716-b4a4-4b32-9f90-40c4e5196b4e','5ab11716-a7c8-4984-8ae1-434ce5196b4e','value',NULL,'0'),
	('5ab11716-bdd0-489a-9c0c-4a7ee5196b4e','5ab11716-9b5c-4239-b102-400fe5196b4e','value',NULL,'0'),
	('5ab11716-bfc8-4754-8970-4d09e5196b4e','5ab11716-df58-4020-a99d-4d82e5196b4e','status','0','1'),
	('5ab11716-c0f4-4cb8-b77f-4d40e5196b4e','5ab11716-a840-438b-a21d-4809e5196b4e','status','','0'),
	('5ab11716-c47c-4114-849d-4878e5196b4e','5ab11716-a840-438b-a21d-4809e5196b4e','owner_id','',NULL),
	('5ab11716-c64c-4f9c-b0a2-4f53e5196b4e','5ab11716-2b00-486e-bf34-4be7e5196b4e','id','','68'),
	('5ab11716-caa8-4d97-a708-417ce5196b4e','5ab11716-13f8-400b-898b-4c20e5196b4e','status','','0'),
	('5ab11716-cadc-498f-8b7b-43e8e5196b4e','5ab11716-818c-41ca-b979-45a0e5196b4e','type','','0'),
	('5ab11716-cb2c-4960-918f-4a71e5196b4e','5ab11716-277c-4382-b162-4c8ae5196b4e','owner_id','',NULL),
	('5ab11716-cd4c-4038-8c34-4a74e5196b4e','5ab11716-818c-41ca-b979-45a0e5196b4e','status','','0'),
	('5ab11716-d1b4-44d1-ba8f-439fe5196b4e','5ab11716-13f8-400b-898b-4c20e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11716-d2d0-4bc4-ab6a-4d1de5196b4e','5ab11716-a840-438b-a21d-4809e5196b4e','model','','SecurityPolicy'),
	('5ab11716-d4b4-430d-9db2-4125e5196b4e','5ab11716-2b00-486e-bf34-4be7e5196b4e','category','','0'),
	('5ab11716-d8c0-4a43-8284-459ee5196b4e','5ab11716-277c-4382-b162-4c8ae5196b4e','type','','0'),
	('5ab11716-d8c8-4e11-b0af-428ee5196b4e','5ab11716-2b00-486e-bf34-4be7e5196b4e','value','',NULL),
	('5ab11716-e358-452d-83f3-42cee5196b4e','5ab11716-277c-4382-b162-4c8ae5196b4e','status','','0'),
	('5ab11716-e6bc-4261-b081-4060e5196b4e','5ab11716-b640-4b8a-83ae-4112e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11716-eb54-46d6-8a5d-4fe1e5196b4e','5ab11716-a35c-48d9-9a84-4bace5196b4e','model','','SecurityPolicy'),
	('5ab11716-ebc8-4cd1-a40e-4be9e5196b4e','5ab11716-a370-462d-9a2d-42f9e5196b4e','value',NULL,'0'),
	('5ab11716-ee2c-4163-89d2-4c32e5196b4e','5ab11716-2b00-486e-bf34-4be7e5196b4e','owner_id','',NULL),
	('5ab11716-f338-4cdf-a47c-42bfe5196b4e','5ab11716-13f8-400b-898b-4c20e5196b4e','owner_id','',NULL),
	('5ab11716-f8ec-485b-84db-4ca7e5196b4e','5ab11716-13f8-400b-898b-4c20e5196b4e','title','','Total number of Security Policies'),
	('5ab11716-fb94-42c6-bab4-46cae5196b4e','5ab11716-a35c-48d9-9a84-4bace5196b4e','id','','69'),
	('5ab11717-0694-4216-a41e-486fe5196b4e','5ab11717-0fc4-4934-957d-4bd5e5196b4e','owner_id','',NULL),
	('5ab11717-08a8-496a-aa7f-4a22e5196b4e','5ab11717-fd10-4b75-83ec-49c1e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11717-0d48-4fce-9296-4fade5196b4e','5ab11717-0614-4506-8838-42e3e5196b4e','model','','SecurityIncident'),
	('5ab11717-142c-4196-a5e8-4060e5196b4e','5ab11717-062c-4b9e-886a-4dcee5196b4e','status','','0'),
	('5ab11717-1bc4-4fc3-be15-42e9e5196b4e','5ab11717-0fc4-4934-957d-4bd5e5196b4e','status','','0'),
	('5ab11717-1df8-4db6-8a4e-4151e5196b4e','5ab11717-062c-4b9e-886a-4dcee5196b4e','value','',NULL),
	('5ab11717-2044-4bf6-bb1b-446ee5196b4e','5ab11717-fd10-4b75-83ec-49c1e5196b4e','id','','74'),
	('5ab11717-211c-4dc2-951a-4cdce5196b4e','5ab11717-0614-4506-8838-42e3e5196b4e','category','','0'),
	('5ab11717-28b0-445d-8d94-42bce5196b4e','5ab11717-0614-4506-8838-42e3e5196b4e','owner_id','',NULL),
	('5ab11717-2a30-43df-bef0-4912e5196b4e','5ab11717-062c-4b9e-886a-4dcee5196b4e','id','','76'),
	('5ab11717-2ba0-4f27-bb17-4c1ce5196b4e','5ab11717-fd10-4b75-83ec-49c1e5196b4e','status','','0'),
	('5ab11717-352c-428b-b0d7-476de5196b4e','5ab11717-fd10-4b75-83ec-49c1e5196b4e','value','',NULL),
	('5ab11717-3628-4946-9e55-4d38e5196b4e','5ab11717-3334-49c6-a3e6-4252e5196b4e','value',NULL,'0'),
	('5ab11717-3a6c-44eb-9600-419ee5196b4e','5ab11717-fd10-4b75-83ec-49c1e5196b4e','model','','SecurityPolicy'),
	('5ab11717-3e8c-4fe0-8050-4cf7e5196b4e','5ab11717-ed54-4ac3-bde9-46a3e5196b4e','value',NULL,'0'),
	('5ab11717-3fd8-4e5f-b27b-4ddce5196b4e','5ab11717-0614-4506-8838-42e3e5196b4e','title','','Total'),
	('5ab11717-48dc-400a-b001-4121e5196b4e','5ab11717-0fd8-4c19-9d87-4457e5196b4e','value','',NULL),
	('5ab11717-4c9c-467d-aebe-4744e5196b4e','5ab11717-5ee4-47b1-b6b1-43fae5196b4e','status','0','1'),
	('5ab11717-4ef0-49fd-9d30-4303e5196b4e','5ab11717-8aa0-45e9-8d0a-46b4e5196b4e','status','0','1'),
	('5ab11717-518c-4bd9-9e8f-4742e5196b4e','5ab11717-0614-4506-8838-42e3e5196b4e','id','','75'),
	('5ab11717-5b24-43af-9a45-4dc0e5196b4e','5ab11717-0fc4-4934-957d-4bd5e5196b4e','title','','Closed'),
	('5ab11717-5cf8-483f-9bab-4610e5196b4e','5ab11717-0fd8-4c19-9d87-4457e5196b4e','type','','1'),
	('5ab11717-5ec0-4548-bdc7-496be5196b4e','5ab11717-0fd8-4c19-9d87-4457e5196b4e','status','','0'),
	('5ab11717-601c-4f3a-80a2-4ab4e5196b4e','5ab11717-0fc4-4934-957d-4bd5e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11717-63a8-480f-bcbc-4a6de5196b4e','5ab11717-0fc4-4934-957d-4bd5e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11717-65b0-4770-8a56-46e1e5196b4e','5ab11717-34ec-4673-a738-4425e5196b4e','value',NULL,'0'),
	('5ab11717-6c00-4de2-a137-4a7fe5196b4e','5ab11717-0fd8-4c19-9d87-4457e5196b4e','owner_id','',NULL),
	('5ab11717-7180-4722-8aaa-4ef1e5196b4e','5ab11717-0fc4-4934-957d-4bd5e5196b4e','id','','77'),
	('5ab11717-755c-4921-a831-4e8ae5196b4e','5ab11717-0fc4-4934-957d-4bd5e5196b4e','type','','0'),
	('5ab11717-7814-42be-a4a4-4a02e5196b4e','5ab11717-8370-4354-91f9-4150e5196b4e','status','0','1'),
	('5ab11717-7f90-4340-8e2b-4585e5196b4e','5ab11717-0614-4506-8838-42e3e5196b4e','type','','0'),
	('5ab11717-7fd8-4f31-91f0-402fe5196b4e','5ab11717-062c-4b9e-886a-4dcee5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11717-82d8-45be-a08b-4eb7e5196b4e','5ab11717-0fd8-4c19-9d87-4457e5196b4e','json','','{"DynamicFilter":"recently_created"}'),
	('5ab11717-8c1c-41bd-9928-4fdbe5196b4e','5ab11717-062c-4b9e-886a-4dcee5196b4e','type','','0'),
	('5ab11717-9044-4863-9b09-401de5196b4e','5ab11717-0614-4506-8838-42e3e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11717-9b10-4692-be43-4af8e5196b4e','5ab11717-ec60-4f50-8944-43d0e5196b4e','status','0','1'),
	('5ab11717-9d60-4f9b-b075-4fd5e5196b4e','5ab11717-0fd8-4c19-9d87-4457e5196b4e','title','','Security Policies created during the past two weeks'),
	('5ab11717-9e08-4a8b-818a-4c41e5196b4e','5ab11717-062c-4b9e-886a-4dcee5196b4e','title','','Open'),
	('5ab11717-a0e8-4855-80d8-491ae5196b4e','5ab11717-fd10-4b75-83ec-49c1e5196b4e','json','','{"DynamicFilter":"recently_deleted"}'),
	('5ab11717-a888-4f4b-a5ac-4c0ce5196b4e','5ab11717-c218-4edf-9e33-4139e5196b4e','value',NULL,'0'),
	('5ab11717-aa74-4bc3-87e0-43fae5196b4e','5ab11717-fd10-4b75-83ec-49c1e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11717-adb0-4254-bf53-4d00e5196b4e','5ab11717-663c-4945-ad65-43a9e5196b4e','status','0','1'),
	('5ab11717-b0ec-4565-af60-4116e5196b4e','5ab11717-0614-4506-8838-42e3e5196b4e','json','','{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11717-b38c-4444-bfcf-497ee5196b4e','5ab11717-5234-48c9-84b3-44c6e5196b4e','value',NULL,'0'),
	('5ab11717-b3a0-447a-aeb2-4ed4e5196b4e','5ab11717-062c-4b9e-886a-4dcee5196b4e','model','','SecurityIncident'),
	('5ab11717-b738-419b-adb5-4196e5196b4e','5ab11717-0614-4506-8838-42e3e5196b4e','status','','0'),
	('5ab11717-b7f0-4f26-a93b-49f3e5196b4e','5ab11717-0fc4-4934-957d-4bd5e5196b4e','model','','SecurityIncident'),
	('5ab11717-b9b8-4d26-9d39-4e67e5196b4e','5ab11717-0fd8-4c19-9d87-4457e5196b4e','model','','SecurityPolicy'),
	('5ab11717-ba94-4765-9f19-4c2de5196b4e','5ab11716-b640-4b8a-83ae-4112e5196b4e','json','','{"Admin":"missed_reviews"}'),
	('5ab11717-bd6c-4a5e-9cc5-42d7e5196b4e','5ab11716-b640-4b8a-83ae-4112e5196b4e','value','',NULL),
	('5ab11717-c0a0-474a-95c7-4ef3e5196b4e','5ab11717-0614-4506-8838-42e3e5196b4e','value','',NULL),
	('5ab11717-c8d0-480d-ba23-46a0e5196b4e','5ab11717-0614-4506-8838-42e3e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11717-c9a0-418e-a6b0-4245e5196b4e','5ab11717-0fc4-4934-957d-4bd5e5196b4e','value','',NULL),
	('5ab11717-cf20-42c8-ac85-4966e5196b4e','5ab11717-0fc4-4934-957d-4bd5e5196b4e','json','','{"User":"closed","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11717-cf3c-43e5-9eed-43efe5196b4e','5ab11717-36d8-4e0e-839f-4736e5196b4e','status','0','1'),
	('5ab11717-d030-4626-98d2-4ea9e5196b4e','5ab11717-062c-4b9e-886a-4dcee5196b4e','category','','0'),
	('5ab11717-d98c-4a2c-be17-4883e5196b4e','5ab11717-0fd8-4c19-9d87-4457e5196b4e','category','','1'),
	('5ab11717-e14c-436a-a21e-4422e5196b4e','5ab11717-fd10-4b75-83ec-49c1e5196b4e','type','','1'),
	('5ab11717-e564-40dc-a2ef-4183e5196b4e','5ab11717-062c-4b9e-886a-4dcee5196b4e','owner_id','',NULL),
	('5ab11717-e628-494b-9878-402ee5196b4e','5ab11716-b640-4b8a-83ae-4112e5196b4e','status','','0'),
	('5ab11717-e844-43fb-8ff0-49fce5196b4e','5ab11717-fd10-4b75-83ec-49c1e5196b4e','category','','1'),
	('5ab11717-e954-4360-8558-48d5e5196b4e','5ab11717-0fd8-4c19-9d87-4457e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11717-ecdc-4a99-946c-46c9e5196b4e','5ab11717-fd10-4b75-83ec-49c1e5196b4e','title','','Security Policies deleted during the past two weeks'),
	('5ab11717-f0d4-4776-96e1-4f59e5196b4e','5ab11717-062c-4b9e-886a-4dcee5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11717-f604-4aa9-8333-41dfe5196b4e','5ab11717-fd10-4b75-83ec-49c1e5196b4e','owner_id','',NULL),
	('5ab11717-f604-4bd9-882d-48fae5196b4e','5ab11717-062c-4b9e-886a-4dcee5196b4e','json','','{"User":"open","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11717-f6b0-488a-9be2-4444e5196b4e','5ab11717-9238-45d1-b616-4a84e5196b4e','value',NULL,'0'),
	('5ab11717-f7cc-473a-82ee-4784e5196b4e','5ab11717-0fc4-4934-957d-4bd5e5196b4e','category','','0'),
	('5ab11717-f9a8-4f07-a590-4506e5196b4e','5ab11717-0fd8-4c19-9d87-4457e5196b4e','id','','73'),
	('5ab11717-fc1c-4721-af06-4ff8e5196b4e','5ab11717-0fd8-4c19-9d87-4457e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11718-00e8-451a-8d63-4e6fe5196b4e','5ab11718-afac-49b1-9b32-4cf8e5196b4e','type','','1'),
	('5ab11718-0128-4ebb-9748-459ce5196b4e','5ab11718-5ca0-40c4-a707-4ad7e5196b4e','id','','85'),
	('5ab11718-0144-45ee-b072-4b0be5196b4e','5ab11718-71d4-4a45-ab15-49dde5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11718-0464-4e8b-9c34-4670e5196b4e','5ab11718-e3d8-45bb-9b34-4dcae5196b4e','model','','SecurityIncident'),
	('5ab11718-064c-4d50-9c83-4c7be5196b4e','5ab11718-e3d8-45bb-9b34-4dcae5196b4e','id','','80'),
	('5ab11718-0720-406e-8e35-4b77e5196b4e','5ab11718-4150-4ceb-8b0b-4ee1e5196b4e','status','0','1'),
	('5ab11718-0a18-46ea-b21e-4f99e5196b4e','5ab11718-e3d8-45bb-9b34-4dcae5196b4e','json','','{"Admin":"open"}'),
	('5ab11718-0af4-4559-b5c1-434be5196b4e','5ab11718-b9fc-4c28-8822-488fe5196b4e','json','','{"Admin":"incomplete_stage"}'),
	('5ab11718-0b74-4512-ba09-4047e5196b4e','5ab11718-e3d8-45bb-9b34-4dcae5196b4e','status','','0'),
	('5ab11718-0b98-4d38-a696-400ce5196b4e','5ab11718-b9fc-4c28-8822-488fe5196b4e','id','','82'),
	('5ab11718-0c20-41e1-ad9a-4bcce5196b4e','5ab11718-afac-49b1-9b32-4cf8e5196b4e','status','','0'),
	('5ab11718-110c-4007-9cf0-42c5e5196b4e','5ab11718-71d4-4a45-ab15-49dde5196b4e','category','','1'),
	('5ab11718-14f0-4c36-b4e7-49d8e5196b4e','5ab11718-ac60-4121-ada5-4789e5196b4e','category','','0'),
	('5ab11718-19a0-4a08-92f9-4bdae5196b4e','5ab11718-f570-4073-b3a8-4c79e5196b4e','category','','0'),
	('5ab11718-1c6c-49c6-9b55-4e06e5196b4e','5ab11718-71d4-4a45-ab15-49dde5196b4e','json','','{"DynamicFilter":"recently_deleted"}'),
	('5ab11718-1cac-41da-96e7-423ae5196b4e','5ab11718-ac60-4121-ada5-4789e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11718-1d1c-4075-b974-42f9e5196b4e','5ab11718-f570-4073-b3a8-4c79e5196b4e','owner_id','',NULL),
	('5ab11718-204c-4f94-9e75-4019e5196b4e','5ab11718-15c8-49df-9b26-490ae5196b4e','value',NULL,'0'),
	('5ab11718-2164-44a2-9088-4869e5196b4e','5ab11718-f570-4073-b3a8-4c79e5196b4e','model','','SecurityIncident'),
	('5ab11718-217c-4aac-91d1-4e70e5196b4e','5ab11718-e3d8-45bb-9b34-4dcae5196b4e','owner_id','',NULL),
	('5ab11718-2200-467f-99dd-4d8fe5196b4e','5ab11718-e3d8-45bb-9b34-4dcae5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11718-222c-43ef-9c89-4cd6e5196b4e','5ab11718-e054-40c3-af4d-4a9be5196b4e','value',NULL,'0'),
	('5ab11718-22d4-48ba-8d1c-41cee5196b4e','5ab11718-71d4-4a45-ab15-49dde5196b4e','title','','Security Incidents deleted during the past two weeks'),
	('5ab11718-2420-4221-b82c-4ae8e5196b4e','5ab11718-afac-49b1-9b32-4cf8e5196b4e','value','',NULL),
	('5ab11718-276c-4582-88f7-4a67e5196b4e','5ab11718-b9fc-4c28-8822-488fe5196b4e','owner_id','',NULL),
	('5ab11718-2874-4293-a1c1-408ae5196b4e','5ab11718-5ca0-40c4-a707-4ad7e5196b4e','model','','ComplianceAnalysisFinding'),
	('5ab11718-2d78-4c63-9991-4ef0e5196b4e','5ab11718-afac-49b1-9b32-4cf8e5196b4e','model','','SecurityIncident'),
	('5ab11718-2de4-4563-b0cb-4513e5196b4e','5ab11718-5ca0-40c4-a707-4ad7e5196b4e','type','','0'),
	('5ab11718-2edc-4554-9fbe-49f5e5196b4e','5ab11718-b9fc-4c28-8822-488fe5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11718-3610-4679-a9eb-4786e5196b4e','5ab11718-5be0-4d9b-8cfb-4bd5e5196b4e','status','0','1'),
	('5ab11718-396c-41be-ac1f-487be5196b4e','5ab11718-f570-4073-b3a8-4c79e5196b4e','json','','{"Admin":"closed"}'),
	('5ab11718-3e58-4f58-a7ec-4542e5196b4e','5ab11718-b9fc-4c28-8822-488fe5196b4e','value','',NULL),
	('5ab11718-421c-4cf0-8f34-402fe5196b4e','5ab11718-b9fc-4c28-8822-488fe5196b4e','title','','Incomplete Lifecycle'),
	('5ab11718-43bc-4721-8dec-4197e5196b4e','5ab11718-ac60-4121-ada5-4789e5196b4e','type','','0'),
	('5ab11718-4860-495c-b29b-432de5196b4e','5ab11718-f570-4073-b3a8-4c79e5196b4e','title','','Closed'),
	('5ab11718-4b10-4c01-bb67-437fe5196b4e','5ab11718-f080-43a7-b019-4926e5196b4e','status','0','1'),
	('5ab11718-4bac-4d6b-ab39-4178e5196b4e','5ab11718-ac60-4121-ada5-4789e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11718-4bfc-4749-a048-49e8e5196b4e','5ab11718-b9fc-4c28-8822-488fe5196b4e','model','','SecurityIncident'),
	('5ab11718-4fd0-4482-a5a8-416ee5196b4e','5ab11718-e3d8-45bb-9b34-4dcae5196b4e','title','','Open'),
	('5ab11718-51f4-4c22-9345-4338e5196b4e','5ab11718-b9fc-4c28-8822-488fe5196b4e','type','','1'),
	('5ab11718-5510-484b-b1b1-4758e5196b4e','5ab11718-f570-4073-b3a8-4c79e5196b4e','status','','0'),
	('5ab11718-55ec-4cdc-9f84-41fce5196b4e','5ab11718-119c-4de1-a681-4184e5196b4e','title','','Security Incidents created during the past two weeks'),
	('5ab11718-5608-4151-856d-4310e5196b4e','5ab11718-119c-4de1-a681-4184e5196b4e','owner_id','',NULL),
	('5ab11718-596c-4e19-bd2a-4e19e5196b4e','5ab11718-afac-49b1-9b32-4cf8e5196b4e','id','','79'),
	('5ab11718-5980-4d4d-87f6-47d2e5196b4e','5ab11718-ac60-4121-ada5-4789e5196b4e','value','',NULL),
	('5ab11718-5a3c-43f1-a834-496be5196b4e','5ab11718-5ca0-40c4-a707-4ad7e5196b4e','title','','Expired'),
	('5ab11718-5ee4-4d30-b8f2-417ce5196b4e','5ab11718-71d4-4a45-ab15-49dde5196b4e','owner_id','',NULL),
	('5ab11718-5f84-482f-8aa6-46c5e5196b4e','5ab11718-119c-4de1-a681-4184e5196b4e','model','','SecurityIncident'),
	('5ab11718-601c-459a-b710-452fe5196b4e','5ab11718-71d4-4a45-ab15-49dde5196b4e','value','',NULL),
	('5ab11718-6474-491f-81e3-4f97e5196b4e','5ab11718-ac60-4121-ada5-4789e5196b4e','owner_id','',NULL),
	('5ab11718-66ac-409e-977c-432be5196b4e','5ab11718-119c-4de1-a681-4184e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11718-6988-4cdb-837c-4f3ee5196b4e','5ab11718-b9fc-4c28-8822-488fe5196b4e','status','','0'),
	('5ab11718-6b14-4bc6-a3b2-4636e5196b4e','5ab11718-76d4-455a-a2fc-4880e5196b4e','value',NULL,'0'),
	('5ab11718-6e44-4202-b4d4-4842e5196b4e','5ab11718-119c-4de1-a681-4184e5196b4e','id','','83'),
	('5ab11718-6e84-4b65-8932-4316e5196b4e','5ab11718-afac-49b1-9b32-4cf8e5196b4e','json','','{"Admin":"total"}'),
	('5ab11718-6f68-4118-8808-4814e5196b4e','5ab11718-9110-499b-88fc-4af5e5196b4e','status','0','1'),
	('5ab11718-749c-4818-bf27-4042e5196b4e','5ab11718-e3d8-45bb-9b34-4dcae5196b4e','type','','1'),
	('5ab11718-7568-4f5c-a5dd-4312e5196b4e','5ab11718-f570-4073-b3a8-4c79e5196b4e','value','',NULL),
	('5ab11718-7818-4829-9b31-48b9e5196b4e','5ab11718-71d4-4a45-ab15-49dde5196b4e','model','','SecurityIncident'),
	('5ab11718-78e0-429c-8d41-4118e5196b4e','5ab11718-5ca0-40c4-a707-4ad7e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11718-8044-44db-8412-43ace5196b4e','5ab11718-f570-4073-b3a8-4c79e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11718-8144-430f-97ac-4bcfe5196b4e','5ab11718-631c-48b0-a685-4039e5196b4e','status','0','1'),
	('5ab11718-8394-4134-bba2-4f53e5196b4e','5ab11718-ac60-4121-ada5-4789e5196b4e','model','','SecurityIncident'),
	('5ab11718-83e0-42d2-9ad2-4545e5196b4e','5ab11718-5ca0-40c4-a707-4ad7e5196b4e','owner_id','',NULL),
	('5ab11718-8488-43a5-93c6-40c2e5196b4e','5ab11718-e3d8-45bb-9b34-4dcae5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11718-8bdc-41de-afdc-4706e5196b4e','5ab11718-f570-4073-b3a8-4c79e5196b4e','type','','1'),
	('5ab11718-8ff4-4cb2-bd5a-4fe4e5196b4e','5ab11718-00c4-4ce0-9423-4a53e5196b4e','status','0','1'),
	('5ab11718-949c-4e27-af50-4510e5196b4e','5ab11718-f570-4073-b3a8-4c79e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11718-98e4-4604-8cb0-46f0e5196b4e','5ab11718-8f8c-4593-bf2c-49f2e5196b4e','status','0','1'),
	('5ab11718-9ca8-4918-913d-4f52e5196b4e','5ab11718-a060-4321-b3ff-4570e5196b4e','value',NULL,'0'),
	('5ab11718-a17c-4731-8851-4febe5196b4e','5ab11718-5ca0-40c4-a707-4ad7e5196b4e','category','','0'),
	('5ab11718-a3cc-427a-9fd9-45fee5196b4e','5ab11718-71d4-4a45-ab15-49dde5196b4e','status','','0'),
	('5ab11718-a3f4-467b-8756-4987e5196b4e','5ab11718-119c-4de1-a681-4184e5196b4e','json','','{"DynamicFilter":"recently_created"}'),
	('5ab11718-a8a0-4f62-9c42-4bf9e5196b4e','5ab11718-119c-4de1-a681-4184e5196b4e','category','','1'),
	('5ab11718-ad54-47d2-bfb5-4f4be5196b4e','5ab11718-b9fc-4c28-8822-488fe5196b4e','category','','0'),
	('5ab11718-ba68-4774-bb0f-40b4e5196b4e','5ab11718-afac-49b1-9b32-4cf8e5196b4e','class_name','','Dashboard.DashboardKpiObject'),
	('5ab11718-bc38-46e2-8363-46c2e5196b4e','5ab11718-71d4-4a45-ab15-49dde5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11718-bcd0-4ea9-8a28-4917e5196b4e','5ab11718-afac-49b1-9b32-4cf8e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11718-bd60-4c6e-8372-4ef4e5196b4e','5ab11718-e3d8-45bb-9b34-4dcae5196b4e','category','','0'),
	('5ab11718-be5c-4306-950d-42f3e5196b4e','5ab11718-71d4-4a45-ab15-49dde5196b4e','id','','84'),
	('5ab11718-c754-4ec3-9685-4d0ce5196b4e','5ab11718-119c-4de1-a681-4184e5196b4e','value','',NULL),
	('5ab11718-cd88-44ce-8b5c-459ee5196b4e','5ab11718-f570-4073-b3a8-4c79e5196b4e','id','','81'),
	('5ab11718-ce94-4390-b7dd-46dde5196b4e','5ab11718-119c-4de1-a681-4184e5196b4e','status','','0'),
	('5ab11718-cef8-46e2-a9f4-4e79e5196b4e','5ab11718-b9fc-4c28-8822-488fe5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11718-d5f8-49d5-886d-408fe5196b4e','5ab11718-78a4-4350-836f-4754e5196b4e','value',NULL,'0'),
	('5ab11718-dc68-47d9-a167-421be5196b4e','5ab11718-afac-49b1-9b32-4cf8e5196b4e','category','','0'),
	('5ab11718-de98-49c3-8395-4cdfe5196b4e','5ab11718-ac60-4121-ada5-4789e5196b4e','id','','78'),
	('5ab11718-dec8-4ad0-9816-4878e5196b4e','5ab11718-ac60-4121-ada5-4789e5196b4e','title','','Incomplete Lifecycle'),
	('5ab11718-dfb8-42d8-9e37-42d3e5196b4e','5ab11718-71d4-4a45-ab15-49dde5196b4e','type','','1'),
	('5ab11718-e160-45a3-a2ed-4168e5196b4e','5ab11718-8790-498f-9b99-4fb5e5196b4e','value',NULL,'0'),
	('5ab11718-e870-417f-a1bc-4299e5196b4e','5ab11718-ac60-4121-ada5-4789e5196b4e','status','','0'),
	('5ab11718-ed28-4818-ae47-4ee6e5196b4e','5ab11718-119c-4de1-a681-4184e5196b4e','type','','1'),
	('5ab11718-f01c-483f-a78b-4d13e5196b4e','5ab11718-0fac-473c-8a0d-4087e5196b4e','value',NULL,'0'),
	('5ab11718-f028-4f2f-9425-473fe5196b4e','5ab11718-afac-49b1-9b32-4cf8e5196b4e','owner_id','',NULL),
	('5ab11718-f494-46e6-8b2f-42a8e5196b4e','5ab11718-e3d8-45bb-9b34-4dcae5196b4e','value','',NULL),
	('5ab11718-f774-43cf-85da-4280e5196b4e','5ab11718-afac-49b1-9b32-4cf8e5196b4e','title','','Total'),
	('5ab11718-f81c-41e7-aca0-42bbe5196b4e','5ab11718-119c-4de1-a681-4184e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11718-f868-440f-9be1-438be5196b4e','5ab11718-ac60-4121-ada5-4789e5196b4e','json','','{"User":"incomplete_stage","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11719-226c-44b0-ab50-4eb0e5196b4e','5ab11719-3ba4-43b9-8d7f-45d9e5196b4e','category','','0'),
	('5ab11719-29f0-4fa0-b0c1-4dbfe5196b4e','5ab11718-5ca0-40c4-a707-4ad7e5196b4e','value','',NULL),
	('5ab11719-3280-4e2d-b94a-4d6fe5196b4e','5ab11719-36b8-4a32-b614-414ae5196b4e','value',NULL,'0'),
	('5ab11719-375c-4b4c-af90-4979e5196b4e','5ab11719-0500-417f-b5e1-4de6e5196b4e','status','0','1'),
	('5ab11719-3e30-42b5-bad2-43cbe5196b4e','5ab11719-3ba4-43b9-8d7f-45d9e5196b4e','owner_id','',NULL),
	('5ab11719-44d4-4d87-93b8-40abe5196b4e','5ab11719-3ba4-43b9-8d7f-45d9e5196b4e','type','','0'),
	('5ab11719-4ae4-4e80-b68c-4877e5196b4e','5ab11719-3ba4-43b9-8d7f-45d9e5196b4e','id','','86'),
	('5ab11719-533c-48ef-8c0b-4c33e5196b4e','5ab11719-3ba4-43b9-8d7f-45d9e5196b4e','status','','0'),
	('5ab11719-61f4-425f-91a0-4cf3e5196b4e','5ab11719-3ba4-43b9-8d7f-45d9e5196b4e','title','','Expired'),
	('5ab11719-74a4-40d2-9fd9-48c4e5196b4e','5ab11718-5ca0-40c4-a707-4ad7e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11719-7e7c-41a8-bb7d-4e32e5196b4e','5ab11718-5ca0-40c4-a707-4ad7e5196b4e','json','','{"User":"expired","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab11719-871c-49e8-a6cb-42c2e5196b4e','5ab11719-3ba4-43b9-8d7f-45d9e5196b4e','value','',NULL),
	('5ab11719-ab54-4449-b126-44f2e5196b4e','5ab11719-fc00-41c4-9aaf-464be5196b4e','value',NULL,'0'),
	('5ab11719-b744-43ca-a947-4b35e5196b4e','5ab11719-3ba4-43b9-8d7f-45d9e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab11719-c1f8-47b0-8aaa-4e7ae5196b4e','5ab11719-3ba4-43b9-8d7f-45d9e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab11719-c560-49c7-b2fd-4ee7e5196b4e','5ab11718-5ca0-40c4-a707-4ad7e5196b4e','status','','0'),
	('5ab11719-cff8-4ea1-9b27-4d17e5196b4e','5ab11719-3ba4-43b9-8d7f-45d9e5196b4e','json','','{"User":"expired","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}'),
	('5ab11719-ef38-46f2-afb5-4e70e5196b4e','5ab11719-3ba4-43b9-8d7f-45d9e5196b4e','model','','ComplianceAnalysisFinding'),
	('5ab1171a-0670-4ce0-b28a-4b59e5196b4e','5ab1171a-705c-4605-8163-45dae5196b4e','status','0','1'),
	('5ab1171a-09dc-43ad-9656-4a63e5196b4e','5ab1171a-9a78-4bd6-b526-42b2e5196b4e','type','','0'),
	('5ab1171a-0d68-4dc8-a234-46abe5196b4e','5ab1171a-398c-4251-84c9-4208e5196b4e','category','','0'),
	('5ab1171a-1a6c-45f7-a576-417ee5196b4e','5ab1171a-398c-4251-84c9-4208e5196b4e','json','','{"User":"open","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}'),
	('5ab1171a-1dd8-460f-a9cd-4f3ee5196b4e','5ab1171a-398c-4251-84c9-4208e5196b4e','type','','0'),
	('5ab1171a-268c-4eb9-87dd-478ae5196b4e','5ab1171a-6228-4432-a40c-4160e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1171a-30e8-4eec-b29a-43efe5196b4e','5ab1171a-9a78-4bd6-b526-42b2e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1171a-3140-44cd-91a2-4d39e5196b4e','5ab1171a-6228-4432-a40c-4160e5196b4e','type','','0'),
	('5ab1171a-31a0-49e7-9d94-4935e5196b4e','5ab1171a-c060-4931-9434-43a2e5196b4e','status','0','1'),
	('5ab1171a-36c4-4aad-b8e6-4dfce5196b4e','5ab1171a-9a78-4bd6-b526-42b2e5196b4e','json','','{"User":"open","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}'),
	('5ab1171a-3d74-4305-a535-4f17e5196b4e','5ab1171a-ae9c-4f71-8caf-476fe5196b4e','value',NULL,'0'),
	('5ab1171a-4630-43d8-beaa-4931e5196b4e','5ab1171a-9a78-4bd6-b526-42b2e5196b4e','status','','0'),
	('5ab1171a-481c-4adb-8172-49aae5196b4e','5ab1171a-6228-4432-a40c-4160e5196b4e','status','','0'),
	('5ab1171a-49fc-4ba3-b2b6-4fe7e5196b4e','5ab1171a-97a4-43c2-8bdb-45fee5196b4e','value',NULL,'0'),
	('5ab1171a-4d9c-4f09-a9af-47b2e5196b4e','5ab1171a-052c-4d10-98bb-4698e5196b4e','type','','0'),
	('5ab1171a-4db8-4667-95d1-4820e5196b4e','5ab1171a-398c-4251-84c9-4208e5196b4e','value','',NULL),
	('5ab1171a-4f64-4078-9c88-45d2e5196b4e','5ab1171a-398c-4251-84c9-4208e5196b4e','owner_id','',NULL),
	('5ab1171a-4ff4-4bab-815f-4381e5196b4e','5ab1171a-9a78-4bd6-b526-42b2e5196b4e','model','','ComplianceAnalysisFinding'),
	('5ab1171a-5264-4525-80a5-4c22e5196b4e','5ab1171a-6228-4432-a40c-4160e5196b4e','owner_id','',NULL),
	('5ab1171a-5448-45a6-956a-4995e5196b4e','5ab1171a-6228-4432-a40c-4160e5196b4e','json','','{"User":"closed","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}'),
	('5ab1171a-5b8c-4a3c-ab3b-4e9be5196b4e','5ab1171a-052c-4d10-98bb-4698e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1171a-5b90-4b1e-a755-496fe5196b4e','5ab1171a-052c-4d10-98bb-4698e5196b4e','status','','0'),
	('5ab1171a-5cf0-465e-bff3-4c33e5196b4e','5ab1171a-398c-4251-84c9-4208e5196b4e','title','','Open'),
	('5ab1171a-6aec-41ff-90d4-4ac9e5196b4e','5ab1171a-9a78-4bd6-b526-42b2e5196b4e','owner_id','',NULL),
	('5ab1171a-6ff8-422a-ac40-4125e5196b4e','5ab11719-4720-4d7d-a45b-4c08e5196b4e','status','0','1'),
	('5ab1171a-7770-445f-8af1-4003e5196b4e','5ab1171a-7d9c-4722-9b27-44dce5196b4e','value',NULL,'0'),
	('5ab1171a-7ae8-4ef1-bcab-40e8e5196b4e','5ab1171a-9a78-4bd6-b526-42b2e5196b4e','id','','88'),
	('5ab1171a-819c-4fe3-a0de-48cce5196b4e','5ab1171a-052c-4d10-98bb-4698e5196b4e','owner_id','',NULL),
	('5ab1171a-8228-49f4-8d89-4263e5196b4e','5ab1171a-6228-4432-a40c-4160e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1171a-8884-49f0-ae46-4c34e5196b4e','5ab1171a-6228-4432-a40c-4160e5196b4e','id','','90'),
	('5ab1171a-88c4-4116-94d5-4e8be5196b4e','5ab1171a-052c-4d10-98bb-4698e5196b4e','model','','ComplianceAnalysisFinding'),
	('5ab1171a-9378-4b7c-8b5e-4a02e5196b4e','5ab1171a-6228-4432-a40c-4160e5196b4e','model','','ComplianceAnalysisFinding'),
	('5ab1171a-95a4-4a01-98cd-4c17e5196b4e','5ab1171a-398c-4251-84c9-4208e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1171a-9904-4a13-ab63-4142e5196b4e','5ab1171a-3ca4-48dd-8682-4b8ce5196b4e','value',NULL,'0'),
	('5ab1171a-9978-43ce-aed6-426de5196b4e','5ab1171a-da3c-4c7d-89d7-4a2ee5196b4e','status','0','1'),
	('5ab1171a-acfc-4100-809e-4438e5196b4e','5ab1171a-052c-4d10-98bb-4698e5196b4e','value','',NULL),
	('5ab1171a-b490-4464-9bad-4223e5196b4e','5ab1171a-052c-4d10-98bb-4698e5196b4e','category','','0'),
	('5ab1171a-b934-4440-b137-48f6e5196b4e','5ab1171a-9a78-4bd6-b526-42b2e5196b4e','value','',NULL),
	('5ab1171a-bcb8-4f52-a1fa-4e86e5196b4e','5ab1171a-9a78-4bd6-b526-42b2e5196b4e','title','','Open'),
	('5ab1171a-bdd8-43ce-bac3-420ce5196b4e','5ab1171a-052c-4d10-98bb-4698e5196b4e','class_name','','Visualisation.VisualizedKpi'),
	('5ab1171a-c130-4957-9284-41b7e5196b4e','5ab1171a-052c-4d10-98bb-4698e5196b4e','title','','Closed'),
	('5ab1171a-c9a0-4dfc-bf9c-432de5196b4e','5ab1171a-398c-4251-84c9-4208e5196b4e','status','','0'),
	('5ab1171a-ce18-4260-883c-49e8e5196b4e','5ab1171a-6228-4432-a40c-4160e5196b4e','title','','Closed'),
	('5ab1171a-d2cc-463e-9fed-4fe3e5196b4e','5ab1171a-9a78-4bd6-b526-42b2e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1171a-d7f0-4fe7-902a-4e02e5196b4e','5ab1171a-398c-4251-84c9-4208e5196b4e','dashboard_kpi_attribute_count','','0'),
	('5ab1171a-d878-4e29-8a79-4146e5196b4e','5ab1171a-9a78-4bd6-b526-42b2e5196b4e','category','','0'),
	('5ab1171a-e5c4-4047-9215-41dfe5196b4e','5ab1171a-398c-4251-84c9-4208e5196b4e','model','','ComplianceAnalysisFinding'),
	('5ab1171a-e640-4ef7-b142-4d58e5196b4e','5ab1171a-052c-4d10-98bb-4698e5196b4e','id','','89'),
	('5ab1171a-f594-4276-a469-474fe5196b4e','5ab1171a-6228-4432-a40c-4160e5196b4e','value','',NULL),
	('5ab1171a-f7d4-46ec-acfa-41f6e5196b4e','5ab1171a-ccf4-4c65-9b61-49d4e5196b4e','status','0','1'),
	('5ab1171a-fa58-4f4a-aae2-41e7e5196b4e','5ab1171a-398c-4251-84c9-4208e5196b4e','id','','87'),
	('5ab1171a-ff78-4923-85bf-42b0e5196b4e','5ab1171a-6228-4432-a40c-4160e5196b4e','category','','0'),
	('5ab1171a-fff8-4528-b79d-423de5196b4e','5ab1171a-052c-4d10-98bb-4698e5196b4e','json','','{"User":"closed","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}');
ALTER TABLE `audit_deltas` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_programs` WRITE;
ALTER TABLE `awareness_programs` DISABLE KEYS;
ALTER TABLE `awareness_programs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_overtime_graphs` WRITE;
ALTER TABLE `awareness_overtime_graphs` DISABLE KEYS;
ALTER TABLE `awareness_overtime_graphs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_program_active_users` WRITE;
ALTER TABLE `awareness_program_active_users` DISABLE KEYS;
ALTER TABLE `awareness_program_active_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_program_compliant_users` WRITE;
ALTER TABLE `awareness_program_compliant_users` DISABLE KEYS;
ALTER TABLE `awareness_program_compliant_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_program_demos` WRITE;
ALTER TABLE `awareness_program_demos` DISABLE KEYS;
ALTER TABLE `awareness_program_demos` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_program_ignored_users` WRITE;
ALTER TABLE `awareness_program_ignored_users` DISABLE KEYS;
ALTER TABLE `awareness_program_ignored_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_program_ldap_groups` WRITE;
ALTER TABLE `awareness_program_ldap_groups` DISABLE KEYS;
ALTER TABLE `awareness_program_ldap_groups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_program_recurrences` WRITE;
ALTER TABLE `awareness_program_recurrences` DISABLE KEYS;
ALTER TABLE `awareness_program_recurrences` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_program_missed_recurrences` WRITE;
ALTER TABLE `awareness_program_missed_recurrences` DISABLE KEYS;
ALTER TABLE `awareness_program_missed_recurrences` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_program_not_compliant_users` WRITE;
ALTER TABLE `awareness_program_not_compliant_users` DISABLE KEYS;
ALTER TABLE `awareness_program_not_compliant_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_programs_security_policies` WRITE;
ALTER TABLE `awareness_programs_security_policies` DISABLE KEYS;
ALTER TABLE `awareness_programs_security_policies` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_reminders` WRITE;
ALTER TABLE `awareness_reminders` DISABLE KEYS;
ALTER TABLE `awareness_reminders` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_users` WRITE;
ALTER TABLE `awareness_users` DISABLE KEYS;
ALTER TABLE `awareness_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `awareness_trainings` WRITE;
ALTER TABLE `awareness_trainings` DISABLE KEYS;
ALTER TABLE `awareness_trainings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `backups` WRITE;
ALTER TABLE `backups` DISABLE KEYS;
ALTER TABLE `backups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `bulk_actions` WRITE;
ALTER TABLE `bulk_actions` DISABLE KEYS;
ALTER TABLE `bulk_actions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `bulk_action_objects` WRITE;
ALTER TABLE `bulk_action_objects` DISABLE KEYS;
ALTER TABLE `bulk_action_objects` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities` WRITE;
ALTER TABLE `business_continuities` DISABLE KEYS;
ALTER TABLE `business_continuities` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities_business_continuity_plans` WRITE;
ALTER TABLE `business_continuities_business_continuity_plans` DISABLE KEYS;
ALTER TABLE `business_continuities_business_continuity_plans` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities_business_units` WRITE;
ALTER TABLE `business_continuities_business_units` DISABLE KEYS;
ALTER TABLE `business_continuities_business_units` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities_compliance_managements` WRITE;
ALTER TABLE `business_continuities_compliance_managements` DISABLE KEYS;
ALTER TABLE `business_continuities_compliance_managements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities_goals` WRITE;
ALTER TABLE `business_continuities_goals` DISABLE KEYS;
ALTER TABLE `business_continuities_goals` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities_processes` WRITE;
ALTER TABLE `business_continuities_processes` DISABLE KEYS;
ALTER TABLE `business_continuities_processes` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities_projects` WRITE;
ALTER TABLE `business_continuities_projects` DISABLE KEYS;
ALTER TABLE `business_continuities_projects` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities_risk_classifications` WRITE;
ALTER TABLE `business_continuities_risk_classifications` DISABLE KEYS;
ALTER TABLE `business_continuities_risk_classifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities_risk_exceptions` WRITE;
ALTER TABLE `business_continuities_risk_exceptions` DISABLE KEYS;
ALTER TABLE `business_continuities_risk_exceptions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities_security_services` WRITE;
ALTER TABLE `business_continuities_security_services` DISABLE KEYS;
ALTER TABLE `business_continuities_security_services` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities_threats` WRITE;
ALTER TABLE `business_continuities_threats` DISABLE KEYS;
ALTER TABLE `business_continuities_threats` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuities_vulnerabilities` WRITE;
ALTER TABLE `business_continuities_vulnerabilities` DISABLE KEYS;
ALTER TABLE `business_continuities_vulnerabilities` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuity_plan_audit_dates` WRITE;
ALTER TABLE `business_continuity_plan_audit_dates` DISABLE KEYS;
ALTER TABLE `business_continuity_plan_audit_dates` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuity_plan_audit_improvements` WRITE;
ALTER TABLE `business_continuity_plan_audit_improvements` DISABLE KEYS;
ALTER TABLE `business_continuity_plan_audit_improvements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuity_plan_audit_improvements_projects` WRITE;
ALTER TABLE `business_continuity_plan_audit_improvements_projects` DISABLE KEYS;
ALTER TABLE `business_continuity_plan_audit_improvements_projects` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuity_plan_audit_improvements_security_incidents` WRITE;
ALTER TABLE `business_continuity_plan_audit_improvements_security_incidents` DISABLE KEYS;
ALTER TABLE `business_continuity_plan_audit_improvements_security_incidents` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuity_plans` WRITE;
ALTER TABLE `business_continuity_plans` DISABLE KEYS;
ALTER TABLE `business_continuity_plans` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuity_plan_audits` WRITE;
ALTER TABLE `business_continuity_plan_audits` DISABLE KEYS;
ALTER TABLE `business_continuity_plan_audits` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuity_tasks` WRITE;
ALTER TABLE `business_continuity_tasks` DISABLE KEYS;
ALTER TABLE `business_continuity_tasks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_continuity_task_reminders` WRITE;
ALTER TABLE `business_continuity_task_reminders` DISABLE KEYS;
ALTER TABLE `business_continuity_task_reminders` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_units` WRITE;
ALTER TABLE `business_units` DISABLE KEYS;
INSERT INTO `business_units` (`id`, `name`, `description`, `workflow_status`, `workflow_owner_id`, `_hidden`, `created`, `modified`, `deleted`, `deleted_date`) VALUES 
	(1,'Everyone','',4,NULL,1,'2015-12-19 00:00:00','2015-12-19 00:00:00',0,NULL);
ALTER TABLE `business_units` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_units_data_assets` WRITE;
ALTER TABLE `business_units_data_assets` DISABLE KEYS;
ALTER TABLE `business_units_data_assets` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `business_units_legals` WRITE;
ALTER TABLE `business_units_legals` DISABLE KEYS;
ALTER TABLE `business_units_legals` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `cake_sessions` WRITE;
ALTER TABLE `cake_sessions` DISABLE KEYS;
INSERT INTO `cake_sessions` (`id`, `data`, `expires`) VALUES 
	('0c4n722nj4p3qmfshcm3kmc3kp','Config|a:3:{s:9:"userAgent";s:0:"";s:4:"time";i:1521560635;s:9:"countdown";i:10;}Message|a:1:{s:5:"flash";a:3:{s:7:"message";s:38:"<success>Aco Update Complete</success>";s:7:"element";s:7:"default";s:6:"params";a:0:{}}}',1521560743),
	('fr4ea5lco8ddb9adsrdvljkdj0','Config|a:3:{s:9:"userAgent";s:0:"";s:4:"time";i:1521560591;s:9:"countdown";i:10;}Auth|a:1:{s:4:"User";a:16:{s:2:"id";s:1:"1";s:4:"name";s:5:"Admin";s:7:"surname";s:5:"Admin";s:5:"email";s:16:"admin@eramba.org";s:5:"login";s:5:"admin";s:8:"password";s:60:"$2a$10$WhVO3Jj4nFhCj6bToUOztun/oceKY6rT2db2bu430dW5/lU0w9KJ.";s:8:"language";s:3:"eng";s:6:"status";s:1:"1";s:7:"blocked";s:1:"0";s:13:"local_account";s:1:"1";s:9:"api_allow";s:1:"0";s:7:"created";s:19:"2013-10-14 16:19:04";s:8:"modified";s:19:"2015-09-11 18:19:52";s:9:"full_name";s:11:"Admin Admin";s:19:"full_name_with_type";s:18:"Admin Admin (User)";s:6:"Groups";a:1:{i:0;s:2:"10";}}}',1521560594),
	('h5rkhld4q61amonha4l7t1pjh7','Config|a:3:{s:9:"userAgent";s:0:"";s:4:"time";i:1521560595;s:9:"countdown";i:10;}Auth|a:1:{s:4:"User";a:16:{s:2:"id";s:1:"1";s:4:"name";s:5:"Admin";s:7:"surname";s:5:"Admin";s:5:"email";s:16:"admin@eramba.org";s:5:"login";s:5:"admin";s:8:"password";s:60:"$2a$10$WhVO3Jj4nFhCj6bToUOztun/oceKY6rT2db2bu430dW5/lU0w9KJ.";s:8:"language";s:3:"eng";s:6:"status";s:1:"1";s:7:"blocked";s:1:"0";s:13:"local_account";s:1:"1";s:9:"api_allow";s:1:"0";s:7:"created";s:19:"2013-10-14 16:19:04";s:8:"modified";s:19:"2015-09-11 18:19:52";s:9:"full_name";s:11:"Admin Admin";s:19:"full_name_with_type";s:18:"Admin Admin (User)";s:6:"Groups";a:1:{i:0;s:2:"10";}}}',1521560598),
	('k4tv26pmc9obcu6fvm565casit','Config|a:3:{s:9:"userAgent";s:0:"";s:4:"time";i:1521560612;s:9:"countdown";i:10;}',1521560634),
	('u7ukt3bk22alneukbtd10m9cq0','Config|a:3:{s:9:"userAgent";s:0:"";s:4:"time";i:1521560599;s:9:"countdown";i:10;}Auth|a:1:{s:4:"User";a:16:{s:2:"id";s:1:"1";s:4:"name";s:5:"Admin";s:7:"surname";s:5:"Admin";s:5:"email";s:16:"admin@eramba.org";s:5:"login";s:5:"admin";s:8:"password";s:60:"$2a$10$WhVO3Jj4nFhCj6bToUOztun/oceKY6rT2db2bu430dW5/lU0w9KJ.";s:8:"language";s:3:"eng";s:6:"status";s:1:"1";s:7:"blocked";s:1:"0";s:13:"local_account";s:1:"1";s:9:"api_allow";s:1:"0";s:7:"created";s:19:"2013-10-14 16:19:04";s:8:"modified";s:19:"2015-09-11 18:19:52";s:9:"full_name";s:11:"Admin Admin";s:19:"full_name_with_type";s:18:"Admin Admin (User)";s:6:"Groups";a:1:{i:0;s:2:"10";}}}',1521560602);
ALTER TABLE `cake_sessions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `comments` WRITE;
ALTER TABLE `comments` DISABLE KEYS;
ALTER TABLE `comments` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_analysis_findings` WRITE;
ALTER TABLE `compliance_analysis_findings` DISABLE KEYS;
ALTER TABLE `compliance_analysis_findings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_analysis_findings_compliance_managements` WRITE;
ALTER TABLE `compliance_analysis_findings_compliance_managements` DISABLE KEYS;
ALTER TABLE `compliance_analysis_findings_compliance_managements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_analysis_findings_compliance_package_items` WRITE;
ALTER TABLE `compliance_analysis_findings_compliance_package_items` DISABLE KEYS;
ALTER TABLE `compliance_analysis_findings_compliance_package_items` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_analysis_findings_third_parties` WRITE;
ALTER TABLE `compliance_analysis_findings_third_parties` DISABLE KEYS;
ALTER TABLE `compliance_analysis_findings_third_parties` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_audit_feedback_profiles` WRITE;
ALTER TABLE `compliance_audit_feedback_profiles` DISABLE KEYS;
ALTER TABLE `compliance_audit_feedback_profiles` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_audit_feedbacks` WRITE;
ALTER TABLE `compliance_audit_feedbacks` DISABLE KEYS;
ALTER TABLE `compliance_audit_feedbacks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_audit_auditee_feedbacks` WRITE;
ALTER TABLE `compliance_audit_auditee_feedbacks` DISABLE KEYS;
ALTER TABLE `compliance_audit_auditee_feedbacks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_audits` WRITE;
ALTER TABLE `compliance_audits` DISABLE KEYS;
ALTER TABLE `compliance_audits` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_audit_feedbacks_compliance_audits` WRITE;
ALTER TABLE `compliance_audit_feedbacks_compliance_audits` DISABLE KEYS;
ALTER TABLE `compliance_audit_feedbacks_compliance_audits` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_audit_overtime_graphs` WRITE;
ALTER TABLE `compliance_audit_overtime_graphs` DISABLE KEYS;
ALTER TABLE `compliance_audit_overtime_graphs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_audit_provided_feedbacks` WRITE;
ALTER TABLE `compliance_audit_provided_feedbacks` DISABLE KEYS;
ALTER TABLE `compliance_audit_provided_feedbacks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_audit_settings` WRITE;
ALTER TABLE `compliance_audit_settings` DISABLE KEYS;
ALTER TABLE `compliance_audit_settings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_audit_setting_notifications` WRITE;
ALTER TABLE `compliance_audit_setting_notifications` DISABLE KEYS;
ALTER TABLE `compliance_audit_setting_notifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_audit_settings_auditees` WRITE;
ALTER TABLE `compliance_audit_settings_auditees` DISABLE KEYS;
ALTER TABLE `compliance_audit_settings_auditees` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_exceptions` WRITE;
ALTER TABLE `compliance_exceptions` DISABLE KEYS;
ALTER TABLE `compliance_exceptions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_exceptions_compliance_findings` WRITE;
ALTER TABLE `compliance_exceptions_compliance_findings` DISABLE KEYS;
ALTER TABLE `compliance_exceptions_compliance_findings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_managements` WRITE;
ALTER TABLE `compliance_managements` DISABLE KEYS;
ALTER TABLE `compliance_managements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_exceptions_compliance_managements` WRITE;
ALTER TABLE `compliance_exceptions_compliance_managements` DISABLE KEYS;
ALTER TABLE `compliance_exceptions_compliance_managements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_finding_classifications` WRITE;
ALTER TABLE `compliance_finding_classifications` DISABLE KEYS;
ALTER TABLE `compliance_finding_classifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_finding_statuses` WRITE;
ALTER TABLE `compliance_finding_statuses` DISABLE KEYS;
INSERT INTO `compliance_finding_statuses` (`id`, `name`) VALUES 
	(1,'Open Item'),
	(2,'Closed Item');
ALTER TABLE `compliance_finding_statuses` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_findings` WRITE;
ALTER TABLE `compliance_findings` DISABLE KEYS;
ALTER TABLE `compliance_findings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_findings_third_party_risks` WRITE;
ALTER TABLE `compliance_findings_third_party_risks` DISABLE KEYS;
ALTER TABLE `compliance_findings_third_party_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_managements_projects` WRITE;
ALTER TABLE `compliance_managements_projects` DISABLE KEYS;
ALTER TABLE `compliance_managements_projects` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_managements_risks` WRITE;
ALTER TABLE `compliance_managements_risks` DISABLE KEYS;
ALTER TABLE `compliance_managements_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_managements_security_policies` WRITE;
ALTER TABLE `compliance_managements_security_policies` DISABLE KEYS;
ALTER TABLE `compliance_managements_security_policies` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_managements_security_services` WRITE;
ALTER TABLE `compliance_managements_security_services` DISABLE KEYS;
ALTER TABLE `compliance_managements_security_services` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_managements_third_party_risks` WRITE;
ALTER TABLE `compliance_managements_third_party_risks` DISABLE KEYS;
ALTER TABLE `compliance_managements_third_party_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_packages` WRITE;
ALTER TABLE `compliance_packages` DISABLE KEYS;
ALTER TABLE `compliance_packages` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_package_items` WRITE;
ALTER TABLE `compliance_package_items` DISABLE KEYS;
ALTER TABLE `compliance_package_items` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_statuses` WRITE;
ALTER TABLE `compliance_statuses` DISABLE KEYS;
INSERT INTO `compliance_statuses` (`id`, `name`) VALUES 
	(1,'On-Going'),
	(2,'Compliant'),
	(3,'Non-Compliant'),
	(4,'Not-Applicable');
ALTER TABLE `compliance_statuses` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `compliance_treatment_strategies` WRITE;
ALTER TABLE `compliance_treatment_strategies` DISABLE KEYS;
INSERT INTO `compliance_treatment_strategies` (`id`, `name`) VALUES 
	(1,'Compliant'),
	(2,'Not Applicable'),
	(3,'Not Compliant');
ALTER TABLE `compliance_treatment_strategies` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `countries` WRITE;
ALTER TABLE `countries` DISABLE KEYS;
ALTER TABLE `countries` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `cron` WRITE;
ALTER TABLE `cron` DISABLE KEYS;
ALTER TABLE `cron` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `custom_field_options` WRITE;
ALTER TABLE `custom_field_options` DISABLE KEYS;
ALTER TABLE `custom_field_options` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `custom_field_settings` WRITE;
ALTER TABLE `custom_field_settings` DISABLE KEYS;
INSERT INTO `custom_field_settings` (`id`, `model`, `status`) VALUES 
	(1,'SecurityService',0),
	(2,'SecurityServiceAudit',0),
	(3,'SecurityServiceMaintenance',0),
	(4,'BusinessUnit',0),
	(5,'Process',0),
	(6,'ThirdParty',0),
	(7,'Asset',0),
	(8,'Risk',0),
	(9,'ThirdPartyRisk',0),
	(10,'BusinessContinuity',0),
	(11,'ComplianceAnalysisFinding',0),
	(13,'RiskException',0),
	(14,'PolicyException',0),
	(15,'ComplianceException',0),
	(16,'SecurityIncident',0),
	(17,'DataAsset',0),
	(18,'ProgramIssue',0),
	(19,'Goal',0),
	(20,'TeamRole',0),
	(21,'Legal',0),
	(22,'SecurityPolicy',0),
	(23,'ComplianceManagement',0),
	(24,'Project',0);
ALTER TABLE `custom_field_settings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `custom_forms` WRITE;
ALTER TABLE `custom_forms` DISABLE KEYS;
ALTER TABLE `custom_forms` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `custom_fields` WRITE;
ALTER TABLE `custom_fields` DISABLE KEYS;
ALTER TABLE `custom_fields` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `custom_field_values` WRITE;
ALTER TABLE `custom_field_values` DISABLE KEYS;
ALTER TABLE `custom_field_values` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `custom_roles_groups` WRITE;
ALTER TABLE `custom_roles_groups` DISABLE KEYS;
INSERT INTO `custom_roles_groups` (`id`, `group_id`, `created`) VALUES 
	(1,10,'2018-03-20 15:13:07'),
	(2,11,'2018-03-20 15:13:07'),
	(3,12,'2018-03-20 15:13:07'),
	(4,13,'2018-03-20 15:13:07');
ALTER TABLE `custom_roles_groups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `custom_roles_roles` WRITE;
ALTER TABLE `custom_roles_roles` DISABLE KEYS;
ALTER TABLE `custom_roles_roles` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `custom_roles_role_groups` WRITE;
ALTER TABLE `custom_roles_role_groups` DISABLE KEYS;
ALTER TABLE `custom_roles_role_groups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `custom_roles_role_users` WRITE;
ALTER TABLE `custom_roles_role_users` DISABLE KEYS;
ALTER TABLE `custom_roles_role_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `custom_roles_users` WRITE;
ALTER TABLE `custom_roles_users` DISABLE KEYS;
INSERT INTO `custom_roles_users` (`id`, `user_id`, `created`) VALUES 
	(1,1,'2018-03-20 15:13:07');
ALTER TABLE `custom_roles_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `custom_validator_fields` WRITE;
ALTER TABLE `custom_validator_fields` DISABLE KEYS;
ALTER TABLE `custom_validator_fields` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `dashboard_kpis` WRITE;
ALTER TABLE `dashboard_kpis` DISABLE KEYS;
INSERT INTO `dashboard_kpis` (`id`, `class_name`, `title`, `model`, `type`, `category`, `owner_id`, `dashboard_kpi_attribute_count`, `json`, `created`, `modified`, `value`, `status`) VALUES 
	(1,'Visualisation.VisualizedKpi','Total','Risk',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:32','2018-03-20 15:13:46',0,1),
	(2,'Visualisation.VisualizedKpi','Total','Risk',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:33','2018-03-20 15:13:47',0,1),
	(3,'Visualisation.VisualizedKpi','Expired','Risk',0,0,NULL,3,'{"User":"next_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:33','2018-03-20 15:13:47',0,1),
	(4,'Visualisation.VisualizedKpi','Expired','Risk',0,0,NULL,3,'{"User":"next_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:33','2018-03-20 15:13:48',0,1),
	(5,'Visualisation.VisualizedKpi','Coming Reviews (14 Days)','Risk',0,0,NULL,3,'{"User":"missed_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:33','2018-03-20 15:13:48',0,1),
	(6,'Visualisation.VisualizedKpi','Coming Reviews (14 Days)','Risk',0,0,NULL,3,'{"User":"missed_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:34','2018-03-20 15:13:48',0,1),
	(7,'Dashboard.DashboardKpiObject','Total number of Asset Risk Management','Risk',1,0,NULL,1,'{"Admin":"total"}','2018-03-20 15:13:34','2018-03-20 15:13:48',0,1),
	(8,'Dashboard.DashboardKpiObject','Expired','Risk',1,0,NULL,1,'{"Admin":"next_reviews"}','2018-03-20 15:13:34','2018-03-20 15:13:48',0,1),
	(9,'Dashboard.DashboardKpiObject','Coming Reviews (14 Days)','Risk',1,0,NULL,1,'{"Admin":"missed_reviews"}','2018-03-20 15:13:34','2018-03-20 15:13:48',0,1),
	(10,'CustomQueryKpi','Current Total Risk Score','Risk',1,0,NULL,1,'{"CustomQuery":"total_risk_score"}','2018-03-20 15:13:34','2018-03-20 15:13:48',0,1),
	(11,'CustomQueryKpi','Current Total Residual Score','Risk',1,0,NULL,1,'{"CustomQuery":"total_residual_score"}','2018-03-20 15:13:34','2018-03-20 15:13:48',0,1),
	(12,'Dashboard.DashboardKpiObject','Asset Risk Management created during the past two weeks','Risk',1,1,NULL,1,'{"DynamicFilter":"recently_created"}','2018-03-20 15:13:34','2018-03-20 15:13:49',0,1),
	(13,'Dashboard.DashboardKpiObject','Asset Risk Management deleted during the past two weeks','Risk',1,1,NULL,1,'{"DynamicFilter":"recently_deleted"}','2018-03-20 15:13:35','2018-03-20 15:13:49',0,1),
	(14,'Visualisation.VisualizedKpi','Total','BusinessContinuity',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:35','2018-03-20 15:13:49',0,1),
	(15,'Visualisation.VisualizedKpi','Total','BusinessContinuity',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:35','2018-03-20 15:13:49',0,1),
	(16,'Visualisation.VisualizedKpi','Expired','BusinessContinuity',0,0,NULL,3,'{"User":"next_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:35','2018-03-20 15:13:49',0,1),
	(17,'Visualisation.VisualizedKpi','Expired','BusinessContinuity',0,0,NULL,3,'{"User":"next_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:35','2018-03-20 15:13:49',0,1),
	(18,'Visualisation.VisualizedKpi','Coming Reviews (14 Days)','BusinessContinuity',0,0,NULL,3,'{"User":"missed_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:35','2018-03-20 15:13:49',0,1),
	(19,'Visualisation.VisualizedKpi','Coming Reviews (14 Days)','BusinessContinuity',0,0,NULL,3,'{"User":"missed_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:35','2018-03-20 15:13:49',0,1),
	(20,'Dashboard.DashboardKpiObject','Total number of Business Impact Analysis','BusinessContinuity',1,0,NULL,1,'{"Admin":"total"}','2018-03-20 15:13:36','2018-03-20 15:13:49',0,1),
	(21,'Dashboard.DashboardKpiObject','Expired','BusinessContinuity',1,0,NULL,1,'{"Admin":"next_reviews"}','2018-03-20 15:13:36','2018-03-20 15:13:49',0,1),
	(22,'Dashboard.DashboardKpiObject','Coming Reviews (14 Days)','BusinessContinuity',1,0,NULL,1,'{"Admin":"missed_reviews"}','2018-03-20 15:13:36','2018-03-20 15:13:49',0,1),
	(23,'CustomQueryKpi','Current Total Risk Score','BusinessContinuity',1,0,NULL,1,'{"CustomQuery":"total_risk_score"}','2018-03-20 15:13:36','2018-03-20 15:13:49',0,1),
	(24,'CustomQueryKpi','Current Total Residual Score','BusinessContinuity',1,0,NULL,1,'{"CustomQuery":"total_residual_score"}','2018-03-20 15:13:36','2018-03-20 15:13:49',0,1),
	(25,'Dashboard.DashboardKpiObject','Business Impact Analysis created during the past two weeks','BusinessContinuity',1,1,NULL,1,'{"DynamicFilter":"recently_created"}','2018-03-20 15:13:36','2018-03-20 15:13:49',0,1),
	(26,'Dashboard.DashboardKpiObject','Business Impact Analysis deleted during the past two weeks','BusinessContinuity',1,1,NULL,1,'{"DynamicFilter":"recently_deleted"}','2018-03-20 15:13:36','2018-03-20 15:13:50',0,1),
	(27,'Visualisation.VisualizedKpi','Total','ThirdPartyRisk',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:36','2018-03-20 15:13:50',0,1),
	(28,'Visualisation.VisualizedKpi','Total','ThirdPartyRisk',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:36','2018-03-20 15:13:50',0,1),
	(29,'Visualisation.VisualizedKpi','Expired','ThirdPartyRisk',0,0,NULL,3,'{"User":"next_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:36','2018-03-20 15:13:50',0,1),
	(30,'Visualisation.VisualizedKpi','Expired','ThirdPartyRisk',0,0,NULL,3,'{"User":"next_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:36','2018-03-20 15:13:50',0,1),
	(31,'Visualisation.VisualizedKpi','Coming Reviews (14 Days)','ThirdPartyRisk',0,0,NULL,3,'{"User":"missed_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:37','2018-03-20 15:13:50',0,1),
	(32,'Visualisation.VisualizedKpi','Coming Reviews (14 Days)','ThirdPartyRisk',0,0,NULL,3,'{"User":"missed_reviews","CustomRoles.CustomRole":"Stakeholder","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:37','2018-03-20 15:13:50',0,1),
	(33,'Dashboard.DashboardKpiObject','Total number of Third Party Risk Management','ThirdPartyRisk',1,0,NULL,1,'{"Admin":"total"}','2018-03-20 15:13:37','2018-03-20 15:13:50',0,1),
	(34,'Dashboard.DashboardKpiObject','Expired','ThirdPartyRisk',1,0,NULL,1,'{"Admin":"next_reviews"}','2018-03-20 15:13:37','2018-03-20 15:13:50',0,1),
	(35,'Dashboard.DashboardKpiObject','Coming Reviews (14 Days)','ThirdPartyRisk',1,0,NULL,1,'{"Admin":"missed_reviews"}','2018-03-20 15:13:37','2018-03-20 15:13:50',0,1),
	(36,'CustomQueryKpi','Current Total Risk Score','ThirdPartyRisk',1,0,NULL,1,'{"CustomQuery":"total_risk_score"}','2018-03-20 15:13:37','2018-03-20 15:13:50',0,1),
	(37,'CustomQueryKpi','Current Total Residual Score','ThirdPartyRisk',1,0,NULL,1,'{"CustomQuery":"total_residual_score"}','2018-03-20 15:13:37','2018-03-20 15:13:50',0,1),
	(38,'Dashboard.DashboardKpiObject','Third Party Risk Management created during the past two weeks','ThirdPartyRisk',1,1,NULL,1,'{"DynamicFilter":"recently_created"}','2018-03-20 15:13:37','2018-03-20 15:13:51',0,1),
	(39,'Dashboard.DashboardKpiObject','Third Party Risk Management deleted during the past two weeks','ThirdPartyRisk',1,1,NULL,1,'{"DynamicFilter":"recently_deleted"}','2018-03-20 15:13:37','2018-03-20 15:13:51',0,1),
	(40,'Visualisation.VisualizedKpi','Total','Project',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:37','2018-03-20 15:13:51',0,1),
	(41,'Visualisation.VisualizedKpi','Expired','Project',0,0,NULL,3,'{"User":"expired","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:38','2018-03-20 15:13:51',0,1),
	(42,'Visualisation.VisualizedKpi','Coming Deadline (14 Days)','Project',0,0,NULL,3,'{"User":"comming_dates","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:38','2018-03-20 15:13:51',0,1),
	(43,'Visualisation.VisualizedKpi','Project with Expired Tasks','Project',0,0,NULL,3,'{"User":"expired_tasks","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:38','2018-03-20 15:13:51',0,1),
	(44,'Dashboard.DashboardKpiObject','Total number of Projects','Project',1,0,NULL,1,'{"Admin":"total"}','2018-03-20 15:13:38','2018-03-20 15:13:51',0,1),
	(45,'Dashboard.DashboardKpiObject','Expired','Project',1,0,NULL,1,'{"Admin":"expired"}','2018-03-20 15:13:38','2018-03-20 15:13:51',0,1),
	(46,'Dashboard.DashboardKpiObject','Coming Deadline (14 Days)','Project',1,0,NULL,1,'{"Admin":"comming_dates"}','2018-03-20 15:13:38','2018-03-20 15:13:51',0,1),
	(47,'Dashboard.DashboardKpiObject','Project with Expired Tasks','Project',1,0,NULL,1,'{"Admin":"expired_tasks"}','2018-03-20 15:13:38','2018-03-20 15:13:51',0,1),
	(48,'Dashboard.DashboardKpiObject','Projects created during the past two weeks','Project',1,1,NULL,1,'{"DynamicFilter":"recently_created"}','2018-03-20 15:13:38','2018-03-20 15:13:51',0,1),
	(49,'Dashboard.DashboardKpiObject','Projects deleted during the past two weeks','Project',1,1,NULL,1,'{"DynamicFilter":"recently_deleted"}','2018-03-20 15:13:38','2018-03-20 15:13:51',0,1),
	(50,'Visualisation.VisualizedKpi','Total','SecurityService',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"ServiceOwner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:38','2018-03-20 15:13:51',0,1),
	(51,'Visualisation.VisualizedKpi','Total','SecurityService',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:39','2018-03-20 15:13:52',0,1),
	(52,'Visualisation.VisualizedKpi','Missing Audits','SecurityService',0,0,NULL,3,'{"User":"missing_audits","CustomRoles.CustomRole":"ServiceOwner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:39','2018-03-20 15:13:52',0,1),
	(53,'Visualisation.VisualizedKpi','Missing Audits','SecurityService',0,0,NULL,3,'{"User":"missing_audits","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:39','2018-03-20 15:13:52',0,1),
	(54,'Visualisation.VisualizedKpi','Failed Audits','SecurityService',0,0,NULL,3,'{"User":"failed_audits","CustomRoles.CustomRole":"ServiceOwner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:40','2018-03-20 15:13:52',0,1),
	(55,'Visualisation.VisualizedKpi','Failed Audits','SecurityService',0,0,NULL,3,'{"User":"failed_audits","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:40','2018-03-20 15:13:52',0,1),
	(56,'Visualisation.VisualizedKpi','Issues','SecurityService',0,0,NULL,3,'{"User":"issue","CustomRoles.CustomRole":"ServiceOwner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:40','2018-03-20 15:13:52',0,1),
	(57,'Visualisation.VisualizedKpi','Issues','SecurityService',0,0,NULL,3,'{"User":"issue","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:40','2018-03-20 15:13:52',0,1),
	(58,'Dashboard.DashboardKpiObject','Total number of Security Services','SecurityService',1,0,NULL,1,'{"Admin":"total"}','2018-03-20 15:13:40','2018-03-20 15:13:52',0,1),
	(59,'Dashboard.DashboardKpiObject','Missing Audits','SecurityService',1,0,NULL,1,'{"Admin":"missing_audits"}','2018-03-20 15:13:41','2018-03-20 15:13:52',0,1),
	(60,'Dashboard.DashboardKpiObject','Failed Audits','SecurityService',1,0,NULL,1,'{"Admin":"failed_audits"}','2018-03-20 15:13:41','2018-03-20 15:13:52',0,1),
	(61,'Dashboard.DashboardKpiObject','Issues','SecurityService',1,0,NULL,1,'{"Admin":"issue"}','2018-03-20 15:13:41','2018-03-20 15:13:52',0,1),
	(62,'Dashboard.DashboardKpiObject','Security Services created during the past two weeks','SecurityService',1,1,NULL,1,'{"DynamicFilter":"recently_created"}','2018-03-20 15:13:41','2018-03-20 15:13:52',0,1),
	(63,'Dashboard.DashboardKpiObject','Security Services deleted during the past two weeks','SecurityService',1,1,NULL,1,'{"DynamicFilter":"recently_deleted"}','2018-03-20 15:13:41','2018-03-20 15:13:52',0,1),
	(64,'Visualisation.VisualizedKpi','Total','SecurityPolicy',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:41','2018-03-20 15:13:52',0,1),
	(65,'Visualisation.VisualizedKpi','Total','SecurityPolicy',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:41','2018-03-20 15:13:52',0,1),
	(66,'Visualisation.VisualizedKpi','Expired','SecurityPolicy',0,0,NULL,3,'{"User":"next_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:42','2018-03-20 15:13:52',0,1),
	(67,'Visualisation.VisualizedKpi','Expired','SecurityPolicy',0,0,NULL,3,'{"User":"next_reviews","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:42','2018-03-20 15:13:52',0,1),
	(68,'Visualisation.VisualizedKpi','Coming Reviews (14 Days)','SecurityPolicy',0,0,NULL,3,'{"User":"missed_reviews","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:42','2018-03-20 15:13:53',0,1),
	(69,'Visualisation.VisualizedKpi','Coming Reviews (14 Days)','SecurityPolicy',0,0,NULL,3,'{"User":"missed_reviews","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:42','2018-03-20 15:13:53',0,1),
	(70,'Dashboard.DashboardKpiObject','Total number of Security Policies','SecurityPolicy',1,0,NULL,1,'{"Admin":"total"}','2018-03-20 15:13:42','2018-03-20 15:13:53',0,1),
	(71,'Dashboard.DashboardKpiObject','Expired','SecurityPolicy',1,0,NULL,1,'{"Admin":"next_reviews"}','2018-03-20 15:13:42','2018-03-20 15:13:53',0,1),
	(72,'Dashboard.DashboardKpiObject','Coming Reviews (14 Days)','SecurityPolicy',1,0,NULL,1,'{"Admin":"missed_reviews"}','2018-03-20 15:13:42','2018-03-20 15:13:53',0,1),
	(73,'Dashboard.DashboardKpiObject','Security Policies created during the past two weeks','SecurityPolicy',1,1,NULL,1,'{"DynamicFilter":"recently_created"}','2018-03-20 15:13:43','2018-03-20 15:13:53',0,1),
	(74,'Dashboard.DashboardKpiObject','Security Policies deleted during the past two weeks','SecurityPolicy',1,1,NULL,1,'{"DynamicFilter":"recently_deleted"}','2018-03-20 15:13:43','2018-03-20 15:13:53',0,1),
	(75,'Visualisation.VisualizedKpi','Total','SecurityIncident',0,0,NULL,3,'{"User":"total","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:43','2018-03-20 15:13:53',0,1),
	(76,'Visualisation.VisualizedKpi','Open','SecurityIncident',0,0,NULL,3,'{"User":"open","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:43','2018-03-20 15:13:53',0,1),
	(77,'Visualisation.VisualizedKpi','Closed','SecurityIncident',0,0,NULL,3,'{"User":"closed","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:43','2018-03-20 15:13:53',0,1),
	(78,'Visualisation.VisualizedKpi','Incomplete Lifecycle','SecurityIncident',0,0,NULL,3,'{"User":"incomplete_stage","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:44','2018-03-20 15:13:53',0,1),
	(79,'Dashboard.DashboardKpiObject','Total','SecurityIncident',1,0,NULL,1,'{"Admin":"total"}','2018-03-20 15:13:44','2018-03-20 15:13:53',0,1),
	(80,'Dashboard.DashboardKpiObject','Open','SecurityIncident',1,0,NULL,1,'{"Admin":"open"}','2018-03-20 15:13:44','2018-03-20 15:13:53',0,1),
	(81,'Dashboard.DashboardKpiObject','Closed','SecurityIncident',1,0,NULL,1,'{"Admin":"closed"}','2018-03-20 15:13:44','2018-03-20 15:13:53',0,1),
	(82,'Dashboard.DashboardKpiObject','Incomplete Lifecycle','SecurityIncident',1,0,NULL,1,'{"Admin":"incomplete_stage"}','2018-03-20 15:13:44','2018-03-20 15:13:53',0,1),
	(83,'Dashboard.DashboardKpiObject','Security Incidents created during the past two weeks','SecurityIncident',1,1,NULL,1,'{"DynamicFilter":"recently_created"}','2018-03-20 15:13:44','2018-03-20 15:13:53',0,1),
	(84,'Dashboard.DashboardKpiObject','Security Incidents deleted during the past two weeks','SecurityIncident',1,1,NULL,1,'{"DynamicFilter":"recently_deleted"}','2018-03-20 15:13:44','2018-03-20 15:13:53',0,1),
	(85,'Visualisation.VisualizedKpi','Expired','ComplianceAnalysisFinding',0,0,NULL,3,'{"User":"expired","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:44','2018-03-20 15:13:53',0,1),
	(86,'Visualisation.VisualizedKpi','Expired','ComplianceAnalysisFinding',0,0,NULL,3,'{"User":"expired","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:45','2018-03-20 15:13:54',0,1),
	(87,'Visualisation.VisualizedKpi','Open','ComplianceAnalysisFinding',0,0,NULL,3,'{"User":"open","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:46','2018-03-20 15:13:54',0,1),
	(88,'Visualisation.VisualizedKpi','Open','ComplianceAnalysisFinding',0,0,NULL,3,'{"User":"open","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:46','2018-03-20 15:13:54',0,1),
	(89,'Visualisation.VisualizedKpi','Closed','ComplianceAnalysisFinding',0,0,NULL,3,'{"User":"closed","CustomRoles.CustomRole":"Owner","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:46','2018-03-20 15:13:54',0,1),
	(90,'Visualisation.VisualizedKpi','Closed','ComplianceAnalysisFinding',0,0,NULL,3,'{"User":"closed","CustomRoles.CustomRole":"Collaborator","CustomRoles.CustomUser":"1"}','2018-03-20 15:13:46','2018-03-20 15:13:54',0,1);
ALTER TABLE `dashboard_kpis` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `dashboard_kpi_attributes` WRITE;
ALTER TABLE `dashboard_kpi_attributes` DISABLE KEYS;
INSERT INTO `dashboard_kpi_attributes` (`id`, `kpi_id`, `model`, `foreign_key`) VALUES 
	(1,1,'User','total'),
	(2,1,'CustomRoles.CustomRole','Owner'),
	(3,1,'CustomRoles.CustomUser','1'),
	(4,2,'User','total'),
	(5,2,'CustomRoles.CustomRole','Stakeholder'),
	(6,2,'CustomRoles.CustomUser','1'),
	(7,3,'User','next_reviews'),
	(8,3,'CustomRoles.CustomRole','Owner'),
	(9,3,'CustomRoles.CustomUser','1'),
	(10,4,'User','next_reviews'),
	(11,4,'CustomRoles.CustomRole','Stakeholder'),
	(12,4,'CustomRoles.CustomUser','1'),
	(13,5,'User','missed_reviews'),
	(14,5,'CustomRoles.CustomRole','Owner'),
	(15,5,'CustomRoles.CustomUser','1'),
	(16,6,'User','missed_reviews'),
	(17,6,'CustomRoles.CustomRole','Stakeholder'),
	(18,6,'CustomRoles.CustomUser','1'),
	(19,7,'Admin','total'),
	(20,8,'Admin','next_reviews'),
	(21,9,'Admin','missed_reviews'),
	(22,10,'CustomQuery','total_risk_score'),
	(23,11,'CustomQuery','total_residual_score'),
	(24,12,'DynamicFilter','recently_created'),
	(25,13,'DynamicFilter','recently_deleted'),
	(26,14,'User','total'),
	(27,14,'CustomRoles.CustomRole','Owner'),
	(28,14,'CustomRoles.CustomUser','1'),
	(29,15,'User','total'),
	(30,15,'CustomRoles.CustomRole','Stakeholder'),
	(31,15,'CustomRoles.CustomUser','1'),
	(32,16,'User','next_reviews'),
	(33,16,'CustomRoles.CustomRole','Owner'),
	(34,16,'CustomRoles.CustomUser','1'),
	(35,17,'User','next_reviews'),
	(36,17,'CustomRoles.CustomRole','Stakeholder'),
	(37,17,'CustomRoles.CustomUser','1'),
	(38,18,'User','missed_reviews'),
	(39,18,'CustomRoles.CustomRole','Owner'),
	(40,18,'CustomRoles.CustomUser','1'),
	(41,19,'User','missed_reviews'),
	(42,19,'CustomRoles.CustomRole','Stakeholder'),
	(43,19,'CustomRoles.CustomUser','1'),
	(44,20,'Admin','total'),
	(45,21,'Admin','next_reviews'),
	(46,22,'Admin','missed_reviews'),
	(47,23,'CustomQuery','total_risk_score'),
	(48,24,'CustomQuery','total_residual_score'),
	(49,25,'DynamicFilter','recently_created'),
	(50,26,'DynamicFilter','recently_deleted'),
	(51,27,'User','total'),
	(52,27,'CustomRoles.CustomRole','Owner'),
	(53,27,'CustomRoles.CustomUser','1'),
	(54,28,'User','total'),
	(55,28,'CustomRoles.CustomRole','Stakeholder'),
	(56,28,'CustomRoles.CustomUser','1'),
	(57,29,'User','next_reviews'),
	(58,29,'CustomRoles.CustomRole','Owner'),
	(59,29,'CustomRoles.CustomUser','1'),
	(60,30,'User','next_reviews'),
	(61,30,'CustomRoles.CustomRole','Stakeholder'),
	(62,30,'CustomRoles.CustomUser','1'),
	(63,31,'User','missed_reviews'),
	(64,31,'CustomRoles.CustomRole','Owner'),
	(65,31,'CustomRoles.CustomUser','1'),
	(66,32,'User','missed_reviews'),
	(67,32,'CustomRoles.CustomRole','Stakeholder'),
	(68,32,'CustomRoles.CustomUser','1'),
	(69,33,'Admin','total'),
	(70,34,'Admin','next_reviews'),
	(71,35,'Admin','missed_reviews'),
	(72,36,'CustomQuery','total_risk_score'),
	(73,37,'CustomQuery','total_residual_score'),
	(74,38,'DynamicFilter','recently_created'),
	(75,39,'DynamicFilter','recently_deleted'),
	(76,40,'User','total'),
	(77,40,'CustomRoles.CustomRole','Owner'),
	(78,40,'CustomRoles.CustomUser','1'),
	(79,41,'User','expired'),
	(80,41,'CustomRoles.CustomRole','Owner'),
	(81,41,'CustomRoles.CustomUser','1'),
	(82,42,'User','comming_dates'),
	(83,42,'CustomRoles.CustomRole','Owner'),
	(84,42,'CustomRoles.CustomUser','1'),
	(85,43,'User','expired_tasks'),
	(86,43,'CustomRoles.CustomRole','Owner'),
	(87,43,'CustomRoles.CustomUser','1'),
	(88,44,'Admin','total'),
	(89,45,'Admin','expired'),
	(90,46,'Admin','comming_dates'),
	(91,47,'Admin','expired_tasks'),
	(92,48,'DynamicFilter','recently_created'),
	(93,49,'DynamicFilter','recently_deleted'),
	(94,50,'User','total'),
	(95,50,'CustomRoles.CustomRole','ServiceOwner'),
	(96,50,'CustomRoles.CustomUser','1'),
	(97,51,'User','total'),
	(98,51,'CustomRoles.CustomRole','Collaborator'),
	(99,51,'CustomRoles.CustomUser','1'),
	(100,52,'User','missing_audits'),
	(101,52,'CustomRoles.CustomRole','ServiceOwner'),
	(102,52,'CustomRoles.CustomUser','1'),
	(103,53,'User','missing_audits'),
	(104,53,'CustomRoles.CustomRole','Collaborator'),
	(105,53,'CustomRoles.CustomUser','1'),
	(106,54,'User','failed_audits'),
	(107,54,'CustomRoles.CustomRole','ServiceOwner'),
	(108,54,'CustomRoles.CustomUser','1'),
	(109,55,'User','failed_audits'),
	(110,55,'CustomRoles.CustomRole','Collaborator'),
	(111,55,'CustomRoles.CustomUser','1'),
	(112,56,'User','issue'),
	(113,56,'CustomRoles.CustomRole','ServiceOwner'),
	(114,56,'CustomRoles.CustomUser','1'),
	(115,57,'User','issue'),
	(116,57,'CustomRoles.CustomRole','Collaborator'),
	(117,57,'CustomRoles.CustomUser','1'),
	(118,58,'Admin','total'),
	(119,59,'Admin','missing_audits'),
	(120,60,'Admin','failed_audits'),
	(121,61,'Admin','issue'),
	(122,62,'DynamicFilter','recently_created'),
	(123,63,'DynamicFilter','recently_deleted'),
	(124,64,'User','total'),
	(125,64,'CustomRoles.CustomRole','Owner'),
	(126,64,'CustomRoles.CustomUser','1'),
	(127,65,'User','total'),
	(128,65,'CustomRoles.CustomRole','Collaborator'),
	(129,65,'CustomRoles.CustomUser','1'),
	(130,66,'User','next_reviews'),
	(131,66,'CustomRoles.CustomRole','Owner'),
	(132,66,'CustomRoles.CustomUser','1'),
	(133,67,'User','next_reviews'),
	(134,67,'CustomRoles.CustomRole','Collaborator'),
	(135,67,'CustomRoles.CustomUser','1'),
	(136,68,'User','missed_reviews'),
	(137,68,'CustomRoles.CustomRole','Owner'),
	(138,68,'CustomRoles.CustomUser','1'),
	(139,69,'User','missed_reviews'),
	(140,69,'CustomRoles.CustomRole','Collaborator'),
	(141,69,'CustomRoles.CustomUser','1'),
	(142,70,'Admin','total'),
	(143,71,'Admin','next_reviews'),
	(144,72,'Admin','missed_reviews'),
	(145,73,'DynamicFilter','recently_created'),
	(146,74,'DynamicFilter','recently_deleted'),
	(147,75,'User','total'),
	(148,75,'CustomRoles.CustomRole','Owner'),
	(149,75,'CustomRoles.CustomUser','1'),
	(150,76,'User','open'),
	(151,76,'CustomRoles.CustomRole','Owner'),
	(152,76,'CustomRoles.CustomUser','1'),
	(153,77,'User','closed'),
	(154,77,'CustomRoles.CustomRole','Owner'),
	(155,77,'CustomRoles.CustomUser','1'),
	(156,78,'User','incomplete_stage'),
	(157,78,'CustomRoles.CustomRole','Owner'),
	(158,78,'CustomRoles.CustomUser','1'),
	(159,79,'Admin','total'),
	(160,80,'Admin','open'),
	(161,81,'Admin','closed'),
	(162,82,'Admin','incomplete_stage'),
	(163,83,'DynamicFilter','recently_created'),
	(164,84,'DynamicFilter','recently_deleted'),
	(165,85,'User','expired'),
	(166,85,'CustomRoles.CustomRole','Owner'),
	(167,85,'CustomRoles.CustomUser','1'),
	(168,86,'User','expired'),
	(169,86,'CustomRoles.CustomRole','Collaborator'),
	(170,86,'CustomRoles.CustomUser','1'),
	(171,87,'User','open'),
	(172,87,'CustomRoles.CustomRole','Owner'),
	(173,87,'CustomRoles.CustomUser','1'),
	(174,88,'User','open'),
	(175,88,'CustomRoles.CustomRole','Collaborator'),
	(176,88,'CustomRoles.CustomUser','1'),
	(177,89,'User','closed'),
	(178,89,'CustomRoles.CustomRole','Owner'),
	(179,89,'CustomRoles.CustomUser','1'),
	(180,90,'User','closed'),
	(181,90,'CustomRoles.CustomRole','Collaborator'),
	(182,90,'CustomRoles.CustomUser','1');
ALTER TABLE `dashboard_kpi_attributes` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `dashboard_kpi_logs` WRITE;
ALTER TABLE `dashboard_kpi_logs` DISABLE KEYS;
ALTER TABLE `dashboard_kpi_logs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `dashboard_kpi_values` WRITE;
ALTER TABLE `dashboard_kpi_values` DISABLE KEYS;
INSERT INTO `dashboard_kpi_values` (`id`, `kpi_id`, `user_id`, `value`, `type`) VALUES 
	(1,1,NULL,0,0),
	(2,2,NULL,0,0),
	(3,3,NULL,0,0),
	(4,4,NULL,0,0),
	(5,5,NULL,0,0),
	(6,6,NULL,0,0),
	(7,7,NULL,0,0),
	(8,8,NULL,0,0),
	(9,9,NULL,0,0),
	(10,10,NULL,0,0),
	(11,11,NULL,0,0),
	(12,12,NULL,0,0),
	(13,13,NULL,0,0),
	(14,14,NULL,0,0),
	(15,15,NULL,0,0),
	(16,16,NULL,0,0),
	(17,17,NULL,0,0),
	(18,18,NULL,0,0),
	(19,19,NULL,0,0),
	(20,20,NULL,0,0),
	(21,21,NULL,0,0),
	(22,22,NULL,0,0),
	(23,23,NULL,0,0),
	(24,24,NULL,0,0),
	(25,25,NULL,0,0),
	(26,26,NULL,0,0),
	(27,27,NULL,0,0),
	(28,28,NULL,0,0),
	(29,29,NULL,0,0),
	(30,30,NULL,0,0),
	(31,31,NULL,0,0),
	(32,32,NULL,0,0),
	(33,33,NULL,0,0),
	(34,34,NULL,0,0),
	(35,35,NULL,0,0),
	(36,36,NULL,0,0),
	(37,37,NULL,0,0),
	(38,38,NULL,0,0),
	(39,39,NULL,0,0),
	(40,40,NULL,0,0),
	(41,41,NULL,0,0),
	(42,42,NULL,0,0),
	(43,43,NULL,0,0),
	(44,44,NULL,0,0),
	(45,45,NULL,0,0),
	(46,46,NULL,0,0),
	(47,47,NULL,0,0),
	(48,48,NULL,0,0),
	(49,49,NULL,0,0),
	(50,50,NULL,0,0),
	(51,51,NULL,0,0),
	(52,52,NULL,0,0),
	(53,53,NULL,0,0),
	(54,54,NULL,0,0),
	(55,55,NULL,0,0),
	(56,56,NULL,0,0),
	(57,57,NULL,0,0),
	(58,58,NULL,0,0),
	(59,59,NULL,0,0),
	(60,60,NULL,0,0),
	(61,61,NULL,0,0),
	(62,62,NULL,0,0),
	(63,63,NULL,0,0),
	(64,64,NULL,0,0),
	(65,65,NULL,0,0),
	(66,66,NULL,0,0),
	(67,67,NULL,0,0),
	(68,68,NULL,0,0),
	(69,69,NULL,0,0),
	(70,70,NULL,0,0),
	(71,71,NULL,0,0),
	(72,72,NULL,0,0),
	(73,73,NULL,0,0),
	(74,74,NULL,0,0),
	(75,75,NULL,0,0),
	(76,76,NULL,0,0),
	(77,77,NULL,0,0),
	(78,78,NULL,0,0),
	(79,79,NULL,0,0),
	(80,80,NULL,0,0),
	(81,81,NULL,0,0),
	(82,82,NULL,0,0),
	(83,83,NULL,0,0),
	(84,84,NULL,0,0),
	(85,85,NULL,0,0),
	(86,86,NULL,0,0),
	(87,87,NULL,0,0),
	(88,88,NULL,0,0),
	(89,89,NULL,0,0),
	(90,90,NULL,0,0);
ALTER TABLE `dashboard_kpi_values` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `dashboard_kpi_value_logs` WRITE;
ALTER TABLE `dashboard_kpi_value_logs` DISABLE KEYS;
INSERT INTO `dashboard_kpi_value_logs` (`id`, `kpi_value_id`, `kpi_id`, `value`, `request_id`, `timestamp`, `created`) VALUES 
	(1,1,1,0,NULL,1521555213,'2018-03-20 15:13:33'),
	(2,2,2,0,NULL,1521555213,'2018-03-20 15:13:33'),
	(3,3,3,0,NULL,1521555213,'2018-03-20 15:13:33'),
	(4,4,4,0,NULL,1521555213,'2018-03-20 15:13:33'),
	(5,5,5,0,NULL,1521555214,'2018-03-20 15:13:34'),
	(6,6,6,0,NULL,1521555214,'2018-03-20 15:13:34'),
	(7,7,7,0,NULL,1521555214,'2018-03-20 15:13:34'),
	(8,8,8,0,NULL,1521555214,'2018-03-20 15:13:34'),
	(9,9,9,0,NULL,1521555214,'2018-03-20 15:13:34'),
	(10,10,10,0,NULL,1521555214,'2018-03-20 15:13:34'),
	(11,11,11,0,NULL,1521555214,'2018-03-20 15:13:34'),
	(12,12,12,0,NULL,1521555215,'2018-03-20 15:13:35'),
	(13,13,13,0,NULL,1521555215,'2018-03-20 15:13:35'),
	(14,14,14,0,NULL,1521555215,'2018-03-20 15:13:35'),
	(15,15,15,0,NULL,1521555215,'2018-03-20 15:13:35'),
	(16,16,16,0,NULL,1521555215,'2018-03-20 15:13:35'),
	(17,17,17,0,NULL,1521555215,'2018-03-20 15:13:35'),
	(18,18,18,0,NULL,1521555215,'2018-03-20 15:13:35'),
	(19,19,19,0,NULL,1521555215,'2018-03-20 15:13:35'),
	(20,20,20,0,NULL,1521555216,'2018-03-20 15:13:36'),
	(21,21,21,0,NULL,1521555216,'2018-03-20 15:13:36'),
	(22,22,22,0,NULL,1521555216,'2018-03-20 15:13:36'),
	(23,23,23,0,NULL,1521555216,'2018-03-20 15:13:36'),
	(24,24,24,0,NULL,1521555216,'2018-03-20 15:13:36'),
	(25,25,25,0,NULL,1521555216,'2018-03-20 15:13:36'),
	(26,26,26,0,NULL,1521555216,'2018-03-20 15:13:36'),
	(27,27,27,0,NULL,1521555216,'2018-03-20 15:13:36'),
	(28,28,28,0,NULL,1521555216,'2018-03-20 15:13:36'),
	(29,29,29,0,NULL,1521555216,'2018-03-20 15:13:36'),
	(30,30,30,0,NULL,1521555216,'2018-03-20 15:13:36'),
	(31,31,31,0,NULL,1521555217,'2018-03-20 15:13:37'),
	(32,32,32,0,NULL,1521555217,'2018-03-20 15:13:37'),
	(33,33,33,0,NULL,1521555217,'2018-03-20 15:13:37'),
	(34,34,34,0,NULL,1521555217,'2018-03-20 15:13:37'),
	(35,35,35,0,NULL,1521555217,'2018-03-20 15:13:37'),
	(36,36,36,0,NULL,1521555217,'2018-03-20 15:13:37'),
	(37,37,37,0,NULL,1521555217,'2018-03-20 15:13:37'),
	(38,38,38,0,NULL,1521555217,'2018-03-20 15:13:37'),
	(39,39,39,0,NULL,1521555217,'2018-03-20 15:13:37'),
	(40,40,40,0,NULL,1521555218,'2018-03-20 15:13:38'),
	(41,41,41,0,NULL,1521555218,'2018-03-20 15:13:38'),
	(42,42,42,0,NULL,1521555218,'2018-03-20 15:13:38'),
	(43,43,43,0,NULL,1521555218,'2018-03-20 15:13:38'),
	(44,44,44,0,NULL,1521555218,'2018-03-20 15:13:38'),
	(45,45,45,0,NULL,1521555218,'2018-03-20 15:13:38'),
	(46,46,46,0,NULL,1521555218,'2018-03-20 15:13:38'),
	(47,47,47,0,NULL,1521555218,'2018-03-20 15:13:38'),
	(48,48,48,0,NULL,1521555218,'2018-03-20 15:13:38'),
	(49,49,49,0,NULL,1521555218,'2018-03-20 15:13:38'),
	(50,50,50,0,NULL,1521555219,'2018-03-20 15:13:39'),
	(51,51,51,0,NULL,1521555219,'2018-03-20 15:13:39'),
	(52,52,52,0,NULL,1521555219,'2018-03-20 15:13:39'),
	(53,53,53,0,NULL,1521555220,'2018-03-20 15:13:40'),
	(54,54,54,0,NULL,1521555220,'2018-03-20 15:13:40'),
	(55,55,55,0,NULL,1521555220,'2018-03-20 15:13:40'),
	(56,56,56,0,NULL,1521555220,'2018-03-20 15:13:40'),
	(57,57,57,0,NULL,1521555220,'2018-03-20 15:13:40'),
	(58,58,58,0,NULL,1521555221,'2018-03-20 15:13:41'),
	(59,59,59,0,NULL,1521555221,'2018-03-20 15:13:41'),
	(60,60,60,0,NULL,1521555221,'2018-03-20 15:13:41'),
	(61,61,61,0,NULL,1521555221,'2018-03-20 15:13:41'),
	(62,62,62,0,NULL,1521555221,'2018-03-20 15:13:41'),
	(63,63,63,0,NULL,1521555221,'2018-03-20 15:13:41'),
	(64,64,64,0,NULL,1521555221,'2018-03-20 15:13:41'),
	(65,65,65,0,NULL,1521555222,'2018-03-20 15:13:42'),
	(66,66,66,0,NULL,1521555222,'2018-03-20 15:13:42'),
	(67,67,67,0,NULL,1521555222,'2018-03-20 15:13:42'),
	(68,68,68,0,NULL,1521555222,'2018-03-20 15:13:42'),
	(69,69,69,0,NULL,1521555222,'2018-03-20 15:13:42'),
	(70,70,70,0,NULL,1521555222,'2018-03-20 15:13:42'),
	(71,71,71,0,NULL,1521555222,'2018-03-20 15:13:42'),
	(72,72,72,0,NULL,1521555223,'2018-03-20 15:13:43'),
	(73,73,73,0,NULL,1521555223,'2018-03-20 15:13:43'),
	(74,74,74,0,NULL,1521555223,'2018-03-20 15:13:43'),
	(75,75,75,0,NULL,1521555223,'2018-03-20 15:13:43'),
	(76,76,76,0,NULL,1521555223,'2018-03-20 15:13:43'),
	(77,77,77,0,NULL,1521555223,'2018-03-20 15:13:43'),
	(78,78,78,0,NULL,1521555224,'2018-03-20 15:13:44'),
	(79,79,79,0,NULL,1521555224,'2018-03-20 15:13:44'),
	(80,80,80,0,NULL,1521555224,'2018-03-20 15:13:44'),
	(81,81,81,0,NULL,1521555224,'2018-03-20 15:13:44'),
	(82,82,82,0,NULL,1521555224,'2018-03-20 15:13:44'),
	(83,83,83,0,NULL,1521555224,'2018-03-20 15:13:44'),
	(84,84,84,0,NULL,1521555224,'2018-03-20 15:13:44'),
	(85,85,85,0,NULL,1521555225,'2018-03-20 15:13:45'),
	(86,86,86,0,NULL,1521555225,'2018-03-20 15:13:45'),
	(87,87,87,0,NULL,1521555226,'2018-03-20 15:13:46'),
	(88,88,88,0,NULL,1521555226,'2018-03-20 15:13:46'),
	(89,89,89,0,NULL,1521555226,'2018-03-20 15:13:46'),
	(90,90,90,0,NULL,1521555226,'2018-03-20 15:13:46'),
	(91,1,1,0,NULL,1521555226,'2018-03-20 15:13:46'),
	(92,2,2,0,NULL,1521555227,'2018-03-20 15:13:47'),
	(93,3,3,0,NULL,1521555227,'2018-03-20 15:13:47'),
	(94,4,4,0,NULL,1521555227,'2018-03-20 15:13:47'),
	(95,5,5,0,NULL,1521555228,'2018-03-20 15:13:48'),
	(96,6,6,0,NULL,1521555228,'2018-03-20 15:13:48'),
	(97,7,7,0,NULL,1521555228,'2018-03-20 15:13:48'),
	(98,8,8,0,NULL,1521555228,'2018-03-20 15:13:48'),
	(99,9,9,0,NULL,1521555228,'2018-03-20 15:13:48'),
	(100,10,10,0,NULL,1521555228,'2018-03-20 15:13:48'),
	(101,11,11,0,NULL,1521555228,'2018-03-20 15:13:48'),
	(102,12,12,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(103,13,13,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(104,14,14,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(105,15,15,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(106,16,16,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(107,17,17,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(108,18,18,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(109,19,19,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(110,20,20,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(111,21,21,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(112,22,22,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(113,23,23,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(114,24,24,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(115,25,25,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(116,26,26,0,NULL,1521555229,'2018-03-20 15:13:49'),
	(117,27,27,0,NULL,1521555230,'2018-03-20 15:13:50'),
	(118,28,28,0,NULL,1521555230,'2018-03-20 15:13:50'),
	(119,29,29,0,NULL,1521555230,'2018-03-20 15:13:50'),
	(120,30,30,0,NULL,1521555230,'2018-03-20 15:13:50'),
	(121,31,31,0,NULL,1521555230,'2018-03-20 15:13:50'),
	(122,32,32,0,NULL,1521555230,'2018-03-20 15:13:50'),
	(123,33,33,0,NULL,1521555230,'2018-03-20 15:13:50'),
	(124,34,34,0,NULL,1521555230,'2018-03-20 15:13:50'),
	(125,35,35,0,NULL,1521555230,'2018-03-20 15:13:50'),
	(126,36,36,0,NULL,1521555230,'2018-03-20 15:13:50'),
	(127,37,37,0,NULL,1521555230,'2018-03-20 15:13:50'),
	(128,38,38,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(129,39,39,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(130,40,40,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(131,41,41,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(132,42,42,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(133,43,43,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(134,44,44,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(135,45,45,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(136,46,46,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(137,47,47,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(138,48,48,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(139,49,49,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(140,50,50,0,NULL,1521555231,'2018-03-20 15:13:51'),
	(141,51,51,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(142,52,52,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(143,53,53,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(144,54,54,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(145,55,55,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(146,56,56,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(147,57,57,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(148,58,58,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(149,59,59,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(150,60,60,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(151,61,61,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(152,62,62,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(153,63,63,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(154,64,64,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(155,65,65,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(156,66,66,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(157,67,67,0,NULL,1521555232,'2018-03-20 15:13:52'),
	(158,68,68,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(159,69,69,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(160,70,70,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(161,71,71,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(162,72,72,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(163,73,73,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(164,74,74,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(165,75,75,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(166,76,76,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(167,77,77,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(168,78,78,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(169,79,79,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(170,80,80,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(171,81,81,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(172,82,82,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(173,83,83,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(174,84,84,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(175,85,85,0,NULL,1521555233,'2018-03-20 15:13:53'),
	(176,86,86,0,NULL,1521555234,'2018-03-20 15:13:54'),
	(177,87,87,0,NULL,1521555234,'2018-03-20 15:13:54'),
	(178,88,88,0,NULL,1521555234,'2018-03-20 15:13:54'),
	(179,89,89,0,NULL,1521555234,'2018-03-20 15:13:54'),
	(180,90,90,0,NULL,1521555234,'2018-03-20 15:13:54');
ALTER TABLE `dashboard_kpi_value_logs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_asset_gdpr` WRITE;
ALTER TABLE `data_asset_gdpr` DISABLE KEYS;
ALTER TABLE `data_asset_gdpr` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_asset_gdpr_archiving_drivers` WRITE;
ALTER TABLE `data_asset_gdpr_archiving_drivers` DISABLE KEYS;
ALTER TABLE `data_asset_gdpr_archiving_drivers` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_asset_gdpr_collection_methods` WRITE;
ALTER TABLE `data_asset_gdpr_collection_methods` DISABLE KEYS;
ALTER TABLE `data_asset_gdpr_collection_methods` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_asset_gdpr_data_types` WRITE;
ALTER TABLE `data_asset_gdpr_data_types` DISABLE KEYS;
ALTER TABLE `data_asset_gdpr_data_types` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_asset_gdpr_lawful_bases` WRITE;
ALTER TABLE `data_asset_gdpr_lawful_bases` DISABLE KEYS;
ALTER TABLE `data_asset_gdpr_lawful_bases` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_asset_gdpr_third_party_countries` WRITE;
ALTER TABLE `data_asset_gdpr_third_party_countries` DISABLE KEYS;
ALTER TABLE `data_asset_gdpr_third_party_countries` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_asset_instances` WRITE;
ALTER TABLE `data_asset_instances` DISABLE KEYS;
ALTER TABLE `data_asset_instances` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_asset_settings` WRITE;
ALTER TABLE `data_asset_settings` DISABLE KEYS;
ALTER TABLE `data_asset_settings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_asset_settings_third_parties` WRITE;
ALTER TABLE `data_asset_settings_third_parties` DISABLE KEYS;
ALTER TABLE `data_asset_settings_third_parties` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_asset_settings_users` WRITE;
ALTER TABLE `data_asset_settings_users` DISABLE KEYS;
ALTER TABLE `data_asset_settings_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_asset_statuses` WRITE;
ALTER TABLE `data_asset_statuses` DISABLE KEYS;
INSERT INTO `data_asset_statuses` (`id`, `name`) VALUES 
	(1,'Created'),
	(2,'Modified'),
	(3,'Stored'),
	(4,'Transit'),
	(5,'Deleted'),
	(6,'Tainted / Broken'),
	(7,'Unnecessary');
ALTER TABLE `data_asset_statuses` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_assets` WRITE;
ALTER TABLE `data_assets` DISABLE KEYS;
ALTER TABLE `data_assets` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_assets_projects` WRITE;
ALTER TABLE `data_assets_projects` DISABLE KEYS;
ALTER TABLE `data_assets_projects` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_assets_risks` WRITE;
ALTER TABLE `data_assets_risks` DISABLE KEYS;
ALTER TABLE `data_assets_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_assets_security_policies` WRITE;
ALTER TABLE `data_assets_security_policies` DISABLE KEYS;
ALTER TABLE `data_assets_security_policies` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_assets_security_services` WRITE;
ALTER TABLE `data_assets_security_services` DISABLE KEYS;
ALTER TABLE `data_assets_security_services` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `data_assets_third_parties` WRITE;
ALTER TABLE `data_assets_third_parties` DISABLE KEYS;
ALTER TABLE `data_assets_third_parties` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goal_audit_dates` WRITE;
ALTER TABLE `goal_audit_dates` DISABLE KEYS;
ALTER TABLE `goal_audit_dates` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goal_audit_improvements` WRITE;
ALTER TABLE `goal_audit_improvements` DISABLE KEYS;
ALTER TABLE `goal_audit_improvements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goal_audit_improvements_projects` WRITE;
ALTER TABLE `goal_audit_improvements_projects` DISABLE KEYS;
ALTER TABLE `goal_audit_improvements_projects` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goal_audit_improvements_security_incidents` WRITE;
ALTER TABLE `goal_audit_improvements_security_incidents` DISABLE KEYS;
ALTER TABLE `goal_audit_improvements_security_incidents` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goals` WRITE;
ALTER TABLE `goals` DISABLE KEYS;
ALTER TABLE `goals` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goal_audits` WRITE;
ALTER TABLE `goal_audits` DISABLE KEYS;
ALTER TABLE `goal_audits` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goals_program_issues` WRITE;
ALTER TABLE `goals_program_issues` DISABLE KEYS;
ALTER TABLE `goals_program_issues` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goals_projects` WRITE;
ALTER TABLE `goals_projects` DISABLE KEYS;
ALTER TABLE `goals_projects` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goals_risks` WRITE;
ALTER TABLE `goals_risks` DISABLE KEYS;
ALTER TABLE `goals_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goals_security_policies` WRITE;
ALTER TABLE `goals_security_policies` DISABLE KEYS;
ALTER TABLE `goals_security_policies` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goals_security_services` WRITE;
ALTER TABLE `goals_security_services` DISABLE KEYS;
ALTER TABLE `goals_security_services` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `goals_third_party_risks` WRITE;
ALTER TABLE `goals_third_party_risks` DISABLE KEYS;
ALTER TABLE `goals_third_party_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `groups` WRITE;
ALTER TABLE `groups` DISABLE KEYS;
INSERT INTO `groups` (`id`, `name`, `description`, `status`, `created`, `modified`) VALUES 
	(10,'Admin','',1,'2013-10-14 16:18:08','2013-10-14 16:18:08'),
	(11,'Third Party Feedback','',1,'2016-01-07 17:07:53','2016-01-07 17:07:53'),
	(12,'Notification Feedback','',1,'2016-01-07 17:08:02','2016-01-07 17:08:02'),
	(13,'All but Settings','',1,'2016-01-07 17:08:10','2016-01-07 17:08:10');
ALTER TABLE `groups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `issues` WRITE;
ALTER TABLE `issues` DISABLE KEYS;
ALTER TABLE `issues` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ldap_connectors` WRITE;
ALTER TABLE `ldap_connectors` DISABLE KEYS;
ALTER TABLE `ldap_connectors` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ldap_connector_authentication` WRITE;
ALTER TABLE `ldap_connector_authentication` DISABLE KEYS;
INSERT INTO `ldap_connector_authentication` (`id`, `auth_users`, `auth_users_id`, `oauth_google`, `oauth_google_id`, `auth_awareness`, `auth_awareness_id`, `auth_policies`, `auth_policies_id`, `auth_compliance_audit`, `auth_vendor_assessment`, `modified`) VALUES 
	(1,0,NULL,0,0,0,NULL,0,NULL,0,0,'2015-08-16 11:20:01');
ALTER TABLE `ldap_connector_authentication` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `legals` WRITE;
ALTER TABLE `legals` DISABLE KEYS;
ALTER TABLE `legals` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `legals_third_parties` WRITE;
ALTER TABLE `legals_third_parties` DISABLE KEYS;
ALTER TABLE `legals_third_parties` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `log_security_policies` WRITE;
ALTER TABLE `log_security_policies` DISABLE KEYS;
ALTER TABLE `log_security_policies` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `notification_system_item_custom_roles` WRITE;
ALTER TABLE `notification_system_item_custom_roles` DISABLE KEYS;
ALTER TABLE `notification_system_item_custom_roles` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `notification_system_item_custom_users` WRITE;
ALTER TABLE `notification_system_item_custom_users` DISABLE KEYS;
ALTER TABLE `notification_system_item_custom_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `notification_system_item_emails` WRITE;
ALTER TABLE `notification_system_item_emails` DISABLE KEYS;
ALTER TABLE `notification_system_item_emails` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `notification_system_item_logs` WRITE;
ALTER TABLE `notification_system_item_logs` DISABLE KEYS;
ALTER TABLE `notification_system_item_logs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `notification_system_item_feedbacks` WRITE;
ALTER TABLE `notification_system_item_feedbacks` DISABLE KEYS;
ALTER TABLE `notification_system_item_feedbacks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `notification_system_items` WRITE;
ALTER TABLE `notification_system_items` DISABLE KEYS;
ALTER TABLE `notification_system_items` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `notification_system_items_objects` WRITE;
ALTER TABLE `notification_system_items_objects` DISABLE KEYS;
ALTER TABLE `notification_system_items_objects` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `notification_system_items_scopes` WRITE;
ALTER TABLE `notification_system_items_scopes` DISABLE KEYS;
ALTER TABLE `notification_system_items_scopes` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `notification_system_items_users` WRITE;
ALTER TABLE `notification_system_items_users` DISABLE KEYS;
ALTER TABLE `notification_system_items_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `notifications` WRITE;
ALTER TABLE `notifications` DISABLE KEYS;
ALTER TABLE `notifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `oauth_connectors` WRITE;
ALTER TABLE `oauth_connectors` DISABLE KEYS;
ALTER TABLE `oauth_connectors` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `object_status_statuses` WRITE;
ALTER TABLE `object_status_statuses` DISABLE KEYS;
ALTER TABLE `object_status_statuses` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `object_status_object_statuses` WRITE;
ALTER TABLE `object_status_object_statuses` DISABLE KEYS;
ALTER TABLE `object_status_object_statuses` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `phinxlog` WRITE;
ALTER TABLE `phinxlog` DISABLE KEYS;
INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES 
	(20170222204044,'Initial','2018-03-20 15:11:24','2018-03-20 15:11:35',0),
	(20170224222143,'LdapConnectorFieldLength','2018-03-20 15:11:35','2018-03-20 15:11:35',0),
	(20170227174535,'SecurityIncidentAutoClose','2018-03-20 15:11:35','2018-03-20 15:11:35',0),
	(20170304180120,'ComplianceAnalysisFindings','2018-03-20 15:11:35','2018-03-20 15:11:36',0),
	(20170306150509,'ExceptionCustomFields','2018-03-20 15:11:36','2018-03-20 15:11:36',0),
	(20170419103452,'Release34','2018-03-20 15:11:36','2018-03-20 15:11:36',0),
	(20170419103453,'BulkActionMigration','2018-03-20 15:11:36','2018-03-20 15:11:37',0),
	(20170427111638,'Workflows','2018-03-20 15:11:37','2018-03-20 15:11:39',0),
	(20170501232248,'HideDashboardSetting','2018-03-20 15:11:39','2018-03-20 15:11:39',0),
	(20170505114957,'RiskDescriptionField','2018-03-20 15:11:39','2018-03-20 15:11:39',0),
	(20170505115348,'SecurityIncidentsCustomFields','2018-03-20 15:11:39','2018-03-20 15:11:39',0),
	(20170505133034,'RiskGranularitySetting','2018-03-20 15:11:39','2018-03-20 15:11:39',0),
	(20170512081755,'ComplianceFindingsRequirements','2018-03-20 15:11:39','2018-03-20 15:11:39',0),
	(20170514184834,'Release35','2018-03-20 15:11:39','2018-03-20 15:11:39',0),
	(20170530050821,'VisualisationMigration','2018-03-20 15:11:39','2018-03-20 15:11:40',0),
	(20170530162223,'ComplianceSectionMigration','2018-03-20 15:11:40','2018-03-20 15:11:40',0),
	(20170531083319,'VisualisationUpdate','2018-03-20 15:11:40','2018-03-20 15:11:41',0),
	(20170531141458,'CompliancePackageSoftDelete','2018-03-20 15:11:41','2018-03-20 15:11:41',0),
	(20170601152538,'ComplianceManagementSoftDelete','2018-03-20 15:11:41','2018-03-20 15:11:41',0),
	(20170606000803,'VisualisationOrder','2018-03-20 15:11:41','2018-03-20 15:11:41',0),
	(20170616135005,'BusinessUnitSoftDelete','2018-03-20 15:11:41','2018-03-20 15:11:41',0),
	(20170619150358,'ProcessSoftDelete','2018-03-20 15:11:41','2018-03-20 15:11:41',0),
	(20170619161129,'LegalSoftDelete','2018-03-20 15:11:41','2018-03-20 15:11:41',0),
	(20170619165455,'ThirdPartySoftDelete','2018-03-20 15:11:41','2018-03-20 15:11:41',0),
	(20170620132546,'NotificationsUpdate','2018-03-20 15:11:41','2018-03-20 15:11:42',0),
	(20170620191731,'VisualisationConstraints','2018-03-20 15:11:42','2018-03-20 15:11:42',0),
	(20170625133642,'Release36','2018-03-20 15:11:42','2018-03-20 15:11:42',0),
	(20170627130016,'RiskExceptionPolicyExceptionSoftDelete','2018-03-20 15:11:42','2018-03-20 15:11:42',0),
	(20170628084922,'ProjectSoftDelete','2018-03-20 15:11:42','2018-03-20 15:11:42',0),
	(20170628121821,'ProjectAchievementSoftDelete','2018-03-20 15:11:42','2018-03-20 15:11:42',0),
	(20170628124938,'ProjectExpenseSoftDelete','2018-03-20 15:11:42','2018-03-20 15:11:42',0),
	(20170629075407,'SecurityIncidentSoftDelete','2018-03-20 15:11:42','2018-03-20 15:11:42',0),
	(20170629110422,'AwarenessProgramSoftDelete','2018-03-20 15:11:42','2018-03-20 15:11:42',0),
	(20170714125121,'SecurityServiceUrlText','2018-03-20 15:11:42','2018-03-20 15:11:42',0),
	(20170718154512,'CustomRolesUpdates','2018-03-20 15:11:42','2018-03-20 15:11:43',0),
	(20170720180052,'ThirdPartyAuditsPortal','2018-03-20 15:11:43','2018-03-20 15:11:43',0),
	(20170726164030,'Release37','2018-03-20 15:11:43','2018-03-20 15:11:43',0),
	(20170727102926,'DataAssets','2018-03-20 15:11:43','2018-03-20 15:11:45',0),
	(20170728173003,'ObjectStatusMigration','2018-03-20 15:11:45','2018-03-20 15:11:45',0),
	(20170728173555,'DataAssetObjectStatus','2018-03-20 15:11:45','2018-03-20 15:11:45',0),
	(20170804080341,'DataAssetSoftDelete','2018-03-20 15:11:45','2018-03-20 15:11:45',0),
	(20170808090720,'DataAssetCustomFields','2018-03-20 15:11:45','2018-03-20 15:11:45',0),
	(20170810141903,'DataAssetInstancesObjectStatus','2018-03-20 15:11:45','2018-03-20 15:11:46',0),
	(20170811112035,'DataAssetSettingAllCountries','2018-03-20 15:11:46','2018-03-20 15:11:46',0),
	(20170814130739,'DataAssetInstanceNewObjecStatus','2018-03-20 15:11:46','2018-03-20 15:11:46',0),
	(20170815121843,'DataAssetSettingsThirdPartyMigration','2018-03-20 15:11:46','2018-03-20 15:11:46',0),
	(20170817080806,'DataAssetStagesRemoval','2018-03-20 15:11:46','2018-03-20 15:11:46',0),
	(20170825112416,'SecurityServiceObjectStatusFields','2018-03-20 15:11:46','2018-03-20 15:11:46',0),
	(20170831130151,'DataAssetGdprUpdate','2018-03-20 15:11:46','2018-03-20 15:11:47',0),
	(20170906202831,'DataAssetThirdPartyInvolvedRebase','2018-03-20 15:11:47','2018-03-20 15:11:47',0),
	(20170907102831,'ObjectStatusStatusGroup','2018-03-20 15:11:47','2018-03-20 15:11:47',0),
	(20170911142950,'ComplianceManagementComplianceExceptionsHABTM','2018-03-20 15:11:47','2018-03-20 15:11:47',0),
	(20170919113030,'Release39','2018-03-20 15:11:47','2018-03-20 15:11:47',0),
	(20170926145600,'CustomFieldsMigration','2018-03-20 15:11:47','2018-03-20 15:11:47',0),
	(20170929172112,'DataAssetEmptyFields','2018-03-20 15:11:47','2018-03-20 15:11:47',0),
	(20170930183852,'DashboardsMigration','2018-03-20 15:11:47','2018-03-20 15:11:48',0),
	(20171008095457,'DashboardsUpdate1','2018-03-20 15:11:48','2018-03-20 15:11:48',0),
	(20171012203713,'DashboardsUpdate2','2018-03-20 15:11:48','2018-03-20 15:11:48',0),
	(20171030114955,'DashboardsUpdate3','2018-03-20 15:11:48','2018-03-20 15:11:48',0),
	(20171030114956,'Release41','2018-03-20 15:11:48','2018-03-20 15:11:48',0),
	(20171103155724,'Release43','2018-03-20 15:11:48','2018-03-20 15:11:48',0),
	(20171108163033,'Release44','2018-03-20 15:11:48','2018-03-20 15:11:48',0),
	(20171115130844,'AddUrlToCron','2018-03-20 15:11:48','2018-03-20 15:11:48',0),
	(20171116160123,'VisualisationMigrationRls45','2018-03-20 15:11:48','2018-03-20 15:11:48',0),
	(20171125171527,'Release45','2018-03-20 15:11:48','2018-03-20 15:11:48',0),
	(20171125171528,'Release46','2018-03-20 15:11:48','2018-03-20 15:11:48',0),
	(20171201145757,'SecurityPolicyDocumentTypeMigration','2018-03-20 15:11:48','2018-03-20 15:11:49',0),
	(20171201161058,'CreateOauthConnectors','2018-03-20 15:11:49','2018-03-20 15:11:49',0),
	(20171204034932,'AddColumnsToLdapConnectorAuthentication','2018-03-20 15:11:49','2018-03-20 15:11:49',0),
	(20171204035405,'InsertRecordsToSettingGroups','2018-03-20 15:11:49','2018-03-20 15:11:49',0),
	(20171206194944,'CustomValidator','2018-03-20 15:11:49','2018-03-20 15:11:49',0),
	(20171207151610,'RiskAppetitesMigration','2018-03-20 15:11:49','2018-03-20 15:11:49',0),
	(20171208100925,'RiskAppetitesMigration2','2018-03-20 15:11:49','2018-03-20 15:11:49',0),
	(20171208120946,'RiskAppetitesMigration3','2018-03-20 15:11:49','2018-03-20 15:11:49',0),
	(20171208125049,'RiskAppetitesMigration4','2018-03-20 15:11:49','2018-03-20 15:11:50',0),
	(20171208130627,'RiskAppetitesMigration5','2018-03-20 15:11:50','2018-03-20 15:11:50',0),
	(20171208132710,'AssetsComplianceManagement','2018-03-20 15:11:50','2018-03-20 15:11:50',0),
	(20171208133329,'RiskAppetitesMigration6','2018-03-20 15:11:50','2018-03-20 15:11:50',0),
	(20171208143754,'AssetsPolicyException','2018-03-20 15:11:50','2018-03-20 15:11:50',0),
	(20171212094447,'CreateServiceContractsOwners','2018-03-20 15:11:50','2018-03-20 15:11:50',0),
	(20171212133530,'AwarenessProgramTextFrameSize','2018-03-20 15:11:50','2018-03-20 15:11:50',0),
	(20171213145227,'RiskAppetitesMigration7','2018-03-20 15:11:50','2018-03-20 15:11:50',0),
	(20171214125455,'QueueTransportLimit','2018-03-20 15:11:50','2018-03-20 15:11:50',0),
	(20171219163259,'RiskAppetitesMigration8','2018-03-20 15:11:50','2018-03-20 15:11:51',0),
	(20171220095239,'RiskAppetitesMigration9','2018-03-20 15:11:51','2018-03-20 15:11:51',0),
	(20171222142512,'AddColumnsToSecurityServices','2018-03-20 15:11:51','2018-03-20 15:11:51',0),
	(20171222184923,'AddAuditEvidenceOwnerIdToSecurityServiceAudits','2018-03-20 15:11:51','2018-03-20 15:11:51',0),
	(20180103153429,'RiskAppetitesMigration10','2018-03-20 15:11:51','2018-03-20 15:11:51',0),
	(20180108115941,'ChangeForeignKeyInSecurityServiceAudits','2018-03-20 15:11:51','2018-03-20 15:11:51',0),
	(20180108120257,'ChangeForeignKeyInSecurityServiceMaintenances','2018-03-20 15:11:51','2018-03-20 15:11:52',0),
	(20180110104002,'RiskAppetitesMigration11','2018-03-20 15:11:52','2018-03-20 15:11:52',0),
	(20180110104003,'Release47','2018-03-20 15:11:52','2018-03-20 15:11:52',0),
	(20180131115100,'Release48','2018-03-20 15:11:52','2018-03-20 15:11:52',0),
	(20180201120000,'CreateUsersGroups','2018-03-20 15:11:52','2018-03-20 15:11:52',0),
	(20180201120001,'RemoveGroupIdFromUsers','2018-03-20 15:11:52','2018-03-20 15:11:52',0),
	(20180201120002,'MultipleGroupsForUser','2018-03-20 15:11:52','2018-03-20 15:11:52',0),
	(20180201120003,'MultipleGroupsCustomRoles','2018-03-20 15:11:52','2018-03-20 15:11:52',0),
	(20180201120004,'RemoveLegalUsersTable','2018-03-20 15:11:52','2018-03-20 15:11:56',0),
	(20180201120005,'RemoveBusinessUnitUsersTable','2018-03-20 15:11:56','2018-03-20 15:11:58',0),
	(20180201120006,'RemoveFieldsFromBusinessContinuitiesTable','2018-03-20 15:11:58','2018-03-20 15:12:01',0),
	(20180201120007,'RemoveFieldsFromRisksTable','2018-03-20 15:12:01','2018-03-20 15:12:04',0),
	(20180201120008,'RemoveFieldsFromThirdPartyRisksTable','2018-03-20 15:12:04','2018-03-20 15:12:07',0),
	(20180201120009,'RemoveFieldsFromBusinessContinuityPlansTable','2018-03-20 15:12:07','2018-03-20 15:12:09',0),
	(20180201120010,'RemoveFieldsFromBusinessContinuityTasksTable','2018-03-20 15:12:09','2018-03-20 15:12:10',0),
	(20180201120011,'RemoveComplianceAnalysisFindingsTables','2018-03-20 15:12:10','2018-03-20 15:12:12',0),
	(20180201120012,'RemoveComplianceExceptionsUsersTable','2018-03-20 15:12:12','2018-03-20 15:12:15',0),
	(20180201120013,'RemoveThirdPartiesUsersTable','2018-03-20 15:12:15','2018-03-20 15:12:20',0),
	(20180201120014,'RemovePolicyExceptionsUsersTable','2018-03-20 15:12:20','2018-03-20 15:12:22',0),
	(20180201120015,'RemoveUserIdFromProjectsTable','2018-03-20 15:12:22','2018-03-20 15:12:25',0),
	(20180201120016,'RemoveUserIdFromProjectAchievementsTable','2018-03-20 15:12:25','2018-03-20 15:12:28',0),
	(20180201120017,'RemoveAuthorIdFromRiskExceptionsTable','2018-03-20 15:12:28','2018-03-20 15:12:31',0),
	(20180201120018,'RemoveUserIdFromSecurityIncidentsTable','2018-03-20 15:12:31','2018-03-20 15:12:34',0),
	(20180201120019,'RemoveAuthorAndCollaboratosFromSecurityPolicies','2018-03-20 15:12:34','2018-03-20 15:12:36',0),
	(20180201120020,'RemoveServiceContractsOwnersTable','2018-03-20 15:12:36','2018-03-20 15:12:39',0),
	(20180201120021,'RemoveFieldsFromSecurityServicesTable','2018-03-20 15:12:39','2018-03-20 15:12:42',0),
	(20180201120022,'RemoveSecurityServicesUsersTable','2018-03-20 15:12:42','2018-03-20 15:12:45',0),
	(20180201120023,'RemoveFieldsFromSecurityServiceAuditsTable','2018-03-20 15:12:45','2018-03-20 15:12:48',0),
	(20180201120024,'RemoveFieldsFromSecurityServiceMaintenancesTable','2018-03-20 15:12:48','2018-03-20 15:12:51',0),
	(20180201120025,'VisualisationUserFieldsMigration','2018-03-20 15:12:51','2018-03-20 15:12:53',0),
	(20180203152011,'MigrationSystemLogs','2018-03-20 15:12:53','2018-03-20 15:12:53',0),
	(20180203161141,'MigrationVendorAssessments','2018-03-20 15:12:53','2018-03-20 15:12:54',0),
	(20180209085053,'VendorAssessmentParentMigration','2018-03-20 15:12:54','2018-03-20 15:12:54',0),
	(20180209140231,'RiskAppetiteColorPicker','2018-03-20 15:12:54','2018-03-20 15:12:54',0),
	(20180212144357,'VendorAssessmentFeedbackCompletedMigration','2018-03-20 15:12:54','2018-03-20 15:12:54',0),
	(20180214132429,'AddColumnsToRiskExceptionsTable','2018-03-20 15:12:54','2018-03-20 15:12:55',0),
	(20180214144524,'ComplianceAnalysisFindingExpiredStatus','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180214162750,'RiskAppetiteColorPicker2','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180215031921,'AddColumnsToComplianceExceptionsTable','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180215033742,'AddColumnsToPolicyExceptionsTable','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180215141504,'VendorAssessmentFindingsMigration','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180216111645,'VendorAssessmentFindingsQuestionsMigration','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180219143852,'VendorAssessmentQuestionWidgetMigration','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180221151936,'VendorAssessmentFeedbackBlockMigration','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180223163448,'VendorAssessmentVisualisationMigration','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180226180509,'ReorganizeSettingGroups','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180227100404,'CronErrorMessage','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180228000000,'Release49','2018-03-20 15:12:55','2018-03-20 15:12:55',0),
	(20180228000001,'Release50','2018-03-20 15:12:55','2018-03-20 15:12:56',0),
	(20180301000000,'RemoveGroupIdFromUsers2','2018-03-20 15:12:56','2018-03-20 15:12:56',0),
	(20180301000001,'Release51','2018-03-20 15:12:56','2018-03-20 15:12:56',0),
	(20180305183613,'VendorAssessmentFindingCloseDate','2018-03-20 15:12:56','2018-03-20 15:12:56',0),
	(20180309000001,'Release52','2018-03-20 15:12:56','2018-03-20 15:12:56',0);
ALTER TABLE `phinxlog` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `policy_exceptions` WRITE;
ALTER TABLE `policy_exceptions` DISABLE KEYS;
ALTER TABLE `policy_exceptions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `policy_exception_classifications` WRITE;
ALTER TABLE `policy_exception_classifications` DISABLE KEYS;
ALTER TABLE `policy_exception_classifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `policy_exceptions_security_policies` WRITE;
ALTER TABLE `policy_exceptions_security_policies` DISABLE KEYS;
ALTER TABLE `policy_exceptions_security_policies` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `policy_exceptions_third_parties` WRITE;
ALTER TABLE `policy_exceptions_third_parties` DISABLE KEYS;
ALTER TABLE `policy_exceptions_third_parties` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `policy_users` WRITE;
ALTER TABLE `policy_users` DISABLE KEYS;
ALTER TABLE `policy_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `processes` WRITE;
ALTER TABLE `processes` DISABLE KEYS;
ALTER TABLE `processes` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `program_issues` WRITE;
ALTER TABLE `program_issues` DISABLE KEYS;
ALTER TABLE `program_issues` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `program_issue_types` WRITE;
ALTER TABLE `program_issue_types` DISABLE KEYS;
ALTER TABLE `program_issue_types` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `program_scopes` WRITE;
ALTER TABLE `program_scopes` DISABLE KEYS;
ALTER TABLE `program_scopes` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `project_achievements` WRITE;
ALTER TABLE `project_achievements` DISABLE KEYS;
ALTER TABLE `project_achievements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `project_expenses` WRITE;
ALTER TABLE `project_expenses` DISABLE KEYS;
ALTER TABLE `project_expenses` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `project_overtime_graphs` WRITE;
ALTER TABLE `project_overtime_graphs` DISABLE KEYS;
ALTER TABLE `project_overtime_graphs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `project_statuses` WRITE;
ALTER TABLE `project_statuses` DISABLE KEYS;
INSERT INTO `project_statuses` (`id`, `name`) VALUES 
	(1,'Planned'),
	(2,'Ongoing'),
	(3,'Completed');
ALTER TABLE `project_statuses` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `projects` WRITE;
ALTER TABLE `projects` DISABLE KEYS;
ALTER TABLE `projects` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `projects_risks` WRITE;
ALTER TABLE `projects_risks` DISABLE KEYS;
ALTER TABLE `projects_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `projects_security_policies` WRITE;
ALTER TABLE `projects_security_policies` DISABLE KEYS;
ALTER TABLE `projects_security_policies` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `projects_security_service_audit_improvements` WRITE;
ALTER TABLE `projects_security_service_audit_improvements` DISABLE KEYS;
ALTER TABLE `projects_security_service_audit_improvements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_services` WRITE;
ALTER TABLE `security_services` DISABLE KEYS;
ALTER TABLE `security_services` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `projects_security_services` WRITE;
ALTER TABLE `projects_security_services` DISABLE KEYS;
ALTER TABLE `projects_security_services` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `projects_third_party_risks` WRITE;
ALTER TABLE `projects_third_party_risks` DISABLE KEYS;
ALTER TABLE `projects_third_party_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `queue` WRITE;
ALTER TABLE `queue` DISABLE KEYS;
ALTER TABLE `queue` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `reviews` WRITE;
ALTER TABLE `reviews` DISABLE KEYS;
ALTER TABLE `reviews` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_appetite_thresholds` WRITE;
ALTER TABLE `risk_appetite_thresholds` DISABLE KEYS;
ALTER TABLE `risk_appetite_thresholds` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_appetite_threshold_risk_classifications` WRITE;
ALTER TABLE `risk_appetite_threshold_risk_classifications` DISABLE KEYS;
ALTER TABLE `risk_appetite_threshold_risk_classifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_appetite_thresholds_risks` WRITE;
ALTER TABLE `risk_appetite_thresholds_risks` DISABLE KEYS;
ALTER TABLE `risk_appetite_thresholds_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_appetites` WRITE;
ALTER TABLE `risk_appetites` DISABLE KEYS;
INSERT INTO `risk_appetites` (`id`, `method`, `modified`) VALUES 
	(1,0,'2018-03-20 15:11:49');
ALTER TABLE `risk_appetites` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_appetites_risk_classification_types` WRITE;
ALTER TABLE `risk_appetites_risk_classification_types` DISABLE KEYS;
ALTER TABLE `risk_appetites_risk_classification_types` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_calculations` WRITE;
ALTER TABLE `risk_calculations` DISABLE KEYS;
INSERT INTO `risk_calculations` (`id`, `model`, `method`, `modified`) VALUES 
	(1,'Risk','eramba','2016-11-18 14:38:23'),
	(2,'ThirdPartyRisk','eramba','2016-11-18 14:38:23'),
	(3,'BusinessContinuity','eramba','2016-11-18 14:38:23');
ALTER TABLE `risk_calculations` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_calculation_values` WRITE;
ALTER TABLE `risk_calculation_values` DISABLE KEYS;
ALTER TABLE `risk_calculation_values` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_classification_types` WRITE;
ALTER TABLE `risk_classification_types` DISABLE KEYS;
ALTER TABLE `risk_classification_types` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_classifications` WRITE;
ALTER TABLE `risk_classifications` DISABLE KEYS;
ALTER TABLE `risk_classifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_classifications_risks` WRITE;
ALTER TABLE `risk_classifications_risks` DISABLE KEYS;
ALTER TABLE `risk_classifications_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_classifications_third_party_risks` WRITE;
ALTER TABLE `risk_classifications_third_party_risks` DISABLE KEYS;
ALTER TABLE `risk_classifications_third_party_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_exceptions` WRITE;
ALTER TABLE `risk_exceptions` DISABLE KEYS;
ALTER TABLE `risk_exceptions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risks` WRITE;
ALTER TABLE `risks` DISABLE KEYS;
ALTER TABLE `risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_exceptions_risks` WRITE;
ALTER TABLE `risk_exceptions_risks` DISABLE KEYS;
ALTER TABLE `risk_exceptions_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_exceptions_third_party_risks` WRITE;
ALTER TABLE `risk_exceptions_third_party_risks` DISABLE KEYS;
ALTER TABLE `risk_exceptions_third_party_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_mitigation_strategies` WRITE;
ALTER TABLE `risk_mitigation_strategies` DISABLE KEYS;
INSERT INTO `risk_mitigation_strategies` (`id`, `name`) VALUES 
	(1,'Accept'),
	(2,'Avoid'),
	(3,'Mitigate'),
	(4,'Transfer');
ALTER TABLE `risk_mitigation_strategies` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risk_overtime_graphs` WRITE;
ALTER TABLE `risk_overtime_graphs` DISABLE KEYS;
ALTER TABLE `risk_overtime_graphs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risks_security_incidents` WRITE;
ALTER TABLE `risks_security_incidents` DISABLE KEYS;
ALTER TABLE `risks_security_incidents` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risks_security_policies` WRITE;
ALTER TABLE `risks_security_policies` DISABLE KEYS;
ALTER TABLE `risks_security_policies` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risks_security_services` WRITE;
ALTER TABLE `risks_security_services` DISABLE KEYS;
ALTER TABLE `risks_security_services` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risks_threats` WRITE;
ALTER TABLE `risks_threats` DISABLE KEYS;
ALTER TABLE `risks_threats` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `risks_vulnerabilities` WRITE;
ALTER TABLE `risks_vulnerabilities` DISABLE KEYS;
ALTER TABLE `risks_vulnerabilities` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `schema_migrations` WRITE;
ALTER TABLE `schema_migrations` DISABLE KEYS;
INSERT INTO `schema_migrations` (`id`, `class`, `type`, `created`) VALUES 
	(1,'InitMigrations','Migrations','2016-01-17 20:45:25'),
	(2,'ConvertVersionToClassNames','Migrations','2016-01-17 20:45:25'),
	(3,'IncreaseClassNameLength','Migrations','2016-01-17 20:45:25'),
	(4,'E101000','app','2016-01-17 20:47:16'),
	(5,'E101001','app','2016-11-18 14:34:44'),
	(6,'E101002','app','2016-11-18 14:38:23'),
	(7,'E101003','app','2016-11-18 14:39:17'),
	(8,'E101004','app','2016-11-18 14:39:23'),
	(9,'E101005','app','2016-11-18 14:40:22'),
	(10,'E101006','app','2016-11-18 14:40:47'),
	(11,'E101007','app','2016-11-18 14:42:46'),
	(12,'E101008','app','2016-11-18 14:47:11'),
	(13,'E101009','app','2016-11-18 14:48:32'),
	(14,'E101010','app','2017-02-22 21:32:29'),
	(15,'E101011','app','2017-02-22 21:32:35'),
	(16,'E101012','app','2017-02-22 21:32:37'),
	(17,'E101013','app','2017-02-22 21:32:39'),
	(18,'E101014','app','2017-02-22 21:32:39'),
	(19,'E101015','app','2017-02-22 21:32:40'),
	(20,'E101016','app','2017-02-22 21:32:40');
ALTER TABLE `schema_migrations` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `scopes` WRITE;
ALTER TABLE `scopes` DISABLE KEYS;
ALTER TABLE `scopes` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `sections` WRITE;
ALTER TABLE `sections` DISABLE KEYS;
ALTER TABLE `sections` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_incident_classifications` WRITE;
ALTER TABLE `security_incident_classifications` DISABLE KEYS;
ALTER TABLE `security_incident_classifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_incident_stages` WRITE;
ALTER TABLE `security_incident_stages` DISABLE KEYS;
ALTER TABLE `security_incident_stages` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_incident_stages_security_incidents` WRITE;
ALTER TABLE `security_incident_stages_security_incidents` DISABLE KEYS;
ALTER TABLE `security_incident_stages_security_incidents` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_incident_statuses` WRITE;
ALTER TABLE `security_incident_statuses` DISABLE KEYS;
INSERT INTO `security_incident_statuses` (`id`, `name`) VALUES 
	(2,'Ongoing'),
	(3,'Closed');
ALTER TABLE `security_incident_statuses` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_incidents` WRITE;
ALTER TABLE `security_incidents` DISABLE KEYS;
ALTER TABLE `security_incidents` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_incidents_security_service_audit_improvements` WRITE;
ALTER TABLE `security_incidents_security_service_audit_improvements` DISABLE KEYS;
ALTER TABLE `security_incidents_security_service_audit_improvements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_incidents_security_services` WRITE;
ALTER TABLE `security_incidents_security_services` DISABLE KEYS;
ALTER TABLE `security_incidents_security_services` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_incidents_third_parties` WRITE;
ALTER TABLE `security_incidents_third_parties` DISABLE KEYS;
ALTER TABLE `security_incidents_third_parties` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_policies` WRITE;
ALTER TABLE `security_policies` DISABLE KEYS;
ALTER TABLE `security_policies` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_policies_related` WRITE;
ALTER TABLE `security_policies_related` DISABLE KEYS;
ALTER TABLE `security_policies_related` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_policies_security_services` WRITE;
ALTER TABLE `security_policies_security_services` DISABLE KEYS;
ALTER TABLE `security_policies_security_services` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_policy_document_types` WRITE;
ALTER TABLE `security_policy_document_types` DISABLE KEYS;
INSERT INTO `security_policy_document_types` (`id`, `name`, `editable`, `created`, `modified`) VALUES 
	(1,'Procedure',0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
	(2,'Standard',0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
	(3,'Policy',0,'0000-00-00 00:00:00','0000-00-00 00:00:00');
ALTER TABLE `security_policy_document_types` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_policy_ldap_groups` WRITE;
ALTER TABLE `security_policy_ldap_groups` DISABLE KEYS;
ALTER TABLE `security_policy_ldap_groups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_policy_reviews` WRITE;
ALTER TABLE `security_policy_reviews` DISABLE KEYS;
ALTER TABLE `security_policy_reviews` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_service_audit_dates` WRITE;
ALTER TABLE `security_service_audit_dates` DISABLE KEYS;
ALTER TABLE `security_service_audit_dates` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_service_audits` WRITE;
ALTER TABLE `security_service_audits` DISABLE KEYS;
ALTER TABLE `security_service_audits` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_service_audit_improvements` WRITE;
ALTER TABLE `security_service_audit_improvements` DISABLE KEYS;
ALTER TABLE `security_service_audit_improvements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_service_classifications` WRITE;
ALTER TABLE `security_service_classifications` DISABLE KEYS;
ALTER TABLE `security_service_classifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_service_maintenance_dates` WRITE;
ALTER TABLE `security_service_maintenance_dates` DISABLE KEYS;
ALTER TABLE `security_service_maintenance_dates` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_service_maintenances` WRITE;
ALTER TABLE `security_service_maintenances` DISABLE KEYS;
ALTER TABLE `security_service_maintenances` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_service_types` WRITE;
ALTER TABLE `security_service_types` DISABLE KEYS;
INSERT INTO `security_service_types` (`id`, `name`) VALUES 
	(2,'Design'),
	(4,'Production');
ALTER TABLE `security_service_types` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_services_service_contracts` WRITE;
ALTER TABLE `security_services_service_contracts` DISABLE KEYS;
ALTER TABLE `security_services_service_contracts` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `security_services_third_party_risks` WRITE;
ALTER TABLE `security_services_third_party_risks` DISABLE KEYS;
ALTER TABLE `security_services_third_party_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `service_classifications` WRITE;
ALTER TABLE `service_classifications` DISABLE KEYS;
ALTER TABLE `service_classifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `service_contracts` WRITE;
ALTER TABLE `service_contracts` DISABLE KEYS;
ALTER TABLE `service_contracts` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `setting_groups` WRITE;
ALTER TABLE `setting_groups` DISABLE KEYS;
INSERT INTO `setting_groups` (`id`, `slug`, `parent_slug`, `name`, `icon_code`, `notes`, `url`, `hidden`, `order`) VALUES 
	(1,'ACCESSLST','ACCESSMGT','Access Lists',NULL,NULL,'{"controller":"admin", "action":"acl", "0" :"aros", "1":"ajax_role_permissions"}',0,0),
	(2,'ACCESSMGT',NULL,'Access Management','icon-cog',NULL,NULL,0,0),
	(3,'AUTH','ACCESSMGT','Authentication ',NULL,NULL,'{"controller":"ldapConnectors","action":"authentication"}',0,0),
	(4,'BANNER','SEC','Banners',NULL,NULL,NULL,1,0),
	(5,'BAR','DB','Backup & Restore',NULL,NULL,'{"controller":"backupRestore","action":"index", "plugin":"backupRestore"}',0,0),
	(6,'BFP','SEC','Brute Force Protection',NULL,'This setting allows you to protect the login page of eramba from being brute-force attacked.',NULL,0,0),
	(7,'CUE','LOC','Currency',NULL,NULL,NULL,0,0),
	(8,'DASH',NULL,'Dashboard','icon-cog',NULL,NULL,0,0),
	(9,'DASHRESET','DASH','Reset Dashboards',NULL,NULL,'{"controller":"settings","action":"resetDashboards"}',0,0),
	(10,'DB',NULL,'Database','icon-cog',NULL,NULL,0,0),
	(11,'DBCNF','DB','Database Configurations',NULL,NULL,NULL,1,0),
	(12,'DBRESET','DB','Reset Database',NULL,NULL,'{"controller":"settings","action":"resetDatabase"}',0,0),
	(13,'DEBUG',NULL,'Debug Settings and Logs','icon-cog',NULL,NULL,0,0),
	(14,'DEBUGCFG','DEBUG','Debug Config',NULL,NULL,NULL,0,0),
	(15,'ERRORLOG','DEBUG','Error Log',NULL,NULL,'{"controller":"settings","action":"logs", "0":"error"}',0,0),
	(16,'GROUP','ACCESSMGT','Groups ',NULL,NULL,'{"controller":"groups","action":"index"}',0,0),
	(17,'LDAP','ACCESSMGT','LDAP Connectors',NULL,NULL,'{"controller":"ldapConnectors","action":"index"}',0,0),
	(18,'LOC',NULL,'Localization','icon-cog',NULL,NULL,0,0),
	(19,'MAIL',NULL,'Mail','icon-cog',NULL,NULL,0,0),
	(20,'MAILCNF','MAIL','Mail Configurations',NULL,NULL,NULL,0,0),
	(21,'MAILLOG','DEBUG','Email Log',NULL,NULL,'{"controller":"settings","action":"logs", "0":"email"}',0,0),
	(22,'PRELOAD','DB','Pre-load the database with default databases',NULL,NULL,NULL,1,0),
	(23,'RISK',NULL,'Risk','icon-cog',NULL,NULL,1,0),
	(24,'RISKAPPETITE','RISK','Risk appetite',NULL,NULL,NULL,0,0),
	(25,'ROLES','ACCESSMGT','Roles',NULL,NULL,'{"controller":"scopes","action":"index"}',0,0),
	(26,'SEC',NULL,'Security','icon-cog',NULL,NULL,0,0),
	(27,'SECKEY','CRONJOBS','Crontab Security Key',NULL,NULL,NULL,0,0),
	(28,'USER','ACCESSMGT','User Management',NULL,NULL,'{"controller":"users","action":"index"}',0,0),
	(29,'CLRCACHE','DEBUG','Clear Cache',NULL,NULL,'{"controller":"settings","action":"deleteCache"}',0,0),
	(30,'CLRACLCACHE','DEBUG','Clear ACL Cache',NULL,NULL,'{"controller":"settings","action":"deleteCache", "0":"acl"}',1,0),
	(31,'LOGO','LOC','Custom Logo',NULL,NULL,'{"controller":"settings","action":"customLogo"}',0,0),
	(32,'HEALTH','SEC','System Health',NULL,NULL,'{"controller":"settings","action":"systemHealth"}',0,0),
	(33,'TZONE','LOC','Timezone',NULL,NULL,NULL,0,0),
	(34,'UPDATES','SEC','Updates',NULL,NULL,'{"controller":"updates","action":"index"}',0,0),
	(35,'NOTIFICATION','ACCESSMGT','Notifications',NULL,NULL,'{"controller":"notificationSystem","action":"listItems"}',0,0),
	(36,'CRON','CRONJOBS','Crontab History',NULL,NULL,'{"controller":"cron","action":"index"}',0,0),
	(37,'BACKUP','DB','Backup Configuration',NULL,NULL,NULL,0,2),
	(38,'QUEUE','MAIL','Emails In Queue',NULL,NULL,'{"controller":"queue", "action":"index", "?" :"advanced_filter=1"}',0,0),
	(39,'VISUALISATION','ACCESSMGT','Visualisation',NULL,NULL,'{"controller":"visualisationSettings","action":"index", "plugin":"visualisation"}',0,0),
	(40,'OAUTH','ACCESSMGT','OAuth Connectors',NULL,NULL,'{"controller":"oauthConnectors","action":"index"}',0,0),
	(41,'CRONJOBS',NULL,'Cron Jobs','icon-cog',NULL,NULL,0,0);
ALTER TABLE `setting_groups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `settings` WRITE;
ALTER TABLE `settings` DISABLE KEYS;
INSERT INTO `settings` (`id`, `active`, `name`, `variable`, `value`, `default_value`, `values`, `type`, `options`, `hidden`, `required`, `setting_group_slug`, `setting_type`, `order`, `modified`, `created`) VALUES 
	(2,1,'DB Schema Version','DB_SCHEMA_VERSION','c1.0.1.032',NULL,NULL,'text',NULL,1,0,NULL,'constant',0,'2017-02-22 21:32:39','2015-12-19 00:00:00'),
	(3,1,'Client ID','CLIENT_ID',NULL,NULL,NULL,'text',NULL,1,0,NULL,'constant',0,'2016-11-18 14:37:22','2015-12-19 00:00:00'),
	(4,1,'Bruteforce wrong logins','BRUTEFORCE_WRONG_LOGINS','3',NULL,NULL,'number','{"min":1,"max":10,"step":1}',0,0,'BFP','constant',0,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(5,1,'Bruteforce second ago','BRUTEFORCE_SECONDS_AGO','60',NULL,NULL,'number','{"min":10,"max":120,"step":1}',0,0,'BFP','constant',0,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(10,1,'Default currency','DEFAULT_CURRENCY','EUR',NULL,'configDefaultCurrency','select','{"AUD":"AUD","CAD":"CAD","USD":"USD","EUR":"EUR","GBP":"GBP","JPY":"JPY"}',0,0,'CUE','config',0,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(11,1,'Type','SMTP_USE','0',NULL,NULL,'select','{"0":"Mail","1":"SMTP"}',0,0,'MAILCNF','constant',0,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(12,1,'SMTP host','SMTP_HOST','',NULL,NULL,'text',NULL,0,0,'MAILCNF','constant',1,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(13,1,'SMTP user','SMTP_USER','',NULL,NULL,'text',NULL,0,0,'MAILCNF','constant',3,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(14,1,'SMTP password','SMTP_PWD','',NULL,NULL,'password',NULL,0,0,'MAILCNF','constant',4,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(15,1,'SMTP timeout','SMTP_TIMEOUT','60',NULL,NULL,'number','{"min":1,"max":120,"step":1}',0,0,'MAILCNF','constant',5,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(16,1,'SMTP port','SMTP_PORT','',NULL,NULL,'text',NULL,0,0,'MAILCNF','constant',6,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(18,1,'No reply Email','NO_REPLY_EMAIL','noreply@domain.org',NULL,NULL,'text',NULL,0,0,'MAILCNF','constant',7,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(19,1,'Cron security key','CRON_SECURITY_KEY','egkrjng328525798',NULL,NULL,'text',NULL,0,0,'SECKEY','constant',0,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(20,1,'Bruteforce ban from minutes','BRUTEFORCE_BAN_FOR_MINUTES','5',NULL,NULL,'number','{"min":1,"max":120,"step":1}',0,0,'BFP','constant',0,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(21,1,'Banners off','BANNERS_OFF','1',NULL,NULL,'checkbox',NULL,0,0,'BANNER','constant',0,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(22,1,'Debug','DEBUG','0',NULL,'configDebug','checkbox',NULL,0,0,'DEBUGCFG','config',0,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(23,1,'Email Debug','EMAIL_DEBUG','0',NULL,'configEmailDebug','checkbox',NULL,0,0,'DEBUGCFG','config',0,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(24,1,'Risk Appetite','RISK_APPETITE','1',NULL,NULL,'number','{"min":0,"max":999999,"step":1}',0,0,'RISKAPPETITE','constant',0,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(25,1,'Encryption','USE_SSL','0',NULL,NULL,'select','{"0":"No Encryption","1":"SSL","2":"TLS"}',0,0,'MAILCNF','constant',2,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(26,1,'Timezone','TIMEZONE',NULL,NULL,'configTimezone','select',NULL,0,0,'TZONE','config',0,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(27,1,'Backups Enabled','BACKUPS_ENABLED','0',NULL,NULL,'checkbox',NULL,0,0,'BACKUP','constant',0,'2017-02-22 21:32:29','2017-02-22 21:32:29'),
	(28,1,'Backup Day Period','BACKUP_DAY_PERIOD','1',NULL,NULL,'select','{"1":"Every day","2":"Every 2 days","3":"Every 3 days","4":"Every 4 days","5":"Every 5 days","6":"Every 6 days","7":"Every 7 days"}',0,0,'BACKUP','constant',0,'2017-02-22 21:32:29','2017-02-22 21:32:29'),
	(29,1,'Backup Files Limit','BACKUP_FILES_LIMIT','15',NULL,NULL,'select','{"1":"1","5":"5","10":"10","15":"15"}',0,0,'BACKUP','constant',0,'2017-02-22 21:32:29','2017-02-22 21:32:29'),
	(30,1,'Name','EMAIL_NAME','',NULL,NULL,'text',NULL,0,0,'MAILCNF','constant',6,'2017-02-22 21:32:29','2017-02-22 21:32:29'),
	(31,1,'Risk Granularity','RISK_GRANULARITY','10',NULL,NULL,'number',NULL,0,0,NULL,'constant',0,'2017-04-19 00:00:00','2017-04-19 00:00:00'),
	(32,1,'Email Queue Throughput','QUEUE_TRANSPORT_LIMIT','15',NULL,NULL,'number',NULL,0,0,'MAILCNF','constant',8,'2018-03-20 15:11:50','2018-03-20 15:11:50');
ALTER TABLE `settings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `status_triggers` WRITE;
ALTER TABLE `status_triggers` DISABLE KEYS;
ALTER TABLE `status_triggers` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `suggestions` WRITE;
ALTER TABLE `suggestions` DISABLE KEYS;
ALTER TABLE `suggestions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `system_logs` WRITE;
ALTER TABLE `system_logs` DISABLE KEYS;
ALTER TABLE `system_logs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `system_records` WRITE;
ALTER TABLE `system_records` DISABLE KEYS;
ALTER TABLE `system_records` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `tags` WRITE;
ALTER TABLE `tags` DISABLE KEYS;
ALTER TABLE `tags` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `team_roles` WRITE;
ALTER TABLE `team_roles` DISABLE KEYS;
ALTER TABLE `team_roles` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `third_parties` WRITE;
ALTER TABLE `third_parties` DISABLE KEYS;
INSERT INTO `third_parties` (`id`, `name`, `description`, `third_party_type_id`, `security_incident_count`, `security_incident_open_count`, `service_contract_count`, `workflow_status`, `workflow_owner_id`, `_hidden`, `created`, `modified`, `deleted`, `deleted_date`) VALUES 
	(1,'None','',NULL,0,0,0,0,NULL,1,'2015-12-19 00:00:00','2015-12-19 00:00:00',0,NULL);
ALTER TABLE `third_parties` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `third_parties_third_party_risks` WRITE;
ALTER TABLE `third_parties_third_party_risks` DISABLE KEYS;
ALTER TABLE `third_parties_third_party_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `third_parties_vendor_assessments` WRITE;
ALTER TABLE `third_parties_vendor_assessments` DISABLE KEYS;
ALTER TABLE `third_parties_vendor_assessments` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `third_party_audit_overtime_graphs` WRITE;
ALTER TABLE `third_party_audit_overtime_graphs` DISABLE KEYS;
ALTER TABLE `third_party_audit_overtime_graphs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `third_party_incident_overtime_graphs` WRITE;
ALTER TABLE `third_party_incident_overtime_graphs` DISABLE KEYS;
ALTER TABLE `third_party_incident_overtime_graphs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `third_party_overtime_graphs` WRITE;
ALTER TABLE `third_party_overtime_graphs` DISABLE KEYS;
ALTER TABLE `third_party_overtime_graphs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `third_party_risk_overtime_graphs` WRITE;
ALTER TABLE `third_party_risk_overtime_graphs` DISABLE KEYS;
ALTER TABLE `third_party_risk_overtime_graphs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `third_party_risks` WRITE;
ALTER TABLE `third_party_risks` DISABLE KEYS;
ALTER TABLE `third_party_risks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `third_party_risks_threats` WRITE;
ALTER TABLE `third_party_risks_threats` DISABLE KEYS;
ALTER TABLE `third_party_risks_threats` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `third_party_risks_vulnerabilities` WRITE;
ALTER TABLE `third_party_risks_vulnerabilities` DISABLE KEYS;
ALTER TABLE `third_party_risks_vulnerabilities` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `third_party_types` WRITE;
ALTER TABLE `third_party_types` DISABLE KEYS;
INSERT INTO `third_party_types` (`id`, `name`) VALUES 
	(1,'Customers'),
	(2,'Suppliers'),
	(3,'Regulators');
ALTER TABLE `third_party_types` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `threats` WRITE;
ALTER TABLE `threats` DISABLE KEYS;
INSERT INTO `threats` (`id`, `name`) VALUES 
	(1,'Intentional Complot'),
	(2,'Pandemic Issues'),
	(3,'Strikes'),
	(4,'Unintentional Loss of Equipment'),
	(5,'Intentional Theft of Equipment'),
	(6,'Unintentional Loss of Information'),
	(7,'Intentional Theft of Information'),
	(8,'Remote Exploit'),
	(9,'Abuse of Service'),
	(10,'Web Application Attack'),
	(11,'Network Attack'),
	(12,'Sniffing'),
	(13,'Phishing'),
	(14,'Malware/Trojan Distribution'),
	(15,'Viruses'),
	(16,'Copyright Infrigment'),
	(17,'Social Engineering'),
	(18,'Natural Disasters'),
	(19,'Fire'),
	(20,'Flooding'),
	(21,'Ilegal Infiltration'),
	(22,'DOS Attack'),
	(23,'Brute Force Attack'),
	(24,'Tampering'),
	(25,'Tunneling'),
	(26,'Man in the Middle'),
	(27,'Fraud'),
	(28,'Other'),
	(30,'Terrorist Attack'),
	(31,'Floodings'),
	(32,'Third Party Intrusion'),
	(33,'Abuse of Priviledge'),
	(34,'Unauthorised records'),
	(35,'Spying');
ALTER TABLE `threats` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `tickets` WRITE;
ALTER TABLE `tickets` DISABLE KEYS;
ALTER TABLE `tickets` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `user_bans` WRITE;
ALTER TABLE `user_bans` DISABLE KEYS;
ALTER TABLE `user_bans` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `user_fields_groups` WRITE;
ALTER TABLE `user_fields_groups` DISABLE KEYS;
ALTER TABLE `user_fields_groups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users` WRITE;
ALTER TABLE `users` DISABLE KEYS;
INSERT INTO `users` (`id`, `name`, `surname`, `email`, `login`, `password`, `language`, `status`, `blocked`, `local_account`, `api_allow`, `created`, `modified`) VALUES 
	(1,'Admin','Admin','admin@eramba.org','admin','$2a$10$WhVO3Jj4nFhCj6bToUOztun/oceKY6rT2db2bu430dW5/lU0w9KJ.','eng',1,0,1,0,'2013-10-14 16:19:04','2015-09-11 18:19:52');
ALTER TABLE `users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `user_fields_users` WRITE;
ALTER TABLE `user_fields_users` DISABLE KEYS;
ALTER TABLE `user_fields_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users_groups` WRITE;
ALTER TABLE `users_groups` DISABLE KEYS;
INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES 
	(1,1,10);
ALTER TABLE `users_groups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users_vendor_assessment_findings` WRITE;
ALTER TABLE `users_vendor_assessment_findings` DISABLE KEYS;
ALTER TABLE `users_vendor_assessment_findings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users_vendor_assessments` WRITE;
ALTER TABLE `users_vendor_assessments` DISABLE KEYS;
ALTER TABLE `users_vendor_assessments` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `vendor_assessment_feedbacks` WRITE;
ALTER TABLE `vendor_assessment_feedbacks` DISABLE KEYS;
ALTER TABLE `vendor_assessment_feedbacks` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `vendor_assessment_files` WRITE;
ALTER TABLE `vendor_assessment_files` DISABLE KEYS;
ALTER TABLE `vendor_assessment_files` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `vendor_assessment_findings` WRITE;
ALTER TABLE `vendor_assessment_findings` DISABLE KEYS;
ALTER TABLE `vendor_assessment_findings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `vendor_assessment_findings_questions` WRITE;
ALTER TABLE `vendor_assessment_findings_questions` DISABLE KEYS;
ALTER TABLE `vendor_assessment_findings_questions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `vendor_assessment_options` WRITE;
ALTER TABLE `vendor_assessment_options` DISABLE KEYS;
ALTER TABLE `vendor_assessment_options` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `vendor_assessment_questionnaires` WRITE;
ALTER TABLE `vendor_assessment_questionnaires` DISABLE KEYS;
ALTER TABLE `vendor_assessment_questionnaires` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `vendor_assessment_questions` WRITE;
ALTER TABLE `vendor_assessment_questions` DISABLE KEYS;
ALTER TABLE `vendor_assessment_questions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `vendor_assessments` WRITE;
ALTER TABLE `vendor_assessments` DISABLE KEYS;
ALTER TABLE `vendor_assessments` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `visualisation_settings` WRITE;
ALTER TABLE `visualisation_settings` DISABLE KEYS;
INSERT INTO `visualisation_settings` (`id`, `model`, `status`, `order`) VALUES 
	(1,'Asset',1,1),
	(2,'AssetReview',1,2),
	(3,'Risk',1,10),
	(4,'ThirdPartyRisk',1,17),
	(5,'BusinessContinuity',1,3),
	(6,'RiskReview',1,11),
	(7,'ThirdPartyRiskReview',1,18),
	(8,'BusinessContinuityReview',1,4),
	(9,'SecurityPolicy',1,12),
	(10,'SecurityPolicyReview',1,13),
	(11,'SecurityService',1,14),
	(12,'SecurityServiceAudit',1,15),
	(13,'SecurityServiceMaintenance',1,16),
	(14,'ComplianceException',1,8),
	(16,'ComplianceAudit',1,6),
	(17,'ComplianceAnalysisFinding',1,5),
	(18,'ComplianceAuditSetting',1,7),
	(19,'ComplianceFinding',1,9),
	(20,'BusinessUnit',1,999),
	(21,'AwarenessProgram',1,999),
	(22,'BusinessContinuityPlan',1,999),
	(23,'BusinessContinuityPlanAudit',1,999),
	(24,'DataAssetInstance',1,999),
	(25,'DataAsset',1,999),
	(26,'Goal',1,999),
	(27,'Legal',1,999),
	(28,'PolicyException',1,999),
	(29,'Process',1,999),
	(30,'ProgramIssue',1,999),
	(31,'ProgramScope',1,999),
	(32,'Project',1,999),
	(33,'ProjectAchievement',1,999),
	(34,'ProjectExpense',1,999),
	(35,'RiskException',1,999),
	(36,'SecurityIncident',1,999),
	(37,'ThirdParty',1,999),
	(38,'VendorAssessment',1,999),
	(39,'VendorAssessmentFinding',1,999);
ALTER TABLE `visualisation_settings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `visualisation_settings_groups` WRITE;
ALTER TABLE `visualisation_settings_groups` DISABLE KEYS;
ALTER TABLE `visualisation_settings_groups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `visualisation_settings_users` WRITE;
ALTER TABLE `visualisation_settings_users` DISABLE KEYS;
ALTER TABLE `visualisation_settings_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `visualisation_share` WRITE;
ALTER TABLE `visualisation_share` DISABLE KEYS;
ALTER TABLE `visualisation_share` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `visualisation_share_groups` WRITE;
ALTER TABLE `visualisation_share_groups` DISABLE KEYS;
ALTER TABLE `visualisation_share_groups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `visualisation_share_users` WRITE;
ALTER TABLE `visualisation_share_users` DISABLE KEYS;
ALTER TABLE `visualisation_share_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `vulnerabilities` WRITE;
ALTER TABLE `vulnerabilities` DISABLE KEYS;
INSERT INTO `vulnerabilities` (`id`, `name`) VALUES 
	(1,'Lack of Information'),
	(2,'Lack of Integrity Checks'),
	(3,'Lack of Logs'),
	(4,'No Change Management'),
	(5,'Weak CheckOut Procedures'),
	(6,'Supplier Failure'),
	(7,'Lack of alternative Power Sources'),
	(8,'Lack of Physical Guards'),
	(9,'Lack of Patching'),
	(10,'Web Application Vulnerabilities'),
	(11,'Lack of CCTV'),
	(12,'Lack of Movement Sensors'),
	(13,'Lack of Procedures'),
	(14,'Lack of Network Controls'),
	(15,'Lack of Strong Authentication'),
	(16,'Lack of Encryption in Motion'),
	(17,'Lack of Encryption at Rest'),
	(18,'Creeping Accounts'),
	(19,'Hardware Malfunction'),
	(20,'Software Malfunction'),
	(21,'Lack of Fire Extinguishers'),
	(22,'Lack of alternative exit doors'),
	(23,'Weak Passwords'),
	(24,'Weak Awareness'),
	(25,'Missing Configuration Standards'),
	(26,'Open Network Ports'),
	(27,'Reputational Issues'),
	(28,'Seismic Areas'),
	(29,'Prone to Natural Disasters Area'),
	(30,'Flood Prone Areas'),
	(31,'Other'),
	(32,'Unprotected Network'),
	(33,'Cabling Unsecured'),
	(34,'Weak Software Development Procedures');
ALTER TABLE `vulnerabilities` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `wf_access_models` WRITE;
ALTER TABLE `wf_access_models` DISABLE KEYS;
ALTER TABLE `wf_access_models` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `wf_access_types` WRITE;
ALTER TABLE `wf_access_types` DISABLE KEYS;
ALTER TABLE `wf_access_types` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `wf_accesses` WRITE;
ALTER TABLE `wf_accesses` DISABLE KEYS;
ALTER TABLE `wf_accesses` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `wf_stages` WRITE;
ALTER TABLE `wf_stages` DISABLE KEYS;
ALTER TABLE `wf_stages` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `wf_instance_approvals` WRITE;
ALTER TABLE `wf_instance_approvals` DISABLE KEYS;
ALTER TABLE `wf_instance_approvals` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `wf_instances` WRITE;
ALTER TABLE `wf_instances` DISABLE KEYS;
ALTER TABLE `wf_instances` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `wf_instance_logs` WRITE;
ALTER TABLE `wf_instance_logs` DISABLE KEYS;
ALTER TABLE `wf_instance_logs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `wf_stage_steps` WRITE;
ALTER TABLE `wf_stage_steps` DISABLE KEYS;
ALTER TABLE `wf_stage_steps` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `wf_instance_requests` WRITE;
ALTER TABLE `wf_instance_requests` DISABLE KEYS;
ALTER TABLE `wf_instance_requests` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `wf_settings` WRITE;
ALTER TABLE `wf_settings` DISABLE KEYS;
ALTER TABLE `wf_settings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `wf_stage_step_conditions` WRITE;
ALTER TABLE `wf_stage_step_conditions` DISABLE KEYS;
ALTER TABLE `wf_stage_step_conditions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflow_acknowledgements` WRITE;
ALTER TABLE `workflow_acknowledgements` DISABLE KEYS;
ALTER TABLE `workflow_acknowledgements` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflow_items` WRITE;
ALTER TABLE `workflow_items` DISABLE KEYS;
ALTER TABLE `workflow_items` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflow_logs` WRITE;
ALTER TABLE `workflow_logs` DISABLE KEYS;
ALTER TABLE `workflow_logs` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflows` WRITE;
ALTER TABLE `workflows` DISABLE KEYS;
INSERT INTO `workflows` (`id`, `model`, `name`, `notifications`, `parent_id`, `created`, `modified`) VALUES 
	(1,'SecurityIncident','Security Incidents',1,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(2,'BusinessUnit','Business Units',1,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(3,'Legal','Legals',1,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(4,'ThirdParty','Third Parties',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(5,'Process','Processes',0,2,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(6,'Asset','Assets',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(7,'AssetClassification','Asset Classifications',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(8,'AssetLabel','Asset Labeling',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(9,'RiskClassification','Risk Classifications',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(10,'RiskException','Risk Exceptions',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(11,'Risk','Risks',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(12,'ThirdPartyRisk','Third Party Risks',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(13,'BusinessContinuity','Business Continuities',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(14,'SecurityService','Security Services',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(15,'ServiceContract','Service Contracts',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(16,'ServiceClassification','Service Classifications',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(17,'BusinessContinuityPlan','Business Continuity Plans',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(18,'SecurityPolicy','Security Policies',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(19,'PolicyException','Policy Exceptions',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(20,'Project','Projects',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(22,'ProjectAchievement','Project Achievements',0,20,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(23,'ProjectExpense','Project Expenses',0,20,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(24,'SecurityServiceAudit','Security Service Audits',0,14,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(25,'SecurityServiceMaintenance','Security Service Maintenances',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(26,'CompliancePackageItem','Compliance Package Items',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(27,'DataAsset','Data Assets',0,6,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(28,'ComplianceManagement','Compliance Managements',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(29,'BusinessContinuityPlanAudit','Business Continuity Plan Audits',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(31,'BusinessContinuityTask','Business Continuity Tasks',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(32,'LdapConnector','LDAP Connectors',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(33,'SecurityPolicyReview','Security Policy Reviews',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(34,'RiskReview','Risk Reviews',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(35,'ThirdPartyRiskReview','ThirdPartyRisk Reviews',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(36,'BusinessContinuityReview','BusinessContinuity Reviews',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(37,'AssetReview','Asset Reviews',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(38,'SecurityIncidentStage','Security Incident Stage',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(39,'SecurityIncidentStagesSecurityIncident','Security Incident Stages Security Incident',0,39,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(41,'AwarenessProgram','Awareness Programs',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(42,'ProgramScope','Scopes',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(43,'ProgramIssue','Issues',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(44,'TeamRole','Team Roles',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(45,'Goal','Goals',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(46,'GoalAudit','Goal Audits',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00'),
	(47,'SecurityServiceIssue','Security Service Issues',0,NULL,'2015-12-19 00:00:00','2015-12-19 00:00:00');
ALTER TABLE `workflows` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflows_all_approver_items` WRITE;
ALTER TABLE `workflows_all_approver_items` DISABLE KEYS;
ALTER TABLE `workflows_all_approver_items` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflows_all_validator_items` WRITE;
ALTER TABLE `workflows_all_validator_items` DISABLE KEYS;
ALTER TABLE `workflows_all_validator_items` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflows_approver_scopes` WRITE;
ALTER TABLE `workflows_approver_scopes` DISABLE KEYS;
ALTER TABLE `workflows_approver_scopes` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflows_approvers` WRITE;
ALTER TABLE `workflows_approvers` DISABLE KEYS;
ALTER TABLE `workflows_approvers` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflows_custom_approvers` WRITE;
ALTER TABLE `workflows_custom_approvers` DISABLE KEYS;
ALTER TABLE `workflows_custom_approvers` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflows_custom_validators` WRITE;
ALTER TABLE `workflows_custom_validators` DISABLE KEYS;
ALTER TABLE `workflows_custom_validators` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflows_validator_scopes` WRITE;
ALTER TABLE `workflows_validator_scopes` DISABLE KEYS;
ALTER TABLE `workflows_validator_scopes` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `workflows_validators` WRITE;
ALTER TABLE `workflows_validators` DISABLE KEYS;
ALTER TABLE `workflows_validators` ENABLE KEYS;
UNLOCK TABLES;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


