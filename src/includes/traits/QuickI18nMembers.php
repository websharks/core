<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\AppUtils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

/**
 * Quick i18n members.
 *
 * @since 150424 Initial release.
 */
trait QuickI18nMembers
{
    /**
     * Translate a string.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $string String to translate.
     *
     * @return string Translated string.
     */
    protected function __(string $string): string
    {
        if (!empty($this->App->Config->text_domain)) {
            return $string;
        }
        return $string;
    }
}