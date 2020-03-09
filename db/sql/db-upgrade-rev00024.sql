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
-- # AT: Create people_access table
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.people_access
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."people_access";


CREATE TABLE "p57b_business"."people_access"
(
    "id" serial PRIMARY KEY,
    "businessId" VARCHAR(255) NOT NULL,
    "data" TEXT NOT NULL,
    "created_at" timestamp without time zone DEFAULT now(),
    "send_at" timestamp without time zone,
    "status" varchar(255) NOT NULL,
    "url" varchar(1024)
);

ALTER TABLE "p57b_business"."people_access"
  OWNER TO p57b_business;

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
