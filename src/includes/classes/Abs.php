<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Base abstraction.
 *
 * @since 150424 Initial release.
 */
abstract class Abs implements \Serializable, \JsonSerializable
{
    use Traits\CacheMembers;
    use Traits\OverloadMembers;

    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     */
    public function __construct()
    {
        // Nothing at this time.
    }
}
