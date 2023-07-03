<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Buyer;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Verification;
use Carbon\Carbon;
use GuzzleHttp\Client;
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

        $query = trim($query);

        $searchForID = preg_match('/^#\d*$/', $query);

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
                $buyers = Buyer::where('buyer.status', $status)->take($limit)->skip(($pageNumber - 1) * $limit)->leftJoin('address', 'address.buyer_id', 'buyer.buyer_id')->leftJoin('region', 'address.region_id', 'region.region_id')->orderBy('first_name', $sort)->get([
                    'buyer.buyer_id', 'buyer.first_name', 'buyer.last_name', 'buyer.email', 'buyer.status', 'buyer.created_at', 'buyer.date_of_birth', 'buyer.banned_at', 'buyer.banned_by', 'address.address_id', 'address.city', 'address.street_number', 'address.building_number', 'address.zipcode', 'region.region_id', 'region.region_name'
                ]);
                $data = $buyers;
            }
            else if ($searchForID) {
                $id = intval(substr($query, 1));
                $buyer = Buyer::where('buyer_id', $id)->where('status', $status)->first();
                if ($buyer === null) return response()->json([]);
                return response()->json([$buyer], 200);
            }
            else {
                $buyers = Buyer::search($query)->where('status', $status)->orderBy('first_name', $sort)->paginate($limit, 'page', $pageNumber);
                $data = $buyers->items();
            }

            return response($data);

        }

        if ($userType == 'seller') {
            if (empty($query)) {
                $sellers = Seller::where('seller.status', $status)->take($limit)->skip(($pageNumber - 1) * $limit)->leftJoin('verification', 'verification.seller_id', '=', 'seller.seller_id')->leftJoin('address', 'address.seller_id', 'seller.seller_id')->join('region', 'address.region_id', 'region.region_id')->leftJoin('admin', 'admin.admin_id', 'verification.verified_by')->get(['seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.status', 'seller.created_at', 'seller.date_of_birth', 'seller.banned_at', 'seller.banned_by', 'verification.ver_id', 'verification.store_name', 'verification.business_info', 'verification.verified_by', 'verification.verified_at', 'address.address_id', 'address.city', 'address.street_number', 'address.building_number', 'address.zipcode', 'region.region_id', 'region.region_name', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name']);

                $data = $sellers;
            }
            else if ($searchForID) {
                $id = intval(substr($query, 1));
                $seller = Seller::where('seller.seller_id', $id)->where('seller.status', $status)->take($limit)->skip(($pageNumber - 1) * $limit)->leftJoin('verification', 'verification.seller_id', '=', 'seller.seller_id')->join('address', 'address.seller_id', 'seller.seller_id')->join('region', 'address.region_id', 'region.region_id')->leftJoin('admin', 'admin.admin_id', 'verification.verified_by')->get(['seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.status', 'seller.created_at', 'seller.date_of_birth', 'seller.banned_at', 'seller.banned_by', 'verification.ver_id', 'verification.store_name', 'verification.business_info', 'verification.verified_by', 'verification.verified_at', 'address.address_id', 'address.city', 'address.street_number', 'address.building_number', 'address.zipcode', 'region.region_id', 'region.region_name', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name']);

                if ($seller === null) return response()->json([]);
                $data = $seller;
            }
            else {
                $verifications = Verification::search($query)->paginate($limit,'page', $pageNumber);

                $allVerificationResult = $verifications->items();
                $data = [];

                foreach($allVerificationResult as $verification) {
                    $_seller = Seller::where('seller.seller_id', $verification->seller_id)->where('seller.status', $status)->leftJoin('verification', 'verification.seller_id', '=', 'seller.seller_id')->join('address', 'address.seller_id', 'seller.seller_id')->join('region', 'address.region_id', 'region.region_id')->join('admin', 'admin.admin_id', 'verification.verified_by')->get(['seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.status', 'seller.created_at', 'seller.date_of_birth', 'seller.banned_at', 'seller.banned_by', 'verification.ver_id', 'verification.store_name', 'verification.business_info', 'verification.verified_by', 'verification.verified_at', 'address.address_id', 'address.city', 'address.street_number', 'address.building_number', 'address.zipcode', 'region.region_id', 'region.region_name', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name']);
                    array_push($data, $_seller);
                }

                $sellers = Seller::search($query)->where('status', $status)->orderBy('first_name', $sort)->paginate($limit,'page', $pageNumber);
                $allSellers = $sellers->items();

                foreach ($allSellers as $seller) {
                    // return response()->json($seller, 200);
                    $_seller = Seller::where('seller.seller_id', $seller->seller_id)->where('seller.status', $status)->leftJoin('verification', 'verification.seller_id', '=', 'seller.seller_id')->join('address', 'address.seller_id', 'seller.seller_id')->join('region', 'address.region_id', 'region.region_id')->leftJoin('admin', 'admin.admin_id', 'verification.verified_by')->get(['seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.status', 'seller.created_at', 'seller.date_of_birth', 'seller.banned_at', 'seller.banned_by', 'verification.ver_id', 'verification.store_name', 'verification.business_info', 'verification.verified_by', 'verification.verified_at', 'address.city', 'address.street_number', 'address.building_number', 'address.zipcode', 'region.region_id', 'region.region_name', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name']);
                    array_push($data, $_seller[0]);
                }
                return response()->json($data, 200);
            }

            return response($data);
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
        $request->has('limit') ? $limit = intval($request->limit) : $limit = 20;
        $request->has('page') ? $page = intval($request->page) : $page = 1;
        $query = $request->q;

        $searchByID = preg_match('/^#\d*$/', $query);

        if (empty($query)) {
            $listingRequests = DB::table('listing_request')->skip($limit * ($page - 1))->take($limit)->join('product', 'listing_request.product_id', '=', 'product.product_id')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', '=', 'seller.seller_id')->orderBy("listing_request.created_at")->where('product.is_approved', '0')->get();
            $total = $listingRequests = DB::table('listing_request')->join('product', 'listing_request.product_id', '=', 'product.product_id')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', '=', 'seller.seller_id')->where('product.is_approved', '0')->count();
        }

        else if ($searchByID) {
            $id = intval(substr($query, 1));
            $listingRequests = DB::table('listing_request')->join('product', 'listing_request.product_id', '=', 'product.product_id')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', '=', 'seller.seller_id')->orderBy("listing_request.created_at")->where('listing_request.product_id', $id)->where('product.is_approved', '0')->get();
            $total = 1;
        }

        else {
            $searchResults = Product::search($query)->orderBy('created_at')->paginate($limit, 'page', $page);
            $total = $searchResults->total();
            $listingRequests = [];
            foreach($searchResults->items() as $result) {
                $other_info = DB::table('product')->where('product.product_id', $result->product_id)->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', '=', 'seller.seller_id')->where('product.is_approved', '0')->get();
                array_push($listingRequests, $other_info);
            }

        }
        return response()->json(['products' => $listingRequests, 'total' => $total], 200);
    }

    public function approveProduct(Request $request) {
        $admin = $request->user();
        $product_id = $request->id;

        $approve = DB::table('listing_request')->where('product_id', $product_id)->update(['approved_at' => Carbon::now(), 'approved_by' => $admin->admin_id]);

        if ($approve) {
            $update_is_approved = DB::table('product')->where('product_id', $product_id)->update(['is_approved' => 1]);
            return response()->json(['message' => 'approved'], 200);
        }
        else return response()->json(['error' => 'something went wrong'], 500);
    }

    public function unapproveProduct(Request $request) {
        $product_id = $request->id;
        $unapprove = DB::table('listing_request')->where('product_id', $product_id)->update(['approved_at' => null, 'approved_by' => null]);

        if ($unapprove) {
            $update_is_approved = DB::table('product')->where('product_id', $product_id)->update(['is_approved' => 0]);
            return response()->json(['message' => 'unapproved'], 200);
        }

        else return response()->json(['error' => 'something went wrong'], 500);
    }

    public function getCategories(Request $request) {
        $categories = DB::table('category')->orderBy('category_name')->get();
        return response()->json(['categories' => $categories], 200);
    }

    public function getProducts(Request $request) {
        if ($request->has('category_id'))
            $category_id = intval($request->input('category_id'));
        else $category_id = 0;

        if ($request->has('limit'))
            $limit = intval($request->input('limit'));
        else $limit = 20;

        if ($request->has('page'))
            $page = intval($request->input('page'));
        else $page = 1;

        // if has sort ....

        if ($request->has('q'))
            $query = $request->input('q');
        else $query = '';

        $query = trim($query);
        $searchByID = preg_match('/^#\d*$/', $query);

        // return response()->json(['category_id' => $category_id, 'limit' => $limit, 'page' => $page, 'query' => $query]);

        // return response()->json(['category_id' => $category_id, 'limit' => $limit, 'page' => $page, 'query' => $query]);

        if ($category_id === 0) {
            if (empty($query)) {
                $products = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->where('product.is_approved', 1)->whereNull('product.banned_by')->take($limit)->offset($limit * ($page - 1))->get(['product.*', 'listing_request.approved_at', 'listing_request.approved_by AS approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'verification.store_name', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);

                $total = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->where('product.is_approved', 1)->whereNull('product.banned_by')->count();
            }
            else if ($searchByID) {
                $id = intval(substr($query, 1));
                $products = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->where('product.product_id', $id)->where('product.is_approved', 1)->whereNull('product.banned_by')->take($limit)->offset($limit * ($page - 1))->get(['product.*', 'listing_request.approved_at', 'listing_request.approved_by AS approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'verification.store_name', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);

                $total = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->where('product.product_id', $id)->where('product.is_approved', 1)->whereNull('product.banned_by')->count();
            }
            else {
                 $results = Product::search($query)->where('is_approved', 1)->paginate($limit, 'page', $page);
                // return response()->json($results);
                if (count($results->items()) == 0) return response()->json(['products' => [], 'total' => 0]);
                $total = $results->total();
                $products = [];
                foreach($results->items() as $result) {
                    $product = DB::table('product')->join('seller', 'seller.seller_id', '=', 'product.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->where('product.product_id', $result->product_id)->whereNull('product.banned_by')->get(['product.*', 'verification.store_name', 'listing_request.approved_at', 'listing_request.approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);

                    array_push($products, $product);
                }

                if (count($products) == 1) $products = $products[0];
            }
        }

        else {
            if (empty($query)) {
                $products = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->where('product.category_id', $category_id)->where('product.is_approved', 1)->whereNull('product.banned_by')->take($limit)->offset($limit * ($page - 1))->get(['product.*', 'listing_request.approved_at', 'listing_request.approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'verification.store_name', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);

                $total = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->where('product.category_id', $category_id)->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->where('product.is_approved', 1)->whereNull('product.banned_by')->count();
            }
            else if ($searchByID) {
                $id = intval(substr($query, 1));
                $products = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->where('product.product_id', $id)->where('product.is_approved', 1)->where('category.category_id', $category_id)->whereNull('product.banned_by')->take($limit)->offset($limit * ($page - 1))->get(['product.*', 'listing_request.approved_at', 'listing_request.approved_by AS approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'verification.store_name', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);

                $total = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->where('product.product_id', $id)->where('product.is_approved', 1)->where('category.category_id', $category_id)->whereNull('product.banned_by')->count();
            }
            else {
                $results = Product::search($query)->where('category_id', $category_id)->where('is_approved', 1)->paginate($limit, 'page', $page);
                if (count($results->items()) == 0) return response()->json(['products' => [], 'total' => 0]);

                $total = $results->total();

                $products = [];
                foreach($results->items() as $result) {
                    $product = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->where('product.product_id', $result->product_id)->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->whereNull('product.banned_by')->get(['product.*', 'listing_request.approved_at', 'listing_request.approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'verification.store_name', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);

                    array_push($products, $product);
                }

                if (count($products) == 1) $products = $products[0];
            }
        }

        return response()->json(['products' => $products, 'total' => $total], 200);
    }

    public function getBannedProducts(Request $request) {
        if ($request->has('category_id'))
            $category_id = intval($request->input('category_id'));
        else $category_id = 0;

        if ($request->has('limit'))
            $limit = intval($request->input('limit'));
        else $limit = 20;

        if ($request->has('page'))
            $page = intval($request->input('page'));
        else $page = 1;

        // if has sort ....

        if ($request->has('q'))
            $query = $request->input('q');
        else $query = '';

        $query = trim($query);
        $searchByID = preg_match('/^#\d*$/', $query);

        // return response()->json(['category_id' => $category_id, 'limit' => $limit, 'page' => $page, 'query' => $query]);

        if ($category_id === 0) {
            if (empty($query)) {
                $products = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->join('admin AS ban_admin', 'ban_admin.admin_id', 'product.banned_by')->where('product.is_approved', 1)->whereNotNull('product.banned_by')->take($limit)->offset($limit * ($page - 1))->get(['product.*', 'listing_request.approved_at', 'listing_request.approved_by AS approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'ban_admin.first_name AS ban_admin_first_name', 'ban_admin.last_name AS ban_admin_last_name', 'verification.store_name', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);
            }
            else if ($searchByID) {
                $id = intval(substr($query, 1));
                $products = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->join('admin AS ban_admin', 'ban_admin.admin_id', 'product.banned_by')->where('product.product_id', $id)->where('product.is_approved', 1)->whereNotNull('product.banned_by')->take($limit)->offset($limit * ($page - 1))->get(['product.*', 'listing_request.approved_at', 'listing_request.approved_by AS approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'ban_admin.first_name AS ban_admin_first_name', 'ban_admin.last_name AS ban_admin_last_name', 'verification.store_name', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);
            }
            else {
                 $results = Product::search($query)->where('is_approved', 1)->paginate($limit, 'page', $page);

                if (count($results->items()) == 0) return response()->json([]);

                $products = [];
                foreach($results->items() as $result) {
                    $product = DB::table('product')->join('seller', 'seller.seller_id', '=', 'product.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->join('admin AS ban_admin', 'ban_admin.admin_id', 'product.banned_by')->where('product.product_id', $result->product_id)->whereNotNull('product.banned_by')->get(['product.*', 'verification.store_name', 'listing_request.approved_at', 'listing_request.approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'ban_admin.first_name AS ban_admin_first_name', 'ban_admin.last_name AS ban_admin_last_name', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);

                    array_push($products, $product);
                }

                if (count($products) == 1) $products = $products[0];
            }
        }

        else {
            if (empty($query)) {
                $products = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin AS ban_admin', 'ban_admin.admin_id', 'product.banned_by')->where('product.category_id', $category_id)->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->where('product.is_approved', 1)->whereNotNull('product.banned_by')->take($limit)->offset($limit * ($page - 1))->get(['product.*', 'listing_request.approved_at', 'listing_request.approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'ban_admin.first_name AS ban_admin_first_name', 'ban_admin.last_name AS ban_admin_last_name', 'verification.store_name', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);
            }
            else if ($searchByID) {
                $id = intval(substr($query, 1));
                $products = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->join('admin AS ban_admin', 'ban_admin.admin_id', 'product.banned_by')->where('product.product_id', $id)->where('product.is_approved', 1)->where('category.category_id', $category_id)->whereNotNull('product.banned_by')->take($limit)->offset($limit * ($page - 1))->get(['product.*', 'listing_request.approved_at', 'listing_request.approved_by AS approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'ban_admin.first_name AS ban_admin_first_name', 'ban_admin.last_name AS ban_admin_last_name', 'verification.store_name', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);
            }
            else {
                $results = Product::search($query)->where('category_id', $category_id)->where('is_approved', 1)->paginate($limit, 'page', $page);

                if (count($results->items()) == 0) return response()->json([]);

                $products = [];
                foreach($results->items() as $result) {
                    $product = DB::table('product')->join('seller', 'product.seller_id', '=', 'seller.seller_id')->join('product_img', 'product.product_id', '=', 'product_img.product_id')->join('category', 'product.category_id', '=', 'category.category_id')->join('verification', 'verification.seller_id', 'seller.seller_id')->join('listing_request', 'listing_request.product_id', '=', 'product.product_id')->where('product.product_id', $result->product_id)->join('admin', 'listing_request.approved_by', '=', 'admin.admin_id')->join('admin AS ban_admin', 'ban_admin.admin_id', 'product.banned_by')->whereNotNull('product.banned_by')->get(['product.*', 'listing_request.approved_at', 'listing_request.approved_by', 'admin.first_name AS admin_first_name', 'admin.last_name AS admin_last_name', 'admin.admin_id', 'ban_admin.first_name AS ban_admin_first_name', 'ban_admin.last_name AS ban_admin_last_name', 'verification.store_name', 'product_img.img_url AS product_img', 'seller.seller_id', 'seller.first_name AS seller_first_name', 'seller.last_name AS seller_last_name', 'seller.email', 'seller.img_url AS store_logo', 'category.category_name']);

                    array_push($products, $product);
                }

                if (count($products) == 1) $products = $products[0];
            }
        }

        return response()->json($products, 200);
    }

    public function banProduct(Request $request) {
        $admin_id = $request->user()->admin_id;
        $product_id = $request->id;
        $ban = DB::table('product')->where('product_id', $product_id)->update([ 'banned_at' => Carbon::now(), 'banned_by' => $admin_id]);

        return response()->json(['success' => 'banned product ID '.$product_id, 'banned_by' => $admin_id], 200);
    }

    public function unbanProduct(Request $request) {
        $product_id = $request->id;
        $ban = DB::table('product')->where('product_id', $product_id)->update([ 'banned_at' => null, 'banned_by' => null]);

        return response()->json(['success' => 'unbanned product ID '.$product_id, 'banned_by' => null], 200);
    }

    public function removeProduct(Request $request) {
        $product_id = $request->id;
        $removeProduct = DB::table('product')->where('product_id', $product_id)->delete();
        $removeImg = DB::table('product_img')->where('product_id', $product_id)->delete();
        $removeListingRequest = DB::table('listing_request')->where('product_id', $product_id)->delete();

        return response()->json(['success' => 'removed product ID '.$product_id], 200);
    }

    public function getAdminInformation(Request $request) {
        // $token = $request->bearerToken();
        $admin_id = $request->user()->admin_id;
        $admin = DB::table('admin')->where('admin_id', $admin_id)->take(1)->get(['admin_id', 'first_name', 'last_name', 'username', 'img_url']);
        $admin = $admin[0];
        return response()->json($admin, 200);
    }

    public function editAccount(Request $request) {
        $admin = $request->user();
        $username = $request->input('username');
        $password = $request->input('password');

        if ($username == $admin->username) {
            $rules = [
                'password' => 'required|min:8',
                'confirmPassword' => 'same:password',
            ];
        }

        else {
            if (isset($password)) {
                $rules = [
                    'username' => 'required|unique:admin,username',
                    'password' => 'required|min:8',
                    'confirmPassword' => 'same:password',
                ];
            }
            else {
                $rules = [
                    'username' => 'required|unique:admin,username',
                ];
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin->username = $username;
        if (isset($password)) {
            if (Hash::check($password, $admin->password)) {
                return response()->json(['errors' => ['password' => ['password cannot be the same as the old one']]], 422);
            }
            else {
                $admin->password = Hash::make($password);
            }
        }
        $admin->save();

        return response()->json(['success' => 'successfully updated'], 200);
    }

    public function editLogo(Request $request) {
        $admin = $request->user();

        $validator = Validator::make($request->all(), [
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $logo = $this->uploadImage($request, 'logo');

        if (empty($logo)) {
            return response()->json(['errors' => ['logo' => ['failed to upload logo']]], 500);
        }

        $admin->img_url = $logo;
        $admin->save();

        return response()->json(['success' => 'successfully updated', 'img_url' => $logo], 200);
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
        $address = DB::table('address')->where('seller_id', $seller_id)->delete();

        if ($reject) return response()->json(['success' => 'rejected verification ID '.$ver_id]);
        else return response()->json(['error' => 'something went wrong'], 500);
    }

    public function getAllAccountInformation(Request $request) {
        $buyer_count_30_days = DB::table('buyer')->where('created_at', '>', Carbon::now()->subDays(30))->count();
        $seller_count_30_days = DB::table('seller')->where('created_at', '>', Carbon::now()->subDays(30))->count();

        $all_buyer_count = DB::table('buyer')->count();
        $all_seller_count = DB::table('seller')->count();

        return response()->json(['buyerCount30Days' => $buyer_count_30_days, 'sellerCount30Days' => $seller_count_30_days, 'totalCount30Days' => $buyer_count_30_days + $seller_count_30_days, 'totalCountAllTime' => $all_buyer_count + $all_seller_count], 200);
    }

    public function uploadImage(Request $request, $name) {
        $image = $request->file($name);
            $client = new Client();
            $response = $client->request('POST', 'https://api.imgur.com/3/image', [
                'headers' => [
                        'authorization' => 'Client-ID ' . env('imgur_client_id'),
                        'content-type' => 'application/x-www-form-urlencoded',
                    ],
                'form_params' => [
                        'image' => base64_encode(file_get_contents($image))
                    ],
                ]);
            return json_decode($response->getBody()->getContents())->data->link;
    }
}
