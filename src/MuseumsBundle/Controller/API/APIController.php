<?php

namespace MuseumsBundle\Controller\API;

use AdvertisementBundle\Entity\Advertisement;
use MuseumsBundle\Entity\Canton;
use MuseumsBundle\Entity\Category;
use MuseumsBundle\Entity\Critere;
use MuseumsBundle\Entity\Museum;
use Proxies\__CG__\ExpositionBundle\Entity\Exposition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class APIController extends Controller
{
    /**
     * This method can be used to extract a list of all museums obeying certain criteria
     * that can be specified by filtering, museum name with ambiguous characters must have slashes added
     * example : to search for "Pasquart Kunsthaus Centre d'art" the name sent in the filter must be
     * Pasquart Kunsthaus Centre d\'art
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Museums with filtering",
     *  filters={
     *      {"name"="date", "dataType"="date yy-mm-dd", "required"="false"},
     *      {"name"="id", "dataType"="integer", "required"="false"},
     *      {"name"="name", "dataType"="string", "required"="false"},
     *      {"name"="categories", "datatype"="string", "required"="false"},
     *      {"name"="criteres", "datatype"="string", "required"="false"},
     *      {"name"="canton", "datatype"="string", "required"="false"},
     *      {"name"="lang", "datatype"="fr | it | de | en", "required"="false"},
     *      {"name"="limit", "datatype"="integer", "required"="false"},
     *      {"name"="offset", "datatype"="integer", "required"="false"},
     *  },
     *
     *  output={"collection"=true, "collectionName"="list", "class"="MuseumsBundle\Museum"}
     * )
     * @Route("/api/museums", name="show_museum_by_params")
     * @Method({"GET"})
     */
    public function getMuseumsByParameter(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $filters[] = $request->query->all();
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(Museum::class)->APIfind($filters);

        if(null === $list)
        {
            $response = array(
                'code' => 0,
                'message' => "no museums available",
                'error' => null,
                'result' => null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($list, 'json');

        $response = array(
            'code' => 1,
            'message' => "success",
            'error' => null,
            'result' => json_decode($data)
        );

        return new JsonResponse($response, 200);
    }

    /**
     * This method can be used to extract a list of all expositions obeying certain criteria
     * that can be specified by filtering, museum name with ambiguous characters must have slashes added
     * example : to search for "Pasquart Kunsthaus Centre d'art" the name sent in the filter must be
     * Pasquart Kunsthaus Centre d\'art
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Expositions with filtering",
     *  filters={
     *      {"name"="date", "dataType"="date yy-mm-dd", "required"="false"},
     *      {"name"="id", "dataType"="integer", "required"="false"},
     *      {"name"="name", "dataType"="string", "required"="false"},
     *      {"name"="after_date", "datatype"="date yy-mm-dd", "required"="false"},
     *      {"name"="before_date", "datatype"="date yy-mm-dd", "required"="false"},
     *      {"name"="hosting_museum_name", "datatype"="string", "required"="false"},
     *      {"name"="lang", "datatype"="fr | it | de | en", "required"="false"},
     *      {"name"="limit", "datatype"="integer", "required"="false"},
     *      {"name"="offset", "datatype"="integer", "required"="false"},
     *  },
     *
     *  output={"collection"=true, "collectionName"="list", "class"="ExpositionBundle\Exposition"}
     * )
     * @Route("/api/expositions", name="show_expositions_by_params")
     * @Method({"GET"})
     */
    public function getExpositionsByParameter(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $filters[] = $request->query->all();
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(Exposition::class)->APIfind($filters);

        if(null === $list)
        {
            $response = array(
                'code' => 0,
                'message' => "no expositions available",
                'error' => null,
                'result' => null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($list, 'json');

        $response = array(
            'code' => 1,
            'message' => "success",
            'error' => null,
            'result' => json_decode($data)
        );

        return new JsonResponse($response, 200);
    }

    /**
     * This method can be used to extract a list of all Cantons obeying certain criteria
     * that can be specified by filtering.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Cantons with filtering",
     *  filters={
     *      {"name"="date", "dataType"="date yy-mm-dd", "required"="false"},
     *      {"name"="id", "dataType"="integer", "required"="false"},
     *      {"name"="title", "dataType"="string", "required"="false"},
     *      {"name"="limit", "datatype"="integer", "required"="false"},
     *      {"name"="offset", "datatype"="integer", "required"="false"},
     *  },
     *
     *  output={"collection"=true, "collectionName"="list", "class"="MuseumsBundle\Canton"}
     * )
     * @Route("/api/cantons", name="show_canton_by_params")
     * @Method({"GET"})
     */
    public function getCantonByParameter(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $filters[] = $request->query->all();
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(Canton::class)->APIfind($filters);

        if(null === $list)
        {
            $response = array(
                'code' => 0,
                'message' => "no cantons available",
                'error' => null,
                'result' => null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($list, 'json');

        $response = array(
            'code' => 1,
            'message' => "success",
            'error' => null,
            'result' => json_decode($data)
        );

        return new JsonResponse($response, 200);
    }

    /**
     * This method can be used to extract a list of all Categories obeying certain criteria
     * that can be specified by filtering.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Categories with filtering",
     *  filters={
     *      {"name"="date", "dataType"="date yy-mm-dd", "required"="false"},
     *      {"name"="id", "dataType"="integer", "required"="false"},
     *      {"name"="title", "dataType"="string", "required"="false"},
     *      {"name"="limit", "datatype"="integer", "required"="false"},
     *      {"name"="offset", "datatype"="integer", "required"="false"},
     *  },
     *
     *  output={"collection"=true, "collectionName"="list", "class"="MuseumsBundle\Categories"}
     * )
     * @Route("/api/categories", name="show_categories_by_params")
     * @Method({"GET"})
     */
    public function getCategoriesByParameter(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $filters[] = $request->query->all();
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(Category::class)->APIfind($filters);

        if(null === $list)
        {
            $response = array(
                'code' => 0,
                'message' => "no categories available",
                'error' => null,
                'result' => null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($list, 'json');

        $response = array(
            'code' => 1,
            'message' => "success",
            'error' => null,
            'result' => json_decode($data)
        );

        return new JsonResponse($response, 200);
    }

    /**
     * This method can be used to extract a list of all Criteria obeying certain filtering propreties.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Criteria with filtering",
     *  filters={
     *      {"name"="date", "dataType"="date yy-mm-dd", "required"="false"},
     *      {"name"="id", "dataType"="integer", "required"="false"},
     *      {"name"="title", "dataType"="string", "required"="false"},
     *      {"name"="limit", "datatype"="integer", "required"="false"},
     *      {"name"="offset", "datatype"="integer", "required"="false"},
     *  },
     *
     *  output={"collection"=true, "collectionName"="list", "class"="MuseumsBundle\Critere"}
     * )
     * @Route("/api/criteria", name="show_criteria_by_params")
     * @Method({"GET"})
     */
    public function getCriteriaByParameter(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $filters[] = $request->query->all();
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(Critere::class)->APIfind($filters);

        if(null === $list)
        {
            $response = array(
                'code' => 0,
                'message' => "no criteria available",
                'error' => null,
                'result' => null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($list, 'json');

        $response = array(
            'code' => 1,
            'message' => "success",
            'error' => null,
            'result' => json_decode($data)
        );

        return new JsonResponse($response, 200);
    }

    /**
     * This method can be used to extract a list of all Advertisements obeying certain filtering propreties.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Advertisments with filtering",
     *  filters={
     *      {"name"="date", "dataType"="date yy-mm-dd", "required"="false"},
     *      {"name"="id", "dataType"="integer", "required"="false"},
     *      {"name"="company", "dataType"="string", "required"="false"},
     *      {"name"="type", "dataType"="string : splash | banner", "required"="false"},
     *      {"name"="limit", "datatype"="integer", "required"="false"},
     *      {"name"="offset", "datatype"="integer", "required"="false"},
     *  },
     *
     *  output={"collection"=true, "collectionName"="list", "class"="MuseumsBundle\Advertisment"}
     * )
     * @Route("/api/advertisments", name="show_advertisment_by_params")
     * @Method({"GET"})
     */
    public function getAdvertismentByParameter(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $filters[] = $request->query->all();
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(Advertisement::class)->APIfind($filters);

        if(null === $list)
        {
            $response = array(
                'code' => 0,
                'message' => "no advertisments available",
                'error' => null,
                'result' => null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($list, 'json');

        $response = array(
            'code' => 1,
            'message' => "success",
            'error' => null,
            'result' => json_decode($data)
        );

        return new JsonResponse($response, 200);
    }
}