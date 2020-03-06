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
INSERT INTO "p57b_business"."auth_item_child" ("parent", "child") VALUES ('idb_user','profile_edit_contact');

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
