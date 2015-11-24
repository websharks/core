<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Serializer.
 *
 * @since 15xxxx Initial release.
 */
class Serializer extends Classes\AbsBase
{
    const MARK = "\u{2982}\u{FEFF}\u{2982}";

    /**
     * Maybe serialize value.
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed $value Value to serialize (maybe).
     *
     * @return string A string value or a serialized value as a string.
     */
    public function maybeSerialize($value): string
    {
        if (!is_string($value)) {
            if (is_bool($value)) {
                $value = (string) (int) $value;
            } elseif (is_int($value) || is_float($value)) {
                $value = (string) $value;
            } elseif (is_resource($value)) {
                throw new Exception('Cannot serialize a resource.');
            } else { // Serialize.
                $value = $this::MARK.serialize($value);
            }
        } elseif ($value && mb_strpos($value, $this::MARK) === 0) {
            throw new Exception('String may unserialize inadvertently.');
        }
        return $value; // Possibly serialized value.
    }

    /**
     * Maybe unserialize value.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $string String to unserialize (maybe).
     *
     * @return mixed The unserialized value.
     */
    public function maybeUnserialize(string $string)
    {
        $value = $string; // Initialize.

        if ($value && mb_strpos($value, $this::MARK) === 0) {
            $value = @unserialize($this->Utils->ReplaceOnce($this::MARK, '', $value));
        }
        return $value;
    }

    /**
     * Check/set expected type.
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed  $value         Value.
     * @param string $expected_type Data type.
     *
     * @return mixed|null The typified value; else `null`.
     */
    public function checkSetType($value, string $expected_type)
    {
        switch ($expected_type) {
            case 'bool':
            case 'boolean':
                $expected_type = 'bool';
                if ($value === '0' || $value === '1') {
                    $value = (bool) $value;
                }
                break; // Stop here.

            case 'int':
            case 'integer':
            case 'long':
                $expected_type = 'int';
                if (is_numeric($value) && mb_strpos($value, '.') === false) {
                    $value = (int) $value;
                }
                break; // Stop here.

            case 'float':
            case 'double':
            case 'real':
                $expected_type = 'float';
                if (is_numeric($value)) {
                    $value = (float) $value;
                }
                break; // Stop here.

            case 'string':
            case 'array':
            case 'object':
            case 'null':
                break; // Stop here.

            default: // Catch invalid type checks here.
                throw new Exception(sprintf('Unexpected type: `%1$s`.', $expected_type));
        }
        $is = 'is_'.$expected_type; // See: <http://php.net/manual/en/function.gettype.php>

        return $is($value) ? $value : null;
    }
}
