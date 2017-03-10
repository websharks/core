<?php
/**
 * Template.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

extract($this->setVars([
    'body' => [
        'app_main_enable' => true,
    ],
]));
?>
        <?= $this->get('http/html/includes/footer/app-main.php'); ?>
        <?= $this->get('http/html/includes/footer/scripts.php'); ?>
    </body>
</html>
