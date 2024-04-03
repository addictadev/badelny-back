<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateOrderAPIRequest;
use App\Http\Requests\API\UpdateOrderAPIRequest;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;

/**
 * Class OrderAPIController
 */
class OrderAPIController extends AppBaseController
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
    }

    /**
     * Display a listing of the Orders.
     * GET|HEAD /orders
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($orders->toArray(), 'Orders retrieved successfully');
    }

    /**
     * Store a newly created Order in storage.
     * POST /orders
     */
    public function store(CreateOrderAPIRequest $request): JsonResponse
    {
        // get the bayer id
        try {

        $buyerProduct =  Product::where('id',$request->buyer_product_id)->first();
        $buyerId =  $buyerProduct->user_id;
        // get the seller id
        $sellerProduct =  Product::where('id',$request->seller_product_id)->first();

        $sellerId = $sellerProduct->user_id;

        // save the request of order
        $request->merge(['from' => $buyerId ,'to' =>$sellerId]);
        $input = $request->all();

       if (!$request->is_offer){
           $order = $this->orderRepository->create($input);
       }else{
           $this->storeRequestOffers($input);
       }
        return $this->sendResponse($order->toArray(), 'Order saved successfully');
        }catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * Store Ofeers for Request Orders.
     */
     public function storeRequestOffers($input)
     {
         dd($input);
     }

    /**
     * Update the specified Order in storage.
     * PUT/PATCH /orders/{id}
     */
    public function update($id, UpdateOrderAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Order $order */
        $order = $this->orderRepository->find($id);

        if (empty($order)) {
            return $this->sendError('Order not found');
        }

        $order = $this->orderRepository->update($input, $id);

        return $this->sendResponse($order->toArray(), 'Order updated successfully');
    }

    /**
     * Remove the specified Order from storage.
     * DELETE /orders/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Order $order */
        $order = $this->orderRepository->find($id);

        if (empty($order)) {
            return $this->sendError('Order not found');
        }

        $order->delete();

        return $this->sendSuccess('Order deleted successfully');
    }
}
