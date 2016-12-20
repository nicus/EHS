<?php

namespace EHSBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * NewsletterReceiverRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsletterReceiverRepository extends EntityRepository
{
    public function delInscription($email){
        $em= $this->getEntityManager();
        $query = $em->createQuery('DELETE FROM EHSBundle\\Entity\\NewsletterReceiver n WHERE n.email= :email ')
            ->setParameter('email', $email);
        $query->execute();

    }
}
