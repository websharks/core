<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

// echo c::sha1Color('adsasdffa-x', 2);
echo c::md5GradientImage(md5('x'), 16, 16);
