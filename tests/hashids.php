<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\AppFacades as a;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo a::hashIds(1, 2, 3);
