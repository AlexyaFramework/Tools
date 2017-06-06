<?php
namespace Alexya\Tools\Session;

use Alexya\Tools\Collection;

/**
 * Session class.
 * ==============
 *
 * This class handles the session.
 *
 * The constructor accepts as parameter the session name, the lifetime of the cookie
 * and the path were it should be stored.
 *
 * Once the object is instantiated the session will be started, to stop it use the method `stop`.
 *
 * Method summary:
 *
 *  * `get`: Returns given variable.
 *  * `__get`: Alias of `get`.
 *  * `set`: Sets given variable.
 *  * `__set`: Alias of `set`.
 *  * `exists`: Checks whether a variable exists on the session.
 *  * `__isset`: Alias of `exists`.
 *  * `remove`: Deletes given variable.
 *  * `__unset`: Alias of `remove`
 *
 * Use the magic methods `__get` and `__set` to get and set session variables.
 * To remove a variable use the method `remove` or `__unset`.
 * To check if a variable exists use the method `exists` or `__isset`.
 *
 * Example:
 *
 * ```php
 * $Session = new \Alexya\Session("alexya", (3600 * 24), "sessions");
 * $Session->foo = "bar";
 * ```
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Session extends Collection
{
    /**
     * Constructor.
     *
     * @param string $name     Session name.
     * @param int    $lifetime Cookie's lifetime.
     * @param string $path     Save path.
     */
    public function __construct(string $name, int $lifetime, string $path)
    {
        //Start session
        session_start([
            "name"            => $name,
            "cookie_lifetime" => $lifetime,
            "save_path"       => $path
        ]);

        parent::__construct($_SESSION);
    }

    /**
     * Removes a variable from the session.
     *
     * @param mixed $name Variable name.
     */
    public function deleteByKey($name) : void
    {
        if(!$this->keyExists($name)) {
            return;
        }

        parent::deleteByKey($name);
        unset($_SESSION[$name]);
    }

    /**
     * Sets a variable.
     *
     * @param mixed $name  Variable name.
     * @param mixed $value Variable value.
     */
    public function set($name, $value) : void
    {
        $_SESSION[$name] = $value;

        parent::set($name, $value);
    }

    /**
     * Returns a variable.
     *
     * @param string $name    Variable name.
     * @param mixed  $default Default value to return.
     *
     * @return mixed Variable's value.
     */
    public function get(string $name, $default = null)
    {
        if(!$this->keyExists($name)) {
            return $default;
        }

        return $_SESSION[$name];
    }

    /**
     * Stops session.
     */
    public function stop()
    {
        session_destroy();
    }
}
