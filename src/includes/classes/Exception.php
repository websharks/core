<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Exception.
 *
 * @since 150424 Initial release.
 */
class Exception extends \Exception
{
    /**
     * Error code.
     *
     * @since 150424
     *
     * @type string
     */
    protected $error_code;

    /**
     * Error reason.
     *
     * @since 150424
     *
     * @type string
     */
    protected $reason_code;

    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param string          $message  Message.
     * @param string          $code     Exception code.
     * @param \Exception|null $previous Previous exception.
     */
    public function __construct(string $message, string $code = '', \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->code = $code; // String code.

        if (mb_strpos($this->code, '.') !== false) {
            $this->error_code  = mb_strstr($this->code, '.', true);
            $this->reason_code = mb_substr(mb_strstr($this->code, '.'), 1);
        } else {
            $this->error_code = $this->reason_code = '';
        }
    }

    /**
     * Get exception code.
     *
     * @since 150424 Initial release.
     *
     * @return string Exception code.
     */
    public function getErrorCode(): string
    {
        return $this->error_code;
    }

    /**
     * Get exception reason.
     *
     * @since 150424 Initial release.
     *
     * @return string Exception reason.
     */
    public function getReasonCode(): string
    {
        return $this->reason_code;
    }
}
