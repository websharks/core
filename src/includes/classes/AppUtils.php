<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * App utilities.
 *
 * @since 150424 Initial release.
 */
class AppUtils extends AppBase
{
    /**
     * Magic utility factory.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property.
     *
     * @return mixed Overloaded property value.
     */
    public function __get(string $property)
    {
        if (class_exists($this->App->namespace.'\\Utils\\'.$property)) {
            $utility = $this->App->Di->get($this->App->namespace.'\\Utils\\'.$property);
            $this->overload((object) [$property => $utility], true);
            return $utility;
        } elseif (class_exists(__NAMESPACE__.'\\Utils\\'.$property)) {
            $utility = $this->App->Di->get(__NAMESPACE__.'\\Utils\\'.$property);
            $this->overload((object) [$property => $utility], true);
            return $utility;
        }
        return parent::__get($property);
    }

    /**
     * Magic utility factory.
     *
     * @since 150424 Initial release.
     *
     * @param string $method Method to call upon.
     * @param array  $args   Arguments to pass to the method.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __call(string $method, array $args = [])
    {
        if (isset($this->¤¤overload[$method])) {
            return $this->¤¤overload[$method](...$args);
        } elseif (class_exists($this->App->namespace.'\\Utils\\'.$method)) {
            $utility = $this->App->Di->get($this->App->namespace.'\\Utils\\'.$method);
            $this->overload((object) [$method => $utility], true);
            return $utility(...$args);
        } elseif (class_exists(__NAMESPACE__.'\\Utils\\'.$method)) {
            $utility = $this->App->Di->get(__NAMESPACE__.'\\Utils\\'.$method);
            $this->overload((object) [$method => $utility], true);
            return $utility(...$args);
        }
        return parent::__call($property);
    }
}
