<?php

namespace App\Services;

use App\Repositories\Interface\MockyRepositoryInterface;
use App\Repositories\Interface\OrdersRepositoryInterface;
use App\Repositories\Interface\ReceiversRepositoryInterface;
use App\Repositories\Interface\SendersRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OrdersService
{

    public function __construct(
        protected OrdersRepositoryInterface    $ordersRepositoryInterface,
        protected MockyRepositoryInterface     $mockyRepositoryInterface,
        protected SendersRepositoryInterface   $sendersRepositoryInterface,
        protected ReceiversRepositoryInterface $receiversRepositoryInterface
    ){}

    /**
     * Método responsável por consultar os pedidos no Mocky e persistir eles internamente
     *
     * @return JsonResponse
     */
    public function consultPersistOrders(): JsonResponse
    {
        try {
            $mockyOrders = $this->mockyRepositoryInterface->getOrders();

            if ($mockyOrders['code'] != 200) {
                throw new \Exception($mockyOrders['data']);
            }

            $return = $this->splitPayloadInfo($mockyOrders['data']);

            return response()->json($return['message'], Response::HTTP_OK);

        } catch (\Exception $e){
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    private function splitPayloadInfo(array $data)
    {

        foreach ($data as $order) {
            $this->sendersRepositoryInterface->persistSender($order['_remetente']);

            $this->receiversRepositoryInterface->persistReceiver($order['_destinatario']);

            $this->ordersRepositoryInterface->persistOrder(
                $order,
                $this->sendersRepositoryInterface->getSenderByUuid($order['_remetente']['_nome']),
                $this->receiversRepositoryInterface->getReceiverByUuid($order['_destinatario']['_cpf'])
            );

        }

        return ['message' => 'Orders persisted successfully', 'code' => Response::HTTP_OK];
    }

}
