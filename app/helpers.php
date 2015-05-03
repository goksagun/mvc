<?php

function dd()
{
    $args = func_get_args();

    foreach ($args as $arg) {
        echo "<pre>";
        var_dump($arg);
        echo "</pre>";
    }
    die();
}

function public_path($path = '')
{
    return getcwd().$path;
}

function base_path($path = '')
{
    $path = $path ? starts_with($path, '/') ? $path : '/'.$path : $path;
    return str_replace('/public', '', public_path()).$path;
}

function app_path($path = '')
{
    $path = $path ? starts_with($path, '/') ? $path : '/'.$path : $path;
    return base_path().'/app'.$path;
}

function views_path($path = '')
{
    $path = $path ? starts_with($path, '/') ? $path : '/'.$path : $path;
    return app_path().'/views'.$path;
}


function storage_path($path = '')
{
    $path = $path ? starts_with($path, '/') ? $path : '/'.$path : $path;
    return base_path().'/storage'.$path;
}

function get_url()
{
    $url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    return $url;
}

function json_to($data = '', $options = JSON_FORCE_OBJECT)
{
    echo json_encode($data, $options);
    exit();
}

function now()
{
    return date('Y-m-d H:i:s', time());
}

function bcrypt($password)
{
    /**
     * Note that the salt here is randomly generated.
     * Never use a static salt or one that is not randomly generated.
     *
     * For the VAST majority of use-cases, let password_hash generate the salt randomly for you
     */
    $options = [
        'cost' => 11,
        'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
    ];

    return password_hash($password, config_get('app.cipher'), $options);
}

function env($value = '', $default = false)
{
    return getenv($value) ? getenv($value) : $default;
}

function array_set(&$array, $key, $value)
{
    if (is_null($key)) return $array = $value;

    $keys = explode('.', $key);

    while (count($keys) > 1) {
        $key = array_shift($keys);

        // If the key doesn't exist at this depth, we will just create an empty array
        // to hold the next value, allowing us to create the arrays to hold final
        // values at the correct depth. Then we'll keep digging into the array.
        if ( ! isset($array[$key]) || ! is_array($array[$key])) {
            $array[$key] = [];
        }

        $array =& $array[$key];
    }

    $array[array_shift($keys)] = $value;

    return $array;
}


function array_get($array, $key, $default = null)
{
    if (is_null($key)) return $array;

    if (isset($array[$key])) return $array[$key];

    foreach (explode('.', $key) as $segment) {
        if ( ! is_array($array) || ! array_key_exists($segment, $array)) {
            return $default;
        }

        $array = $array[$segment];
    }

    return $array;
}

function array_has($array, $key)
{
    if (empty($array) || is_null($key)) return false;

    if (array_key_exists($key, $array)) return true;

    foreach (explode('.', $key) as $segment) {
        if ( ! is_array($array) || ! array_key_exists($segment, $array)) {
            return false;
        }

        $array = $array[$segment];
    }

    return true;
}

function config_get($key, $default = null)
{
    return (new App\Config)->get($key, $default);
    // return App\Config::get($key, $default);
}

function config_set($key, $value)
{
    return (new App\Config)->set($key, $value);
    // return App\Config::set($key, $value);
}

function view($view = '', $data = array())
{
    return App\View::render($view, $data);
}

function redirect($uri='', $code=302)
{
    return App\Response::redirect($uri, $code);
}

// returns true if $needle is a substring of $haystack
function contains($needle, $haystack, $insensetive = true)
{
    // return stripos($haystack, $needle) !== false;
    // preg_match("/\bcebir\b/i", "Cebir en sevdiğim derstir.")
    $pattern = $insensetive ? '/'.$needle.'\b/i' : '/'.$needle.'\b/';

    return (bool) preg_match($pattern, $haystack);
}

function is_multi_array($array)
{
    foreach ($array as $item) {
        if (is_array($item)) return true;
    }
    return false;
}

function starts_with($str, $needle)
{
   return substr($str, 0, strlen($needle)) === $needle;
}

function ends_with($str, $needle)
{
   $length = strlen($needle);
   return ! $length || substr($str, - $length) === $needle;
}

function parse_classname($name, $full = false)
{
    $namespace = array_slice(explode('\\', $name), 0, -1);
    $classname = join('', array_slice(explode('\\', $name), -1));

    if ($full) {
        return array(
            'namespace' => $namespace,
            'classname' => $classname,
        );
    }

    return $classname;
}

