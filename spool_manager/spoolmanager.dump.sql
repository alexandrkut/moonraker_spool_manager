----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.9-dev
-- Exported: 2:19pm on March 15, 2023 (MSK)
-- database file: ./spoolmanager.sqlite
----
BEGIN TRANSACTION;

----
-- Drop table for materials
----
DROP TABLE IF EXISTS "materials";

----
-- Table structure for materials
----
CREATE TABLE 'materials' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'name' TEXT NOT NULL, 'density' REAL NOT NULL, 'RetLen' INTEGER NOT NULL DEFAULT 0, 'RetSp' INTEGER NOT NULL DEFAULT 0, 'UnRetExtrLen' INTEGER NOT NULL DEFAULT 0, 'UnRetSp' INTEGER NOT NULL DEFAULT 0);

----
-- Drop table for activ_spool
----
DROP TABLE IF EXISTS "activ_spool";

----
-- Table structure for activ_spool
----
CREATE TABLE 'activ_spool' ('printer_id' TEXT PRIMARY KEY NOT NULL, 'spool_id' INTEGER NOT NULL);

----
-- Drop table for printers
----
DROP TABLE IF EXISTS "printers";

----
-- Table structure for printers
----
CREATE TABLE 'printers' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'serial' TEXT NOT NULL, 'name' TEXT NOT NULL);

----
-- Data dump for printers, a total of 2 rows
----
INSERT INTO "printers" ("id","serial","name") VALUES ('1','UltiSteel','Ulti Steel');

----
-- Drop table for history
----
DROP TABLE IF EXISTS "history";

----
-- Table structure for history
----
CREATE TABLE 'history' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, datetime DATE DEFAULT (datetime('now','localtime')),'printer_id' INTEGER NOT NULL,'spool_id' INTEGER NOT NULL, 'lenght' REAL NOT NULL, 'weight' REAL NOT NULL, 'filename' TEXT, 'printer_name' TEXT, 'spool_name' TEXT);

----
-- Drop table for spools
----
DROP TABLE IF EXISTS "spools";

----
-- Table structure for spools
----
CREATE TABLE "spools" ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,'material_id' INTEGER NOT NULL, 'name' TEXT NOT NULL,'weight' INTEGER NOT NULL,'weight_tare' INTEGER NOT NULL, 'reserve' INTEGER DEFAULT 0, 'flow' REAL DEFAULT 1);

----
-- Drop view for history_view
----
DROP VIEW IF EXISTS "history_view";

----
-- View structure for history_view
----
CREATE VIEW 'history_view' AS select datetime, printers.name as printer, (select name from materials where id =spools.material_id) || ' - ' ||spools.name as spool, round(lenght/1000,3) as lenght, round(history.weight,2) as weight, filename from history,printers,spools where history.printer_id = printers.id and history.spool_id = spools.id;

----
-- Drop view for spools_view
----
DROP VIEW IF EXISTS "spools_view";

----
-- View structure for spools_view
----
CREATE VIEW 'spools_view' AS select "spools".id as id, materials.name as material, "spools".name as name, spools.flow as flow, "spools".weight as weight, "spools"."weight_tare" as weight_tare, materials.density as density, ( select name from printers,activ_spool where activ_spool.spool_id="spools".id and activ_spool.printer_id=printers.id ) as activ_printer, spools.reserve as reserve FROM "spools",materials WHERE "spools".material_id=materials.id order by material;

----
-- Drop index for sqlite_autoindex_activ_spool_1
----
DROP INDEX IF EXISTS "sqlite_autoindex_activ_spool_1";

----
-- structure for index sqlite_autoindex_activ_spool_1 on table activ_spool
----
;
COMMIT;
