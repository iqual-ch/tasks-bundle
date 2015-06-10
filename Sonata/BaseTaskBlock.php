<?php

namespace TasksBundle\Sonata;

use DateTime;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TasksBundle\TaskManager;

class BaseTaskBlock extends BaseBlockService
{
    /**
     *
     * @var TaskManager
     */
    protected $taskManager;
    
    /**
     *
     * @var Request
     */
    protected $request;
    
    /**
     * 
     * @param string $name
     * @param EngineInterface $templating
     * @param TaskManager $taskManager
     */
    public function __construct($name, EngineInterface $templating, TaskManager $taskManager, Request $request)
    {
        parent::__construct($name, $templating);
        $this->taskManager = $taskManager;
        $this->request = $request;
    }
    
    /**
     * 
     * @param BlockContextInterface $blockContext
     * @param Response $response
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $qb = $this->taskManager->getQueryBuilder();
        if (is_callable($blockContext->getSetting('query_builder'))) {
            call_user_func_array($blockContext->getSetting('query_builder'), array($qb));
        }
       
        $tasks = $qb->getQuery()->getResult();
        
        $viewParams = array_replace_recursive(array(
            'block' => $blockContext->getBlock(),
            'block_context'  => $blockContext,
            'tasks' => $tasks,
            'block_id' => $blockContext->getSetting('block_id'),
            'settings' => $blockContext->getSettings(),
            'now' => new DateTime,
            'translation_domain' => $blockContext->getSetting('translation_domain'),
        ), $blockContext->getSetting('view_params'));
        
        return $this->renderPrivateResponse($blockContext->getTemplate(), $viewParams);
    }
    
    /**
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'title' => 'block.title.tasks',
            'template' => 'TasksBundle:Sonata:block_tasks.html.twig',
            'translation_domain' => 'messages',
            'block_id' => uniqid(),
            'view_params' => array()
        ));
    }

}