// /**
//  * Pluralizes a word if quantity is not one.
//  *
//  * @param int $quantity Number of items
//  * @param string $singular Singular form of word
//  * @param string $plural Plural form of word; function will attempt to deduce plural form from singular if not provided
//  * @return string Pluralized word if quantity is not one, otherwise singular
//  */
// function pluralize($quantity, $singular, $plural=null)
// {
//     if($quantity==1 || empty($singular)) return $singular;
//     if($plural!==null) return $plural;

//     $last_letter = strtolower($singular[strlen($singular)-1]);
//     switch($last_letter) {
//         case 'y':
//             return substr($singular,0,-1).'ies';
//         case 's':
//             return $singular.'es';
//         default:
//             return $singular.'s';
//     }
// }

/**
* Pluralizes English nouns.
*
* @access public
* @static
* @param    string    $word    English noun to pluralize
* @return string Plural noun
*/
function pluralize($word)
{
    $plural = array(
        '/(quiz)$/i' => '\1zes',
        '/^(ox)$/i' => '\1en',
        '/([m|l])ouse$/i' => '\1ice',
        '/(matr|vert|ind)ix|ex$/i' => '\1ices',
        '/(x|ch|ss|sh)$/i' => '\1es',
        '/([^aeiouy]|qu)ies$/i' => '\1y',
        '/([^aeiouy]|qu)y$/i' => '\1ies',
        '/(hive)$/i' => '\1s',
        '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
        '/sis$/i' => 'ses',
        '/([ti])um$/i' => '\1a',
        '/(buffal|tomat)o$/i' => '\1oes',
        '/(bu)s$/i' => '\1ses',
        '/(alias|status)/i'=> '\1es',
        '/(octop|vir)us$/i'=> '\1i',
        '/(ax|test)is$/i'=> '\1es',
        '/s$/i'=> 's',
        '/$/'=> 's');
    $uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep');
    $irregular = array(
        'person' => 'people',
        'man' => 'men',
        'child' => 'children',
        'sex' => 'sexes',
        'move' => 'moves'
        );
    $lowercased_word = strtolower($word);
    foreach ($uncountable as $_uncountable){
        if(substr($lowercased_word,(-1*strlen($_uncountable))) == $_uncountable){
            return $word;
        }
    }
    foreach ($irregular as $_plural=> $_singular){
        if (preg_match('/('.$_plural.')$/i', $word, $arr)) {
            return preg_replace('/('.$_plural.')$/i', substr($arr[0],0,1).substr($_singular,1), $word);
        }
    }
    foreach ($plural as $rule => $replacement) {
        if (preg_match($rule, $word)) {
            return preg_replace($rule, $replacement, $word);
        }
    }
    return false;
}
/**
* Singularizes English nouns.
*
* @access public
* @static
* @param    string    $word    English noun to singularize
* @return string Singular noun.
*/
function singularize($word)
{
    $singular = array (
        '/(quiz)zes$/i' => '\1',
        '/(matr)ices$/i' => '\1ix',
        '/(vert|ind)ices$/i' => '\1ex',
        '/^(ox)en/i' => '\1',
        '/(alias|status)(es)?$/i' => '\1',
        '/([octop|vir])i$/i' => '\1us',
        '/(cris|ax|test)es$/i' => '\1is',
        '/(shoe)s$/i' => '\1',
        '/(o)es$/i' => '\1',
        '/(bus)(es)?$/i' => '\1',
        '/([m|l])ice$/i' => '\1ouse',
        '/(x|ch|ss|sh)es$/i' => '\1',
        '/(m)ovies$/i' => '\1ovie',
        '/(s)eries$/i' => '\1eries',
        '/([^aeiouy]|qu)ies$/i' => '\1y',
        '/([lr])ves$/i' => '\1f',
        '/(tive)s$/i' => '\1',
        '/(hive)s$/i' => '\1',
        '/([^f])ves$/i' => '\1fe',
        '/(^analy)ses$/i' => '\1sis',
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
        '/([ti])a$/i' => '\1um',
        '/(n)ews$/i' => '\1ews',
        '/s$/i' => '',
        );
    $uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep');
    $irregular = array(
        'person' => 'people',
        'man' => 'men',
        'child' => 'children',
        'sex' => 'sexes',
        'move' => 'moves');
    $lowercased_word = strtolower($word);
    foreach ($uncountable as $_uncountable){
        if(substr($lowercased_word,(-1*strlen($_uncountable))) == $_uncountable){
            return $word;
        }
    }
    foreach ($irregular as $_plural=> $_singular){
        if (preg_match('/('.$_singular.')$/i', $word, $arr)) {
            return preg_replace('/('.$_singular.')$/i', substr($arr[0],0,1).substr($_plural,1), $word);
        }
    }
    foreach ($singular as $rule => $replacement) {
        if (preg_match($rule, $word)) {
            return preg_replace($rule, $replacement, $word);
        }
    }
    return $word;
}
/**
* Converts an underscored or CamelCase word into a English
* sentence.
*
* The titleize public function converts text like "WelcomePage",
* "welcome_page" or  "welcome page" to this "Welcome
* Page".
* If second parameter is set to 'first' it will only
* capitalize the first character of the title.
*
* @access public
* @static
* @param    string    $word    Word to format as tile
* @param    string    $uppercase    If set to 'first' it will only uppercase the
* first character. Otherwise it will uppercase all
* the words in the title.
* @return string Text formatted as title
*/
function titleize($word, $uppercase = '')
{
    $uppercase = $uppercase == 'first' ? 'ucfirst' : 'ucwords';
    return $uppercase(humanize(underscore($word)));
}
/**
* Returns given word as CamelCased
*
* Converts a word like "send_email" to "SendEmail". It
* will remove non alphanumeric character from the word, so
* "who's online" will be converted to "WhoSOnline"
*
* @access public
* @static
* @see variablize
* @param    string    $word    Word to convert to camel case
* @return string UpperCamelCasedWord
*/
function camelize($word)
{
    return str_replace(' ','',ucwords(preg_replace('/[^A-Z^a-z^0-9]+/',' ',$word)));
}
/**
* Converts a word "into_it_s_underscored_version"
*
* Convert any "CamelCased" or "ordinary Word" into an
* "underscored_word".
*
* This can be really useful for creating friendly URLs.
*
* @access public
* @static
* @param    string    $word    Word to underscore
* @return string Underscored word
*/
function underscore($word)
{
    return  strtolower(preg_replace('/[^A-Z^a-z^0-9]+/','_',
        preg_replace('/([a-zd])([A-Z])/','\1_\2',
            preg_replace('/([A-Z]+)([A-Z][a-z])/','\1_\2',$word))));
}
/**
* Returns a human-readable string from $word
*
* Returns a human-readable string from $word, by replacing
* underscores with a space, and by upper-casing the initial
* character by default.
*
* If you need to uppercase all the words you just have to
* pass 'all' as a second parameter.
*
* @access public
* @static
* @param    string    $word    String to "humanize"
* @param    string    $uppercase    If set to 'all' it will uppercase all the words
* instead of just the first one.
* @return string Human-readable word
*/
function humanize($word, $uppercase = '')
{
    $uppercase = $uppercase == 'all' ? 'ucwords' : 'ucfirst';
    return $uppercase(str_replace('_',' ',preg_replace('/_id$/', '',$word)));
}
/**
* Same as camelize but first char is underscored
*
* Converts a word like "send_email" to "sendEmail". It
* will remove non alphanumeric character from the word, so
* "who's online" will be converted to "whoSOnline"
*
* @access public
* @static
* @see camelize
* @param    string    $word    Word to lowerCamelCase
* @return string Returns a lowerCamelCasedWord
*/
function variablize($word)
{
    $word = camelize($word);
    return strtolower($word[0]).substr($word,1);
}
/**
* Converts a class name to its table name according to rails
* naming conventions.
*
* Converts "Person" to "people"
*
* @access public
* @static
* @see classify
* @param    string    $class_name    Class name for getting related table_name.
* @return string plural_table_name
*/
function tableize($class_name)
{
    return pluralize(underscore($class_name));
}
/**
* Converts a table name to its class name according to rails
* naming conventions.
*
* Converts "people" to "Person"
*
* @access public
* @static
* @see tableize
* @param    string    $table_name    Table name for getting related ClassName.
* @return string SingularClassName
*/
function classify($table_name)
{
    return camelize(singularize($table_name));
}
/**
* Converts number to its ordinal English form.
*
* This method converts 13 to 13th, 2 to 2nd ...
*
* @access public
* @static
* @param    integer    $number    Number to get its ordinal value
* @return string Ordinal representation of given string.
*/
function ordinalize($number)
{
    if (in_array(($number % 100),range(11,13))){
        return $number.'th';
    } else {
        switch (($number % 10)) {
            case 1:
            return $number.'st';
            break;
            case 2:
            return $number.'nd';
            break;
            case 3:
            return $number.'rd';
            default:
            return $number.'th';
            break;
        }
    }
}
