<?php

//
// NOTE Migration Created: 2014-04-09 01:43:13
// --------------------------------------------------
class CreateSynagisEtocDatabase
{

//
// NOTE - Make changes to the database.
// --------------------------------------------------
public function up()
{

//
// NOTE -- messagetype
// --------------------------------------------------
Schema::create('messagetype', function($table) {
    $table->engine = 'InnoDB';

 $table->increments('id');
 $table->string('name', 50);
 });


//
// NOTE -- optintype
// --------------------------------------------------
Schema::create('optintype', function($table) {
    $table->engine = 'InnoDB';

 $table->increments('id');
 $table->string('name', 50);
 });

//
// NOTE -- statustype
// --------------------------------------------------
Schema::create('statustype', function($table) {
    $table->engine = 'InnoDB';

 $table->increments('id');
 $table->string('name', 50);
 });


//
// NOTE -- trackedevent
// --------------------------------------------------
Schema::create('trackedevent', function($table) {
    $table->engine = 'InnoDB';

 $table->increments('id');
 $table->string('name', 50);
 });


//
// NOTE -- representative
// --------------------------------------------------
Schema::create('representative', function($table) {
    $table->engine = 'InnoDB';

 $table->integer('id')->unique();
 $table->string('division_type', 50);
 $table->string('division_id', 50);
 $table->string('title', 20)->nullable();
 $table->string('first_name', 50)->nullable();
 $table->string('last_name', 50)->nullable();
 $table->string('email', 50)->nullable();
 });

//
// NOTE -- region
// --------------------------------------------------
Schema::create('region', function($table) {
    $table->engine = 'InnoDB';

 $table->string('id', 10)->unique();
 $table->string('name', 50);

});

//
// NOTE -- area
// --------------------------------------------------
Schema::create('area', function($table) {
    $table->engine = 'InnoDB';

 $table->string('id', 10)->unique();
 $table->string('name', 50);
 $table->string('region_id', 50);
 $table->foreign('region_id')->references('id')->on('region');
 });

//
// NOTE -- territory
// --------------------------------------------------
Schema::create('territory', function($table) {
    $table->engine = 'InnoDB';

 $table->string('id', 10)->unique();
 $table->string('name', 50);
 $table->string('area_id', 50);
 $table->foreign('area_id')->references('id')->on('area');

 });

//
// NOTE -- zip_territory
// --------------------------------------------------
Schema::create('zip_territory', function($table) {
    $table->engine = 'InnoDB';

 $table->string('zip_code', 10)->unique();
 $table->string('city', 50);
 $table->string('state', 20);
 $table->string('territory_id', 50);
 });

//
// NOTE -- pediatrician
// --------------------------------------------------
Schema::create('pediatrician', function($table) {
    $table->engine = 'InnoDB';

 $table->increments('id');
 $table->integer('representative_id');
 $table->string('zip', 10);
 $table->string('fax', 20);
 $table->dateTime('createdate');
 $table->foreign('representative_id')
             ->references('id'
        )->on('representative');
 });

//
// NOTE -- application
// --------------------------------------------------
Schema::create('application', function($table) {
    $table->engine = 'InnoDB';

    $table->increments('id');
    $table->unsignedInteger('pediatrician_id');
    $table->string('applicationtype', 5);
    $table->string('languagepreference', 5);
    $table->string('hospitalname', 50);
    $table->string('hospitalcontactname', 50);
    $table->string('hospitalphone', 20);
    $table->string('hospitalzip', 15)->nullable();
    $table->string('ip', 15);
    $table->dateTime('createdate');

    $table->foreign('pediatrician_id')
        ->references('id')
        ->on('pediatrician');
 });

//
// NOTE -- application_optin
// --------------------------------------------------
Schema::create('application_optin', function($table) {
    $table->engine = 'InnoDB';

 $table->increments('id');
 $table->unsignedInteger('application_id');
 $table->unsignedInteger('optintype_id');
 $table->dateTime('createdate');
 $table->foreign('application_id')->references('id')->on('application');
 $table->foreign('optintype_id')->references('id')->on('optintype');
 });

//
// NOTE -- application_messagestatus
// --------------------------------------------------
Schema::create('application_messagestatus', function($table) {
    $table->engine = 'InnoDB';

 $table->increments('id');
 $table->unsignedInteger('optin_id');
 $table->unsignedInteger('messagetype_id');
 $table->unsignedInteger('statustype_id');
 $table->string('return_id', 50);
 $table->string('errorcode', 50)->nullable();
 $table->text('errormessage')->nullable();
 $table->dateTime('createdate');
 $table->dateTime('updatedate');
 $table->foreign('optin_id')->references('id')->on('application_optin');
 $table->foreign('messagetype_id')->references('id')->on('messagetype');
 $table->foreign('statustype_id')->references('id')->on('statustype');
 });

//
// NOTE -- application_trackedevent
// --------------------------------------------------
Schema::create('application_trackedevent', function($table) {
    $table->engine = 'InnoDB';

 $table->increments('id');
 $table->unsignedInteger('application_id');
 $table->unsignedInteger('trackedevent_id');
 $table->dateTime('createdate');
 $table->foreign('application_id')->references('id')->on('application');
 $table->foreign('trackedevent_id')->references('id')->on('trackedevent');
 });


//
// NOTE --  Stored Procedure insert_application_optin
// --------------------------------------------------
$sql = <<<SQL
DROP PROCEDURE IF EXISTS `insert_application_optin`;
CREATE PROCEDURE `insert_application_optin`(
  IN application_id INT,
  IN optintype_id INT
)
BEGIN
   INSERT INTO `application_optin`
   (`application_id`,
    `optintype_id`,
    `createdate`)
   VALUES
   (application_id,
    optintype_id,
    NOW()
  );
SELECT LAST_INSERT_ID() AS application_optin_id;
END
SQL;
DB::connection()->getPdo()->exec($sql);

//
// NOTE --  Stored Procedure insert_application
// --------------------------------------------------
$sql = <<<SQL

DROP PROCEDURE IF EXISTS `insert_application`;
CREATE PROCEDURE `insert_application`(
IN pediatrician_id INT,
IN applicationtype VARCHAR(5),
IN languagepreference VARCHAR(5),
IN hospitalname VARCHAR(50),
IN hospitalcontactname VARCHAR(50),
IN hospitalphone VARCHAR(20),
IN hospitalzip VARCHAR(20)
)
BEGIN
INSERT INTO `application`
(`pediatrician_id`,
 `applicationtype`,
 `languagepreference`,
 `hospitalname`,
 `hospitalcontactname`,
 `hospitalphone`,
 `hospitalzip`,
 `ip`,
 `createdate`)
VALUES
(
 pediatrician_id,
 applicationtype,
 languagepreference,
 hospitalname,
 hospitalcontactname,
 hospitalphone,
 hospitalzip,
 ip,
 NOW()
);
SELECT LAST_INSERT_ID() AS application_id;
END
SQL;
DB::connection()->getPdo()->exec($sql);

//
// NOTE --  Stored Procedure insert_application_trackedevent
// --------------------------------------------------
$sql = <<<SQL
DROP PROCEDURE IF EXISTS `insert_application_trackedevent`;
CREATE PROCEDURE `insert_application_trackedevent`(
  IN application_id INT,
  IN trackedevent_id INT
)
BEGIN
   INSERT INTO `application_trackedevent`
   (`application_id`,
    `trackedevent_id`,
    `createdate`)
   VALUES
   (application_id,
    trackedevent_id,
    NOW()
    );

    SELECT LAST_INSERT_ID() AS application_tracked_event_id;
END
SQL;
DB::connection()->getPdo()->exec($sql);

//
// NOTE --  Stored Procedure insert_application_messagestatus
// --------------------------------------------------
$sql = <<<SQL
DROP PROCEDURE IF EXISTS `insert_application_messagestatus`;

CREATE PROCEDURE `insert_application_messagestatus`(
  in optin_id int,
  in messagetype_id int,
  in statustype_id int,
  in return_id varchar(50),
  in errorcode varchar(50),
  in errormessage text
)
BEGIN
    insert into `application_messagestatus`
    (`optin_id`,
     `messagetype_id`,
     `statustype_id`,
     `return_id`,
     `errorcode`,
     `errormessage`,
     `createdate`)
     values(optin_id,
            messagetype_id,
            statustype_id,
            return_id,
            errorcode,
            errormessage,
            now()
     );
    SELECT last_insert_id() AS application_messagestatus_id;
END
SQL;
DB::connection()->getPdo()->exec($sql);

//
// NOTE --  Stored Procedure update_application_messagestatus
// --------------------------------------------------
$sql = <<<SQL
DROP PROCEDURE IF EXISTS `update_application_messagestatus`;

CREATE PROCEDURE `update_application_messagestatus`(
  in p_statustype_id int(10),
  in p_return_id varchar(50),
  in p_errorcode varchar(50),
  in p_errormessage text
)
BEGIN
    update `application_messagestatus`
    SET `statustype_id` = p_statustype_id,
        `errorcode` = p_errorcode,
        `errormessage` = p_errormessage
    WHERE `return_id` = p_return_id;
END
SQL;
DB::connection()->getPdo()->exec($sql);

//
// NOTE --  Stored Procedure insert_pediatrician
// --------------------------------------------------
$sql = <<<SQL
DROP PROCEDURE IF EXISTS `insert_pediatrician`;
CREATE PROCEDURE `insert_pediatrician`(
  in representative_id int,
  in zip varchar(20),
  in fax varchar(20)
)
begin
    insert into `pediatrician`
    (
     `representative_id`,
     `zip`,
     `fax`,
     `createdate`)
    values
    (representative_id,
     zip,
     fax,
     now()
    );
    SELECT last_insert_id() AS pediatrician_id;
END
SQL;
DB::connection()->getPdo()->exec($sql);

//
// NOTE --  Stored Procedure get_representative
// --------------------------------------------------
$sql = <<<SQL
DROP PROCEDURE IF EXISTS `get_representative`;
CREATE PROCEDURE `get_representative`(
  in zip_code varchar(20)
)
begin
   SELECT zt.*, r1.id,
    IF(IFNULL( r1.first_name, r2.first_name), IFNULL( r2.first_name, r3.first_name), r2.first_name) AS first_name,
    IF(IFNULL( r1.last_name, r2.last_name), IFNULL( r2.last_name, r3.last_name), r2.last_name) AS last_name,
    IF(IFNULL( r1.email, r2.email), IFNULL( r2.email, r3.email), r2.email) AS email,
    IF(IFNULL( r1.division_id, r2.division_id), IFNULL( r2.division_id, r3.division_id), r2.division_id) AS division_id
    FROM
    zip_territory     AS zt
    LEFT JOIN
    representative    AS r1
    ON zt.territory_id = r1.division_id
    LEFT JOIN
    representative    AS r2
    ON r2.division_id = substr(r1.division_id,1,5)
    LEFT JOIN
    representative    AS r3
    ON r3.division_id = substr(r2.division_id,1,3)
    WHERE zt.zip_code = zip_code;
END
SQL;
DB::connection()->getPdo()->exec($sql);
}

//
// NOTE - Revert the changes to the database.
// --------------------------------------------------
public function down()
{
Schema::drop('statustype');
Schema::drop('area');
Schema::drop('region');
Schema::drop('territory');
Schema::drop('trackedevent');
Schema::drop('zip_territory');
Schema::drop('messagetype');
Schema::drop('optintype');
Schema::drop('representative');
Schema::drop('pediatrician');
Schema::drop('application_messagestatus');
Schema::drop('application_optin');
Schema::drop('application_trackedevent');
Schema::drop('application');
}
}










