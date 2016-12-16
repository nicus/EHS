<?php

namespace EHSBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends EntityRepository
{
    public function getNoArchivedEvents(){
        $em=$this->getEntityManager();
        $query= $em->createQuery('SELECT e FROM EHSBundle\\Entity\\Event e 
                                  WHERE e.archived=0 ORDER BY e.startDate DESC ');
        $listEvents= $query->getResult();
        return $listEvents;
    }

}
