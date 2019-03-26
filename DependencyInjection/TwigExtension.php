<?php

/**
 * @author Krystian Jasnos <dzejson91@gmail.com>
 */

namespace JasonMx\ExtendBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TwigExtension
 * @package AppBundle\DependencyInjection
 */
class TwigExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * TwigExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getGlobals()
    {
        return array();
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('json_encode', 'json_encode'),
            new \Twig_SimpleFilter('json_decode', 'json_decode'),
        );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('ddd', 'ddd'),
            new \Twig_SimpleFunction('d', 'd'),
            new \Twig_SimpleFunction('dump', 'dump'),

            new \Twig_SimpleFunction('fn', 'call_user_func'),
        );
    }
}