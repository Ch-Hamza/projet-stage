<?php

namespace MuseumsBundle\Repository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CritereRepository extends \Doctrine\ORM\EntityRepository
{
    function APIfind($filters){

        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('c')
            ->from('MuseumsBundle:Critere', 'c')
        ;

        if (!empty($filters))
        {
            foreach ($filters[0] as $key => $value)
            {
                if($key == '_format'){continue;}

                elseif ($key == 'date')
                {
                    $qb->leftJoin('MuseumsBundle:History\Critere_History', 'c_h', 'WITH', 'c_h.critere_id = c.id')
                        ->andWhere('c_h.date = :date')
                        ->setParameter('date', $value);
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

        /*var_dump($qb->getQuery()->getSQL());
        die('test');*/

        return $qb
            ->getQuery()
            ->getResult();
    }
}
