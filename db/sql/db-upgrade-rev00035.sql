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
-- # KD: Create people_access table
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.email_templates
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."email_templates";

CREATE TABLE "p57b_business"."email_templates"
(
    "id" serial PRIMARY KEY,
    "oid" VARCHAR(255) NOT NULL,
    "action_type" VARCHAR(255) NOT NULL,
    "created_at" timestamp without time zone DEFAULT now(),
    "path" varchar(1024) NOT NULL,
    "title" varchar(255),
    "active" BOOL DEFAULT FALSE,
    "language" VARCHAR(255) NOT NULL
);
ALTER TABLE "p57b_business"."email_templates"
    OWNER TO p57b_business;

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
