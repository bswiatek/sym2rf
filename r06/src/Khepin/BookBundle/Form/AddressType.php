<?php

namespace Khepin\BookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $state_options = [
                'choices' => [
                    'AL' => 'Alabama',
                    'AK' => 'Alaska',
                    'AZ' => 'Arizona',
                    'AR' => 'Arkansas',
                    'CA' => 'California',
                    'CO' => 'Colorado',
                    'CT' => 'Connecticut',
                    'DE' => 'Delaware',
                    'DC' => 'District of Columbia',
                    'FL' => 'Florida',
                    'GA' => 'Georgia',
                    'HI' => 'Hawaii',
                    'ID' => 'Idaho',
                    'IL' => 'Illinois',
                    'IN' => 'Indiana',
                    'IA' => 'Iowa',
                    'KS' => 'Kansas',
                    'US' => 'Kentucky',
                    'LA' => 'Louisiana',
                    'ME' => 'Maine',
                    'MD' => 'Maryland',
                    'US' => 'Massachusetts',
                    'MI' => 'Michigan',
                    'MN' => 'Minnesota',
                    'MS' => 'Mississippi',
                    'MO' => 'Missouri',
                    'MT' => 'Montana',
                    'NE' => 'Nebraska',
                    'NV' => 'Nevada',
                    'NH' => 'New Hampshire',
                    'NJ' => 'New Jersey',
                    'NM' => 'New Mexico',
                    'NY' => 'New York',
                    'NC' => 'North Carolina',
                    'ND' => 'North Dakota',
                    'OH' => 'Ohio',
                    'OK' => 'Oklahoma',
                    'OR' => 'Oregon',
                    'US' => 'Pennsylvania',
                    'RI' => 'Rhode Island',
                    'SC' => 'South Carolina',
                    'SD' => 'South Dakota',
                    'TN' => 'Tennessee',
                    'TX' => 'Texas',
                    'UT' => 'Utah',
                    'VT' => 'Vermont',
                    'US' => 'Virginia',
                    'WA' => 'Washington',
                    'WV' => 'West Virginia',
                    'WI' => 'Wisconsin',
                    'WY' => 'Wyoming',
                ]
        ];

        $builder
            ->add('street')
            ->add('number')
            ->add('country', 'choice', [
                'choices' => [
                    'US' => 'USA',
                    'OTHER' => 'Not USA'
                ]
            ])
            ->add('zip')
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($state_options){
            $address = $event->getData();
            if ($address === null) {
                return;
            }

            if ($address->getCountry() == 'US') {
                $event->getForm()->add('state', 'choice', $state_options);
            }
        });

        $builder->addEventListener(FormEvents::PRE_BIND, function(FormEvent $event) use ($state_options){
            $address = $event->getData();

            if ($address['country'] == 'US') {
                $event->getForm()->add('state', 'choice', $state_options);
            }
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Khepin\BookBundle\Entity\Address'
        ));
    }

    public function getName()
    {
        return 'address';
    }
}
