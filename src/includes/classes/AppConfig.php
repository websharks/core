<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * App config.
 *
 * @since 15xxxx Initial release.
 */
class AppConfig extends AbsCore
{
    /**
     * App.
     *
     * @since 15xxxx
     *
     * @type App
     */
    protected $App;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $instance_base Instance base.
     * @param array $instance      Instance args (highest precedence).
     */
    public function __construct(array $instance_base = [], array $instance = [])
    {
        parent::__construct();

        $this->App = $GLOBALS[App::class];

        # Instance base (i.e., default config).

        $default_instance_base = [
            'debug'             => (bool) ($_SERVER['CFG_DEBUG'] ?? false),
            'handle_exceptions' => (bool) ($_SERVER['CFG_HANDLE_EXCEPTIONS'] ?? false),

            'contacts' => [
                'admin' => [
                    'name'         => (string) ($_SERVER['CFG_ADMIN_NAME'] ?? 'Admin'),
                    'email'        => (string) ($_SERVER['CFG_ADMIN_EMAIL'] ?? 'admin@'.$this->App->server_root_host),
                    'public_email' => (string) ($_SERVER['CFG_ADMIN_PUBLIC_EMAIL'] ?? 'admin@'.$this->App->server_root_host),
                ],
            ],
            'di' => [
                'default_rule' => [
                    'new_instances' => [
                        CliOpts::class,
                        Exception::class,
                        Template::class,
                        Tokenizer::class,
                    ],
                ],
            ],
            'mysql_db' => [
                'hosts' => [
                    (string) ($_SERVER['CFG_MYSQL_DB_HOST'] ?? '127.0.0.1') => [
                        'port'    => (int) ($_SERVER['CFG_MYSQL_DB_PORT'] ?? 3306),
                        'charset' => (string) ($_SERVER['CFG_MYSQL_DB_CHARSET'] ?? 'utf8mb4'),
                        'collate' => (string) ($_SERVER['CFG_MYSQL_DB_COLLATE'] ?? 'utf8mb4_unicode_ci'),

                        'username' => (string) ($_SERVER['CFG_MYSQL_DB_USER'] ?? 'root'),
                        'password' => (string) ($_SERVER['CFG_MYSQL_DB_PASSWORD'] ?? ''),

                        'ssl_enable' => (bool) ($_SERVER['CFG_MYSQL_SSL_ENABLE'] ?? false),
                        'ssl_key'    => (string) ($_SERVER['CFG_MYSQL_SSL_KEY'] ?? ''),
                        'ssl_crt'    => (string) ($_SERVER['CFG_MYSQL_SSL_CRT'] ?? ''),
                        'ssl_ca'     => (string) ($_SERVER['CFG_MYSQL_SSL_CA'] ?? ''),
                        'ssl_cipher' => (string) ($_SERVER['CFG_MYSQL_SSL_CIPHER'] ?? ''),
                    ],
                ],
                'shards' => [
                    [
                        'range' => [
                            'from' => 0,
                            'to'   => 65535,
                        ],
                        'properties' => [
                            'host' => (string) ($_SERVER['CFG_MYSQL_DB_HOST'] ?? '127.0.0.1'),
                            'name' => (string) ($_SERVER['CFG_MYSQL_DB_NAME'] ?? 'db0'),
                        ],
                    ],
                ],
            ],
            'brand' => [
                'acronym'     => (string) ($_SERVER['CFG_BRAND_ACRONYM'] ?? 'APP'),
                'name'        => (string) ($_SERVER['CFG_BRAND_NAME'] ?? $this->App->server_host),
                'keywords'    => (string) ($_SERVER['CFG_BRAND_KEYWORDS'] ?? [$this->App->server_host]),
                'description' => (string) ($_SERVER['CFG_BRAND_DESCRIPTION'] ?? 'Just another site powered by the websharks/core.'),
                'tagline'     => (string) ($_SERVER['CFG_BRAND_TAGLINE'] ?? 'Powered by the websharks/core.'),
                'screenshot'  => (string) ($_SERVER['CFG_BRAND_SCREENSHOT'] ?? '/client-s/images/screenshot.png'),
                'favicon'     => (string) ($_SERVER['CFG_BRAND_FAVICON'] ?? '/client-s/images/favicon.ico'),
                'logo'        => (string) ($_SERVER['CFG_BRAND_LOGO'] ?? '/client-s/images/logo.png'),
            ],
            'urls' => [
                'hosts' => [
                    'roots' => [
                        'app' => (string) ($_SERVER['CFG_APP_ROOT_HOST'] ?? $this->App->server_root_host),
                    ],
                    'app'    => (string) ($_SERVER['CFG_APP_HOST'] ?? $this->App->server_host),
                    'cdn'    => (string) ($_SERVER['CFG_CDN_HOST'] ?? 'cdn.'.$this->App->server_root_host),
                    'cdn_s3' => (string) ($_SERVER['CFG_CDN_S3_HOST'] ?? 'cdn-s3.'.$this->App->server_root_host),
                ],
                'default_scheme' => (string) ($_SERVER['CFG_DEFAULT_URL_SCHEME'] ?? 'https'),
                'sig_key'        => (string) ($_SERVER['CFG_URL_SIG_KEY'] ?? ''),
            ],
            'fs_paths' => [
                'cache_dir'     => (string) ($_SERVER['CFG_CACHE_DIR'] ?? '%%app_dir%%/.~cache'),
                'templates_dir' => (string) ($_SERVER['CFG_TEMPLATES_DIR'] ?? '%%app_dir%%/src/includes/templates'),
                'config_file'   => (string) ($_SERVER['CFG_CONFIG_FILE'] ?? '%%app_dir%%/.config.json'),
            ],
            'fs_permissions' => [
                'transient_dirs' => (int) ($_SERVER['CFG_TRANSIENT_DIR_PERMISSIONS'] ?? 02775),
                // `octdec(02775)` = 1533 as an integer.
            ],
            'memcache' => [
                'enabled'   => (bool) ($_SERVER['CFG_MEMCACHE_ENABLED'] ?? true),
                'namespace' => (string) ($_SERVER['CFG_MEMCACHE_NAMESPACE'] ?? 'app'),
                'servers'   => [
                    [
                        'host'   => (string) ($_SERVER['CFG_MEMCACHE_HOST'] ?? '127.0.0.1'),
                        'port'   => (int) ($_SERVER['CFG_MEMCACHE_PORT'] ?? 11211),
                        'weight' => (int) ($_SERVER['CFG_MEMCACHE_WEIGHT'] ?? 0),
                    ],
                ],
            ],
            'i18n' => [
                'locales'     => (array) ($_SERVER['CFG_LOCALES'] ?? ['en_US.UTF-8', 'C']),
                'text_domain' => (string) ($_SERVER['CFG_I18N_TEXT_DOMAIN'] ?? 'app'),
            ],
            'email' => [
                'from_name'  => (string) ($_SERVER['CFG_EMAIL_FROM_NAME'] ?? 'App'),
                'from_email' => (string) ($_SERVER['CFG_EMAIL_FROM_EMAIL'] ?? 'app@'.$this->App->server_root_host),

                'reply_to_name'  => (string) ($_SERVER['CFG_EMAIL_REPLY_TO_NAME'] ?? ''),
                'reply_to_email' => (string) ($_SERVER['CFG_EMAIL_REPLY_TO_EMAIL'] ?? ''),

                'smtp_host'   => (string) ($_SERVER['CFG_EMAIL_SMTP_HOST'] ?? '127.0.0.1'),
                'smtp_port'   => (int) ($_SERVER['CFG_EMAIL_SMTP_PORT'] ?? 25),
                'smtp_secure' => (string) ($_SERVER['CFG_EMAIL_SMTP_SECURE'] ?? ''),

                'smtp_username' => (string) ($_SERVER['CFG_EMAIL_SMTP_USERNAME'] ?? ''),
                'smtp_password' => (string) ($_SERVER['CFG_EMAIL_SMTP_PASSWORD'] ?? ''),
            ],
            'cookies' => [
                'key' => (string) ($_SERVER['CFG_COOKIES_KEY'] ?? ''),
            ],
            'hash_ids' => [
                'key' => (string) ($_SERVER['CFG_HASH_IDS_KEY'] ?? ''),
            ],
            'passwords' => [
                'key' => (string) ($_SERVER['CFG_PASSWORDS_KEY'] ?? ''),
            ],
            'aws' => [
                'access_key' => (string) ($_SERVER['CFG_AWS_ACCESS_KEY'] ?? ''),
                'secret_key' => (string) ($_SERVER['CFG_AWS_SECRET_KEY'] ?? ''),
            ],
            'embedly' => [
                'api_key' => (string) ($_SERVER['CFG_EMBEDLY_KEY'] ?? ''),
            ],
            'web_purify' => [
                'api_key' => (string) ($_SERVER['CFG_WEBPURIFY_KEY'] ?? ''),
            ],
            'bitly' => [
                'api_key' => (string) ($_SERVER['CFG_BITLY_KEY'] ?? ''),
            ],
        ];
        # Merge instance bases together now.

        $instance_base = $this->merge($default_instance_base, $instance_base);

        # Merge a possible JSON configuration file also.
        // @TODO Store config in memory to avoid repeated disk reads.

        $config_file = $instance['fs_paths']['config_file'] ?? $instance_base['fs_paths']['config_file'];

        if ($config_file && is_file($config_file)) { // Has a config file?
            if (!is_array($config = json_decode(file_get_contents($config_file), true))) {
                throw new Exception(sprintf('Invalid config file: `%1$s`.', $config_file));
            }
            $config = $this->merge($instance_base, $config, true);
            $config = $this->merge($config, $instance);
        } else {
            $config = $this->merge($instance_base, $instance);
        }
        # Fill replacement codes and overload the config properties.

        $this->overload((object) $this->fillReplacementCodes($config), true);
    }

