<?php
// @codingStandardsIgnoreFile

declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

error_reporting(-1);
ini_set('display_errors', 'yes');

if (PHP_SAPI !== 'cli') {
    exit('Requires CLI access.');
}

$AppFacades = '<?php
// This file was auto-generated:
// '.date('F jS, Y, g:i a T').'

declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * App pseudo-static facades.
 *
 * @since 160223 Initial release.
 */
class AppFacades
{
';
foreach (dir_recursive_regex(__DIR__, '/\.php$/ui') as $_file) {
    if (mb_strpos(basename($_sub_path_name = $_file->getSubPathname()), '_') !== 0) {
        $AppFacades .= '    use Traits\\Facades\\'.str_replace(['/', '.php'], ['\\', ''], $_file->getSubPathname()).';'."\n";
    }
} // unset($_file); // Housekeeping.

$AppFacades .= '}'."\n"; // Close the class.
file_put_contents(dirname(__FILE__, 3).'/classes/AppFacades.php', $AppFacades);
echo $AppFacades; // Print for debugging purposes.

function dir_recursive_regex(string $dir, string $regex): \RegexIterator
{
    $DirIterator      = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_SELF | \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS);
    $IteratorIterator = new \RecursiveIteratorIterator($DirIterator, \RecursiveIteratorIterator::CHILD_FIRST);
    $RegexIterator    = new \RegexIterator($IteratorIterator, $regex, \RegexIterator::MATCH, \RegexIterator::USE_KEY);

    return $RegexIterator;
}
