<?php

namespace TasksBundle\Sonata;

use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
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
     * @param string $name
     * @param EngineInterface $templating
     * @param TaskManager $taskManager
     */
    public function __construct($name, EngineInterface $templating, TaskManager $taskManager)
    {
        parent::__construct($name, $templating);
        $this->taskManager = $taskManager;
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
        
        $statuses = array(
            'task.status.all'
        );
        
        $entityStatuses = call_user_func_array(array($this->taskManager->getEntityClass(), 'getStatuses'), array());
        foreach ($entityStatuses as $status) {
            $statuses[$status] = 'task.status.' . $status;
        }
        
        $settings = $blockContext->getSettings();
        return $this->renderPrivateResponse($blockContext->getTemplate(), array(
            'block' => $blockContext->getBlock(),
            'block_context'  => $blockContext,
            'tasks' => $tasks,
            'statuses' => $statuses,
            'settings' => $settings,
            'translation_domain' => $blockContext->getSetting('translation_domain'),
        ));
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
            'translation_domain' => 'messages'
        ));
    }

}
