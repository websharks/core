<?php
/**
 * CLI options.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core;

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
use GetOptionKit\Option;
use GetOptionKit\OptionParser;
use GetOptionKit\OptionResult;
use GetOptionKit\OptionCollection;
use GetOptionKit\OptionPrinter\ConsoleOptionPrinter;

/**
 * CLI options.
 *
 * @since 150424 Initial release.
 */
class CliOpts extends Classes\Core\Base\Core
{
    /**
     * Options.
     *
     * @since 150424
     *
     * @var OptionCollection
     */
    protected $OptionCollection;

    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->c::isCli()) {
            throw $this->c::issue('Requires CLI mode.');
        }
        $this->OptionCollection = new OptionCollection();
    }

    /**
     * Get options.
     *
     * @since 150424 Initial release.
     *
     * @param array $specs An array of option specs.
     *
     * @link https://github.com/c9s/GetOptionKit
     */
    public function add(array $specs)
    {
        foreach ($specs as $_spec => $_data) {
            if ($_spec && is_string($_spec) && !empty($_data['desc'])) {
                $_Option = $this->OptionCollection->add($_spec, $_data['desc']);

                if (!empty($_data['type'])) {
                    $_Option->isa($_data['type']);
                }
                if (isset($_data['default'])) {
                    $_Option->defaultValue($_data['default']);
                }
                if (isset($_data['valid_values'])) {
                    $_Option->validValues($_data['valid_values']);
                }
            }
        } // unset($_spec, $_data, $_Option);
    }

    /**
     * Get options.
     *
     * @since 150424 Initial release.
     *
     * @param bool       $extract Extract into an array after parsing?
     * @param array|null $args    Args to parse; default is `$GLOBALS['argv']`.
     *
     * @return array|OptionResult|Option[] Associative array, or the result set of Option objs.
     */
    public function parse(bool $extract = true, array $args = null)
    {
        $Parser = new OptionParser($this->OptionCollection);

        $OptionResult = $Options = $Parser->parse($args ?? $GLOBALS['argv']);

        if (!$extract) { // This contains some additional information for each option.
            return $OptionResult = $Options; // OptionResult|Option[]
        } else {
            $options = []; // Associative array (default behavior).

            foreach ($Options as $_key => $_Option) {
                $_key           = preg_replace('/[^a-z0-9]/ui', '_', $_key);
                $_value         = $_Option->getValue();
                $options[$_key] = $_value;
            } // unset($_key, $_value, $_Option);

            return $options; // Suitable for `extract()` now.
        }
    }

    /**
     * Print option specs.
     *
     * @since 150424 Initial release.
     *
     * @return string Specs as a string.
     */
    public function specs(): string
    {
        $Printer = new ConsoleOptionPrinter();

        return $Printer->render($this->OptionCollection);
    }
}
