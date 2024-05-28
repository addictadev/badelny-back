<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateOrderAPIRequest;
use App\Http\Requests\API\UpdateOrderAPIRequest;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\RequestResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\RequestOffer;
use App\Models\User;
use App\Notifications\RequestNotification;
use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

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
            // get the exchange type from seller product
            $sellerProduct =  Product::where('id',$request->seller_product_id)->first();

            // get categories of buyer products
            $categories = Product::whereIn('id', $request->buyer_product_id)->pluck('category_id')->toArray();


//            if ($sellerProduct->exchange_type != 1){
//                // check the buyer Product Categories == specific exchange Categories of seller
//                if ($sellerProduct->exchange_categories) {
//                    $differenceExchangeCategories = array_diff($categories, $sellerProduct->exchange_categories);
//                }
//            }

            if (!empty($sellerProduct->exchange_categories)) {
                $differenceExchangeCategories = array_diff($categories, $sellerProduct->exchange_categories);
                if ($differenceExchangeCategories != null){
                    return $this->sendError('These products cannot be Exchange with Seller Product ');
                }
            }

            // get buyer id
            foreach ($request->buyer_product_id as $product_id)
            {
                $buyerProduct =  Product::where('id',$product_id)->first();
            }

            $buyerId =  $buyerProduct->user_id;
            // get the seller id
            $sellerId = $sellerProduct->user_id;

            // save the request of order
            $request->merge(['from' => $buyerId ,'to' =>$sellerId]);
            $input = $request->all();

            if (!$request->is_offer){

                $order = $this->orderRepository->create($input);

                $resource = new NotificationResource($order);

                User::find($sellerId)->notify(new RequestNotification($order));

            }else{
                $order =  $this->storeRequestOffers($request->except('is_offer'));
            }
            return $this->sendResponse($order->toArray(), 'request saved successfully');
        }catch (\Exception $e){
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Store Ofeers for RequestNotification Orders.
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
             return $this->sendApiError(__('messages.something_went_wrong'), 500);
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
             $input['status']  = Order::STATUS_PENDING;

             //create final order
             $order = $this->orderRepository->create($input);
             if ($order){
                 $order->OrderStatusHistory()->create([
                     'status' => $order->status,
                     'color' => '#0CB450'
                 ]);
             }
             return $this->sendResponse($order->toArray(), 'Order saved successfully');

         }else{
             $offer->update([
                 'status' => 2
             ]);
             return $this->sendResponse($offer->toArray(), 'offer updated successfully');
         }
     }
    /**
     * get all requests of auth usergetRequests
     */

     public function getRequests()
     {
         $user_id = \request()->user() ? \request()->user()->id : null;
         $limit = \request('limit') ? \request('limit') : 20;
         $offers = $this->orderRepository->getRequests($user_id,$limit);

         return  $this->sendApiResponse(array('data' => ['requests' =>  RequestResource::collection($offers), 'resent' => []]), 'Requests retrieved successfully');

     }
    /**
     * get Request by id
     */
    public function getRequestById(string $id)
    {
        $request = $this->orderRepository->getRequestById($id);
        if (empty($request)) {
            return $this->sendApiError('Request not found', 404);
        }
        return  $this->sendApiResponse(array('data' => new RequestResource($request)), 'Requests retrieved successfully');

    }

    /**
     * get all orders of auth user
     * or filter by status
     */
    public function getOrders(Request $request)
    {
        $status = $request->status;
        $user_id = \request()->user() ? \request()->user()->id : null;
        $limit = \request('limit') ? \request('limit') : 20;
        $offers = $this->orderRepository->getOrders($user_id,$limit,$status);

        return  $this->sendApiResponse(array('data' => OrderResource::collection($offers)), 'Orders retrieved successfully');

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

    public function changeOrderStatus($id, Request $request)
    {
        /** @var Order $order */
        $order = $this->orderRepository->getByID($id);
        if (!$order) {
            return $this->sendApiError('order not found', 404);
        }

        $order->update(['status' => $request->status]);
        return  $this->sendApiResponse(array(), 'Orders retrieved successfully');
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
