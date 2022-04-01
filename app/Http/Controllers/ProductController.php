<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\SingleProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use PhpParser\JsonDecoder;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductResource::collection(
            Product::where('archived', 0)->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation
        $validateOn = [];
        $rules = 'mimes:jpg,jpeg,png,webp,svg|max:4000';
        $validateOn['main'] = 'required|' . $rules;
        for ($i = 0; $i < 3; $i++) {
            $validateOn["other$i"] = $rules;
        }
        $request->validate($validateOn);

        // saving images names in DB and images data in file system
        $path = $request->file('main')->store('public/images');
        $name = explode('/', $path)[2];
        $images = [
            'main' => $name,
            'others' => []
        ];
        for ($i = 0; $i < 3; $i++) {
            if (!$request->file("other$i"))
                break;
            $path = $request->file("other$i")->store('public/images');
            $name = explode('/', $path)[2];
            array_push($images['others'], $name);
        }
        $fields = $request->session()->get('product', null);
        if (!$fields) {
            return response([
                'message' => 'unexpected error: session data not found',
            ], 500);
        }
        $fields['images'] = json_encode($images);

        Product::create($fields);

        $request->session()->forget('product');

        return response([
            'message' => 'product created'
        ], 201);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new SingleProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validateOn = [];
        $rules = 'mimes:jpg,jpeg,png,webp,svg|max:4000';
        if ($request->file('main'))
            $validateOn['main'] = $rules;
        if ($request->file('other0'))
            for ($i = 0; $i < 3; $i++) {
                $validateOn["other$i"] = $rules;
            }
        $request->validate($validateOn);

        // saving images names in DB and images data in file system
        $productImages = json_decode($product->images, true);
        // check if main images is set
        if ($request->file('main')) {
            // delete old image
            $name = $productImages['main'];
            Storage::delete("public/images/$name");
            // store new image
            $path = $request->file('main')->store('public/images');
            $name = explode('/', $path)[2];
            // update images array
            $productImages['main'] = $name;
        }
        // check if at least 1 image is set
        if ($request->file('other0')) {
            // delete all others
            $names = $productImages['others'];
            foreach ($names as $name) {
                Storage::delete("public/images/$name");
            }
            // store the new images
            $productImages['others'] = [];
            for ($i = 0; $i < 3; $i++) {
                if (!$request->file("other$i"))
                    break;
                $path = $request->file("other$i")->store('public/images');
                $name = explode('/', $path)[2];
                array_push($productImages['others'], $name);
            }
        }
        // update product info
        $fields = $request->session()->get('product', null);
        if (!$fields) {
            return response([
                'message' => 'unexpected error: session data not found',
            ], 500);
        }
        $fields['images'] = json_encode($productImages);

        $product->update($fields);

        $request->session()->forget('product');

        return response([
            'message' => 'product updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
    public function archive(Request $request, Product $product)
    {
        $state = $request->all()['state'];
        if ($state === 'archived') {
            $product->archived = 0;
        } else {
            $product->archived = 1;
        }
        $product->save();
        return response([
            'message' => 'action perfomed',
        ]);
    }
    public function featuredProducts()
    {
        return ProductResource::collection(
            Product::where('featured', 1)->latest()->get()
        );
    }
    public function allProducts()
    {
        return ProductResource::collection(
            Product::all()
        );
    }
}