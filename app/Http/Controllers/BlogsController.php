<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogsController extends Controller
{
    /** =======================
     *  INDEX
     *  ======================= */
    public function index()
    {
        $blogs = DB::table('posts as p')
            ->leftJoin('kategori as k', 'k.id', '=', 'p.kategori_id')
            ->where('p.post_type', 'blog')
            ->select('p.*', 'k.name as kategori_name')
            ->orderByDesc('p.id')
            ->get();

        return view('admin.blogs.index', compact('blogs'));
    }

    /** =======================
     *  CREATE
     *  ======================= */
    public function create()
    {
        $cats = DB::table('kategori')
            ->where('cat_type', 'blog')
            ->pluck('name', 'id');

        $tags = DB::table('tags')->pluck('name', 'id');

        return view('admin.blogs.create', compact('cats', 'tags'));
    }

    /** =======================
     *  STORE
     *  ======================= */
    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required',
            'body'   => 'required',
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $blogId = DB::table('posts')->insertGetId([
                'user_id'     => Auth::id(),
                'title'       => $request->title,
                'kategori_id' => $request->kategori_id,
                'slug'        => Str::slug($request->title, '-'),
                'body'        => $request->body,
                'status'      => $request->status,
                'post_type'   => 'blog',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            /** === Upload Gambar === */
            if ($request->hasFile('fotoblog')) {
                $dir = public_path('uploads/blogs');
                if (!file_exists($dir)) mkdir($dir, 0777, true);

                foreach ($request->file('fotoblog') as $i => $file) {
                    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($dir, $fileName);

                    DB::table('blog_images')->insert([
                        'post_id'    => $blogId,
                        'filename'   => $fileName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($i === 0) {
                        DB::table('posts')->where('id', $blogId)->update(['image' => $fileName]);
                    }
                }
            }

            /** === Simpan Tags === */
            if (!empty($request->tagger)) {
                foreach ($request->tagger as $tag) {
                    DB::table('tags')->updateOrInsert(
                        ['slug' => Str::slug($tag, '-')],
                        ['name' => $tag, 'slug' => Str::slug($tag, '-')]
                    );
                }
            }

            DB::commit();
            return redirect()->route('admin.blogs.index')->with('message', 'Blog berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Blog Store Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan blog.');
        }
    }

    /** =======================
     *  EDIT
     *  ======================= */
    public function edit($id)
    {
        $blog = DB::table('posts')->find($id);
        $cats = DB::table('kategori')
            ->where('cat_type', 'blog')
            ->pluck('name', 'id');
        $tags = DB::table('tags')->pluck('name', 'id');
        $images = DB::table('blog_images')->where('post_id', $id)->get();

        return view('admin.blogs.edit', compact('blog', 'cats', 'tags', 'images'));
    }

    /** =======================
     *  UPDATE
     *  ======================= */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'  => 'required',
            'body'   => 'required',
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            DB::table('posts')->where('id', $id)->update([
                'user_id'     => Auth::id(),
                'title'       => $request->title,
                'kategori_id' => $request->kategori_id,
                'slug'        => Str::slug($request->title, '-'),
                'body'        => $request->body,
                'status'      => $request->status,
                'updated_at'  => now(),
            ]);

            /** === Upload gambar baru === */
            if ($request->hasFile('fotoblog')) {
                $dir = public_path('uploads/blogs');
                if (!file_exists($dir)) mkdir($dir, 0777, true);

                foreach ($request->file('fotoblog') as $file) {
                    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($dir, $fileName);

                    DB::table('blog_images')->insert([
                        'post_id'    => $id,
                        'filename'   => $fileName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.blogs.index')->with('message', 'Blog berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Blog Update Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui blog.');
        }
    }

    /** =======================
     *  DELETE
     *  ======================= */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $images = DB::table('blog_images')->where('post_id', $id)->get();
            foreach ($images as $img) {
                $path = public_path('uploads/blogs/' . $img->filename);
                if (file_exists($path)) unlink($path);
            }

            DB::table('blog_images')->where('post_id', $id)->delete();
            DB::table('posts')->where('id', $id)->delete();

            DB::commit();
            return back()->with('message', 'Blog berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Blog Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus blog.');
        }
    }

    /** =======================
     *  TAG AJAX (untuk select2)
     *  ======================= */
    public function getTag(Request $request)
    {
        $search = $request->search;
        $tags = DB::table('tags')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->select('name as id', 'name as text')
            ->orderBy('name')
            ->get();

        return response()->json($tags);
    }

    /** =======================
     *  DELETE GAMBAR
     *  ======================= */
    public function deleteImage($id)
    {
        $image = DB::table('blog_images')->find($id);
        if (!$image) return response()->json(['success' => false]);

        $path = public_path('uploads/blogs/' . $image->filename);
        if (file_exists($path)) unlink($path);
        DB::table('blog_images')->where('id', $id)->delete();

        return response()->json(['success' => true]);
    }

    /** =======================
     *  GANTI GAMBAR
     *  ======================= */
    public function replaceImage(Request $request, $id)
    {
        $image = DB::table('blog_images')->find($id);
        if (!$image || !$request->hasFile('new_image')) {
            return response()->json(['success' => false]);
        }

        $file = $request->file('new_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/blogs'), $filename);

        $oldPath = public_path('uploads/blogs/' . $image->filename);
        if (file_exists($oldPath)) unlink($oldPath);

        DB::table('blog_images')->where('id', $id)->update(['filename' => $filename]);

        return response()->json([
            'success' => true,
            'new_url' => asset('uploads/blogs/' . $filename)
        ]);
    }

    /** =======================
     *  SET COVER IMAGE
     *  ======================= */
    public function setCover($id, $blogId)
    {
        $img = DB::table('blog_images')->where('id', $id)->first();
        if (!$img) return response()->json(['success' => false]);

        DB::table('posts')->where('id', $blogId)->update(['image' => $img->filename]);
        return response()->json(['success' => true]);
    }
}
