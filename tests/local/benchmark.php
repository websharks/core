<?php
declare(strict_types=1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = ' Lörem ipßüm dölör ßit ämet, cönßectetüer ädipißcing elit. ';

/* ------------------------------------------------------------------------------------------------------------------ */

c::benchStart();

for ($i = 0; $i < 500; ++$i) {
    c::mbTrim($string);
}
c::benchPrint();
