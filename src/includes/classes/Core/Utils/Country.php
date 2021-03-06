<?php
/**
 * Country utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;
#
use League\ISO3166\ISO3166;

/**
 * Country utilities.
 *
 * @since 170329.13807 Country utils.
 */
class Country extends Classes\Core\Base\Core
{
    /**
     * ISO3166.
     *
     * @since 170329.13807
     *
     * @type ISO3166.
     */
    protected $ISO3166;

    /**
     * Class constructor.
     *
     * @since 170329.13807 Stripe utils.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->ISO3166 = new ISO3166();
    }

    /**
     * Select menu options.
     *
     * @since 170329.13807 Country utils.
     *
     * @param string $selected Selected value.
     * @param array  $args     Any behavioral args.
     *
     * @return string HTML markup.
     */
    public function selectOptions(string $selected = '', array $args = []): string
    {
        $default_args = [
            'ip'     => null,
            'use_ip' => true,
        ];
        $args += $default_args;

        $is_cli = $this->c::isCli();

        if (!isset($args['ip']) && $args['use_ip']) {
            $args['ip'] = $is_cli ? '' : $this->c::currentIp();
        }
        $args['ip']     = (string) $args['ip'];
        $args['use_ip'] = (bool) $args['use_ip'];

        if (!$selected && $args['ip'] && $args['use_ip']) {
            $selected = $is_cli ? '' : $this->c::ipCountry($args['ip']);
        }
        $markup   = ''; // Initialize.
        $selected = mb_strtoupper($selected);

        foreach ($this->ISO3166->getAll() as $_country) {
            $markup .= '<option value="'.$_country['alpha2'].'"'.($selected ? $this->c::selected($selected, $_country['alpha2']) : '').'>'.$_country['name'].'</option>';
        } // unset($_country); // Housekeeping.

        return $markup;
    }

    /**
     * Dropdown menu items.
     *
     * @since 170329.13807 Country utils.
     *
     * @param string $active Active value.
     * @param array  $args   Any behavioral args.
     *
     * @return string HTML markup.
     */
    public function dropdownItems(string $active = '', array $args = []): string
    {
        $default_args = [
            'ip'     => null,
            'use_ip' => true,
            'flags'  => false,
        ];
        $args += $default_args;

        $is_cli = $this->c::isCli();

        if (!isset($args['ip']) && $args['use_ip']) {
            $args['ip'] = $is_cli ? '' : $this->c::currentIp();
        }
        $args['ip']     = (string) $args['ip'];
        $args['use_ip'] = (bool) $args['use_ip'];
        $args['flags']  = (bool) $args['flags'];

        if (!$active && $args['ip'] && $args['use_ip']) {
            $active = $is_cli ? '' : $this->c::ipCountry($args['ip']);
        }
        $markup = ''; // Initialize.
        $active = mb_strtoupper($active);

        foreach ($this->ISO3166->getAll() as $_country) {
            $_alpha2_lc = mb_strtolower($_country['alpha2']);
            $_flag      = $args['flags'] ? '<i class="'.$_alpha2_lc.' flag"></i>' : '';
            $markup .= '<div class="item'.($active ? $this->c::activeSelected($active, $_country['alpha2']) : '').'" data-value="'.$_country['alpha2'].'">'.$_flag.$_country['name'].'</div>';
        } // unset($_country, $_alpha2_lc, $_flag); // Housekeeping.

        return $markup;
    }
}
