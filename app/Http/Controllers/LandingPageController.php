<?php

namespace App\Http\Controllers;

use App\Http\Requests\updateProfileRequest;
use App\Models\Cart;
use App\Models\category;
use App\Models\Review;
use App\Models\Product;
use App\Models\TransactionDetail;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Switch_;
use RealRashid\SweetAlert\Facades\Alert;


class LandingPageController extends Controller
{
    public function index(){
       $data  =  Product::with('galleries')->get()->take(8);
       $suggestproduct  =  Product::with('galleries')->where('Stock', '!=', 0)->get()->take(3);
       return view('frontend.LandingPage',compact('data','suggestproduct'));
    }
    public function detail($id){
        $review = Review::with('product','user')->where('Products_id',$id)->get();

       $data =  Product::with('galleries')->findOrFail($id);
        return view('frontend.details',compact('data','review'));
    }

        public function add(Request $request,$id){
            $cek = Cart::where('users_id',Auth::user()->id)
                        ->where('Products_id',$id)
                        ->first();
                $data = [
                    'products_id' => $id,
                    'users_id' => Auth::user()->id
                ];
            if($cek == null){
                Cart::create($data);
                if($request->addtocard == true){
                    toast('Succesfully add to cart','success');
                    return back();

                }else{
                    return redirect()->route('cart.index');
                }
            }else{
                return redirect()->route('cart.index');
            }
    }

        public function categories(){
         $category = category::all();

         if(request('searchCategory')){

            if(request('searchCategory') == "all"){
                $data = Product::with('galleries','category')->where('Stock', '!=', 0)->get();
                return view('frontend.categories',compact('data','category'));
            }

            $key = request('searchCategory');
            $getIdCategory = category::where('name',$key)->first();
            $data = Product::with('galleries')->where('category_id',$getIdCategory->id)->Where('Stock', '!=', 0)->get();
            return view('frontend.categories',compact('data','category'));
         }else{
            $data = Product::with('galleries','category')->where('Stock', '!=', 0)->get();
            return view('frontend.categories',compact('data','category'));
         }

    }

    public function addreview(Request $request,$id)
    {
        $data =  $request -> all();
        $data['users_id'] = Auth::user()->id;
        $data['Products_id'] = $id;

        Review::create($data);
        return redirect()->route('detail',$id);
    }

    public function orderhistory()
    {
        $status =  request('status');

        if(request('status')){
            if(request('status') == "all"){
                $history = Transaction::with('transactiondetail.product.galleries')
                ->where('users_id',Auth::user()->id)
                ->orderBy('id','desc')->get();
                return view('frontend.orderhistory',compact('history'));
            }
            $history = Transaction::with('transactiondetail.product.galleries')
            ->where('users_id',Auth::user()->id)
            ->where('transaction_status',$status)
            ->orderBy('id','desc')->get();


            return view('frontend.orderhistory',compact('history'));
        }else{
            $history = Transaction::with('transactiondetail.product.galleries')
            ->where('users_id',Auth::user()->id)
            ->orderBy('id','desc')->get();

            return view('frontend.orderhistory',compact('history'));

        }


    }

    public function ordershow($id)
    {
        $history = Transaction::with('transactiondetail.product.galleries')->where('id',$id)->first();
        $items = TransactionDetail::with('transaction','product.galleries')->where('transactions_id',$id)->get();
        return view('frontend.showhistory',compact('history','items'));
    }
    public function aboutme(){
        return view('frontend.about');
    }
    public function faq()
    {
        return view('frontend.faq');
    }
    public function editProfile($id)
    {
        $data = User::find($id);
        return view('frontend.editprofile',compact('data'));
    }
    public function updateProfile(Request $request, $id)
    {
        $data = $request->all();
        // dd($data);
        if($request->file('avatar')){
             $user = User::find($id);
             $user->avatar = $request->file('avatar')->store('image/avatar','public');
             $user->save();
        }

          $user = User::find($id);
             $user->name = $request->name;
             $user->email = $request->email;
             $user->telp = $request->telp;
           $user->save();

        // Validator::make($data, [
        //     'name' => ['required'],
        //     'email' => ['required'],
        //     'telp' => ['required'],
        //     // 'old_password' => ['required'],
        //     // 'new_password' => ['required'],
        //     // 'password_confirm' => ['same:new_password'],
        // ])->validate();

        if($request->old_password || $request->new_password || $request->password_confirm){
            // $user = User::find(auth()->user()->id);
            if ($request->new_password != $request->password_confirm) {
                return back()->with('error', 'The specified password does not match the database password');
            }else{
                $user = User::find($id);
                $user->password = Hash::make($request->new_password);
                $user->save();
            }
        }
        toast('Berhasil memperbarui profile','success');
        return back();

    }
    public function success()
    {
        return view('frontend.successfully');
    }
    public function successTunai()
    {
        return view('frontend.successTunai');
    }
}
