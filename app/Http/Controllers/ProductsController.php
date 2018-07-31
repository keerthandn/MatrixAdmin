<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Product;
use App\Category;
use App\ProductsAttribute;
use Auth;
use Session;
use Image;
use Vinkla\Hashids\Facades\Hashids;

class ProductsController extends Controller
{
    public function addProduct(Request $request)
    {
    	if($request->isMethod('post'))
    	{
    		$data=$request->all();
    		// echo "<pre>"; print_r($data); die;
    		if(empty($data['category_id']))
    		{
    			return redirect()->back()->with('flash_message_error','Under Category is missing!');
    		}
    		$product=new Product;
    		$product->category_id=$data['category_id'];
    		$product->product_name=$data['product_name'];
    		$product->product_code=$data['product_code'];
    		$product->product_color=$data['product_color'];
    	
    		if(!empty($data['description'])){
    			$product->description = $data['description'];
    		}else{
				$product->description = '';    			
    		}
    		$product->price=$data['price'];
    		
    		if($request->hasFile('image')){
    			$image_tmp = Input::file('image');
    			if($image_tmp->isValid()){
    				$extension = $image_tmp->getClientOriginalExtension();
    				$filename = rand(111,99999).'.'.$extension;
    				$large_image_path = 'images/backend_images/products/large/'.$filename;
    				$medium_image_path = 'images/backend_images/products/medium/'.$filename;
    				$small_image_path = 'images/backend_images/products/small/'.$filename;
    				// Resize Images
    				Image::make($image_tmp)->save($large_image_path);
    				Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
    				Image::make($image_tmp)->resize(300,300)->save($small_image_path);
    				// Store image name in products table
    				$product->image = $filename;
    			}
    		}
    		$product->save();
    		return redirect('/admin/view-products')->with('flash_message_success','Product has been added Successfully!');
    	}
    	$categories=Category::where(['parent_id'=>0])->get();
    	$categories_dropdown="<option selected disabled>Select</option>";
    	foreach($categories as $cat)
    	{
    		$categories_dropdown.="<option value='".$cat->id."'>".$cat->name."</option>";
    		$sub_categories=Category::where(['parent_id'=>$cat->id])->get();
    		foreach($sub_categories as $sub_cat)
    		{
    			$categories_dropdown.="<option value= '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
    		}
    	}
    	return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }

    public function viewProducts(request $request,$id=null)
    {
        $products=Product::get();
        // $products=json_decode(json_encode($products));
        foreach($products as $key=>$val)
        {
            $category_name=Category::where(['id'=>$val->category_id])->first();
            $products[$key]->category_name=$category_name->name;
            $products[$key]->id =$val->id;
        }
        // echo "<pre>"; print_r($products); die;
        return view('admin.products.view_products')->with(compact('products'));

    }

