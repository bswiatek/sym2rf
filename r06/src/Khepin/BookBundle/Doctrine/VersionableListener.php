<?php
namespace Khepin\BookBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\DBAL\LockMode;

class VersionableListener {
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        $versionable = in_array(
            'Khepin\BookBundle\Doctrine\Versionable',
            (new \ReflectionClass($entity))->getTraitNames()
        );

        if ($versionable) {
            $entity->setVersion(0);
            $uow = $em->getUnitOfWork();
            $entity->setVersion($entity->getVersion() + 1);
            $uow->propertyChanged($entity, 'version', 0, 1);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();


        $versionable = in_array(
            'Khepin\BookBundle\Doctrine\Versionable',
            (new \ReflectionClass($entity))->getTraitNames()
        );

        if ($versionable) {
            $em->lock($entity, LockMode::OPTIMISTIC, $entity->getVersion());

            $version = $entity->getVersion();

            $uow = $em->getUnitOfWork();
            $uow->propertyChanged($entity, 'version', $version, $version + 1);
        }
    }
}