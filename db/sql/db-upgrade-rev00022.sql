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
-- # AT: Add columns to tables
-- #############################################################################

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.import
-- # ---------------------------------------------------------------------- # --

alter table "p57b_business"."import" add "aid" varchar(255);

alter table "p57b_business"."import" add "oid" varchar(255);

alter table "p57b_business"."import" add "dbid" varchar(255);

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.import_worksheet
-- # ---------------------------------------------------------------------- # --

alter table "p57b_business"."import_worksheet" add "aid" varchar(255);

alter table "p57b_business"."import_worksheet" add "oid" varchar(255);

alter table "p57b_business"."import_worksheet" add "dbid" varchar(255);

-- # ---------------------------------------------------------------------- # --
-- # Table: p57b_business.export
-- # ---------------------------------------------------------------------- # --

alter table "p57b_business"."export" add "aid" varchar(255);

alter table "p57b_business"."export" add "oid" varchar(255);

alter table "p57b_business"."export" add "dbid" varchar(255);

-- #############################################################################
-- #                               End of file                                 #
-- #############################################################################