    public function editProduct(Request $request,$id=null)
    {

        if($request->isMethod('post'))
        {
            $data=$request->all();
            // echo "<pre>"; print_r($data); die;
            if($request->hasFile('image')){
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    // Resize Images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);

                }
            }
            else
            {
                $filename=$data['current_image'];
            }
            if(empty($data['description']))
            {
                $data['description']='';
            }
// dd($data);
            Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'],
                'product_name'=>$data['product_name'],'product_code'=>$data['product_code'],'product_color'=>$data['product_color'],'description'=>$data['description'],'price'=>$data['price'],'image'=>$filename]);

            return redirect()->back()->with('flash_message_success','Product has been updated Successfully!');
        }
        $productDetails=Product::where(['id'=>Hashids::decode($id)])->first();
        // echo "<pre>"; print_r($productDetails); die;
        $categories=Category::where(['parent_id'=>0])->get();
        $categories_dropdown="<option selected disabled>Select</option>";
        foreach($categories as $cat)
        {
            if($cat->id==$productDetails->category_id)
            {
                $selected="selecetd";

            }
            else
            {
                $selected="";
            }
            $categories_dropdown.="<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
            $sub_categories=Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat)
            {
                if($sub_cat->id=$productDetails->category_id)
                {
                    $selected="selected";
                }
                else
                {
                    $selected="";
                }
                $categories_dropdown.="<option value= '".$sub_cat->id."' ".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
        return view('admin.products.edit_product')->with(compact('productDetails','categories_dropdown'));
    }

    public function deleteProductImage($id=null)
    {
        // Get product image name
        $productImage=Product::where(['id'=>$id])->first();
        // echo $productImage->image; die;

        // Get product image path
        $large_image_path='images/backend_images/products/large/';
        $medium_image_path='images/backend_images/products/medium/';
        $small_image_path='images/backend_images/products/small/';

        // Delete Large image if not exist in folder

        if(file_exists($large_image_path.$productImage->image))
        {
            unlink($large_image_path.$productImage->image);
        }

        // Delete Medium image if not exist in folder

        if(file_exists($medium_image_path.$productImage->image))
        {
            unlink($medium_image_path.$productImage->image);
        }

        // Delete Small image if not exist in folder

        if(file_exists($small_image_path.$productImage->image))
        {
            unlink($small_image_path.$productImage->image);
        }

        // Delete image from product table.
        Product::where(['id'=>$id])->update(['image'=>'']);
        return redirect()->back()->with('flash_message_success','Product Image has been deleted successfully!');
    }

    public function deleteProduct($id=null)
    {
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product has been deleted successfully!');
    }

    public function addAttributes(Request $request,$id=null)
    {
        $productDetails=Product::with('attributes')->where(['id'=>Hashids::decode($id)])->first();
        // $productDetails=json_decode(json_encode($productDetails));
        // echo "<pre>"; print_r($productDetails); die;

        if($request->isMethod('post'))
        {
            $data=$request->all();
            // echo "<pre>"; print_r($data); die;

            foreach($data['sku'] as $key=>$val)
            {
                if(!empty($val))
                {
                    $attributes=new ProductsAttribute;
                    $attributes->product_id=$id;
                    $attributes->sku=$val;
                    $attributes->size=$data['size'][$key];
                    $attributes->price=$data['price'][$key];
                    $attributes->stock=$data['stock'][$key];
                    $attributes->save();
                }
            }
            return redirect('/admin/add-attributes/'.$id)->with('flash_message_success','Product Attributes has been added successfully!');
        }
      
        return view('admin.products.add_attributes')->with(compact('productDetails'));
    }

    public function deleteAttribute($id=null)
    {
        ProductsAttribute::where(['id'=>$id])->delete();
        return redircet()->back()->with('flash_message_success','Attribute has been deleted successfully!');
    }

    public function products($url=null)
    {
        $countCategory=Category::where(['url'=>$url,'status'=>1])->count();
        if($countCategory==0)
        {
            abort(404);
        }
        $categories=Category::with('categories')->where(['parent_id'=>0])->get();
        $categoryDetails=Category::where(['url'=>$url])->first();

        if($categoryDetails->parent_id==0)
        {
            $subcategories=Category::where(['parent_id'=>$categoryDetails->id])->get();
            $cat_ids="";
            foreach($subcategories as $key=>$subcat)
            {
                // if($key==1) $cat_ids.=",";
                $cat_ids.= $subcat->id.",";

            }
            
            $productsAll=Product::whereIn('category_id',array($cat_ids))->get();
            // $productsAll=json_decode(json_encode($productsAll));
            // echo "<pre>"; print_r($productsAll); die;

        }
        else
        {
             $productsAll=Product::where(['category_id'=>$categoryDetails->id])->get();

        }

        // echo $categoryDetails->id; die;
       
        return view('products.listing')->with(compact('categories','categoryDetails','productsAll'));

    }

    public function product($id=null)
    {
        $productDetails=Product::with('attributes')->where('id',$id)->first();
        $productDetails=json_decode(json_encode($productDetails));
        // echo "<pre>"; print_r($productDetails); die;

        // dd($productDetails);
        $categories=Category::with('categories')->where(['parent_id'=>0])->get();

        return view('products.detail')->with(compact('productDetails','categories'));
    }
}
