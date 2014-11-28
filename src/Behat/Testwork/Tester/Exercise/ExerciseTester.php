<?php

/*
 * This file is part of the Behat Testwork.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Testwork\Tester\Exercise;

use Behat\Testwork\Environment\EnvironmentManager;
use Behat\Testwork\Tester\Context\Context;
use Behat\Testwork\Tester\Context\ExerciseContext;
use Behat\Testwork\Tester\Context\SuiteContext;
use Behat\Testwork\Tester\Exception\WrongContextException;
use Behat\Testwork\Tester\Result\IntegerTestResult;
use Behat\Testwork\Tester\Result\TestResults;
use Behat\Testwork\Tester\RunControl;
use Behat\Testwork\Tester\Tester;

/**
 * Tests provided exercise.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
final class ExerciseTester implements Tester
{
    /**
     * @var Tester
     */
    private $suiteTester;
    /**
     * @var EnvironmentManager
     */
    private $environmentManager;

    /**
     * Initializes tester.
     *
     * @param Tester             $suiteTester
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(Tester $suiteTester, EnvironmentManager $environmentManager)
    {
        $this->suiteTester = $suiteTester;
        $this->environmentManager = $environmentManager;
    }

    /**
     * {@inheritdoc}
     */
    public function test(Context $context, RunControl $control)
    {
        $context = $this->castContext($context);
        $results = array();

        foreach ($context->getGroupedSpecificationIterators() as $iterator) {
            $suiteContext = SuiteContext::createUsingManager($iterator, $this->environmentManager);
            $testResult = $this->suiteTester->test($suiteContext, $control);
            $results[] = new IntegerTestResult($testResult->getResultCode());
        }

        return new TestResults($results);
    }

    /**
     * Casts provided context to the expected one.
     *
     * @param Context $context
     *
     * @return ExerciseContext
     *
     * @throws WrongContextException
     */
    private function castContext(Context $context)
    {
        if ($context instanceof ExerciseContext) {
            return $context;
        }

        throw new WrongContextException(
            sprintf(
                'ExerciseTester tests instances of ExerciseContext only, but %s given.',
                get_class($context)
            ), $context
        );
    }
}
