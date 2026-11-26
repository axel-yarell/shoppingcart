<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session; 
use Srmklive\PayPal\Services\ExpressCheckout;
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
            try {
                $oldCart = session::has('cart') ? session::get('cart') : null;
                $cart = new Cart($oldCart);

                $order = new Order();
                $order->first_name = $request->input('firstName');
                $order->last_name = $request->input('lastName');
                $order->address = $request->input('address');
                $order->cart = serialize($cart->items);

                Session::put('order', $order);

                $checkoutData = $this->checkoutData();

                $provider = new ExpressCheckout();
                $response = $provider->setExpressCheckout($checkoutData);

                if (isset($response['paypal_link'])) {
                    return redirect($response['paypal_link']);
                } else {
                    return redirect('/cart')->with('status', 'Erreur lors de la création du paiement PayPal.');
                }

            } catch(\Exception $e){
                return redirect('/cart')->with('status', $e->getMessage());
            }
        }

    
    private function checkoutData(){

        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);

        $data['items'] = [];

        foreach($cart->items as $item ){
                $itemDetails=[
                'name' => $item['product_name'],
                'price' => $item['product_price'],
                'qty' => $item['qty']
                ];

            $data['items'][] = $itemDetails;            
        }

        $checkoutData = [
            'items' => $data['items'],
            'return_url' => url('/payment/success'),
            'cancel_url' => url('/cart'),
            'invoice_id' => uniqid(),
            'invoice_description' => "order description",
            'total' => Session::get('cart')->totalPrice
        ];

        return $checkoutData;
    }

      public function paymentSuccess(Request $request){

        try{

		    $token = $request->get('token');
        	$payerId = $request->get('PayerID');
        	$checkoutData = $this->checkoutData();

        	$provider = new ExpressCheckout();
        	$response = $provider->getExpressCheckoutDetails($token);
        	$response = $provider->doExpressCheckoutPayment($checkoutData, $token, $payerId);

            Session::get('order')->save();

            Session::forget('cart');
            Session::forget('topcart');

            return redirect('/cart')->with('status', 'Votre commande a été effectuée avec succès !! ');
        }
        catch(\Exception $e){
            return redirect('/cart')->with('status', $e->getMessage());
        }
    }
}