// DELIMITER ;;
// CREATE DEFINER=`root`@`localhost` PROCEDURE `get_representative`(
//   in zip_code varchar(20)
// )
// BEGIN
//    SELECT zt.*, r1.id,
//     IF(r1.first_name != '', r1.first_name, IF(r2.first_name != '', r2.first_name, r3.first_name)) AS first_name,
//     IF(r1.last_name != '', r1.last_name, IF(r2.last_name != '', r2.last_name, r3.last_name)) AS last_name,
//     IF(r1.email != 'NULL', r1.email, IF(r2.email != 'NULL', r2.email, r3.email)) AS email,
//     IF(r1.division_id != '', r1.division_id, IF(r2.division_id != '', r2.division_id, r3.division_id)) AS division_id
//     FROM
//     zip_territory     AS zt
//     LEFT JOIN
//     representative    AS r1
//     ON zt.territory_id = r1.division_id
//     LEFT JOIN
//     representative    AS r2
//     ON r2.division_id = substr(r1.division_id,1,5)
//     LEFT JOIN
//     representative    AS r3
//     ON r3.division_id = substr(r2.division_id,1,3)
//     WHERE zt.zip_code = zip_code;
// END;;
// DELIMITER ;


// DELIMITER ;;
// CREATE DEFINER=`mysqluser`@`localhost` PROCEDURE `insert_application`(
// IN pediatrician_id INT,
// IN applicationtype VARCHAR(5),
// IN languagepreference VARCHAR(5),
// IN hospitalname VARCHAR(50),
// IN hospitalcontactname VARCHAR(50),
// IN hospitalphone VARCHAR(20),
// IN hospitalzip VARCHAR(20),
// IN ip VARCHAR(15)
// )
// BEGIN
// INSERT INTO `application`
// (`pediatrician_id`,
//  `applicationtype`,
//  `languagepreference`,
//  `hospitalname`,
//  `hospitalcontactname`,
//  `hospitalphone`,
//  `hospitalzip`,
//  `ip`,
//  `createdate`)
// VALUES
// (
//  pediatrician_id,
//  applicationtype,
//  languagepreference,
//  hospitalname,
//  hospitalcontactname,
//  hospitalphone,
//  hospitalzip,
//  ip,
//  NOW()
// );
// SELECT LAST_INSERT_ID() AS application_id;
// END;;
// DELIMITER ;