    /**
     * Merge config arrays.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $base      Base array.
     * @param array $merge     Array to merge.
     * @param bool  $is_config Is config file?
     *
     * @return array The resuling array after merging.
     */
    protected function merge(array $base, array $merge, bool $is_config = false): array
    {
        if ($is_config) { // Disallow these instance-only keys.
            unset($merge['di'], $merge['fs_paths']['config_file']);
        }
        if (isset($base['di']['default_rule']['new_instances'])) {
            $base_di_default_rule_new_instances = $base['di']['default_rule']['new_instances'];
        } // Save new instances before emptying numeric arrays.

        $base = $this->maybeEmptyNumericArrays($base, $merge); // Maybe empty numeric arrays.

        if (isset($base_di_default_rule_new_instances, $merge['di']['default_rule']['new_instances'])) {
            $merge['di']['default_rule']['new_instances'] = array_merge($base_di_default_rule_new_instances, $merge['di']['default_rule']['new_instances']);
        }
        return $merged = array_replace_recursive($base, $merge);
    }

    /**
     * Empty numeric arrays.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $base  Base array.
     * @param array $merge Array to merge.
     *
     * @return array The `$base` w/ possibly-empty numeric arrays.
     */
    protected function maybeEmptyNumericArrays(array $base, array $merge): array
    {
        if (!$merge) { // Save time. Merge is empty?
            return $base; // Nothing to do here.
        }
        foreach ($base as $_key => &$_value) {
            if (is_array($_value) && array_key_exists($_key, $merge)) {
                if (!$_value || $_value === array_values($_value)) {
                    $_value = []; // Empty numeric arrays.
                } elseif ($merge[$_key] && is_array($merge[$_key])) {
                    $_value = $this->maybeEmptyNumericArrays($_value, $merge[$_key]);
                }
            }
        } // unset($_key, $_value); // Housekeeping.
        return $base; // Return possibly-modified base.
    }

