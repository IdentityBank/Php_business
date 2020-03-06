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
-- # KD: Create Idb Credits log
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_log.idb_credits_log
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_log"."idb_credits_log";

CREATE TABLE "p57b_log"."idb_credits_log"
(
    "id" serial PRIMARY KEY,
    "oid" TEXT NOT NULL,
    "timestamp" timestamp without time zone DEFAULT now(),
    "action_name" VARCHAR(255) NOT NULL,
    "action_type" VARCHAR(255) NOT NULL,
    "cost" INTEGER NOT NULL,
    "credits_before" INTEGER NOT NULL,
    "additional_credits_before" INTEGER NOT NULL
);
ALTER TABLE "p57b_log"."idb_credits_log"
    OWNER TO p57b_log;

CREATE INDEX idb_audit_log_idx_timestamp
    ON "p57b_log"."idb_credits_log" ("timestamp");

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
