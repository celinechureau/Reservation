<?php

namespace ReservationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ReservationBundle\Entity\Salle;
use ReservationBundle\Form\SalleType;


/**
 * Salle controller.
 *
 * @Route("/Salle")
 */
class SalleController extends Controller
{

    /**
     * Lists all Salle entities.
     *
     * @Route("/", name="salle")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ReservationBundle:Salle')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Salle entity.
     *
     * @Route("/", name="salle_create")
     * @Method("POST")
     * @Template("ReservationBundle:Salle:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Salle();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $entity->getImg()->upload();
            
            $em->persist($entity);
            $em->flush();
            
          //  $returnArray = array("respondeCode"=> 200, "welldone" => "La salle a bien été ajoutée!");
          //  $return = json_encode($returnArray);
            
          //  return new Response($return, 200, array("Content-type" => "application/json"));

            return $this->redirect($this->generateUrl('salle_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Salle entity.
     *
     * @param Salle $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Salle $entity)
    {
        $form = $this->createForm(new SalleType(), $entity, array(
            'action' => $this->generateUrl('salle_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Salle entity.
     *
     * @Route("/new", name="salle_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Salle();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Salle entity.
     *
     * @Route("/{id}", name="salle_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ReservationBundle:Salle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Salle entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Salle entity.
     *
     * @Route("/{id}/edit", name="salle_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ReservationBundle:Salle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Salle entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Salle entity.
    *
    * @param Salle $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Salle $entity)
    {
        $form = $this->createForm(new SalleType(), $entity, array(
            'action' => $this->generateUrl('salle_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    /**
     * Edits an existing Salle entity.
     *
     * @Route("/{id}", name="salle_update")
     * @Method("PUT")
     * @Template("ReservationBundle:Salle:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ReservationBundle:Salle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Salle entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
        	
        	   $entity->getImg()->upload();
            $em->flush();

            return $this->redirect($this->generateUrl('salle_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Salle entity.
     *
     * @Route("/{id}", name="salle_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ReservationBundle:Salle')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Salle entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('salle'));
    }

    /**
     * Creates a form to delete a Salle entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('salle_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
        ;
    }
}
