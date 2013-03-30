<?php
/**
 * Slim - a micro PHP 5 framework
 *
 * @author      Josh Lockhart
 * @link        http://www.slimframework.com
 * @copyright   2011 Josh Lockhart
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * View_Mustache
 *
 * The View_Mustache is a Custom View class that renders templates using the
 * Mustache template language (http://mustache.github.com/) and the
 * [Mustache.php library](github.com/bobthecow/mustache.php).
 *
 * There is one field that you, the developer, will need to change:
 * - mustacheDirectory
 * Setting the field is not required if an autoloader is already in place and it
 * finds the Mustache class(es).
 *
 * The class is based on the Mustache view provided with the most recent version
 * of slim-extras, 2.0.3 as of this writing (which contains fixes by bobthecow,
 * the developer behind Mustache.php). On top of that, it contains a number of
 * important customizations:
 *
 * - This version of the class is compatible with PHP 5.2 and does not use
 *   namespaces.
 * - It requires Slim 1.x, which is compatible with PHP 5.2, and not the Slim 2.x
 *   branch (PHP 5.3 only). Hence the class extends Slim_View, not \Slim\View.
 * - The class is called View_Mustache, not Mustache. This is necessary to avoid
 *   conflicts in the absence of namespaces.
 * - The class allows an object to be used as view data, thus enabling the use of
 *   Mustache lambdas in PHP 5.2. The version in the official repo only supports
 *   arrays. This functionality is fully encapsulated in the appendData() method.
 * - Some explanatory comments are fixed.
 *
 * @package Slim
 * @author  Johnson Page <http://johnsonpage.org>
 * @author  Michael Heim <http://www.zeilenwechsel.de>
 */
class View_Mustache extends Slim_View
{
    /**
     * @var string The path to the directory containing Mustache.php.
     *             Not required if an autoloader covering Mustache.php is
     *             already in place.
     */
    public static $mustacheDirectory = null;

    /**
     * @var array An array of Mustache_Engine options
     */
    public static $mustacheOptions = array();

    /**
     * @var Mustache_Engine A Mustache engine instance for this view
     */
    private $engine = null;

    /**
     * @param array $options Mustache_Engine configuration options
     */
    public function __construct(array $options = null)
    {
        if ($options !== null) {
            self::$mustacheOptions = $options;
        }
    }

    /**
     * Append data to existing View data. Accepts arrays as well as an object
     * (for PHP-5.2-compatible lambda functions in Mustache).
     *
     * Note that you can append arrays to an existing object, or an object to an
     * existing array, but NOT an object to an object. Use one object only, at
     * the most.
     *
     * @throws  InvalidArgumentException
     * @param   array|Object $data
     * @return  void
     */
    public function appendData( $data )
    {
        if ( is_object( $this->data ) and is_object($data) )
        {
            // Can't merge two objects safely, internal state might be lost
            throw new InvalidArgumentException("Can't merge view data of multiple objects");
        }
        elseif ( is_object( $this->data ) )
        {
            foreach ( $data as $property => $item ) $this->data->$property = $item;
        }
        elseif ( is_object( $data ) )
        {
            foreach ( $this->data as $property => $item ) $data->$property = $item;
            $this->data = $data;
        }
        else
        {
            $this->data = array_merge( $this->data, $data );
        }
    }

    /**
     * Renders a template using Mustache.php.
     *
     * @see View::render()
     * @param string $template The template name specified in Slim::render()
     * @return string
     */
    public function render($template)
    {
        return $this->getEngine()->render($template, $this->data);
    }

    /**
     * Get a Mustache_Engine instance.
     *
     * @return Mustache_Engine
     */
    private function getEngine()
    {
        if (!isset($this->engine)) {
            // Check for Composer autoloading
            if (!class_exists('Mustache_Engine')) {
                require_once self::$mustacheDirectory . '/Autoloader.php';
                Mustache_Autoloader::register(dirname(self::$mustacheDirectory));
            }

            $options = self::$mustacheOptions;

            // Autoload templates from the templates directory.
            if (!isset($options['loader'])) {
                $options['loader'] = new Mustache_Loader_FilesystemLoader($this->getTemplatesDirectory());
            }

            // If a partials loader is not specified, fall back to the default template loader.
            if (!isset($options['partials_loader']) && !isset($options['partials'])) {
                $options['partials_loader'] = $options['loader'];
            }

            $this->engine = new Mustache_Engine($options);
        }

        return $this->engine;
    }
}
