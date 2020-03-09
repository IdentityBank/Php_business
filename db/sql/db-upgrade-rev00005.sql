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
-- # MZ: Create Tables for Password History and for IDB Accounts
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.password_history
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."password_history";

CREATE TABLE "p57b_business"."password_history"
(
    "id" serial PRIMARY KEY,
    "uid" varchar(255) not null,
    "passwd" varchar(255) not null,
    "createtime" timestamp without time zone default CURRENT_TIMESTAMP
);

CREATE INDEX password_history_idx_uid ON "p57b_business"."password_history" ("uid");

ALTER TABLE "p57b_business"."password_history" OWNER TO p57b_business;

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.idb_organization
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."idb_organization";

CREATE TABLE "p57b_business"."idb_organization"
(
    "oid" varchar(255) not null,
    "name" text not null,
    "created_at" timestamp without time zone default CURRENT_TIMESTAMP,
    "updated_at" timestamp without time zone default CURRENT_TIMESTAMP,
    primary key ("oid")
);

ALTER TABLE "p57b_business"."idb_organization" OWNER TO p57b_business;

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.idb_account
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."idb_account";

CREATE TABLE "p57b_business"."idb_account"
(
    "aid" varchar(255) not null,
    "oid" varchar(255) not null,
    "name" text not null,
    "created_at" timestamp without time zone default CURRENT_TIMESTAMP,
    "updated_at" timestamp without time zone default CURRENT_TIMESTAMP,
    primary key ("aid","oid")
);

CREATE INDEX idb_organization_account_idx_oid ON "p57b_business"."idb_account" ("oid");
CREATE UNIQUE INDEX idb_organization_account_idx_aid ON "p57b_business"."idb_account" ("aid");

ALTER TABLE "p57b_business"."idb_account" OWNER TO p57b_business;

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.idb_database
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."idb_database";

CREATE TABLE "p57b_business"."idb_database"
(
    "dbid" varchar(255) not null,
    "aid" varchar(255) not null,
    "name" text not null,
    "created_at" timestamp without time zone default CURRENT_TIMESTAMP,
    "updated_at" timestamp without time zone default CURRENT_TIMESTAMP,
    primary key ("dbid","aid")
);

CREATE INDEX idb_account_database_idx_aid ON "p57b_business"."idb_database" ("aid");
CREATE UNIQUE INDEX idb_account_database_idx_dbid ON "p57b_business"."idb_database" ("dbid");

ALTER TABLE "p57b_business"."idb_database" OWNER TO p57b_business;

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.idb_account_user
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."idb_account_user";

CREATE TABLE "p57b_business"."idb_account_user"
(
    "aid" varchar(255) not null,
    "uid" varchar(255) not null,
    primary key ("aid", "uid")
);

CREATE INDEX idb_account_user_idx_aid ON "p57b_business"."idb_account_user" ("aid");
CREATE INDEX idb_account_user_idx_uid ON "p57b_business"."idb_account_user" ("uid");

ALTER TABLE "p57b_business"."idb_account_user" OWNER TO p57b_business;

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.idb_db_user
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."idb_db_user";

CREATE TABLE "p57b_business"."idb_db_user"
(
    "dbid" varchar(255) not null,
    "uid" varchar(255) not null,
    primary key ("dbid", "uid")
);

CREATE INDEX idb_db_user_idx_dbid ON "p57b_business"."idb_db_user" ("dbid");
CREATE INDEX idb_db_user_idx_uid ON "p57b_business"."idb_db_user" ("uid");

ALTER TABLE "p57b_business"."idb_db_user" OWNER TO p57b_business;

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
