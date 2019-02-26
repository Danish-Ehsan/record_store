<?php

require_once ('../data/util/main.php');
require_once('data/util/valid_admin.php');
require_once ('data/model/database.php');
require_once ('data/model/admin_db.php');

include('admin/view/header.php');

$adminName = getAdminName();
include('admin/admin_home.php');

include ('admin/view/footer.php');