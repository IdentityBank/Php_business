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
-- # MZ: Initial setup for RBAC
-- #############################################################################

-- # auth_rule
INSERT INTO p57b_business.auth_rule (name, data, created_at, updated_at) VALUES ('isIdbEmail', 'O:26:"app\\rbac\\IdbStaffEmailRule":0:{};}', 1530781694, 1530781694);

-- # auth_item
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('idb_admin', 1, 'Administrate the idb business.', null, null, 1530725241, 1530725241);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('idb_staff', 1, 'Support for the idb business.', 'isIdbEmail', null, 1530726049, 1530726049);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('idb_user', 1, 'The idb business users.', null, null, 1530726293, 1530726293);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('password_policy_index', 2, 'Can list Password Policies.', null, null, 1530726718, 1530726718);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('password_policy_view', 2, 'Can view Password Policy.', null, null, 1530726786, 1530726786);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('password_policy_create', 2, 'Can create Password Policy.', null, null, 1530726860, 1530726860);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('password_policy_update', 2, 'Can update Password Policy.', null, null, 1530727056, 1530727056);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('password_policy_delete', 2, 'Can delete Password Policy.', null, null, 1530727125, 1530727125);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('user_manager_admin', 2, 'Can admin Users.', null, null, 1530812318, 1530812318);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('user_manager_search', 2, 'Can search Users.', null, null, 1530812328, 1530812328);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('user_manager_view', 2, 'Can view Users details.', null, null, 1530812330, 1530812330);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('user_manager_create', 2, 'Can create Users.', null, null, 1530812340, 1530812340);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('user_manager_update', 2, 'Can update Users details.', null, null, 1530812348, 1530812348);
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('user_manager_delete', 2, 'Can delete Users.', null, null, 1530812365, 1530812365);

-- # auth_item_child
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_admin', 'idb_staff');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'idb_user');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'password_policy_index');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'password_policy_view');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'password_policy_create');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'password_policy_update');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_admin', 'password_policy_delete');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_admin', 'user_manager_admin');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'user_manager_search');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'user_manager_view');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'user_manager_create');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_staff', 'user_manager_update');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_admin', 'user_manager_delete');

-- # password_policy
DELETE FROM p57b_business.password_policy WHERE "name" = 'default';
INSERT INTO  p57b_business.password_policy ("name","lowercase","uppercase","digit","special","special_chars_set","min_types","reuse_count","min_recovery_age","max_age","min_length","max_length","change_initial","level") VALUES ('default',4,3,3,2,'!@#$%^&*()',3,10,60,60,12,128,1,300);

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
