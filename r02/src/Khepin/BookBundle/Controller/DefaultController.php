<?php

namespace Khepin\BookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Khepin\BookBundle\Form\JoinEventType;
use Khepin\BookBundle\Event\MeetupEvent;
use Khepin\BookBundle\Event\MeetupEvents;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $boundaries = $this->get('user_locator')->getUserGeoBoundaries();

        // Create our database query
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->select('e')
            ->from('KhepinBookBundle:Event', 'e')
            ->where('e.latitude < :lat_max')
            ->andWhere('e.latitude > :lat_min')
            ->andWhere('e.longitude < :long_max')
            ->andWhere('e.longitude > :long_min')
            ->setParameters($boundaries);

        // Retrieve interesting events
        $events = $qb->getQuery()->execute();

        return compact('events');
    }

    /**
     * @Route("/events/{event_id}/join")
     * @Template()
     */
    public function joinAction($event_id) {
        $em = $this->getDoctrine()->getManager();
        $meetup = $em->getRepository('KhepinBookBundle:Event')->find($event_id);

        $form = $this->createForm(new JoinEventType(), $meetup, array(
            'action' => '',
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Join'));

        $form->handleRequest($this->get('request'));

        $user = $this->get('security.context')->getToken()->getUser();

        if ($form->isValid()) {
            $meetup->addAttendee($user);
            $this->get('event_dispatcher')->dispatch(MeetupEvents::MEETUP_JOIN, new MeetupEvent($user, $meetup));
            $em->flush();
        }

        $form = $form->createView();
        return compact('meetup', 'user', 'form');
    }
}
