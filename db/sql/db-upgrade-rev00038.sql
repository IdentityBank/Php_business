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
-- # KD: Add default reasons to audit messages
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.idb_audit_message
-- # ---------------------------------------------------------------------- # --

INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'New business prospect');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Fulfilment of service');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Verification of details ');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Additional information required');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Regular marketing communication, e.g. newsletters');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Incidental marketing communication, e.g. promotions');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Order status update');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Payment status update');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Tracking information');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Feedback request');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Aftersales service');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Product recall');
INSERT INTO "p57b_business"."idb_audit_message" ("portal_uuid", "message") VALUES ('default_reason', 'Billing information');

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
