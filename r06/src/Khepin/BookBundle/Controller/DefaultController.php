<?php

namespace Khepin\BookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Khepin\BookBundle\Form\JoinEventType;
use Khepin\BookBundle\Event\MeetupEvent;
use Khepin\BookBundle\Event\MeetupEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Khepin\BookBundle\Geo\Coordinate;
use Khepin\BookBundle\Form\AddressType;
use Khepin\BookBundle\Document\Meetup;
use Khepin\BookBundle\Security\Annotation\ValidateUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
            // ->where('e.latitude < :lat_max')
            // ->andWhere('e.latitude > :lat_min')
            // ->andWhere('e.longitude < :long_max')
            // ->andWhere('e.longitude > :long_min')
            // ->setParameters($boundaries)
        ;

        // Retrieve interesting events
        $events = $qb->getQuery()->execute();

        return compact('events');
    }

    /**
     * @Route("/events/{event_id}/join")
     * @Template()
     * @ValidateUser("join_event")
     */
    public function joinAction($event_id) {
        $reader = $this->get('annotation_reader');
        $method = new \ReflectionMethod(get_class($this), 'joinAction');
        $annotation_name = 'Khepin\BookBundle\Security\Annotation\ValidateUser';
        $annotation = $reader->getMethodAnnotations($method);


        $em = $this->getDoctrine()->getManager();
        $meetup = $em->getRepository('KhepinBookBundle:Event')->find($event_id);

        $form = $this->createForm(new JoinEventType(), $meetup, array(
            'action' => '',
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Join'));

        // $form->handleRequest($this->get('request'));

        $user = $this->get('security.context')->getToken()->getUser();

        // if ($form->isValid()) {
        //     $meetup->addAttendee($user);
        //     $this->get('event_dispatcher')->dispatch(MeetupEvents::MEETUP_JOIN, new MeetupEvent($user, $meetup));
        //     $em->flush();
        // }

        $form = $form->createView();
        return compact('meetup', 'user', 'form');
    }

    /**
     * @Route("/map")
     * @Template()
     */
    public function mapAction(Request $request)
    {
        $location = new Coordinate();
        $form = $this->createFormBuilder()
            ->add('location', 'coordinate')
            ->getForm();

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            $location = $form->getData()['location'];
        }

        $form = $form->createView();

        return compact('form', 'location');
    }

    /**
     * @Route("/address")
     * @Template()
     */
    public function addressAction(Request $request)
    {
        $message = '';
        $form = null;

        $address = new \Khepin\BookBundle\Entity\Address;

        if ($request->getMethod() === 'GET') {
            $country = $this->get('user_locator')->getCountryCode();
            $address->setCountry($country);

            $form = $this->createForm(new AddressType, $address, [
                'action' => '',
                'method' => 'POST',
            ]);
        }

        if ($request->getMethod() === 'POST') {
            $form = $this->createForm(new AddressType, $address, [
                'action' => '',
                'method' => 'POST',
            ]);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $message = 'The form is valid';
            }
        }

        $form = $form->createView();
        return compact('form', 'message');
    }

    /**
     * @Route("/recurring")
     * @Template()
     */
    public function recurringAction()
    {
        $retrieved = $this->get('doctrine.odm')->getRepository('KhepinBookBundle:Meetup')->findOneBy(['name' => 'bob']);
        $meetup = new Meetup();
        $meetup->setLocation(new Coordinate(12,33));
        $meetup->setName('bob');

        $this->get('doctrine.odm')->persist($meetup);
        $this->get('doctrine.odm')->flush();

        return compact('retrieved');
    }

    /**
     * @Route("/github")
     */
    public function ghloginAction(Request $request)
    {
        // https://github.com/login/oauth/authorize?client_id=f77f000b2dfc717ade2a&redirect_uri=http://extending.loc/app_dev.php/github_login&scope=user,user:email
        $client = new \Guzzle\Http\Client('https://github.com/login/oauth/access_token');
        $req = $client->post('', null, [
            'client_id' => 'f77f000b2dfc717ade2a',
            'client_secret' => '42967a6f718a83e5f85ad609292a78fce81dd46d',
            'code' => $request->query->get('code')
        ])->setHeader('Accept', 'application/json');

        $res = $req->send()->json();
        $token = $res['access_token'];

        $client = new \Guzzle\Http\Client('https://api.github.com');
        $req = $client->get('/user');
        $req->getQuery()->set('access_token', $token);
        $username = $req->send()->json()['email'];

        return new Response($username);
    }

    /**
     * @Route("/api/status")
     * @Security("has_role('ROLE_USER')")
     */
    public function apiAction()
    {
        return new Response('The API works great!');
    }
}
