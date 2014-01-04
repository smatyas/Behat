<?php

/*
 * This file is part of the Behat.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Behat\Context\ClassGenerator;

use Behat\Testwork\Suite\Suite;

/**
 * Simple context class generator.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class SimpleContextClassGenerator implements ContextClassGenerator
{
    /**
     * @var string
     */
    protected static $template = <<<'PHP'
<?php

{namespace}use Behat\Behat\Context\TurnipAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Behat context class.
 */
class {className} implements TurnipAcceptingContext
{
    /**
     * Initializes context. Every scenario gets it's own context object.
     *
     * @param array $parameters Suite parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
    }
}

PHP;

    /**
     * {@inheritdoc}
     */
    public function supportsSuiteAndClassname(Suite $suite, $classname)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function generateClass(Suite $suite, $classname)
    {
        $fqn = $classname;

        $namespace = '';
        if (false !== $pos = strrpos($fqn, '\\')) {
            $namespace = 'namespace ' . substr($fqn, 0, $pos) . ";\n\n";
            $classname = substr($fqn, $pos + 1);
        }

        return strtr(
            static::$template,
            array(
                '{namespace}' => $namespace,
                '{className}' => $classname,
            )
        );
    }
}
