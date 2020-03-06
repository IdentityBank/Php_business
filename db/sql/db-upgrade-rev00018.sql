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
-- # AT: Create export table
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.export
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."export";

CREATE TABLE "p57b_business"."export"
(
 "id" serial PRIMARY KEY,
 "created_at" timestamp without time zone DEFAULT now(),
 "downloaded_at" timestamp without time zone DEFAULT null,
 "uid" text NOT NULL,
 "file_name" varchar(255) not null,
 "file_path" varchar(255),
 "status" varchar(255) not null,
 "attributes" text,
 "url" varchar(255)
);

ALTER TABLE "p57b_business"."export" OWNER TO p57b_business;

CREATE UNIQUE index export_file_name_file_path_uindex ON p57b_business.export (file_path, file_name);

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