// DELIMITER ;;
// CREATE DEFINER=`mysqluser`@`localhost` PROCEDURE `insert_application_messagestatus`(
//   in optin_id int,
//   in messagetype_id int,
//   in statustype_id int,
//   in return_id varchar(50),
//   in errorcode varchar(50),
//   in errormessage text
// )
// BEGIN
//     insert into `application_messagestatus`
//     (`optin_id`,
//      `messagetype_id`,
//      `statustype_id`,
//      `return_id`,
//      `errorcode`,
//      `errormessage`,
//      `createdate`)
//      values(optin_id,
//             messagetype_id,
//             statustype_id,
//             return_id,
//             errorcode,
//             errormessage,
//             now()
//      );
//     SELECT last_insert_id() AS application_messagestatus_id;
// END;;
// DELIMITER ;




// DELIMITER ;;
// CREATE DEFINER=`mysqluser`@`localhost` PROCEDURE `insert_application_optin`(
//   IN application_id INT,
//   IN optintype_id INT
// )
// BEGIN
//    INSERT INTO `application_optin`
//    (`application_id`,
//     `optintype_id`,
//     `createdate`)
//    VALUES
//    (application_id,
//     optintype_id,
//     NOW()
//   );
// SELECT LAST_INSERT_ID() AS application_optin_id;
// END;;
// DELIMITER ;




