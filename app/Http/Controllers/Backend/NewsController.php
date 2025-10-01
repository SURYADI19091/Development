<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('author');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status == 'published') {
                $query->where('is_published', true);
            } elseif ($request->status == 'draft') {
                $query->where('is_published', false);
            }
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = News::distinct()->pluck('category');

        return view('backend.news.index', compact('news', 'categories'));
    }

    public function create()
    {
        $categories = ['Berita Umum', 'Pengumuman', 'Kegiatan', 'Pembangunan', 'Kesehatan', 'Pendidikan', 'Ekonomi'];
        return view('backend.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->all();
        $data['author_id'] = Auth::id();
        $data['slug'] = Str::slug($request->title);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('news', 'public');
            $data['featured_image'] = $imagePath;
        }

        // Handle tags
        if ($request->tags) {
            $data['tags'] = explode(',', $request->tags);
        }

        // Set published date
        if ($request->is_published) {
            $data['published_at'] = now();
        }

        News::create($data);

        return redirect()->route('backend.news.index')
                        ->with('success', 'News created successfully!');
    }

    public function show($id)
    {
        $news = News::with('author')->findOrFail($id);
        return view('backend.news.show', compact('news'));
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = ['Berita Umum', 'Pengumuman', 'Kegiatan', 'Pembangunan', 'Kesehatan', 'Pendidikan', 'Ekonomi'];
        return view('backend.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('news', 'public');
            $data['featured_image'] = $imagePath;
        }

        // Handle tags
        if ($request->tags) {
            $data['tags'] = explode(',', $request->tags);
        }

        // Set published date if publishing for first time
        if ($request->is_published && !$news->is_published) {
            $data['published_at'] = now();
        }

        $news->update($data);

        return redirect()->route('backend.news.index')
                        ->with('success', 'News updated successfully!');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        
        // Delete featured image
        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }

        $news->delete();

        return redirect()->route('backend.news.index')
                        ->with('success', 'News deleted successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $news = News::findOrFail($id);
        $news->is_published = !$news->is_published;
        
        if ($news->is_published) {
            $news->published_at = now();
        }
        
        $news->save();

        $status = $news->is_published ? 'published' : 'unpublished';
        return response()->json(['message' => "News {$status} successfully!"]);
    }
}