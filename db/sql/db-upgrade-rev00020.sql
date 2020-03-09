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
-- # KD: Add organization_admin role
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.auth_item
-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('organization_admin', 1, 'This is the main account for organization', extract(epoch from now()), extract(epoch from now()));

INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('manage_organization', 2, 'Can manage organization.', extract(epoch from now()), extract(epoch from now()));
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('manage_account', 2, 'Can manage account.', extract(epoch from now()), extract(epoch from now()));
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('access_manager', 2, 'Have access to access manager module.', extract(epoch from now()), extract(epoch from now()));
INSERT INTO "p57b_business"."auth_item" ("name", "type", "description", "created_at", "updated_at") VALUES ('database_manage', 2, 'Can manage account databases', extract(epoch from now()), extract(epoch from now()));

INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('organization_admin', 'manage_organization');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('organization_admin', 'manage_account');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('organization_admin', 'access_manager');
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('organization_admin', 'database_manage');

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