    /**
     * Fill replacement codes.
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed $value Input value.
     *
     * @return mixed string|array|object Output value.
     */
    protected function fillReplacementCodes($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->fillReplacementCodes($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if ($value && is_string($value) && mb_strpos($value, '%%') !== false) {
            $value = str_replace(
                [
                    '%%app_namespace%%',
                    '%%app_namespace_sha1%%',
                    '%%app_dir%%',
                    '%%core_dir%%',
                ],
                [
                    $this->App->namespace,
                    $this->App->namespace_sha1,
                    $this->App->dir,
                    $this->App->core_dir,
                ],
                $value
            );
            if (mb_strpos($value, '%%env[') !== false) {
                // e.g., `%%(int)env[CFG_MYSQL_DB_PORT]%%`, `%%(array)env[CFG_LOCALES]%%`.
                $value = preg_replace_callback('/%%(\((?<type>[^()]+)\))?env\[(?<key>[^%[\]]+)\]%%/u', function ($m) {
                    $env_key_value = $_SERVER[$m['key']] ?? '';

                    if (!empty($m['type'])) {
                        settype($env_key_value, $m['type']);
                    } else {
                        $env_key_value = (string) $env_key_value;
                    }
                    return $env_key_value;
                });
            }
        }
        return $value;
    }
}
