<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Products;
use App\ProductImage;
use App\Bid;
use App\user;
use App\Kategori;
use App\Posts;
use App\Karya;
use App\Events\MessageSent;
use Validator;
use Illuminate\Support\Facades\Log;
use Hash;
use App\Kelengkapan;
use App\Sliders;
use Carbon\Carbon;
use App\Event; // <-- 1. TAMBAHKAN INI

class HomeController extends Controller
{
    public function index()
    {

        $products = Products::active()->orderBy('id','desc')->take(8)->get();
        $sliders = Sliders::active()->get();
        $blogs = Posts::Blog()->orderBy('id','desc')->where('status','PUBLISHED')->take(2)->get();

        // 2. TAMBAHKAN INI (Mengambil 1 event aktif terbaru)
        $featuredEvent = Event::whereIn('status', ['active', 'coming_soon'])
                        ->latest()
                        ->first();

        // 3. MODIFIKASI INI (Tambahkan 'featuredEvent' ke compact)
        return view('web.home',compact('products','sliders','blogs', 'featuredEvent'));
    }

    public function lelang()
    {
        $products = Products::active()->orderBy('id','desc')->paginate(16);
        return view('web.lelang',compact('products'));
    }

    public function detail($slug)
    {
        // ... (sisa kode Anda tetap sama) ...
        //cek data is exist
        $validator = Validator::make(['slug'=>$slug], [
            'slug'=>['required','exists:products,slug']
        ]);

        //jika tidak ada redirect ke halaman 404
        if ($validator->fails()) {
            abort('404');
        }

        try {

            $product = Products::where('slug',$slug)->first();
            //jika data kosong
            if (empty($product)) {
                abort('404');
            }

            if(Auth::check() === FALSE){
                $bid = Bid::with('user')->where('product_id',$product->id)->get();
                $bids = $bid->map(function($data){
                    return [
                        'user'=>$data->user,
                        'message'=>$data->price,
                        'produk'=>$data->product_id
                    ];
                });
                return view('web.detail_new',compact('product','bids'));
            }
            return view('web.detail_new',compact('product'));

        } catch (Exception $e) {
             Log::error('Detail Product :'. $e->getMessage());
        }
    }

    public function category($slug)
    {
        // ... (sisa kode Anda tetap sama) ...
        //cek data is exist
        $validator = Validator::make(['slug'=>$slug], [
            'slug'=>['required','exists:kategori,slug']
        ]);

        //jika tidak ada redirect ke halaman 404
        if ($validator->fails()) {
            abort('404');
        }

        try {

            $products = Products::whereHas('kategori',function($query) use ($slug){
                return $query->where('slug',$slug);
            })->active()->orderBy('id','desc')->paginate(16);

            if ($products) {
                return view('web.category',compact('products'));
            }

            abort(404);

        } catch (Exception $e) {
            Log::error('By Kategori :'. $e->getMessage());
        }
    }

    public function page($slug)
    {
        // ... (sisa kode Anda tetap sama) ...
        $validator = Validator::make(['slug'=>$slug], [
            'slug'=>['required','exists:posts,slug']
        ]);

        //jika tidak ada redirect ke halaman 404
        if ($validator->fails()) {
            abort('404');
        }

        try {

            $page = Posts::where('slug',$slug)->first();

            if ($page) {
                return view('web.page',compact('page'));
            }

            abort(404);

        } catch (Exception $e) {
            Log::error('Page :'. $e->getMessage());
        }
    }

    public function search(Request $request)
    {
      // ... (sisa kode Anda tetap sama) ...
      try {

      $q = $request->input('q');

      $validator = Validator::make(['q'=>$q], ['q'=>['required','string','min:1','max:90']
      ]);

      if ($validator->fails()) {

      return redirect()->route('home');
      }


      $products = Products::active()->where('title', 'LIKE', "%$q%")->paginate(16);
      $products->appends(['q' => $q]);

      return view('web.search', compact('q', 'products'));

      } catch (Exception $e) {
      Log::error('Search :'. $e->getMessage());
      }
    }

    public function galeriKami()
    {
        return view('web.galeri-kami');
    }

    public function seniman($slug)
    {
        // ... (sisa kode Anda tetap sama) ...
        //cek data is exist
        $validator = Validator::make(['slug'=>$slug], [
            'slug'=>['required','exists:karya,slug']
        ]);
        //jika tidak ada redirect ke halaman 404
        if ($validator->fails()) {
            abort('404');
        }

        try {

            $products = Products::whereHas('karya',function($query) use ($slug){
                return $query->where('slug',$slug);
            })->active()->orderBy('id','desc')->paginate(16);

            if ($products) {
                return view('web.seniman',compact('products'));
            }

            abort(404);

        } catch (Exception $e) {
            Log::error('By Seniman :'. $e->getMessage());
        }
    }
}
