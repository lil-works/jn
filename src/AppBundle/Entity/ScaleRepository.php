<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * ScaleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ScaleRepository extends \Doctrine\ORM\EntityRepository
{


    public function findAllByEverything($search){

        $em = $this->getEntityManager();

        $repository = $em->getRepository('AppBundle:Scale');

        if(is_null($search)){
            $query = $repository->createQueryBuilder('s')->getQuery();
        }else{
        $query = $repository->createQueryBuilder('s')
            ->innerJoin('s.intervales', 'i')
            ->innerJoin('s.descriptors', 'd')
            ->where('s.name LIKE :name ')
            ->andWhere('i.id IN ( :intervales )')
            ->andWhere('d.id IN ( :descriptors )')
            ->setParameter('name',  "%".$search["name"]."%" )
            ->setParameter('descriptors',  $search["descriptors"] )
            ->setParameter('intervales',  $search["intervales"] )
            ->getQuery();
        }

        return $query->getResult();
    }

    public function matchingRootScale($scale,$westernSystem){

        $em = $this->getEntityManager();

        /*
         *
         */
        $sql0 = "SELECT
	d3.value AS rootDigit,
    d3.infoTone AS rootInfo,
    s.name as scaleName,
    d2.infoTone as intervalInfo,
    d2.value as intervalDigit
FROM
    scale s
        LEFT JOIN
    scales_intervales si ON si.scale_id = s.id
        LEFT JOIN
    intervale i ON si.intervale_id = i.id
        JOIN
    digit d
        LEFT JOIN
    digit d2 ON d2.value = MOD(d.value + i.delta, 12)
        LEFT JOIN
    digit d3 ON d3.value = d.value

WHERE
    s.id = :scaleId and d3.value = :wsDigitId";
        $rsm0 = new ResultSetMapping;
        $rsm0->addScalarResult('intervalDigit', 'intervalDigit');



        $query0 = $em->createNativeQuery($sql0, $rsm0);
        $query0->setParameter('scaleId', $scale->getId());
        $query0->setParameter('wsDigitId', $westernSystem->getDigit()->getValue());
        $intervales = $query0->getScalarResult();
        $sql = "

                        SELECT *,ROUND(count(rootInfoTone)/totTone,1) as score FROM
            (SELECT
                s1.id as scaleId,
                s1.name as scaleName,
                d1.infoTone as rootInfoTone,
                d1.value as rootDigit,
                (SELECT ws2.name FROM western_system ws2 WHERE ws2.digit = d1.id AND ws2.intervale = 1 LIMIT 1) as rootName,
                (SELECT value FROM digit WHERE value = MOD(d1.value+i1.delta,12)) as calculatedDigit,
                i1.name as intervaleName,
                i1.delta as intervaleDelta
                ,(SELECT count(si2.intervale_id) FROM scales_intervales si2 WHERE si2.scale_id = scaleId) as totTone
                #,count(i1.name)
            FROM
                scale s1
                    LEFT JOIN
                scales_intervales si1 ON si1.scale_id = s1.id
                    LEFT JOIN
                intervale i1 ON i1.id = si1.intervale_id
                    JOIN
                digit d1

            #group by s1.id,rootInfoTone
            HAVING calculatedDigit IN (:digits)
            ) r1
            GROUP BY scaleId,rootInfoTone
            HAVING score >= 1 AND
            ( rootDigit != :rootDigit AND scaleId != :scaleId )
            ORDER BY score DESC



            ";

        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('scaleId', 'scaleId');
        $rsm->addScalarResult('scaleName', 'scaleName');
        $rsm->addScalarResult('rootName', 'rootName');
        $rsm->addScalarResult('rootInfoTone', 'rootInfoTone');
        $rsm->addScalarResult('rootDigit', 'rootDigit');


        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('digits',$intervales);
        $query->setParameter('scaleId',$scale->getId());
        $query->setParameter('rootDigit',$westernSystem->getDigit()->getId());

        return $query->getScalarResult();
    }
    /*
     *
     */
    public function matchingScale($intervales,$currentScaleId = 0){
        $sql = "

            SELECT scaleId,scaleName, scaleNbrTot/count(intervale_id) as ratio,count(intervale_id) as commonIntervale  FROM (
SELECT
s1.id as scaleId,
	s1.name as scaleName,
    si1.intervale_id,
    (SELECT COUNT(intervale_id) FROM scales_intervales WHERE scale_id=s1.id ) as scaleNbrTot
FROM
    scale s1
        LEFT JOIN
    scales_intervales si1 ON si1.scale_id = s1.id

    group by s1.id,si1.intervale_id

HAVING si1.intervale_id IN (:intervales)
)r1
group by scaleId
having commonIntervale >= :countIntervales and scaleId != :currentScaleId
order by ratio asc

            ";
        $em = $this->getEntityManager();
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('scaleId', 'scaleId');
        $rsm->addScalarResult('scaleName', 'scaleName');
        $rsm->addScalarResult('ratio', 'ratio');


        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter("currentScaleId",$currentScaleId);
        $query->setParameter("intervales",$intervales);
        $query->setParameter("countIntervales",count($intervales));
        return $query->getScalarResult();
    }

    public function westernPopulateRootScale($scaleId,$wsName){
        $sql = "

            SELECT
                name as rootName,
                digit as rootDigitId,
                (SELECT d2.value FROM digit d2 WHERE d2.id=digit) as rootDigitA,
                group_concat(newToneName order by intervaleDelta1) as toneList ,
                group_concat(newDigitA+36 order by intervaleDelta1) as digitAList
                FROM
                (
                SELECT
                    *,
                    (SELECT
                            ws3.name
                        FROM
                            western_system ws3
                        WHERE
                            ws3.intervale = intervaleId1
                                AND root = ws2.root limit 1) AS newToneName,
                    (SELECT
                            d1.value
                        FROM
                            western_system ws3
                        LEFT JOIN
                            digit d1 ON d1.id = ws3.digit
                        WHERE
                            ws3.intervale = intervaleId1
                                AND root = ws2.root limit 1) AS newDigitA
                FROM
                    (SELECT
                        s1.id AS scaleId1,
                            s1.name AS scaleName1,
                            i1.id AS intervaleId1,
                            i1.delta AS intervaleDelta1,
                            i1.color AS intervaleColor1
                    FROM
                        scale s1
                    LEFT JOIN scales_intervales si1 ON si1.scale_id = s1.id
                    LEFT JOIN intervale i1 ON i1.id = si1.intervale_id
                    WHERE
                        s1.id = :scaleId) r1
                        JOIN
                    western_system ws2 ON ws2.intervale = 1 AND ws2.name = :wsName
                    )r2

                    group by root

            ";
        $em = $this->getEntityManager();
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('rootName', 'rootName');
        $rsm->addScalarResult('rootDigitA', 'rootDigitA');
        $rsm->addScalarResult('toneList', 'toneList');
        $rsm->addScalarResult('digitAList', 'digitAList');

        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter("scaleId",$scaleId);
        $query->setParameter("wsName",$wsName);
        return $query->getScalarResult();
    }
    public function westernPopulateScale($scaleId){
        $sql = "

            SELECT
                name as rootName,
                digit as rootDigitId,
                (SELECT d2.value FROM digit d2 WHERE d2.id=digit) as rootDigitA,
                group_concat(newToneName order by intervaleDelta1) as toneList ,
                group_concat(newDigitA+36 order by intervaleDelta1) as digitAList
                FROM
                (
                SELECT
                    *,
                    (SELECT
                            ws3.name
                        FROM
                            western_system ws3
                        WHERE
                            ws3.intervale = intervaleId1
                                AND root = ws2.root limit 1) AS newToneName,
                    (SELECT
                            d1.value
                        FROM
                            western_system ws3
                        LEFT JOIN
                            digit d1 ON d1.id = ws3.digit
                        WHERE
                            ws3.intervale = intervaleId1
                                AND root = ws2.root limit 1) AS newDigitA
                FROM
                    (SELECT
                        s1.id AS scaleId1,
                            s1.name AS scaleName1,
                            i1.id AS intervaleId1,
                            i1.delta AS intervaleDelta1,
                            i1.color AS intervaleColor1
                    FROM
                        scale s1
                    LEFT JOIN scales_intervales si1 ON si1.scale_id = s1.id
                    LEFT JOIN intervale i1 ON i1.id = si1.intervale_id
                    WHERE
                        s1.id = :scaleId) r1
                        JOIN
                    western_system ws2 ON ws2.intervale = 1
                    )r2

                    group by root

            ";
        $em = $this->getEntityManager();
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('rootName', 'rootName');
        $rsm->addScalarResult('rootDigitA', 'rootDigitA');
        $rsm->addScalarResult('toneList', 'toneList');
        $rsm->addScalarResult('digitAList', 'digitAList');

        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter("scaleId",$scaleId);
        return $query->getScalarResult();
    }
    public function ajaxFindAll(){
        $sql = "SELECT id,name FROM scale";
        $em = $this->getEntityManager();
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('name', 'name');

        $query = $em->createNativeQuery($sql, $rsm);
        return $query->getScalarResult();
    }
}