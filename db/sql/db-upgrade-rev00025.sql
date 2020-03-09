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
-- # SR: Add tasks to behaviors
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.auth_item
-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('config_send_index', 2, 'Can view Index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','config_send_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('config_send_switcher', 2, 'Can view Switcher', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','config_send_switcher') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('config_send_savedata', 2, 'Can save data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','config_send_savedata') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('accman_index', 2, 'Can view Index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','accman_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('accman_create', 2, 'Can create account', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','accman_create') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('accman_delete', 2, 'Can delete account', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','accman_delete') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('dbman_index', 2, 'Can view Index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','dbman_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('dbman_createdb', 2, 'Can create database', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','dbman_createdb') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('dbman_created', 2, 'Can view if database created', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','dbman_created') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('dbman_select_user', 2, 'Can select user', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','dbman_select_user') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('dbman_role_database', 2, 'Can role database', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','dbman_role_database') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('dbman_assign_role', 2, 'Can assign role to database', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','dbman_assign_role') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('dbman_set_options', 2, 'Set options to metadata', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','dbman_set_options') ON CONFLICT DO NOTHING;


-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('userman_index', 2, 'Can view index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','userman_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('userman_form', 2, 'Can view form', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','userman_form') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('userman_roles', 2, 'Can view roles', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','userman_roles') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('userman_save', 2, 'Can save data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','userman_save') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('userman_assign_account', 2, 'Can assign account', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','userman_assign_account') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('userman_assign_database', 2, 'Can assign database', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','userman_assign_database') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('cr_index', 2, 'Can view Index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','cr_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('cr_reverse', 2, 'Can reverse', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','cr_reverse') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('cr_delete', 2, 'Can delete data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','cr_delete') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('cr_verify', 2, 'Can verify data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','cr_verify') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('contacts_details', 2, 'Can view details', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','contacts_details') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('contacts_checkmap', 2, 'Can check mapping', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','contacts_checkmap') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('contacts_access', 2, 'Can access', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','contacts_access') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('contacts_reset_search', 2, 'Can reset search', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','contacts_reset_search') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('contacts_people', 2, 'Can view People', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','contacts_people') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('contacts_signup', 2, 'Can start signup', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','contacts_signup') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('btpmessages_create', 2, 'Can create message', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','btpmessages_create') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('btpmessages_send', 2, 'Can send message', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','btpmessages_send') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('btpmessages_sent', 2, 'Can view info if message sent', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','btpmessages_sent') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('daac_index', 2, 'Can view index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','daac_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('daac_view', 2, 'Can view', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','daac_view') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('daac_create', 2, 'Can create', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','daac_create') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('daac_update', 2, 'Can update', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','daac_update') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('daac_delete', 2, 'Can delete', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','daac_delete') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('dcsc_index', 2, 'Can view Index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','dcsc_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('dcsc_create', 2, 'Can create Data Client Set', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','dcsc_create') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('data_sets_index', 2, 'Can view index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','data_sets_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('data_sets_view', 2, 'Can view', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','data_sets_view') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('data_sets_create', 2, 'Can create', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','data_sets_create') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('data_sets_update', 2, 'Can update', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','data_sets_update') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('data_sets_delete', 2, 'Can delete', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','data_sets_delete') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('data_types_index', 2, 'Can view index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','data_types_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('data_types_view', 2, 'Can view', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','data_types_view') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('data_types_create', 2, 'Can create', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','data_types_create') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('data_types_update', 2, 'Can update', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','data_types_update') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('data_types_delete', 2, 'Can delete', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','data_types_delete') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('billing_index', 2, 'Can view index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','billing_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('billing_payments', 2, 'Can manage payments', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','billing_payments') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('billing_logs', 2, 'Can view logs', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','billing_logs') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('billing_invoice', 2, 'Can view invoice', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','billing_invoice') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('logs_index', 2, 'Can view logs', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','logs_index') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('recovery_mfa_email', 2, 'Can verification email', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','recovery_mfa_email') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('recovery_mfa_sms', 2, 'Can verification sms', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','recovery_mfa_sms') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('recovery_pass_index', 2, 'Can view Index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','recovery_pass_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('recovery_pass_email', 2, 'Can verification email', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','recovery_pass_email') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('recovery_pass_sms', 2, 'Can verification sms', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','recovery_pass_sms') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('recovery_pass_password', 2, 'Can change password', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','recovery_pass_password') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_index', 2, 'Can view index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_changepassword', 2, 'Can change password', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_changepassword') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_passwordchanged', 2, 'Can view password changed', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_passwordchanged') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_change_account', 2, 'Can change account', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_change_account') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_change_database', 2, 'Can change database', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_change_database') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_edit', 2, 'Can edit profile', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_edit') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_saveedit', 2, 'Can save edit', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_saveedit') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_addbilling', 2, 'Can add billing', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_addbilling') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_editbilling', 2, 'Can edit billing', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_editbilling') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_addbillingdata', 2, 'Can add billing data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_addbillingdata') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_sendemail', 2, 'Can send email', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_sendemail') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_sendsms', 2, 'Can send sms', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_sendsms') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_checkemailcode', 2, 'Can check email code', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_checkemailcode') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_checksmscode', 2, 'Can check sms code', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_checksmscode') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_verifyemail', 2, 'Can verify email', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_verifyemail') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_verifysms', 2, 'Can verify sms', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_verifysms') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_savedata', 2, 'Can save data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_savedata') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('paycheck_index', 2, 'Can payment check', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','paycheck_index') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('export_index', 2, 'Can view Index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','export_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('export_delete', 2, 'Can delete data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','export_delete') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('export_prepare', 2, 'Can prepare data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','export_prepare') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('export_download', 2, 'Can download data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','export_download') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('import_index', 2, 'Can view index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','import_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('import_sheet', 2, 'Can view sheet', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','import_sheet') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('import_data_types', 2, 'Can view data types', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','import_data_types') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('import_delete', 2, 'Can delete data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','import_data_types') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('upload_index', 2, 'Can view index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','upload_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('upload_file', 2, 'Can process file', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','upload_file') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('wizard_index', 2, 'Can view Index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','wizard_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('wizard_file', 2, 'Can process file', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','wizard_file') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('wizard_convert', 2, 'Can convert file', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','wizard_convert') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('wizard_worksheets', 2, 'Can manage worksheet', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','wizard_worksheets') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('wizard_data_types', 2, 'Can view data types', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','wizard_data_types') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('wizard_summary', 2, 'Can view summary', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','wizard_summary') ON CONFLICT DO NOTHING;

-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('idbdata_index', 2, 'Can view index', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','idbdata_index') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('idbdata_welcome', 2, 'Can view welcome', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','idbdata_welcome') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('idbdata_show_all', 2, 'Can view show all', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','idbdata_show_all') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('idbdata_reset', 2, 'Can reset search', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','idbdata_reset') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('idbdata_save_user_for', 2, 'Can save user for', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','idbdata_save_user_for') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('idbdata_set_display', 2, 'Can set display', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','idbdata_set_display') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('idbdata_create', 2, 'Can create data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','idbdata_create') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('idbdata_update', 2, 'Can update data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','idbdata_update') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('idbdata_delete', 2, 'Can delete data', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','idbdata_delete') ON CONFLICT DO NOTHING;

-- #############################################################################
-- New action
-- #############################################################################

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('contacts_start_multi', 2, 'Can start multi register', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','contacts_start_multi') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('contacts_edit', 2, 'Can edit', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','contacts_edit') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('contacts_skip', 2, 'Can skip', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','contacts_skip') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('contacts_send_inv', 2, 'Can send invitation', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','contacts_send_inv') ON CONFLICT DO NOTHING;

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('contacts_send_mails', 2, 'Can send mails', extract(epoch from now()), extract(epoch from now())) ON CONFLICT DO NOTHING;
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','contacts_send_mails') ON CONFLICT DO NOTHING;



INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('profile_edit_contact', 2, 'Can edit contact', extract(epoch from now()), extract(epoch from now()));
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_admin','profile_edit_contact');

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
