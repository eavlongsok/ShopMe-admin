<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Buyer;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        // return response()->json(['message' => 'searching for ' . $userType], 200);
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
        $ban = DB::table($tableName)->where($idName, '=', $id)->update(['status' => 0, 'banned_at' => Carbon::now(), 'banned_by' => $admin_id = $request->user()->admin_id]);
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

    public function getAdminInformation(Request $request) {
        // $token = $request->bearerToken();
        $admin_id = $request->user()->admin_id;
        $info = DB::table('admin')->where('admin_id', $admin_id)->first();
        if ($info) {
            $info = [
                'admin_id' => $info->admin_id,
                'first_name' => $info->first_name,
                'last_name' => $info->last_name,
                'username' => $info->username,
                'created_at' => $info->created_at,
                "updated_at"=> $info->updated_at,
            ];
            return response()->json($info, 200);
        }
        else return response()->json(['error' => 'something went wrong'], 500);
    }

    public function createNewAdmin(Request $request) {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $username = $request->input('username');
        $password = $request->input('password');

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|string|regex:/^\S*$/u|unique:admin,username',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = new Admin();
        $admin->first_name = trim($first_name);
        $admin->last_name = trim($last_name);
        $admin->username = trim($username);
        $admin->password = Hash::make($password);
        $admin->save();

        $newAdmin = Admin::where('username', $username)->first();
        $token = $newAdmin->createToken(time())->plainTextToken;
        $admin->api_token = $token;

        return response()->json(['success' => ['message' => 'Created successfully'], 'token' => $token], 200);
    }

    public function getPendingVerificationAccounts(Request $request) {
        if (isset($request->limit))
            $limit = $request->limit;
        else $limit = 20;
        if (isset($request->page))
            $page = $request->page;
        else $page = 1;

        $pendingVerificationAccounts = DB::table('verification')->leftJoin('seller', 'verification.seller_id', '=', 'seller.seller_id')->leftJoin('address', 'address.seller_id', 'seller.seller_id')->leftJoin('region', 'address.region_id', 'region.region_id')->where('verification.verified_at', null)->where('seller.status', 1)->orderBy('verification.created_at')->paginate($limit, ['verification.ver_id', 'verification.store_name', 'verification.business_info', 'seller.seller_id', 'verification.created_at', 'seller.first_name', 'seller.img_url', 'seller.last_name', 'seller.email', 'seller.date_of_birth', 'address.city', 'address.street_number', 'address.building_number', 'address.zipcode', 'region.region_id', 'region.region_name', 'verification.created_at'], 'page', $request->page);

        return response($pendingVerificationAccounts, 200);
    }

    public function verifyAccount(Request $request) {
        $admin_id = $request->user()->admin_id;
        $ver_id = $request->id;
        $verify = DB::table('verification')->where('ver_id', $ver_id)->update(['verified_at' => Carbon::now(), 'verified_by' => $admin_id]);

        if ($verify) return response()->json(['success' => 'verified verification ID '.$ver_id]);
        else return response()->json(['error' => 'something went wrong'], 500);
    }

    public function unverifyAccount(Request $request) {
        $admin_id = $request->user()->admin_id;
        $ver_id = $request->id;
        $verify = DB::table('verification')->where('ver_id', $ver_id)->update(['verified_at' => null, 'verified_by' => null]);

        if ($verify) return response()->json(['success' => 'unverified verification ID '.$ver_id]);
        else return response()->json(['error' => 'something went wrong'], 500);
    }

    public function rejectVerification(Request $request) {
        $admin_id = $request->user()->admin_id;
        $ver_id = $request->id;
        $seller_id = DB::table('verification')->where('ver_id', $ver_id)->first()->seller_id;
        $reject = DB::table('verification')->where('ver_id', $ver_id)->delete();
        // $address = DB::table('address')->where('seller_id', $seller_id)->delete();

        if ($reject) return response()->json(['success' => 'rejected verification ID '.$ver_id]);
        else return response()->json(['error' => 'something went wrong'], 500);
    }
}
