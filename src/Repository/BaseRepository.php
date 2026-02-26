<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bridge\Doctrine\Types\UuidType;

abstract class BaseRepository extends ServiceEntityRepository
{
    protected string $entityClass;
    public $qty, $total = 0;
    public $page, $step = 1;
    public $base, $prev, $next, $first, $last = "";
    public $criteria = [];
    // public $criteria= ["active" => true];
    public $sort, $pagination = [];

    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        $this->entityClass = $entityClass;
        parent::__construct($registry, $entityClass);
        $this->base = $_ENV["BASE_URL"];
        $this->qty = $_ENV["LIST_QTY"];
    }

    public function getCount($query): int
    {
        $rsm = new ResultSetMappingBuilder($this->_em);
        $rsm->addScalarResult('count', 'count');
        $query = $this->_em->createNativeQuery($query, $rsm);
        return (int)$query->getSingleScalarResult();
    }

    public function setPagination($pagination)
    {
        $page = 0;
        if (array_key_exists('index', $pagination)) {
            $page = intval($pagination['index']);
        }

        if (array_key_exists('size', $pagination)) {
            $this->qty = $pagination['size'];
        }

        $this->page = $page;
        $this->step = $page <= 0 ? 0 : ($page - 1) * $this->qty;
    }

    public function makeFilterArray($extraData)
    {
        $dataInput = $this->criteria;
        foreach ($extraData as $value) {
            $claves = array_keys($value);
            $key = strval($claves[0]);
            $field = $value[$key];
            $subKey = array_keys($field)[0];
            $dataInput[$key] = [
                'operator' => $subKey,
                'value' => $value[$key][$subKey],
            ];
        }
        return $dataInput;
    }

    public function makeSortArray($extraData)
    {
        $dataInput = $this->sort;
        foreach ($extraData as $value) {
            $claves = array_keys($value);
            $key = strval($claves[0]);
            $finalValue = $value[$key];
            $dataInput[$key] = $finalValue;
        }
        return $dataInput;
    }

    public function makePaginationArray($extraData)
    {
        $dataInput = $this->pagination;
        if( $extraData['index'] ){
            $dataInput['index'] = $extraData['index'] + 1;
        }

        if ($extraData['size']) {
            $dataInput['size'] = $extraData['size'];
        }
        return $dataInput;
    }


    public function transformBy(
        $criteriaData = [],
        $sortData = [],
        $paginationData = []
    ) {
        if ($criteriaData) {
            $this->criteria = $this->makeFilterArray($criteriaData);
        }

        if ($sortData) {
            $this->sort = $this->makeSortArray($sortData);
        }

        if ($paginationData) {
            $this->pagination = $this->makePaginationArray($paginationData);
        }

        $this->setPagination($this->pagination);
        $limit = $this->qty;
        $offset = $this->step;
        $data = $this->findBy($this->criteria, $this->sort, $limit, $offset);
        $array = [];
        foreach ($data as $row) {
            $array[] = $this->transform($row);
        }

        $this->total = count($data);
        $jsonString = json_encode(
            [
                "content" => $array,
                "count" => $this->total,
                // "prev"=> $this->prev,
                // "next"=> $this->next,
                // "first"=> $this->first,
                // "last"=> $this->last,
            ]
        );
        $objeto = json_decode($jsonString);
        return $objeto;
    }


    public function recordsFiltered(
        $criteriaData = [],
        $sortData = [],
        $paginationData = []
    ) {
        if ($criteriaData) {
            $this->criteria = $this->makeFilterArray($criteriaData);
        }

        if ($sortData) {
            $this->sort = $this->makeSortArray($sortData);
        }

        if ($paginationData) {
            $this->pagination = $this->makePaginationArray($paginationData);
        }

        $this->setPagination($this->pagination);
        $limit = $this->qty;
        $offset = $this->step;
        $qb = $this->createQueryBuilder('p');
        $qb->where('1 = 1');
        foreach (array_keys($this->criteria) as $key => $value) {
            if ($value == "company") {
                $qb->andWhere('p.company = :companyId')
                    ->setParameter(
                        'companyId',
                        $this->criteria[$value]['value']
                    );
            }
            else if ($value == "between") {
                $timezone = new \DateTimeZone('America/Lima');
                $now = new \DateTimeImmutable('now', $timezone);
                $now = $now->format('Y-m-d H:i:s');
                $qb->andWhere('p.start <= :today AND p.end >= :today')
                    ->setParameter(
                        'today',
                        $now
                    );
            }
            else if ($value == "workspace") {
                // $qb->andWhere('p.workspace = :workspace')
                //     ->setParameter('workspace', $filters['workspace']['eq'], UuidType::NAME);

                $qb->andWhere('p.workspace = :workspace')
                    ->setParameter('workspace',$this->criteria[$value]['value'], UuidType::NAME);

            }

            else if ($value == "workspaceId") { 
                $qb->andWhere('p.id = :workspace')
                    ->setParameter('workspace',$this->criteria[$value]['value'], UuidType::NAME);
            }

            else {
                if ($this->criteria[$value]['operator'] ==  "eq") {
                    $qb->andWhere('p.' . $value . ' = :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "ne") {
                    $qb->andWhere('p.' . $value . ' != :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "lt") {
                    $qb->andWhere('p.' . $value . ' < :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }
                
                if ($this->criteria[$value]['operator'] ==  "lte") {
                    $qb->andWhere('p.' . $value . ' <= :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "gt") {
                    $qb->andWhere('p.' . $value . ' > :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "gte") {
                    $qb->andWhere('p.' . $value . ' >= :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "contains") {
                    $qb->andWhere('p.' . $value . ' LIKE :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            "%" . $this->criteria[$value]['value'] . "%"
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "startsWith") {
                    $qb->andWhere('p.' . $value . ' LIKE :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value'] . "%"
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "endsWith") {
                    $qb->andWhere('p.' . $value . ' LIKE :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            "%" . $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "between") {
                    $dateInitial = $this->criteria[$value]['value'];

                    $dateArray = explode("-", $dateInitial);
                    $startDate = \DateTime::createFromFormat('d/m/Y', '01/' . trim($dateArray[0]));
                    $endDate = \DateTime::createFromFormat('d/m/Y', '01/' . trim($dateArray[1]));  
                    $endDate->modify('last day of this month');
                    
                    $qb->andWhere('p.' . $value . ' >= :firstDate AND p.' . $value . ' <= :secondaDate')
                        ->setParameter( 'firstDate', $startDate )
                        ->setParameter( 'secondaDate', $endDate );
                }
            }
        }
        // POR DEFAULT SOLO ACTIVOS
        // $qb->andWhere('p.active = 1');

        $queryWithOutCount = $qb->getQuery();
        $resultsWithOutCount = $queryWithOutCount->getResult();
        $this->total = count($resultsWithOutCount);
        if ($this->sort != null) {
            foreach (array_keys($this->sort) as $value) {
                $qb->addOrderBy('p.' . $value, $this->sort[$value]);
            }
        }

        if ($offset) $qb->setFirstResult($offset);
        
        if ($limit) $qb->setMaxResults($limit);

        $query = $qb->getQuery();
        // print_r( $query->getSql() );
        // print_r( $query->getParameters() );
        // die( " .. " );
        $dataResult = $query->getResult();
        $dataArray = [];

        foreach ($dataResult as $plan) {
            $dataArray[] = $this->transform($plan);
        }

        $jsonString = json_encode(
            [
                "content" => $dataArray,
                "count" => $this->total,
            ]
        );

        $objeto = json_decode($jsonString);
        return $objeto;
    }


    // ALL DATA
    public function allRecordsFiltered(
        $criteriaData = [],
        $sortData = [],
        $paginationData = []
    ) {
        if ($criteriaData) {
            $this->criteria = $this->makeFilterArray($criteriaData);
        }

        if ($sortData) {
            $this->sort = $this->makeSortArray($sortData);
        }

        // if( $paginationData ){
        //     $this->pagination = $this->makePaginationArray($paginationData );
        // }
        // $this->setPagination($this->pagination);

        $limit = $this->qty;
        $offset = $this->step;
        $qb = $this->createQueryBuilder('p');
        $qb->where('1 = 1');
        foreach (array_keys($this->criteria) as $key => $value) {
            if ($value == "company") {
                $qb->andWhere('p.company = :companyId')
                    ->setParameter(
                        'companyId',
                        $this->criteria[$value]['value']
                    );
            } 
            else if ($value == "isDeleted") {
                $nVal = null;
                if ($this->criteria[$value]['value'] == 1) {
                    $qb->andWhere('p.' . $value . ' = true');
                } else {
                    $qb->andWhere('p.' . $value . ' is null or p.' . $value . '= false');
                }
            } 
            else if ($value == "deleted") {
                $nVal = null;
                if ($this->criteria[$value]['value'] == 1) {
                    $qb->andWhere('p.' . $value . ' = true');
                } else {
                    $qb->andWhere('p.' . $value . ' is null or p.' . $value . '= false');
                }
            } 
            else {
                if ($this->criteria[$value]['operator'] ==  "eq") {
                    $qb->andWhere('p.' . $value . ' = :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "ne") {
                    $qb->andWhere('p.' . $value . ' != :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "lt") {
                    $qb->andWhere('p.' . $value . ' < :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "lte") {
                    $qb->andWhere('p.' . $value . ' <= :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "gt") {
                    $qb->andWhere('p.' . $value . ' > :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "gte") {
                    $qb->andWhere('p.' . $value . ' >= :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "contains") {
                    $qb->andWhere('p.' . $value . ' LIKE :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            "%" . $this->criteria[$value]['value'] . "%"
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "startsWith") {
                    $qb->andWhere('p.' . $value . ' LIKE :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            $this->criteria[$value]['value'] . "%"
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "endsWith") {
                    $qb->andWhere('p.' . $value . ' LIKE :paramVal' . $key)
                        ->setParameter(
                            'paramVal' . $key,
                            "%" . $this->criteria[$value]['value']
                        );
                }

                if ($this->criteria[$value]['operator'] ==  "between") {
                    $dateInitial = $this->criteria[$value]['value'];

                    $dateArray = explode("-", $dateInitial);
                    $startDate = \DateTime::createFromFormat('d/m/Y', '01/' . trim($dateArray[0]));
                    $endDate = \DateTime::createFromFormat('d/m/Y', '01/' . trim($dateArray[1]));  
                    $endDate->modify('last day of this month');
                    
                    $qb->andWhere('p.' . $value . ' >= :firstDate AND p.' . $value . ' <= :secondaDate')
                        ->setParameter( 'firstDate', $startDate )
                        ->setParameter( 'secondaDate', $endDate );
                }
            }
        }

        $queryWithOutCount = $qb->getQuery();
        $resultsWithOutCount = $queryWithOutCount->getResult();
        $this->total = count($resultsWithOutCount);

        if ($this->sort != null) {
            foreach (array_keys($this->sort) as $value) {
                $qb->addOrderBy('p.' . $value, $this->sort[$value]);
            }
        }

        // if ($offset) $qb->setFirstResult($offset);
        // if ($limit) $qb->setMaxResults($limit);

        $query = $qb->getQuery();
        $dataResult = $query->getResult();
        $dataArray = [];
        foreach ($dataResult as $plan) {
            $dataArray[] = $this->transform($plan);
        }

        $jsonString = json_encode(
            [
                "content" => $dataArray,
                "count" => $this->total,
            ]
        );

        $objeto = json_decode($jsonString);
        return $objeto;
    }
}