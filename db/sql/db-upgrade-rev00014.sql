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
-- # AT: Create import table
-- # AT: Create mapping table
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table clean
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS p57b_business.import_worksheet;
DROP TABLE IF EXISTS p57b_business.import;

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.import
-- # ---------------------------------------------------------------------- # --

CREATE TABLE p57b_business.import
(
  "id" serial PRIMARY KEY,
  "uid" varchar(255) not null,
  "created_at" timestamp without time zone DEFAULT now(),
  "file_name" character varying(255) NOT NULL,
  "file_path" varchar(255) not null,
  "status" text
);

ALTER TABLE p57b_business.import
  OWNER TO p57b_business;

CREATE UNIQUE index import_file_name_uindex
  ON p57b_business.import (file_name);

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.import_worksheet
-- # ---------------------------------------------------------------------- # --

CREATE TABLE p57b_business.import_worksheet
(
  "id" serial PRIMARY KEY,
  "uid" varchar(255) not null,
  "name" varchar(255) not null,
  "file_id" int not null,
  "worksheet_id" int not null,
  "status" varchar(255) not null,
  foreign key ("file_id") references "p57b_business"."import" ("id") on delete cascade on update cascade
);

ALTER TABLE p57b_business.import_worksheet
  OWNER TO p57b_business;

CREATE UNIQUE index import_worksheet_id_uindex
  ON p57b_business.import_worksheet (id);

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
