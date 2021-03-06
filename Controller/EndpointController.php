<?php

namespace Soil\QueueHttpEndpointBundle\Controller;

use Soilby\EventComponent\Service\GearmanClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 5.2.15
 * Time: 15.14
 */

class EndpointController {

    /**
     * @var GearmanClient
     */
    protected $gearmanClient;

    protected $queueStream;

    public function __construct($gearmanClient, $queueStream) {
        $this->gearmanClient = $gearmanClient;
        $this->queueStream = $queueStream;
    }


    public function putJobAction(Request $request) {
        $data = null;

        try {
            $data = $request->getContent();

            $job = $this->gearmanClient->sendRaw($this->queueStream, $data);

            $response = new JsonResponse([
                'success' => true,
                'stream' => $this->queueStream,
                'job' => $job
            ]);
        }
        catch(\Exception $e)    {
            $response = new JsonResponse([
                'success' => false,
                'error' => $e->getMessage(),
                'workload' => $data
            ], 500);
        }

        return $response;
    }
} 