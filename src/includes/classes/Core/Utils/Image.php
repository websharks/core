<?php
/**
 * Image utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
//
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
//
use function assert as debug;
use function get_defined_vars as vars;
//
use RedeyeVentures\GeoPattern\GeoPattern;

/**
 * Image utilities.
 *
 * @since 161011 Compression.
 */
class Image extends Classes\Core\Base\Core
{
    /**
     * Geo-pattern generator.
     *
     * @since 17xxxx Geo-patterns.
     *
     * @param array $args Named arguments.
     *
     * @return bool True on success.
     */
    public function geoPattern(array $args): bool
    {
        if (!class_exists('Imagick')) {
            return false;
        }
        $default_args = [
            'string' => '',
            'for'    => '',

            'color'      => '',
            'base_color' => '',

            'output_file'   => '',
            'output_format' => '',
        ];
        $args += $default_args; // Defaults.
        $args = $this->parseFormatArgs($args);

        if (!$args['output_file'] || !$args['output_format']) {
            return false; // Required arguments.
        }
        $args['string'] = (string) $args['string'];
        $args['string'] = $args['string'] ?: (string) $args['for'];

        $args['color']      = (string) $args['color'];
        $args['base_color'] = (string) $args['base_color'];

        $output_file_existed_prior = is_file($args['output_file']);

        try { // Catch exceptions.
            $svg = (new GeoPattern([
                'string'    => $args['string'] ?: null,
                'baseColor' => $args['base_color'] ?: null,
                'color'     => $args['color'] ?: null,
            ]))->toSVG(); // SVG format initially.

            if (file_put_contents($args['output_file'], $svg) === false) {
                throw $this->c::issue('Storage failure.');
            }
            if ($args['output_format'] !== 'svg') {
                if (!$this->convert([
                        'format'        => 'svg',
                        'file'          => $args['output_file'],
                        'output_file'   => $args['output_file'],
                        'output_format' => $args['output_format'],
                    ])) {
                    throw $this->c::issue('Conversion failure.');
                }
            }
            return true; // Success.
            //
        } catch (\Throwable $Exception) {
            if (!$output_file_existed_prior && is_file($args['output_file'])) {
                unlink($args['output_file']);
            }
            return false;
        }
    }

    /**
     * Convert image format.
     *
     * @since 17xxxx Imagick utils.
     *
     * @param array $args Named arguments.
     *
     * @return bool True on success.
     */
    public function convert(array $args): bool
    {
        if (!class_exists('Imagick')) {
            return false;
        }
        $default_args = [
            'file'   => '',
            'format' => '',

            'output_file'   => '',
            'output_format' => '',
        ];
        $args += $default_args; // Defaults.
        $args = $this->parseFormatArgs($args);

        if (!is_file($args['file']) || !$args['format']) {
            return false; // Not possible.
        } elseif (!$args['output_file'] || !$args['output_format']) {
            return false; // Not possible.
        }
        $output_file_existed_prior = is_file($args['output_file']);

        try { // Catch exceptions.
            $image = new \Imagick();
            $image->setBackgroundColor('transparent');
            $image->readImage($args['format'].':'.$args['file']);

            $image->writeImage($args['output_format'].':'.$args['output_file']);
            return true; // Success.
            //
        } catch (\Throwable $Exception) {
            if (!$output_file_existed_prior && is_file($args['output_file'])) {
                unlink($args['output_file']);
            }
            return false;
        }
    }

    /**
     * Resize image.
     *
     * @since 17xxxx Imagick utils.
     *
     * @param array $args Named arguments.
     *
     * @return bool True on success.
     */
    public function resize(array $args): bool
    {
        if (!class_exists('Imagick')) {
            return false;
        }
        $default_args = [
            'file'   => '',
            'format' => '',

            'size'   => 0,
            'width'  => 0,
            'height' => 0,
            'crop'   => '',

            'filter'  => \Imagick::FILTER_LANCZOS,
            'blur'    => 1,
            'bestfit' => false,

            'output_file'   => '',
            'output_format' => '',
        ];
        $args += $default_args; // Defaults.
        $args = $this->parseFormatArgs($args);

        if (!is_file($args['file']) || !$args['format']) {
            return false; // Not possible.
        } elseif (!$args['output_file'] || !$args['output_format']) {
            return false; // Not possible.
        }
        $args['size']   = max(0, (int) $args['size']);
        $args['width']  = max(1, (int) ($args['width'] ?: $args['size']));
        $args['height'] = max(1, (int) ($args['height'] ?: $args['size']));
        $args['crop']   = mb_strtolower((string) $args['crop']);

        $args['filter']  = (int) $args['filter'];
        $args['blur']    = (int) $args['blur'];
        $args['bestfit'] = (bool) $args['bestfit'];

        $output_file_existed_prior = is_file($args['output_file']);

        try { // Catch exceptions.
            $image = new \Imagick();
            $image->setBackgroundColor('transparent');
            $image->readImage($args['format'].':'.$args['file']);

            if ($args['crop'] === 'thumbnail') {
                $image->cropThumbnailImage($args['width'], $args['height']);
                //
            } elseif ($args['format'] === 'svg') {
                $svg   = $image;
                $image = new \Imagick();

                $res_x = ceil(300 * ($args['width'] / $svg->getImageWidth()));
                $res_y = ceil(300 * ($args['height'] / $svg->getImageHeight()));
                $image->setResolution($res_x, $res_y); // Before filling.

                $image->setBackgroundColor('transparent');
                $image->readImage($args['format'].':'.$args['file']);
                $image->scaleImage($args['width'], $args['height'], $args['bestfit']);
            } else {
                $image->resizeImage($args['width'], $args['height'], $args['filter'], $args['blur'], $args['bestfit']);
            }
            $image->writeImage($args['output_format'].':'.$args['output_file']);

            return true; // Success.
            //
        } catch (\Throwable $Exception) {
            echo $Exception->getMessage()."\n";
            if (!$output_file_existed_prior && is_file($args['output_file'])) {
                unlink($args['output_file']);
            }
            return false;
        }
    }

