<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\SingleProductResource;
use App\Models\Category;
use App\Models\Product;
use Cloudinary\Cloudinary;
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
        $rules = 'mimes:jpg,jpeg,png,webp|max:4000';
        $validateOn['main'] = 'required|' . $rules;
        for ($i = 0; $i < 3; $i++) {
            $validateOn["other$i"] = $rules;
        }
        $request->validate($validateOn);

        // saving images names in DB and images data in file system
        // $path = $request->file('main')->store('public/images');
        $mainImg = $request->file('main');
        $result = $mainImg->storeOnCloudinary('tech-store');
        $url = $result->getSecurePath();
        $uploadId = $result->getPublicId();

        $name = explode('/', $url);
        $name = $name[count($name) - 1];

        $images = [
            'main' => $name,
            'others' => [],
            'ids' => ['main' => $uploadId, 'others' => []]
        ];

        for ($i = 0; $i < 3; $i++) {
            if (!$request->file("other$i"))
                break;
            $file = $request->file("other$i");
            $result = $file->storeOnCloudinary('tech-store');
            $url = $result->getSecurePath();
            $uploadId = $result->getPublicId();
            $name = explode('/', $url);
            $name = $name[count($name) - 1];
            array_push($images['others'], $name);
            array_push($images['ids']['others'], $uploadId);
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
        $rules = 'mimes:jpg,jpeg,png,webp|max:4000';
        return response(['message' => $request->file('main')]);
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
            $id = isset($productImages['ids']) ? $productImages['ids']['main'] : null;
            // Storage::delete("public/images/$name");
            return response($id);
            if ($id)
                Cloudinary::destroy($id);
            // store new image
            $mainImg = $request->file('main');
            $result = $mainImg->storeOnCloudinary('tech-store');
            $url = $result->getSecurePath();
            $uploadId = $result->getPublicId();

            $name = explode('/', $url);
            $name = $name[count($name) - 1];
            if (!isset($productImages['ids']))
                $productImages['ids'] = [];
            $productImages['main'] = $name;
            $productImages['ids']['main'] = $uploadId;
        }
        // check if at least 1 image is set
        if ($request->file('other0')) {
            // delete all others
            $names = $productImages['others'];
            $i = 0;
            foreach ($names as $name) {
                // if it does not start with image delete it else keep it
                if (strpos($name, 'image') != 0) {
                    // Storage::delete("public/images/$name");
                    $id = $productImages['ids']['others'][$i];
                    Cloudinary::destroy($id);
                    $i++;
                }
            }
            // store the new images
            if (!isset($productImages['ids']))
                $productImages['ids'] = [];
            $productImages['others'] = [];
            $productImages['ids']['others'] = [];
            for ($i = 0; $i < 3; $i++) {
                if (!$request->file("other$i"))
                    break;
                $file = $request->file("other$i");
                $result = $file->storeOnCloudinary('tech-store');
                $url = $result->getSecurePath();
                $uploadId = $result->getPublicId();
                $name = explode('/', $url);
                $name = $name[count($name) - 1];
                array_push($productImages['others'], $name);
                array_push($productImages['ids']['others'], $uploadId);
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