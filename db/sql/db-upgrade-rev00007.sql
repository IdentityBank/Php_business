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
-- # KD: Create SignUp Table
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.signup
-- # ---------------------------------------------------------------------- # --

DROP TABLE IF EXISTS "p57b_business"."signup";

CREATE TABLE "p57b_business"."signup"
(
  "id" serial PRIMARY KEY,
  "timestamp" timestamp without time zone DEFAULT now(),
  "data" text NOT NULL,
  "auth_key_hash" text NOT NULL,
  "auth_key" text NOT NULL
);
ALTER TABLE "p57b_business"."signup"
  OWNER TO p57b_business;

CREATE INDEX signup_idx_timestamp
  ON "p57b_business"."signup" ("timestamp");

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
