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
-- # MZ: Create data type/sets tables
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: data_types
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."data_types";

CREATE TABLE "p57b_business"."data_types"
(
  "id" serial PRIMARY KEY,
  "internal_name" varchar(255) NOT null,
  "display_name" text NOT NULL,
  "data_type" varchar(255) NOT NULL DEFAULT 'string',
  "searchable" smallint DEFAULT 1,
  "sortable" smallint DEFAULT 1,
  "sensitive" smallint DEFAULT 1,
  "tag" text,
  "created_at" timestamp without time zone DEFAULT now()
);
ALTER TABLE "p57b_business"."data_types"
  OWNER TO p57b_business;

CREATE INDEX data_types_idx_internal_name
  ON "p57b_business"."data_types" ("internal_name");
CREATE INDEX data_types_idx_tag
  ON "p57b_business"."data_types" ("tag");

-- # ---------------------------------------------------------------------- # --
-- # Table: data_sets
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."data_sets";

CREATE TABLE "p57b_business"."data_sets"
(
  "id" serial PRIMARY KEY,
  "internal_name" varchar(255) NOT null,
  "display_name" text NOT NULL,
  "tag" text,
  "created_at" timestamp without time zone DEFAULT now()
);
ALTER TABLE "p57b_business"."data_sets"
  OWNER TO p57b_business;

CREATE INDEX data_sets_idx_internal_name
  ON "p57b_business"."data_sets" ("internal_name");
CREATE INDEX data_sets_idx_tag
  ON "p57b_business"."data_sets" ("tag");

-- # ---------------------------------------------------------------------- # --
-- # Type: data_object_type
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."data_sets_objects";

DROP TABLE IF EXISTS "p57b_business"."data_additional_attributes";

DROP TYPE IF EXISTS "p57b_business"."data_object_type";

CREATE TYPE "p57b_business"."data_object_type" AS ENUM ('type', 'set', 'object');

-- # ---------------------------------------------------------------------- # --
-- # Table: data_sets_objects
-- # ---------------------------------------------------------------------- # --

CREATE TABLE "p57b_business"."data_sets_objects"
(
  "id" serial PRIMARY KEY,
  "dsid" integer NOT NULL,
  "oid" integer NOT NULL,
  "object_type" "p57b_business"."data_object_type" NOT NULL DEFAULT 'type',
  "display_name" text,
  "order" integer DEFAULT 1000,
  "required" smallint DEFAULT 1,
  "used_for" text
);
ALTER TABLE "p57b_business"."data_sets_objects"
  OWNER TO p57b_business;

-- # ---------------------------------------------------------------------- # --
-- # Table: data_attributes
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."data_attributes";

CREATE TABLE "p57b_business"."data_attributes"
(
  "id" serial PRIMARY KEY,
  "name" varchar(255) NOT NULL,
  "data_type" varchar(255) NOT NULL DEFAULT 'string',
  "default_value" text
);
ALTER TABLE "p57b_business"."data_attributes"
  OWNER TO p57b_business;

INSERT INTO p57b_business.data_attributes (name, data_type, default_value) VALUES ('retained_period', 'integer', '30');
INSERT INTO p57b_business.data_attributes (name, data_type) VALUES ('shared', 'string');
INSERT INTO p57b_business.data_attributes (name, data_type) VALUES ('regex', 'string');
INSERT INTO p57b_business.data_attributes (name, data_type, default_value) VALUES ('multiplicity', 'string', '1..*');

-- # ---------------------------------------------------------------------- # --
-- # Table: data_additional_attributes
-- # ---------------------------------------------------------------------- # --

CREATE TABLE "p57b_business"."data_additional_attributes"
(
  "daid" integer,
  "oid" integer,
  "object_type" "p57b_business"."data_object_type" DEFAULT 'type',
  "value" varchar(255) NOT NULL,
  primary key ("daid","oid","object_type")
);
ALTER TABLE "p57b_business"."data_additional_attributes"
  OWNER TO p57b_business;

-- # ---------------------------------------------------------------------- # --
-- # RBAC
-- # ---------------------------------------------------------------------- # --

-- # auth_item
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('manage_data_types', 2, 'Data types manager.', null, null, (select extract(epoch from now())), (select extract(epoch from now()))) ON CONFLICT ("name") DO UPDATE SET "description" = EXCLUDED.description, "updated_at" = (select extract(epoch from now()));
INSERT INTO p57b_business.auth_item (name, type, description, rule_name, data, created_at, updated_at) VALUES ('manage_data_sets', 2, 'Data sets manager.', null, null, (select extract(epoch from now())), (select extract(epoch from now()))) ON CONFLICT ("name") DO UPDATE SET "description" = EXCLUDED.description, "updated_at" = (select extract(epoch from now()));

-- # auth_item_child
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_admin', 'manage_data_types') ON CONFLICT ON CONSTRAINT auth_item_child_pkey DO NOTHING;
INSERT INTO p57b_business.auth_item_child (parent, child) VALUES ('idb_admin', 'manage_data_sets') ON CONFLICT ON CONSTRAINT auth_item_child_pkey DO NOTHING;


-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
