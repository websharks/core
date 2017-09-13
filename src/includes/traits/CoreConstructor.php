<?php
/**
 * Core constructor.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Core constructor.
 *
 * @since 160223 Initial release.
 */
trait CoreConstructor
{
    /**
     * App.
     *
     * @since 160223
     *
     * @type Classes\App
     */
    protected $App;

    /**
     * All facades.
     *
     * @since 160227
     *
     * @type \StdClass
     */
    protected $f;

    /**
     * Core facades.
     *
     * @since 160227
     *
     * @type string
     */
    protected $c;

    /**
     * Secondary core facades.
     *
     * @since 160227
     *
     * @type string
     */
    protected $s;

    /**
     * App facades.
     *
     * @since 160227
     *
     * @type string
     */
    protected $a;

    /**
     * Class constructor.
     *
     * @since 160223 Initial release.
     *
     * @param Classes\App $App App.
     */
    public function __construct(Classes\App $App)
    {
        $this->App = $App;

        if (!isset($this->App->Facades)) {
            $this->App->Facades = (object) ['c' => null, 's' => null, 'a' => null];
        } // In case of the app itself; simply create references for now.
        // This also applies to other classes loaded early-on; e.g., Config, Di, Utils.

        $this->f = &$this->App->Facades;
        $this->c = &$this->App->Facades->c;
        $this->s = &$this->App->Facades->s;
        $this->a = &$this->App->Facades->a;
    }
}
