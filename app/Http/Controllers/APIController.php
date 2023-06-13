<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
    //
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
