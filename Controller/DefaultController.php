<?php

namespace TasksBundle\Controller;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use TasksBundle\Event\TaskEvent;

/**
 * @Route("/tasks", name="tasks")
 */
class DefaultController extends Controller
{

    /**
     * @Route("/", name="tasks_list")
     * @Route("/filter", name="tasks_filter")
     */
    public function indexAction(Request $request)
    {
        $query = $request->query->all();
        if (isset($query['_locale'])) {
            unset($query['_locale']);
        }
        $tasks = $this->get('task_manager')->search($query);
        return $this->render($this->container->getParameter('tasks.template_list'), array(
            'tasks' => $tasks,
            'user' => $this->getUser(),
            'layout' => $this->container->getParameter('tasks.layout')
        ));
    }

    /**
     * @Route("/create", name="tasks_create")
     * @Route("/edit/{id}", name="tasks_edit", requirements={"id": "\d+"})
     * @Template
     */
    public function editAction(Request $request, $id = null)
    {
        if ($id) {
            $task = $this->get('task_manager')->find($id);
            if (!$task) {
                throw $this->createNotFoundException($this->get('translator')->trans('flash.task_not_found', array(), 'TasksBundle'));
            }
        } else {
            $task = $this->get('task_manager')->create();
        }

        /* @var $formType AbstractType */
        $formType = $this->get('tasks.edit_task_type');
        $form = $this->createForm($formType, $task);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $this->get('task_manager')->store($form->getData());
                    $this->addFlash('success', $this->get('translator')->trans('flash.task_edited', array(), 'TasksBundle'));
                    $this->get('event_dispatcher')->dispatch(TaskEvent::EVENT_EDITED, new TaskEvent($form->getData()));
                    
                    if ($this->container->getParameter('tasks.redirect_after_save')) {
                        return $this->redirectToRoute('tasks_list');
                    }
                } catch (Exception $e) {
                    $this->addFlash('error', $e->getMessage());
                    $this->get('event_dispatcher')->dispatch(TaskEvent::EVENT_EDIT_ERROR, new TaskEvent($form->getData()));
                    $this->addFlash('error', $this->get('translator')->trans('flash.task_save_error', array(), 'TasksBundle'));
                }
            }
        }

        return $this->render($this->container->getParameter('tasks.template_edit'), array(
            'form' => $form->createView(),
            'layout' => $this->container->getParameter('tasks.layout')
        ));
    }

    /**
     * @Route("/remove/{id}", name="tasks_remove", requirements={"id": "\d+"})
     */
    public function removeAction($id)
    {
        $task = $this->get('task_manager')->find($id);
        if ($task) {
            $this->get('task_manager')->remove($id);
            $this->addFlash('success', $this->get('translator')->trans('flash.task_removed', array(), 'TasksBundle'));
            $this->get('event_dispatcher')->dispatch(TaskEvent::EVENT_DELETED, new TaskEvent($task));
        } else {
            $this->addFlash('error', $this->get('translator')->trans('flash.task_remove_error', array(), 'TasksBundle'));
            $this->get('event_dispatcher')->dispatch(TaskEvent::EVENT_DELETE_ERROR, new TaskEvent);
        }
        return $this->redirectToRoute('tasks_list');
    }
}