// DELIMITER ;;
// CREATE DEFINER=`mysqluser`@`localhost` PROCEDURE `insert_application_trackedevent`(
//   IN application_id INT,
//   IN trackedevent_id INT
// )
// BEGIN
//    INSERT INTO `application_trackedevent`
//    (`application_id`,
//     `trackedevent_id`,
//     `createdate`)
//    VALUES
//    (application_id,
//     trackedevent_id,
//     NOW()
//     );

//     SELECT LAST_INSERT_ID() AS application_tracked_event_id;
// END;;
// DELIMITER ;



// DELIMITER ;;
// CREATE DEFINER=`mysqluser`@`localhost` PROCEDURE `insert_pediatrician`(
//   in representative_id int,
//   in zip varchar(20),
//   in fax varchar(20)
// )
// begin
//     insert into `pediatrician`
//     (
//      `representative_id`,
//      `zip`,
//      `fax`,
//      `createdate`)
//     values
//     (representative_id,
//      zip,
//      fax,
//      now()
//     );
//     SELECT last_insert_id() AS pediatrician_id;
// END;;
// DELIMITER ;




// DELIMITER ;;
// CREATE DEFINER=`mysqluser`@`localhost` PROCEDURE `update_application_messagestatus`(
//   in p_statustype_id int(10),
//   in p_return_id varchar(50),
//   in p_errorcode varchar(50),
//   in p_errormessage text
// )
// BEGIN
//     update `application_messagestatus`
//     SET `statustype_id` = p_statustype_id,
//         `errorcode` = p_errorcode,
//         `errormessage` = p_errormessage
//     WHERE `return_id` = p_return_id;
// END;;
// DELIMITER ;