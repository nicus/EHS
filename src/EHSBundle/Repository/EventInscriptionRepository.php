<?php

namespace EHSBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EventInscriptionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventInscriptionRepository extends EntityRepository
{
    public function delInscription($id){
        $em= $this->getEntityManager();
        $query = $em->createQuery('DELETE FROM EHSBundle\\Entity\\EventInscription e WHERE e.id= :id ')
            ->setParameter('id', $id);
        $query->execute();

    }
}
