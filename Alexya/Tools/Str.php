<?php
namespace Alexya\Tools;

/**
 * String helpers.
 *
 * This class offers helpers for string manipulation.
 *
 * Method summary:
 *
 * |    Name    |             Parameters             |   Return type   |                                    Description                                    |
 * |------------|:----------------------------------:|:---------------:|-----------------------------------------------------------------------------------|
 * | startsWith |  `string $base`, `string $starts`  |     `bool`      | Checks that `$base` starts with `$starts`, returns `true` if so, `false` if not.  |
 * | endsWith   |   `string $base`, `string $ends`   |     `bool`      | Checks that `$base` ends with `$ends`, returns `true` if so, `false` if not.      |
 * | contains   | `string $base`, `string/array $str |     `bool`      | Checks that `$base` contains any of `$str`, returns `true` if so, `false if not`. |
 * | snake      |         `string/array $str`        |    `string`     | Returns `$str` as `snake_case`.                                                   |
 * | camel      |         `string/array $str`        |    `string`     | Returns `$str` as `camelCase`.                                                    |
 * | singular   |         `string/array $str`        | `string/arrray` | Returns the singular form of `$str`.                                              |
 * | plural     |         `string/array $str`        | `string/array`  | Returns the plural form of `$str`.                                                |
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Str
{
    /**
     * Checks that `$base` starts with `$starts`.
     *
     * @param string $base   Base string to check.
     * @param string $starts The starting string.
     *
     * @return bool `true` if `$base` starts with `$starts`, `false` if not.
     */
    public static function startsWith(string $base, string $starts) : bool
    {
        if(
            $starts === "" ||
            strrpos($base, $starts, -strlen($base)) !== false
        ) {
            return true;
        }

        return false;
    }

    /**
     * Checks that `$base` ends with `$ends`.
     *
     * @param string $base Base string to check.
     * @param string $ends The ending string.
     *
     * @return bool `true` if `$base` ends with `$ends`, `false` if not.
     */
    public static function endsWith(string $base, string $ends) : bool
    {
        if($ends === "") {
            return true;
        }

        $temp = strlen($base) - strlen($ends);

        if(
            $temp >= 0 &&
            strpos($base, $ends, $temp) !== false
        ) {
            return true;
        }

        return false;
    }

    /**
     * Checks that `$str` contains `$chars`.
     *
     * If `$chars` is an array and `$str` contains,at least,
     * one of them, `true` will be returned.
     *
     * @param string       $str   String to check.
     * @param string|array $chars Chars that `$str` should contain.
     *
     * @return bool `true` if `$str` contains any of `$chars`, `false` if not.
     */
    public static function contains(string $str, $chars) : bool
    {
        if(is_array($chars)) {
            foreach($chars as $char) {
                if(Str::contains($str, $char)) {
                    // Too much indentation, I'll find another way to organize this
                    return true;
                }
            }
        }

        if(!is_string($chars)) {
            // Maybe throw exception?
            return false;
        }

        if(strpos($str, $chars) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Parses `$str` and returns it as `snake_case`.
     *
     * If `$str` is an array it will asume each index is a word,
     * if not, each word will start with a capital leter or they
     * will be separated by one or more spaces:
     *
     *     echo Str::snake(["users", "orm"]); // users_orm
     *     echo Str::snake("usersORM"); // users_orm
     *     echo Str::snake("users     orm"); // users_orm
     *
     * @param string|array $str String to parse.
     *
     * @return string `$str` as `snake_case`.
     */
    public function snake($str) : string
    {
        if(is_array($str)) {
            return strtolower(implode("_", $str));
        }

        if(!is_string($str)) {
            // Maybe throw exception?
            return "";
        }

        if(Str::contains($str, " ")) {
            // Replace various spaces, each one next to other ("users    orm")
            $str = preg_replace("(\ *)", " ", $str);

            // Replace spaces with underscore and lowercasefy it
            return strtolower(str_replace(" ", "_", $str));
        }

        // See http://stackoverflow.com/questions/1993721/how-to-convert-camelcase-to-camel-case
        // 'cause copy-pasting is the way to go
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $str, $matches);
        $ret = $matches[0];

        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return Str::snake($ret);
    }

    /**
     * Parses `$str` and returns it as `camelCase`.
     *
     * If `$str` is an array it will asume each index is a word,
     * if not, each word will be separated by one or more underscores (`_`)
     * or spaces (` `)
     *
     *     echo Str::camel(["users", "orm"]); // usersOrm
     *     echo Str::camel("users_ORM"); // usersORM
     *     echo Str::camel("users     orm"); // usersOrm
     *
     * @param string|array $str String to parse.
     *
     * @return string `$str` as `snake_case`.
     */
    public function snake($str) : string
    {
        if(is_array($str)) {
            $ret = $str[0];

            for($i = 1; $i < count($str); $i++) {
                $ret .= ucfirst($str[$i]);
            }

            return $ret;
        }

        if(!is_string($str)) {
            // Maybe throw exception?
            return "";
        }

        $ret = explode(
            "{BIGASSPLACEHOLDERTOASSUREITDOESNTEXISTSINTHEORIGINALSTRING}",
            preg_replace("((\ |\_)*)", "{BIGASSPLACEHOLDERTOASSUREITDOESNTEXISTSINTHEORIGINALSTRING}", $str)
        );

        return Str::camel($ret);
    }

    /**
     * Returns the plural form of `$word`.
     *
     * If `$word` is an array all indexes will be pluralized
     * and returned.
     *
     * @param string|array $word Word(s) to pluralized.
     *
     * @return string|array The pluralized form of `$word`.
     */
    public static function plural($word)
    {
        if(is_array($word)) {
            foreach($word as $key => $value) {
                $word[$key] = Str::plural($word);
            }

            return $word;
        }

        if(!is_string($word)) {
            // Exception?
            return "";
        }

        return Inflector::plural($word);
    }

    /**
     * Returns the singular form of `$word`.
     *
     * If `$word` is an array all indexes will be singularized
     * and returned.
     *
     * @param string|array $word Word(s) to singularized.
     *
     * @return string|array The singularized form of `$word`.
     */
    public static function singular($word)
    {
        if(is_array($word)) {
            foreach($word as $key => $value) {
                $word[$key] = Str::singular($word);
            }

            return $word;
        }

        if(!is_string($word)) {
            // Exception?
            return "";
        }

        return Inflector::singular($word);
    }
}