    /**
     * Texturize image.
     *
     * @since 17xxxx Imagick utils.
     *
     * @param array $args Named arguments.
     *
     * @return bool True on success.
     */
    public function texturize(array $args): bool
    {
        if (!class_exists('Imagick')) {
            return false;
        }
        $default_args = [
            'file'   => '',
            'format' => '',

            'size'   => 0,
            'width'  => 0,
            'height' => 0,

            'output_file'   => '',
            'output_format' => '',
        ];
        $args += $default_args; // Defaults.
        $args = $this->parseFormatArgs($args);

        if (!is_file($args['file']) || !$args['format']) {
            return false; // Not possible.
        } elseif (!$args['output_file'] || !$args['output_format']) {
            return false; // Not possible.
        }
        $args['size']   = max(1, (int) $args['size']);
        $args['width']  = max(1, (int) ($args['width'] ?: $args['size']));
        $args['height'] = max(1, (int) ($args['height'] ?: $args['size']));

        $output_file_existed_prior = is_file($args['output_file']);

        try { // Catch exceptions.
            $image = new \Imagick();
            $image->setBackgroundColor('transparent');
            $image->readImage($args['format'].':'.$args['file']);

            $canvas = new \Imagick(); // To hold texturization.
            $canvas->newImage($args['width'], $args['height'], 'transparent', $args['output_format']);

            $image = $canvas->textureImage($image);
            $image->writeImage($args['output_format'].':'.$args['output_file']);

            return true; // Success.
            //
        } catch (\Throwable $Exception) {
            if (!$output_file_existed_prior && is_file($args['output_file'])) {
                unlink($args['output_file']);
            }
            return false;
        }
    }

    /**
     * Compress image.
     *
     * @since 17xxxx Imagick utils.
     *
     * @param array $args Named arguments.
     *
     * @return bool True on success.
     */
    public function compress(array $args): bool
    {
        if (!class_exists('Imagick')) {
            return false; // Not possible.
        }
        $default_args = [
            'file'   => '',
            'format' => '',

            // For SVGs (1-8).
            'precision' => 0,

            // For PNGs (1-100).
            'min_quality' => 0,
            'max_quality' => 0,

            // For JPGs (1-100).
            'quality' => 0,

            'output_file'   => '',
            'output_format' => '',
        ];
        $args += $default_args; // Defaults.
        $args = $this->parseFormatArgs($args);

        if (!is_file($args['file']) || !$args['format']) {
            return false; // Not possible.
        } elseif (!$args['output_file'] || !$args['output_format']) {
            return false; // Not possible.
        }
        if ($args['output_format'] === 'svg') {
            return $this->compressSvg($args['file'], $args);
        } elseif (mb_stripos($args['output_format'], 'png') === 0) {
            return $this->compressPng($args['file'], $args);
        }
        $args['quality'] = max(0, min(100, (int) $args['quality']));

        $output_file_existed_prior = is_file($args['output_file']);
        $compression_type          = $this->formatToCompressionType($args['output_format']);
        $compression_quality       = $args['quality'] ?: $this->formatToCompressionQuality($args['output_format']);

        try { // Catch exceptions.
            $image = new \Imagick();
            $image->setBackgroundColor('transparent');
            $image->readImage($args['format'].':'.$args['file']);

            $image->stripImage(); // Profiles/comments.
            $image->setImageCompression($compression_type);
            $image->setImageCompressionQuality($compression_quality);
            $image->writeImage($args['output_format'].':'.$args['output_file']);

            return true; // Success.
            //
        } catch (\Throwable $Exception) {
            if (!$output_file_existed_prior && is_file($args['output_file'])) {
                unlink($args['output_file']);
            }
            return false;
        }
    }

