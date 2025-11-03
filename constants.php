<?php
define("TBL_USER", "users");
define("COL_USER_ID", "id");
define("COL_USER_USERNAME", "username");
define("COL_USER_PASSWORD", "password");
define("COL_USER_ROLE", "role");

define("TBL_FIELDS", "fields");
define("COL_FIELD_ID", "id");
define("COL_FIELD_NAME", "name");
define("COL_FIELD_SPORT", "sport_type");

define("TBL_RESERVATIONS", "reservations");
define("COL_RESERVATION_ID", "id");
define("COL_RESERVATION_FIELD", "field_id");
define("COL_RESERVATION_USER", "user_id");
define("COL_RESERVATION_DATE", "date");
define("COL_RESERVATION_START", "start_time");
define("COL_RESERVATION_END", "end_time");
