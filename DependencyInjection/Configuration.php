<?php

namespace TasksBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tasks');

        $rootNode
                ->children()
                    ->scalarNode('layout')
                        ->cannotBeEmpty()
                        ->defaultValue('TasksBundle::layout.html.twig')
                    ->end()
                    ->scalarNode('entity_class')
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('form_type_service')
                        ->cannotBeEmpty()
                        ->defaultValue('tasks.edit_task_type')
                    ->end()
                    ->scalarNode('template_list')
                        ->cannotBeEmpty()
                        ->defaultValue('TasksBundle::Default/index.html.twig')
                    ->end()
                    ->scalarNode('template_edit')
                        ->cannotBeEmpty()
                        ->defaultValue('TasksBundle::Default/edit.html.twig')
                    ->end()
                    ->booleanNode('redirect_after_save')
                        ->defaultValue(true)
                    ->end()
                ->end()
        ;

        return $treeBuilder;
    }
}
