<?php

namespace Khepin\BookBundle\Security;

use Doctrine\Common\Annotations\Reader;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;

class ValidUserListener
{
    private $reader;

    private $router;

    private $session;

    private $sc;

    private $validator;

    private $annotation_name = 'Khepin\BookBundle\Security\Annotation\ValidateUser';

    public function __construct(Reader $reader, Router $router, Session $session, SecurityContext $sc, Validator $validator)
    {
        $this->reader = $reader;
        $this->router = $router;
        $this->session = $session;
        $this->sc = $sc;
        $this->validator = $validator;
    }

    public function onKernelController($event)
    {
        // Get class and method name to read the annotation
        $class_name = get_class($event->getController()[0]);
        $method_name = $event->getController()[1];

        $method = new \ReflectionMethod($class_name, $method_name);
        // Read the annotation
        $annotation = $this->reader->getMethodAnnotation($method, $this->annotation_name);

        // If our controller doesn't have a "ValidateUser" annotation,
        // we don't do anything
        if (!is_null($annotation)) {
            // Retrieve the validation group from the annotation, and try to
            // validate the user
            $validation_group = $annotation->getValidationGroup();
            $user = $this->sc->getToken()->getUser();
            $errors = $this->validator->validate($user, $validation_group);

            if (count($errors)) {
                // If the user is not valid, change the controller to redirect
                // the user
                $event->setController(function(){
                    $this->session->getFlashBag()->add('warning', 'You must fill in your phone number before joining a meetup.');
                    return new RedirectResponse($this->router->generate('fos_user_profile_edit'));
                });
            }
        }
    }
}