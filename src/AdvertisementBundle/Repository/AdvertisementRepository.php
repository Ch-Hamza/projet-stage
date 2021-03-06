<?php

namespace AdvertisementBundle\Repository;

/**
 * AdvertisementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertisementRepository extends \Doctrine\ORM\EntityRepository
{
    function APIfind($filters){

        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('c')
            ->from('AdvertisementBundle:Advertisement', 'c')
            ->innerJoin('c.user', 'u')
            ->select('c.id, c.company, c.type, c.image, c.enabled, u.username, u.email');
        ;

        if (!empty($filters))
        {
            foreach ($filters[0] as $key => $value)
            {
                if($key == '_format'){continue;}

                elseif ($key == 'date')
                {
                    $qb->leftJoin('MuseumsBundle:History\Advertisement_History', 'ad_h', 'WITH', 'ad_h.advertisement_id = c.id')
                        ->andWhere('ad_h.date = :date')
                        ->setParameter('date', $value)
                        ->select('c.id, c.company, c.type, c.image, c.enabled, ad_h.action, ad_h.date, u.username, u.email');
                }

                elseif ($key == 'offset')
                {
                    $qb->setFirstResult($value-1);
                }

                elseif ($key == 'limit')
                {
                    $qb->setMaxResults($value);
                }

                else{
                    $qb->andWhere('c.'.$key.' = :value')
                        ->setParameter('value', $value);
                }
            }
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
}
