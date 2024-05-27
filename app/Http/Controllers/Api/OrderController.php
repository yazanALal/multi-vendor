<?php

namespace App\Http\Controllers\api;

use App\Events\OrderCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeOrderStatusRequest;
use App\Http\Requests\CheckOutRequest;
use App\Http\Resources\OrderResource;
use App\Http\Traits\GeneralTrait;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    use GeneralTrait;

    public function checkout(CheckOutRequest $request)
    {
        DB::beginTransaction();
        try {
            $userId = $request->user('sanctum')->id;
            $exists = Cart::where('user_id', $userId)->exists();

            if (!$exists) {
                return $this->apiResponse(null, false, 'your cart is empty');
            }
            $cartItems = Cart::where('user_id', $userId)->with(['product' => function ($query) {
                $query->select('id', 'store_id');
            }])->get();
            $groupedCartItems = $cartItems->groupBy(function ($item) {
                return $item->product->store_id;
            });
            $order = array();
            $orderIds = array();
            $address = [
                'state' => $request->input('state'),
                'city' => $request->input('city'),
                'country' => $request->input('country'),
            ];
            $address = json_encode($address);
            $orderPrice = 0;
            foreach ($groupedCartItems as $order => $products) {
                foreach ($products as $product) {
                    $orderPrice = $orderPrice + $product['price'];
                }
                $order = [
                    'store_id' => $order,
                    'user_id' => $userId,
                    'status' => 'pending',
                    'address' => $address,
                    'uuid' => Str::uuid(),
                    'created_at' => Carbon::now(),
                    'total_price' => $orderPrice,
                ];
                $orderId = DB::table('orders')->insertGetId($order);
                $orderIds[] = $orderId;
                foreach ($products as $product) {
                    $orderItems[] = [
                        'order_id' => $orderId,
                        'product_id' => $product['product_id'],
                        'price' => $product['price'],
                        'quantity' => $product['quantity'],
                        'uuid' => Str::uuid(),
                        'created_at' => Carbon::now(),
                    ];
                }

                $orderId = DB::table('order_items')->insert($orderItems);
            }
            event(new OrderCreatedEvent($cartItems, $userId, $orderIds));
            DB::commit();
            return $this->apiResponse('added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    //retrieve orders for the store
    public function showOrders(Request $request)
    {
        try {
            $userId = $request->user('sanctum')->id;
            $orders = Order::whereIn('store_id', function ($query)  use ($userId) {
                $query->select('id')
                    ->from('stores')
                    ->where('user_id', $userId);
            })->with([
                'user' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                },
                'orderItems',
                'orderItems.product' => function ($query) {
                    $query->select('id', 'name');
                }
            ])->get();
            return $this->apiResponse(OrderResource::collection($orders));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function changeOrderStatus(ChangeOrderStatusRequest $request)
    {
        try {
            $update = Order::where('uuid', $request->input('uuid'))->update([
                'status' => $request->input('status'),
            ]);
            if ($update) {
                return $this->apiResponse("updated successfully");
            }
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    //retrieve order history for normal users

    public function orderHistory(Request $request)
    {
        try {
           $userId= $request->user('sanctum')->id;
           $orders=Order::where('user_id',$userId)->with(['orderItems','user','orderItems.product'])->get();
           return $this->apiResponse(OrderResource::collection($orders));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }
}
