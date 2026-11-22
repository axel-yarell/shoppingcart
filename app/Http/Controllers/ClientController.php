<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session; 
use App\Models\Product;
use App\Models\Category;
use App\Models\Slider;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Order;

class ClientController extends Controller
{
    //

    public function home()
    {
        $sliders = Slider::where('status', 1)->get();
        $products = Product::where('status', 1)->get();
        return view('client.home', compact('sliders', 'products'));
    }
    
    public function shop()
    {
        $products = Product::where('status', 1)->get();
        return view('client.shop', compact('products'));
    }

    public function cart()
    {
        return view('client.cart');
    } 
    public function checkout()
    {
        if (Session::has('client')) {
            return view('client.checkout');
        } else {
            return redirect('signin')->with('error', 'Please sign in to proceed to checkout.'); 
        }
        return view('client.checkout');
    }

    public function register()
    {
        return view('client.register');
    }

    public function createAccount(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:clients,email',
            'password' => 'required|min:6',
        ]);

        $client = new \App\Models\Client();
        $client->email = $request->input('email');
        $client->password = bcrypt($request->input('password'));
        $client->save();

        return redirect()->back()->with('success', 'Account created successfully. Please sign in.');
    }

    public function signin()
    {
        return view('client.signin');
    }

    public function accessAccount(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $client = Client::where('email', $request->input('email'))->first();

        if ($client && \Hash::check($request->input('password'), $client->password)) {
            Session::put('client', $client);
            return redirect('/shop')->with('success', 'Signed in successfully.');
        } else {
            return redirect()->back()->with('error', 'Invalid email or password.');
        }

        return redirect()->back()->with('error', 'You do not have an account with that email address.');
    }

    public function logout()
    {
        Session::forget('client');
        return redirect('/')->with('success', 'Logged out successfully.');
    }

    public function addtocart($id)
    {
        $product = Product::find($id);
       
        $oldCart = session::has('cart') ? session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product);
        session::put('cart', $cart);
        Session::put('topcart', $cart->items);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function updateCartQty(Request $request, $id)
    {
        $newQty = $request->input('qty');
        $oldCart = session::has('cart') ? session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->updateQty($id, $request->input('qty'));
        session::put('cart', $cart);
        Session::put('topcart', $cart->items);

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function removeItem($id)
    {
        $oldCart = session::has('cart') ? session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        session::put('cart', $cart);
        Session::put('topcart', $cart->items);

        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }

    public function buy(Request $request)
    {
        $oldCart = session::has('cart') ? session::get('cart') : null;
        $cart = new Cart($oldCart);

        $order = new Order();
        $order->first_name = $request->input('firstName');
        $order->last_name = $request->input('lastName');
        $order->address = $request->input('address');
        // $order->cart = json_encode(Session::get('cart'));
        $order->cart = serialize($cart);
        $order->save();

        Session::forget('cart');

        return redirect('/cart')->back()->with('success', 'Purchase completed successfully!');

        // if (!Session::has('client')) {
        //     return redirect('signin')->with('error', 'Please sign in to proceed to checkout.');
        // }

        // $request->validate([
        //     'firstName' => 'required|string|max:255',
        //     'lastName' => 'required|string|max:255',
        //     'address' => 'required|string|max:255',
        // ]);

       
        // Session::forget('cart');
        // Session::forget('topcart');

        // return redirect('/shop')->with('success', 'Purchase completed successfully!');
    }
}
