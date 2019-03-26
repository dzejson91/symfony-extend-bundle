<?php

/**
 * @author Krystian Jasnos <dzejson91@gmail.com>
 */

namespace JasonMx\ExtendBundle\DependencyInjection;

use JasonMx\Components\Form\Extension\CollectionTypeExtension;
use JasonMx\Components\Form\Extension\FormTypeExtension;
use JasonMx\Components\Form\Extension\TransTypeExtension;
use JasonMx\Components\Form\Field\CheckboxToggleType;
use JasonMx\Components\Form\Field\DropzoneType;
use JasonMx\ExtendBundle\EventListener\TablePrefixListener;
use JasonMx\Components\Menu\MenuExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class ExtendExtension
 * @package BaseBundle\DependencyInjection
 */
class ExtendExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $containerBuilder
     * @throws
     */
    public function load(array $configs, ContainerBuilder $containerBuilder)
    {
        $containerBuilder
            ->setDefinition('extend.twig_ext', new Definition(
                TwigExtension::class,
                array(
                    new Reference('service_container'),
                )
            ))
            ->addTag('twig.extension');

        $this->addSubscribers($containerBuilder);
        $this->addFormFields($containerBuilder);
        $this->addFormExtensions($containerBuilder);

        MenuExtension::load($containerBuilder);
    }

    public function addSubscribers(ContainerBuilder $containerBuilder){

        $containerBuilder
            ->setDefinition('extend.table_prefix.listener', new Definition(
                TablePrefixListener::class,
                array('%database_table_prefix%')
            ))
            ->addTag('doctrine.event_listener', ['event' => 'loadClassMetadata']);
    }

    public function addFormFields(ContainerBuilder $containerBuilder){

        $containerBuilder
            ->setDefinition('extend.form.type.checkbox_toogle', new Definition(
                CheckboxToggleType::class, array(
                    new Reference('translator'),
                )
            ))
            ->addTag('form.type');

        $containerBuilder
            ->setDefinition('extend.form.type.dropzone', new Definition(
                DropzoneType::class
            ))
            ->addTag('form.type');
    }

    public function addFormExtensions(ContainerBuilder $containerBuilder){

        $containerBuilder
            ->setDefinition('extend.form.extension', new Definition(
                FormTypeExtension::class
            ))
            ->addTag('form.type_extension', array(
                'extended_type' => FormType::class
            ));

        $containerBuilder
            ->setDefinition('extend.form.extension.collection', new Definition(
                CollectionTypeExtension::class
            ))
            ->addTag('form.type_extension', array(
                'extended_type' => CollectionType::class
            ));

        $containerBuilder
            ->setDefinition('extend.form.extension.trans', new Definition(
                TransTypeExtension::class
            ))
            ->addTag('form.type_extension', array(
                'extended_type' => FormType::class
            ));
    }
}