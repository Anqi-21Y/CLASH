<?php
session_start();
session_unset();
session_destroy();
header('Location: /inner-work/clash/admin/login.php');
exit;