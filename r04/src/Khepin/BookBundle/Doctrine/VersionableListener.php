<?php
namespace Khepin\BookBundle\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;

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
            $uow = $em->getUnitOfWork();
            $changes = $uow->getEntityChangeset($entity);
            if (!isset($changes['version'])) {
                throw new \Exception('You must send the version number to update a versionable entity');
            }

            if ($changes['version'][1] < $changes['version'][0]) {
                throw new \Exception('Trying to update an outdated version');
            }

            if ($changes['version'][1] == $changes['version'][0]) {
                $entity->setVersion($entity->getVersion() + 1);
                $uow->propertyChanged($entity, 'version', $entity->getVersion() - 1, $entity->getVersion());
            }
        }
    }
}