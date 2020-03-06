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
-- # AT: Create Idb Delete business user
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.delete_business
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."delete_business";

CREATE TABLE "p57b_business"."delete_business"
(
    "id" serial PRIMARY KEY,
    "uid" TEXT NOT NULL,
    "created_at" timestamp without time zone DEFAULT now(),
    "status" VARCHAR(255) NOT NULL,
    "can_restore" BOOLEAN DEFAULT TRUE
);
ALTER TABLE "p57b_business"."delete_business"
    OWNER TO p57b_business;

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
