<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * PHP memory utilities.
 *
 * @since 150424 Initial release.
 */
class PhpMemory extends Classes\AbsBase
{
    /**
     * Get/set available memory.
     *
     * @since 150424 Initial release.
     *
     * @param string|null $limit Size abbr.
     *
     * @return float Current max memory; in bytes.
     */
    public function limit(string $limit = null): float
    {
        if (isset($limit) && $limit) {
            @ini_set('memory_limit', $limit);
        }
        return $this->Utils->FsSize->abbrBytes(ini_get('memory_limit'));
    }
}