-- # * ********************************************************************* *
-- # *                                                                       *
-- # *   Business Portal                                                     *
-- # *   This file is part of business. This project may be found at:        *
-- # *   https://github.com/IdentityBank/Php_business.                       *
-- # *                                                                       *
-- # *   Copyright (C) 2020 by Identity Bank. All Rights Reserved.           *
-- # *   https://www.identitybank.eu - You belong to you                     *
-- # *                                                                       *
-- # *   This program is free software: you can redistribute it and/or       *
-- # *   modify it under the terms of the GNU Affero General Public          *
-- # *   License as published by the Free Software Foundation, either        *
-- # *   version 3 of the License, or (at your option) any later version.    *
-- # *                                                                       *
-- # *   This program is distributed in the hope that it will be useful,     *
-- # *   but WITHOUT ANY WARRANTY; without even the implied warranty of      *
-- # *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the        *
-- # *   GNU Affero General Public License for more details.                 *
-- # *                                                                       *
-- # *   You should have received a copy of the GNU Affero General Public    *
-- # *   License along with this program. If not, see                        *
-- # *   https://www.gnu.org/licenses/.                                      *
-- # *                                                                       *
-- # * ********************************************************************* *

-- #############################################################################
-- # DB migration file
-- #############################################################################

-- #############################################################################
-- # MZ: Roles for business portal
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.password_policy
-- # ---------------------------------------------------------------------- # --

-- # Replace idb_user role with idb_business

DELETE FROM p57b_business.auth_assignment WHERE item_name = 'idb_user';
DELETE FROM p57b_business.auth_item_child WHERE parent = 'idb_user';
DELETE FROM p57b_business.auth_item_child WHERE child = 'idb_user';
DELETE FROM p57b_business.auth_item WHERE name = 'idb_user';
DELETE FROM p57b_business.auth_item WHERE name = 'idb_business';
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('idb_business', 1, 'The idb business users.', null, null, 1530726293, 1530726293) ON CONFLICT DO NOTHING;
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('organization_billing', 1, 'The idb business users.', null, null, 1530726293, 1530726293) ON CONFLICT DO NOTHING;
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('organization_user', 1, 'The idb business users.', null, null, 1530726293, 1530726293) ON CONFLICT DO NOTHING;

-- # New actions for idb_business

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_organization_manager', 2, 'Access to manage organization', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_organization_billing_manager', 2, 'Access to manage billing', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_account_manager', 2, 'Access to manage organization accounts', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_database_manager', 2, 'Access to manage organization accounts', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_profile', 2, 'Access to user profile', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_profile_password', 2, 'Allow user to change password', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_idbdata', 2, 'Allow to manage customers data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_import', 2, 'Allow import data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_export', 2, 'Allow export data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_logs_used_data', 2, 'Allow to view used data logs', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_logs_change_request', 2, 'Allow to view change requests', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_b2p_messages', 2, 'Business to People portal contact', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('action_manage_users', 2, 'Manage organization users', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;

-- # Assign actions for idb_business

INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('organization_admin','action_organization_manager') ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('organization_admin','action_manage_users') ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('organization_billing','action_organization_billing_manager') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_business','action_database_manager') ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_business','action_profile') ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_business','action_profile_password') ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_business','action_idbdata') ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_business','action_import') ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_business','action_export') ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_business','action_logs_used_data') ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_business','action_logs_change_request') ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_business','action_give_people_access') ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_business','action_b2p_messages') ON CONFLICT DO NOTHING;

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
