<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
//
use Embedly\Embedly;

/**
 * OEmbed utilities.
 *
 * @since 151125 OEmbed utilities.
 */
class OEmbed extends Classes\AbsBase implements Interfaces\UrlConstants
{
    /**
     * Cache directory.
     *
     * @since 15xxxx
     *
     * @type string
     */
    protected $cache_dir;

    /**
     * Class constructor.
     *
     * @since 151125 OEmbed utilities.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->App->Config->fs_paths['cache_dir']) {
            throw new Exception('Missing cache directory.');
        }
        $this->cache_dir = $this->App->Config->fs_paths['cache_dir'].'/oembed';
    }

    /**
     * OEmbed parser.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param mixed $value Any input value.
     * @param array $args  Any additional behavioral args.
     *
     * @return string|array|object Html markup value(s).
     */
    public function __invoke($value, array $args = [])
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $args);
            } //unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        if (mb_strpos($string, '://') === false) {
            return $string; // Nothing to do.
        }
        $default_args = ['via' => ''];
        $args         = array_merge($default_args, $args);
        $args         = array_intersect_key($args, $default_args);

        $via = (string) $args['via']; // Force string.

        $Tokenizer = c\tokenize(
            $string,
            [
                'shortcodes',
                'pre',
                'code',
                'samp',
                'anchors',
                'md_fences',
                'md_links',
            ]
        ); // Now get string by reference.
        $string = &$Tokenizer->getString();

        switch ($via) {
            case 'embedly':
                $string = $this->viaEmbedly($string);
                break; // All done here.

            case 'wordpress':
                $string = $this->viaWordPress($string);
                break; // All done here.

            default: // Defaults.
                if (defined('WPINC')) {
                    $string = $this->viaWordPress($string);
                } else { // Default outside WP.
                    $string = $this->viaEmbedly($string);
                } // See {@link viaEmbedly()} routines below.
        }
        return $string = $Tokenizer->restoreGetString();
    }

    /**
     * Via Embedly.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param string $string String to parse.
     */
    protected function viaEmbedly(string $string): string
    {
        # Require a valid API key for Embedly.

        if (!$this->App->Config->embedly['api_key']) {
            throw new Exception('Missing Embedly API key.');
        }
        # Initialize several variables.

        $tokens  = $uncached_urls  = $new_embeds  = [];
        $marker  = str_replace('.', '', uniqid('', true));
        $Embedly = new Embedly(['key' => $this->App->Config->embedly['api_key']]);

        # Tokenize all URLs on a line of their own.

        $string = preg_replace_callback($this::URL_REGEX_VALID.'m', function ($m) use (&$tokens, &$uncached_urls, $marker) {
            $token = '%#%oembed-token-'.$marker.'-'.(count($tokens) - 1).'%#%';
            $tokens[$token] = $uncached_urls[$m[0]] = $m[0];
            return $token; // Replaced w/ unique token.
        }, $string); // Tokenizes all possible embed URLs.

        # Replace and flag URLs already cached.

        foreach ($tokens as $_token => $_url) {
            if (!($_embed = $this->getEmbedlyCache($_url))) {
                continue; // Not cached yet.
            }
            unset($uncached_urls[$_url]); // Cached already.

            if ($_embed->type === 'error') {
                continue; // Not possible.
            } elseif (!empty($_embed->error_code)) {
                continue; // Not possible.
            }
            if (($_markup = $this->embedlyMarkup($_url, $_embed))) {
                $string = str_replace($_token, $_markup, $string);
            }
        } // unset($_token, $_url, $_embed, $_markup); // Housekeeping.

        # Retrieve embed objects for any uncached URLs now.

        for ($_i = 1; $_i <= count($uncached_urls); $_i = $_i + 10) {
            $_urls = array_slice($uncached_urls, $_i - 1, 10);
            $_urls = array_values($_urls);

            $_args = [ // Batches of 10.
                'urls'     => $_urls,
                'secure'   => true,
                'width'    => 800,
                'maxwidth' => 800,
                'wmode'    => 'transparent',
            ]; // See: <http://embed.ly/docs/api/embed/arguments>

            foreach ($Embedly->oembed($_args) as $_key => $_embed) {
                $new_embeds[$_urls[$_key]] = $_embed;
            } // unset($_key, $_embed); // Housekeeping.
        } // unset($_i, $_urls, $_args); // Housekeeping.

        # Cache each of the new embeds now, and replace any
        # tokens associated w/ new embeds obtained above.

        foreach ($new_embeds as $_url => $_embed) {
            $this->setEmbedlyCache($_url, $_embed);

            if ($_embed->type === 'error') {
                continue; // Not possible.
            } elseif (!empty($_embed->error_code)) {
                continue; // Not possible.
            }
            if (($_markup = $this->embedlyMarkup($_url, $_embed))) {
                foreach (array_keys($tokens, $_url, true) as $_token) {
                    $string = str_replace($_token, $_markup, $string);
                } // unset($_token); // Housekeeping.
            }
        } // unset($_url, $_embed, $_markup); // Housekeeping.

        # Restore URLs that didn't trigger embeds & return.

        foreach (array_reverse($tokens, true) as $_token => $_url) {
            $string = str_replace($_token, $_url, $string);
        } // unset($_token, $_url); // Housekeeping.

        return $string;
    }

    /**
     * Get Embedly markup.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param string    $url   URL the embed is for.
     * @param \stdClass $embed The embed response object.
     *
     * @return string The Embedly HTML markup.
     */
    protected function embedlyMarkup(string $url, \stdClass $embed): string
    {
        $markup = ''; // Initialize HTML markup.

        if (!$url || empty($embed->type) || empty($embed->provider_name)) {
            return $markup; // Not possible; i.e., empty string.
        }
        $provider_slug = c\name_to_slug($embed->provider_name);
        $classes       = '-_embedly -_'.$embed->type.' -_'.$provider_slug;

        switch ($embed->type) { // See: <http://jas.xyz/1O7ymwT>
            case 'rich':
            case 'video':
                if (!empty($embed->html)) {
                    $markup = '<div class="'.c\esc_attr($classes).'">'.
                        $embed->html.
                    '</div>';
                }
                break;

            case 'photo':
                if (!empty($embed->url) && isset($embed->width, $embed->height)) {
                    $markup = '<div class="'.c\esc_attr($classes).'">'.
                        '<a href="'.c\esc_url($url).'" title="'.c\esc_attr($embed->title ?? '').'">'.
                            '<img src="'.c\esc_url($embed->url).'" width="'.c\esc_attr((string) $embed->width).'" height="'.c\esc_attr((string) $embed->height).'" alt="" />'.
                        '</a>'.
                    '</div>';
                }
                break;

            case 'link':
                if (!empty($embed->thumbnail_url) && isset($embed->thumbnail_width, $embed->thumbnail_height)) {
                    $markup = '<div class="'.c\esc_attr($classes).'">'.
                        '<a href="'.c\esc_url($url).'" title="'.c\esc_attr($embed->title ?? '').'">'.
                            '<img src="'.c\esc_url($embed->thumbnail_url).'" width="'.c\esc_attr((string) $embed->thumbnail_width).'" height="'.c\esc_attr((string) $embed->thumbnail_height).'" alt="" />'.
                        '</a>'.
                    '</div>';
                }
                break;
        }
        return $markup;
    }

    /**
     * Get Embedly cache.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param string $url URL to check the cache for.
     *
     * @return \stdClass|null Cached embed code, if possible.
     */
    protected function getEmbedlyCache(string $url)
    {
        $cache_dir = $this->cache_dir.'/embedly';
        $cache_dir .= '/'.c\sha1_mod_shard_id($url);
        $cache_file = $cache_dir.'/'.sha1($url);

        if (is_file($cache_file)) {
            $embed = file_get_contents($cache_file);
            $embed = unserialize($embed);
            if ($embed instanceof \stdClass) {
                return $embed;
            }
        }
    }

    /**
     * Set Embedly cache.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param string    $url   URL the embed is for.
     * @param \stdClass $embed The embed response object.
     */
    protected function setEmbedlyCache(string $url, \stdClass $embed)
    {
        $url_sha1              = sha1($url);
        $cache_dir             = $this->cache_dir.'/embedly/'.c\sha1_mod_shard_id($url_sha1, true);
        $cache_dir_permissions = $this->App->Config->fs_permissions['transient_dirs'];
        $cache_file            = $cache_dir.'/'.$url_sha1;

        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, $cache_dir_permissions, true);
        }
        file_put_contents($cache_file, serialize($embed));
    }

    /**
     * Via WordPress.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param string $string String to parse.
     */
    protected function viaWordPress(string $string): string
    {
        if (!defined('WPINC')
            || !c\can_call_func('wp_oembed_get')
            || !c\can_call_func('wp_embed_defaults')) {
            throw new Exception('Unable to oEmbed via WordPress.');
        }
        $oembed_args = array_merge(wp_embed_defaults(), ['discover' => false]);
        $string      = preg_replace_callback('/^\s*(https?:\/\/[^\s"]+)\s*$/uim', function ($m) use ($oembed_args) {
            $oembed = wp_oembed_get($m[1], $oembed_args);
            return $oembed ? $oembed : $m[0];
        }, $string);

        return $string;
    }
}