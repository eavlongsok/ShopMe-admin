<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
    //
    public function getUsers(Request $request) {
        if (isset($request->limit))
            $limit = $request->limit;
        else $limit = 20;

        if (isset($request->offset))
            $offset = $request->offset;
        else $offset = 0;

        $userType = $request->userType;

        $tableName = $userType == 2 ? 'seller' : 'buyer';

        $idName = $userType == 2 ? 'seller_id' : 'buyer_id';

        $users = DB::table($tableName)->skip($offset)->take($limit)->where('status', 1)->get([$idName, 'first_name', 'last_name', 'email', 'date_of_birth', 'status', 'created_at']);
        if (isset($users))
            return response($users, 200);
        else return response()->json(['message' => 'no users found'], 404);
    }

    public function searchUsers(Request $request, $userType) {
        $query = $request->q;

        if ($request->has('limit'))
            $limit = $request->limit;
        else $limit = 20;

        if ($request->has('pageNumber'))
            $pageNumber = $request->pageNumber;
        else $pageNumber = 1;

        if ($request->has('status'))
        	$status = intval($request->status);
        else $status = 1;

        // return response()->json(['status' => $status, 'limit' => $limit, 'pageNumber' => $pageNumber, 'query' => $query]);
        if ($request->has('sort'))
            $sort = $request->sort;
        else $sort = 'asc';

        if ($userType == 'buyer') {
            if (empty($query)) {
                $buyers = Buyer::where('status', $status)->take($limit)->skip(($pageNumber - 1) * $limit)->orderBy('first_name', $sort)->get();
                $data = $buyers;
            }
            else {
                $buyers = Buyer::search($query)->where('status', $status)->orderBy('first_name', $sort)->paginate($limit, 'page', $pageNumber);
                $data = $buyers->items();
            }
            $filteredBuyers = [];

            foreach ($data as $buyer) {
                $newBuyer = [
                    'buyer_id' => $buyer->buyer_id,
                    'first_name' => $buyer->first_name,
                    'last_name' => $buyer->last_name,
                    'email' => $buyer->email,
                    'status' => $buyer->status,
                    'created_at' => $buyer->created_at,
                    'date_of_birth' => $buyer->date_of_birth,
                    'banned_at' => $buyer->banned_at,
                    'banned_by' => $buyer->banned_by,
                ];
                array_push($filteredBuyers, $newBuyer);
            }
            return response($filteredBuyers);

        }

        if ($userType == 'seller') {
            if (empty($query)) {
                $sellers = Seller::where('status', $status)->take($limit)->skip(($pageNumber - 1) * $limit)->orderBy('first_name', $sort)->get();
                $data = $sellers;
            }
            else {
                $sellers = Seller::search($query)->where('status', $status)->orderBy('first_name', $sort)->paginate($limit,'page', $pageNumber);
                $data = $sellers->items();
            }
            $filteredSellers = [];

            foreach ($data as $seller) {
                $newSeller = [
                    'seller_id' => $seller->seller_id,
                    'first_name' => $seller->first_name,
                    'last_name' => $seller->last_name,
                    'email' => $seller->email,
                    'status' => $seller->status,
                    'created_at' => $seller->created_at,
                    'date_of_birth' => $seller->date_of_birth,
                    'banned_at' => $seller->banned_at,
                    'banned_by' => $seller->banned_by,
                ];
                array_push($filteredSellers, $newSeller);
            }

            return response($filteredSellers);
        }
    }

    public function ban(Request $request, $userType) {
        $id = $request->id;
        $tableName = $userType;
        $idName = $userType == 'seller' ? 'seller_id' : 'buyer_id';
        $ban = DB::table($tableName)->where($idName, '=', $id)->update(['status' => 0, 'banned_at' => Carbon::now(), 'banned_by' => Auth::guard('admin_token')->user()->admin_id]);
        if ($ban) {
            return response()->json(['message' => 'banned ' . $tableName . ' with the ID of ' . $id], 200);
        }
        else {
            return response()->json(['error' => 'something went wrong'], 500);
        }
    }

    public function unban(Request $request, $userType) {
        $id = $request->id;
        $tableName = $userType;
        $idName = $userType == 'seller' ? 'seller_id' : 'buyer_id';
        $ban = DB::table($tableName)->where($idName, $id)->update(['status' => 1, 'banned_at' => null, 'banned_by' => null]);
        if ($ban) {
            return response()->json(['message' => 'unbanned ' . $tableName . ' with the ID of ' . $id], 200);
        }
        else {
            return response()->json(['error' => 'something went wrong'], 500);
        }
    }

    public function listingRequests(Request $request)
    {
        $limit = $request->limit;
        $offset = $request->offset;
        $listingRequests = DB::table('listing_request')->skip($offset)->take($limit)->join('product', 'listing_request.product_id', '=', 'product.product_id')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->orderBy("listing_request.created_at")->where('product.is_approved', '0')->get();
        return response($listingRequests, 200);
    }

    public function approveProduct(Request $request) {
        $token = $request->bearerToken();
        $admin = Auth::guard('admin_token')->user();
        $product_id = $request->id;
        $approve = DB::table('listing_request')->where('product_id', $product_id)->update(['approval_date' => Carbon::now(), 'approved_by' => $admin->admin_id]);

        if ($approve) {
            $update_is_approved = DB::table('product')->where('product_id', $product_id)->update(['is_approved' => 1]);
            return response()->json(['message' => 'approved'], 200);
        }
        else return response()->json(['error' => 'something went wrong'], 500);
    }
}
