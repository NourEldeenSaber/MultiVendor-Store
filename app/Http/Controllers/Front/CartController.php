<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositiories\Cart\CartModelRepository;
use App\Repositiories\Cart\CartRepository;
use Illuminate\Http\Request;


class CartController extends Controller
{
    protected $cart;
    public function __construct(CartRepository $cart)
    {
        $this->cart = $cart ;
    }
    public function index()
    {
    
        return view('front.cart'  , [
            'cart' => $this->cart, 
        ]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required' , 'int' , 'exists:products,id'],
            'quantity' => ['nullable' , 'int' , 'min:1'],
        ]);

        $product = Product::findOrFail($request->post('product_id'));
        $this->cart->add($product , $request->post('quantity'));

        return redirect()->route('cart.index')->with('success' , 'Product added to cart!');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => ['required' , 'int' , 'min:1'],
        ]);


        $this->cart->update($id , $request->post('quantity'));
    }


    public function destroy(string $id)
    {
        $this->cart->delete($id);
        return [
            'message' => 'Item deleted!',
        ];
    }
}
