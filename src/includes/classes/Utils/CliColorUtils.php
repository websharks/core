<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * CLI color utilities.
 *
 * @since 150424 Initial release.
 */
class CliColorUtils extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Foreground colors.
     *
     * @since 150424 Initial release.
     *
     * @type array FG colors.
     */
    protected $cli_colors_fg = [
        'black'  => '0;30',
        'red'    => '0;31',
        'green'  => '0;32',
        'yellow' => '0;33',
        'blue'   => '0;34',
        'purple' => '0;35',
        'cyan'   => '0;36',
        'white'  => '0;37',

        'black_bold'  => '1;30',
        'red_bold'    => '1;31',
        'green_bold'  => '1;32',
        'yellow_bold' => '1;33',
        'blue_bold'   => '1;34',
        'purple_bold' => '1;35',
        'cyan_bold'   => '1;36',
        'white_bold'  => '1;37',

        'black_underline'  => '4;30',
        'red_underline'    => '4;31',
        'green_underline'  => '4;32',
        'yellow_underline' => '4;33',
        'blue_underline'   => '4;34',
        'purple_underline' => '4;35',
        'cyan_underline'   => '4;36',
        'white_underline'  => '4;37',
    ];

    /**
     * Background colors.
     *
     * @since 150424 Initial release.
     *
     * @type array BG colors.
     */
    protected $cli_colors_bg = [
        'black'      => '40',
        'red'        => '41',
        'green'      => '42',
        'yellow'     => '43',
        'blue'       => '44',
        'magenta'    => '45',
        'cyan'       => '46',
        'light_gray' => '47',
    ];

    /**
     * Colorizer.
     *
     * @since 150424 Initial release.
     *
     * @param string $string   Input string to colorize.
     * @param string $fg_color Foreground color to use.
     * @param string $bg_color Background color to use.
     * @param array  $args     Any additional argument values.
     *
     * @return string Colorized input string.
     */
    public function cliColorize($string, $fg_color = '', $bg_color = '', array $args = [])
    {
        $string   = (string) $string;
        $fg_color = (string) $fg_color;
        $bg_color = (string) $bg_color;

        $default_args = [
            'code_color' => 'cyan_bold',
            'link_color' => 'blue_underline',
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $code_color = (string) $args['code_color'];
        $link_color = (string) $args['link_color'];

        colorize_string: // Target point for colorization.

        $colorized_string = ''; // Initialize string.

        if ($fg_color && isset($this->cli_colors_fg[$fg_color])) {
            $colorized_string .= "\033".'['.$this->cli_colors_fg[$fg_color].'m';
        }
        if ($bg_color && isset($this->cli_colors_bg[$bg_color])) {
            $colorized_string .= "\033".'['.$this->cli_colors_bg[$bg_color].'m';
        }
        $colorized_string .= $string; // The string we are coloring now.

        if ($fg_color || $bg_color) {
            $colorized_string .= "\033".'[0m'; // Reset.
        }
        colorize_code: // Target point for code colorization.

        if ($code_color && isset($this->cli_colors_fg[$code_color])) {
            if ($code_color !== $fg_color && !$bg_color) {
                $colorized_string = preg_replace_callback(
                    '/(`+)(?P<code>[^`]*?)\\1/',
                    function ($m) use ($fg_color, $code_color) {
                        return "\033".'['.$this->cli_colors_fg[$code_color].'m'.$m['code']."\033".'[0m'.
                                ($fg_color && isset($this->cli_colors_fg[$fg_color]) ? "\033".'['.$this->cli_colors_fg[$fg_color].'m' : '');
                                // ↑ Restores original color; if there is a foreground color.
                    },
                    $colorized_string // e.g., `<http://colorized.link/path/?query>`
                );
            }
        }
        colorize_links: // Target point for link colorization.

        if ($link_color && isset($this->cli_colors_fg[$link_color])) {
            if ($link_color !== $fg_color && !$bg_color) {
                $colorized_string = preg_replace_callback(
                    '/(?<o>\<)(?P<link>'.substr($this->def_regex_valid_url, 2, -2).')(?<c>\>)/',
                    function ($m) use ($fg_color, $link_color) {
                        return $m['o']."\033".'['.$this->cli_colors_fg[$link_color].'m'.$m['link']."\033".'[0m'.
                                ($fg_color && isset($this->cli_colors_fg[$fg_color]) ? "\033".'['.$this->cli_colors_fg[$fg_color].'m' : '').
                                $m['c']; // ↑ Restores original color; if there is a foreground color.
                    },
                    $colorized_string // e.g., `<http://colorized.link/path/?query>`
                );
            }
        }
        finale: // Target for for grand finale.

        return $colorized_string;
    }
}