    /**
     * Compress SVG image.
     *
     * @since 17xxxx Initial release.
     *
     * @param string $file SVG file path.
     * @param array  $args Behavioral args.
     *
     * @return bool True on success.
     *
     * @see https://github.com/svg/svgo
     */
    protected function compressSvg(string $file, array $args = []): bool
    {
        if (!$this->c::canCallFunc('exec')) {
            return false;
        }
        $default_args = [
            'precision'   => 0,
            'output_file' => '',
        ];
        $args += $default_args; // Defaults.

        if (!$file || !is_file($file)) {
            return false; // Not possible.
        }
        $args['precision'] = (int) ($args['precision'] ?: 4);
        $args['precision'] = max(1, min(8, $args['precision']));

        $args['output_file'] = (string) $args['output_file'];
        $args['output_file'] = $args['output_file'] ?: $file;

        $output_file_existed_prior = is_file($args['output_file']);

        $esc_file        = $this->c::escShellArg($file);
        $esc_precision   = $this->c::escShellArg($args['precision']);
        $esc_output_file = $this->c::escShellArg($args['output_file']);
        exec('svgo --quiet --precision='.$esc_precision.' --output='.$esc_output_file.' '.$esc_file, $_, $status);

        if ($status === 0) {
            return true; // Success.
        } else {
            if (!$output_file_existed_prior && is_file($args['output_file'])) {
                unlink($args['output_file']);
            }
            return false;
        }
    }

    /**
     * Compress PNG image.
     *
     * @since 161011 Initial release.
     *
     * @param string $file PNG file path.
     * @param array  $args Behavioral args.
     *
     * @return bool True on success.
     *
     * @see https://pngquant.org/php.html
     */
    protected function compressPng(string $file, array $args = []): bool
    {
        if (!$this->c::canCallFunc('exec')) {
            return false;
        }
        $default_args = [
            'min_quality' => 0,
            'max_quality' => 0,
            'output_file' => '',
        ];
        $args += $default_args; // Defaults.

        if (!$file || !is_file($file)) {
            return false; // Not possible.
        }
        $args['min_quality'] = (int) ($args['min_quality'] ?: 60);
        $args['min_quality'] = max(0, min(100, $args['min_quality']));

        $args['max_quality'] = (int) ($args['max_quality'] ?: 90);
        $args['max_quality'] = max($args['min_quality'], min(100, $args['max_quality']));

        $args['output_file'] = (string) $args['output_file'];
        $args['output_file'] = $args['output_file'] ?: $file;

        $output_file_existed_prior = is_file($args['output_file']);

        $esc_file        = $this->c::escShellArg($file);
        $esc_output_file = $this->c::escShellArg($args['output_file']);
        $esc_quality     = $this->c::escShellArg($args['min_quality'].'-'.$args['max_quality']);
        exec('pngquant --strip --skip-if-larger --quality='.$esc_quality.' --force --output='.$esc_output_file.' '.$esc_file, $_, $status);

        if ($status === 0) {
            return true; // Success.
            //
        } elseif (in_array($status, [98, 99], true)) {
            if ($args['output_file'] !== $file) {
                return copy($file, $args['output_file']);
            } else {
                return true;
            } // See `$ man pngquant` for further details.
            // `98` = --skip-if-larger, `99` = --quality min-max.
        } else {
            if (!$output_file_existed_prior && is_file($args['output_file'])) {
                unlink($args['output_file']);
            }
            return false;
        }
    }

    /**
     * Decode image data URL.
     *
     * @since 161011 Initial release.
     *
     * @param string $url Data URL.
     *
     * @return array `[raw_image_data, extension]`.
     */
    public function decodeDataUrl(string $url): array
    {
        if (mb_stripos($url, 'data:') !== 0) {
            return []; // Not applicable.
        }
        $data     = str_replace(' ', '+', $url);
        $data_100 = mb_substr($data, 0, 100);

        if (mb_stripos($data_100, 'data:image/x-icon;base64,') === 0) {
            $extension = 'ico';
            //
        } elseif (mb_stripos($data_100, 'data:image/icon;base64,') === 0) {
            $extension = 'ico';
            //
        } elseif (mb_stripos($data_100, 'data:image/ico;base64,') === 0) {
            $extension = 'ico';
            //
        } elseif (mb_stripos($data_100, 'data:image/gif;base64,') === 0) {
            $extension = 'gif';
            //
        } elseif (mb_stripos($data_100, 'data:image/jpg;base64,') === 0) {
            $extension = 'jpg';
            //
        } elseif (mb_stripos($data_100, 'data:image/jpeg;base64,') === 0) {
            $extension = 'jpg';
            //
        } elseif (mb_stripos($data_100, 'data:image/png;base64,') === 0) {
            $extension = 'png';
            //
        } elseif (mb_stripos($data_100, 'data:image/svg+xml;base64,') === 0) {
            $extension = 'svg';
            //
        } elseif (mb_stripos($data_100, 'data:image/webp;base64,') === 0) {
            $extension = 'webp';
        }
        if (empty($extension)) {
            return []; // Not possible.
            //
        } elseif (!($raw_image_data = mb_substr($data, mb_strpos($data, ',') + 1))) {
            return []; // Decode failure.
            //
        } elseif (!($raw_image_data = base64_decode($raw_image_data, true))) {
            return []; // Decode failure.
        }
        return compact('raw_image_data', 'extension');
    }

