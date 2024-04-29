<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateOrderAPIRequest;
use App\Http\Requests\API\UpdateOrderAPIRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\RequestOffer;
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

           $order =  $this->storeRequestOffers($request->except('is_offer'));
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
         try {
             // get user send offer
         $auth_user = auth()->id();

             // get user receive offer
         if($auth_user == $input['from']){
             $to =  $input['to'];
         }else{
             $to =  $input['from'];
         }
         //modify request data before saving
           $input['from'] = $auth_user;
           $input['to'] =$to;

           // save request
       return  $offer = $this->orderRepository->create($input);

         }catch (\Exception $e){
         }
     }

     public function changeStatus(Request $request)
     {
         // get the offer
         $offer = RequestOffer::find($request->offer_id);
         if (empty($offer)) {
             return $this->sendError('offer not found');
         }
         // change status for offer
         if ($request->status == 1){
             $offer->update([
                 'status' => 1
             ]);

             $input['from'] = $offer->request->from;
             $input['to'] = $offer->request->to;
             $input['buyer_product_id'] = $offer->buyer_product_id;
             $input['seller_product_id']  = $offer->seller_product_id;
             $input['points']  = $offer->points;
             $input['request_id']  = $offer->request->id;
             $input['exchange_type']  = $offer->exchange_type;
             $input['status']  = 0;

             //create final order
             $order = $this->orderRepository->create($input);
             return $this->sendResponse($order->toArray(), 'Order saved successfully');

         }else{
             $offer->update([
                 'status' => 2
             ]);
             return $this->sendResponse($offer->toArray(), 'offer updated successfully');
         }
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
