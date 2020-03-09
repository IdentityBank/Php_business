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
-- # MZ: Setup for RBAC (ang. role-based access control, kontrola dostępu oparta na rolach)
-- #############################################################################

-- # auth_item
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('rbac_manager', 2, 'Manage RBAC tasks and roles.', null, null, (select extract(epoch from now())), (select extract(epoch from now()))) ON CONFLICT ("name") DO UPDATE SET "description" = EXCLUDED.description, "updated_at" = (select extract(epoch from now()));
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('rbac_actions', 2, 'Manage RBAC actions.', null, null, (select extract(epoch from now())), (select extract(epoch from now()))) ON CONFLICT ("name") DO UPDATE SET "description" = EXCLUDED.description, "updated_at" = (select extract(epoch from now()));
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('user_manager_roles', 2, 'Manage User roles.', null, null, (select extract(epoch from now())), (select extract(epoch from now()))) ON CONFLICT ("name") DO UPDATE SET "description" = EXCLUDED.description, "updated_at" = (select extract(epoch from now()));

INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('upload_controller_action_index', 2, 'Allow upload.', null, null, (select extract(epoch from now())), (select extract(epoch from now()))) ON CONFLICT ("name") DO UPDATE SET "description" = EXCLUDED.description, "updated_at" = (select extract(epoch from now()));
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('action_contacts', 2, 'Manage Contacts.', null, null, (select extract(epoch from now())), (select extract(epoch from now()))) ON CONFLICT ("name") DO UPDATE SET "description" = EXCLUDED.description, "updated_at" = (select extract(epoch from now()));

INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('notifications', 2, 'Manage notifications.', null, null, (select extract(epoch from now())), (select extract(epoch from now()))) ON CONFLICT ("name") DO UPDATE SET "description" = EXCLUDED.description, "updated_at" = (select extract(epoch from now()));
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('notifications_update', 2, 'Update notifications.', null, null, (select extract(epoch from now())), (select extract(epoch from now()))) ON CONFLICT ("name") DO UPDATE SET "description" = EXCLUDED.description, "updated_at" = (select extract(epoch from now()));
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('notifications_delete', 2, 'Delete notifications.', null, null, (select extract(epoch from now())), (select extract(epoch from now()))) ON CONFLICT ("name") DO UPDATE SET "description" = EXCLUDED.description, "updated_at" = (select extract(epoch from now()));

INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('action_give_people_access', 2, 'Allow to initiate people sign up.', null, null, (select extract(epoch from now())), (select extract(epoch from now()))) ON CONFLICT ("name") DO UPDATE SET "description" = EXCLUDED.description, "updated_at" = (select extract(epoch from now()));

-- # auth_item_child
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_admin', 'rbac_manager') ON CONFLICT ON CONSTRAINT auth_item_child_pkey DO NOTHING;
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'rbac_actions') ON CONFLICT ON CONSTRAINT auth_item_child_pkey DO NOTHING;
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'user_manager_roles') ON CONFLICT ON CONSTRAINT auth_item_child_pkey DO NOTHING;

INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_admin', 'notifications_update') ON CONFLICT ON CONSTRAINT auth_item_child_pkey DO NOTHING;
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_admin', 'notifications_delete') ON CONFLICT ON CONSTRAINT auth_item_child_pkey DO NOTHING;
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'notifications') ON CONFLICT ON CONSTRAINT auth_item_child_pkey DO NOTHING;

INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_user', 'upload_controller_action_index') ON CONFLICT ON CONSTRAINT auth_item_child_pkey DO NOTHING;
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_user', 'action_contacts') ON CONFLICT ON CONSTRAINT auth_item_child_pkey DO NOTHING;
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_user', 'action_give_people_access') ON CONFLICT ON CONSTRAINT auth_item_child_pkey DO NOTHING;

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