    /**
     * Extension to format.
     *
     * @since 17xxxx Imagick utils.
     *
     * @param string $ext_file Extension (or file).
     *
     * @return string Extension format.
     */
    protected function extToFormat(string $ext_file): string
    {
        if (mb_strpos($ext_file, '.') !== false) {
            $ext = $this->c::fileExt($ext_file);
        } else {
            $ext = $ext_file;
        }
        switch (($ext = mb_strtolower($ext))) {
            case 'jpg':
                $format = 'jpeg';
                break;

            case 'png':
                $format = 'png32';
                break;

            default:
                $format = $ext;
        }
        return $format;
    }

    /**
     * Format to extension.
     *
     * @since 17xxxx Imagick utils.
     *
     * @param string $format Image format.
     *
     * @return string Format extension.
     */
    protected function formatToExt(string $format): string
    {
        switch (($format = mb_strtolower($format))) {
            case 'jpeg':
                $ext = 'jpg';
                break;

            case 'png8':
            case 'png16':
            case 'png24':
            case 'png32':
            case 'png64':
                $ext = 'png';
                break;

            default:
                $ext = $format;
        }
        return $ext;
    }

    /**
     * Format to compression type.
     *
     * @since 17xxxx Imagick utils.
     *
     * @param string $format Image format.
     *
     * @return int Format compression type.
     */
    protected function formatToCompressionType(string $format): int
    {
        if (!class_exists('Imagick')) {
            return 0; // Not possible.
        }
        switch ($this->formatToExt($format)) {
            case 'jpg':
                $type = \Imagick::COMPRESSION_JPEG;
                break;

            default:
                $type = \Imagick::COMPRESSION_UNDEFINED;
        }
        return $type;
    }

    /**
     * Format to compression quality.
     *
     * @since 17xxxx Imagick utils.
     *
     * @param string $format Image format.
     *
     * @return int Format compression quality.
     */
    protected function formatToCompressionQuality(string $format): int
    {
        switch ($this->formatToExt($format)) {
            case 'jpg':
                $quality = 85;
                break;

            default:
                $quality = 92;
        }
        return $quality;
    }

    /**
     * Set format extension.
     *
     * @since 17xxxx Imagick utils.
     *
     * @param string $file   Image file.
     * @param string $format Image format.
     *
     * @return string File w/ format extension.
     */
    protected function setFormatExt(string $file, string $format): string
    {
        return $this->c::setFileExt($file, $this->formatToExt($format));
    }

    /**
     * Change format extension.
     *
     * @since 17xxxx Imagick utils.
     *
     * @param string $file   Image file.
     * @param string $format Image format.
     *
     * @return string File w/ possible format extension.
     */
    protected function changeFormatExt(string $file, string $format): string
    {
        return $this->c::changeFileExt($file, $this->formatToExt($format));
    }

    /**
     * Parse format args.
     *
     * @since 17xxxx Imagick utils.
     *
     * @param array $args Named arguments.
     *
     * @return array Parsed arguments.
     */
    protected function parseFormatArgs(array $args): array
    {
        $default_args = [
            'file'   => '',
            'format' => '',

            'output_file'   => '',
            'output_format' => '',
        ];
        $args += $default_args; // Defaults.

        $args['file']          = (string) $args['file'];
        $args['output_file']   = (string) $args['output_file'];

        $args['format'] = mb_strtolower((string) $args['format']);
        $args['format'] = $args['format'] === 'jpg' ? 'jpeg' : $args['format'];

        $args['output_format'] = mb_strtolower((string) $args['output_format']);
        $args['output_format'] = $args['output_format'] === 'jpg' ? 'jpeg' : $args['output_format'];

        $args['format']        = $args['format'] ?: $this->extToFormat($args['file']);
        $args['output_file']   = $args['output_file'] ?: $this->changeFormatExt($args['file'], $args['output_format']);
        $args['output_format'] = $args['output_format'] ?: $this->extToFormat($args['output_file']);
        $args['output_file']   = $this->changeFormatExt($args['output_file'], $args['output_format']);

        return $args;
    }
}
