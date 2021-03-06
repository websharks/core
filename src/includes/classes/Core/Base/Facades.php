<?php
/**
 * Facades.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types=1);
namespace WebSharks\Core\Classes\Core\Base;

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
 * Pseudo-static facades.
 *
 * @since 160223 Initial release.
 */
abstract class Facades
{
    use Traits\Facades\Apache;
    use Traits\Facades\App;
    use Traits\Facades\Arrays;
    use Traits\Facades\Aws;
    use Traits\Facades\Backtrace;
    use Traits\Facades\Base64;
    use Traits\Facades\Benchmark;
    use Traits\Facades\Bitly;
    use Traits\Facades\Boolean;
    use Traits\Facades\Captcha;
    use Traits\Facades\Cli;
    use Traits\Facades\Clippers;
    use Traits\Facades\Color;
    use Traits\Facades\Cookies;
    use Traits\Facades\Country;
    use Traits\Facades\Crc32;
    use Traits\Facades\Crypto;
    use Traits\Facades\Csrf;
    use Traits\Facades\Currency;
    use Traits\Facades\Debugging;
    use Traits\Facades\Defuse;
    use Traits\Facades\Deprecated;
    use Traits\Facades\Dimensions;
    use Traits\Facades\DirCache;
    use Traits\Facades\Dirs;
    use Traits\Facades\Dump;
    use Traits\Facades\Email;
    use Traits\Facades\Eols;
    use Traits\Facades\Errors;
    use Traits\Facades\Escapes;
    use Traits\Facades\Fatalities;
    use Traits\Facades\Files;
    use Traits\Facades\GitHub;
    use Traits\Facades\Gravatar;
    use Traits\Facades\HashIds;
    use Traits\Facades\Headers;
    use Traits\Facades\Html;
    use Traits\Facades\Image;
    use Traits\Facades\Indents;
    use Traits\Facades\Ips;
    use Traits\Facades\Iterators;
    use Traits\Facades\Keygen;
    use Traits\Facades\MailChimp;
    use Traits\Facades\Markdown;
    use Traits\Facades\Memcache;
    use Traits\Facades\Multibyte;
    use Traits\Facades\Names;
    use Traits\Facades\NoCache;
    use Traits\Facades\Nonce;
    use Traits\Facades\OAuthServer;
    use Traits\Facades\OEmbed;
    use Traits\Facades\Os;
    use Traits\Facades\Output;
    use Traits\Facades\Paginator;
    use Traits\Facades\Password;
    use Traits\Facades\Patterns;
    use Traits\Facades\Pdo;
    use Traits\Facades\Percentages;
    use Traits\Facades\Php;
    use Traits\Facades\Redirect;
    use Traits\Facades\Replacements;
    use Traits\Facades\Request;
    use Traits\Facades\RequestType;
    use Traits\Facades\Response;
    use Traits\Facades\Routes;
    use Traits\Facades\SearchTerms;
    use Traits\Facades\Serializer;
    use Traits\Facades\Session;
    use Traits\Facades\Sha1;
    use Traits\Facades\Sha1Mods;
    use Traits\Facades\Sha256;
    use Traits\Facades\SimpleExpressions;
    use Traits\Facades\Slack;
    use Traits\Facades\Slashes;
    use Traits\Facades\Slugs;
    use Traits\Facades\Spellcheck;
    use Traits\Facades\SpinReload;
    use Traits\Facades\Split;
    use Traits\Facades\Sris;
    use Traits\Facades\Stripe;
    use Traits\Facades\Templates;
    use Traits\Facades\Throwables;
    use Traits\Facades\Time;
    use Traits\Facades\Tokenizer;
    use Traits\Facades\Translits;
    use Traits\Facades\Twitter;
    use Traits\Facades\UrlBuilders;
    use Traits\Facades\UrlCurrent;
    use Traits\Facades\UrlParsers;
    use Traits\Facades\UrlQuerys;
    use Traits\Facades\UrlUtils;
    use Traits\Facades\UserAgent;
    use Traits\Facades\Uuid64;
    use Traits\Facades\Uuids;
    use Traits\Facades\Varz;
    use Traits\Facades\Versions;
    use Traits\Facades\Webpurify;
    use Traits\Facades\WordPress;
    use Traits\Facades\Xml;
    use Traits\Facades\Yaml;
    use Traits\Facades\ZenHub;
}
