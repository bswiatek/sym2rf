<?php
namespace Khepin\BookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Ivory\GoogleMapBundle\Entity\Coordinate as GMapsCoordinate;
use Khepin\BookBundle\Form\Transformer\GeoTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Khepin\BookBundle\Geo\UserLocator;
use Khepin\BookBundle\Geo\Coordinate;

class CoordinateType extends AbstractType
{
    protected $map;

    public function __construct($map, UserLocator $user_locator)
    {
        $this->map = $map;
        $this->locator = $user_locator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new GeoTransformer);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($builder) {
            $data = $event->getData();

            if (null === $data->getLatitude()) {
                $geocoded = $this->locator->getUserCoordinates();
                $value = new Coordinate($geocoded->getLatitude(), $geocoded->getLongitude());
                $event->setData($value);
            }

        });
    }

    public function getName()
    {
        return 'coordinate';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'coordinate',
            'compound' => false,
            'data_class' => 'Khepin\BookBundle\Geo\Coordinate',
            'data' => new Coordinate(),
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $center = new GMapsCoordinate($form->getData()->getLatitude(), $form->getData()->getLongitude());
        $this->map->setCenter($center);
        $this->map->setMapOption('zoom', 10);

        $view->vars['map'] = $this->map;
    }
}