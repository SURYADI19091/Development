<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use App\Traits\HasPagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    use HasPagination;

    public function index(Request $request)
    {
        $query = News::with('author');

        // Apply search
        $searchableFields = ['title', 'content', 'author.name'];
        $query = $this->applySearch($query, $request, $searchableFields);

        // Apply filters
        $filters = [
            'category' => 'category',
            'status' => [
                'callback' => function ($query, $value) {
                    if ($value === 'published') {
                        return $query->where('is_published', true);
                    } elseif ($value === 'draft') {
                        return $query->where('is_published', false);
                    }
                    return $query;
                }
            ]
        ];
        $query = $this->applyFilters($query, $request, $filters);

        // Apply sorting
        $query = $this->applySorting($query, $request, 'created_at', 'desc');

        // Paginate results
        $news = $this->paginateQuery($query, $request);

        // Get categories for filter
        $categories = News::distinct()->whereNotNull('category')->pluck('category');

        // Get statistics
        $stats = [
            'total' => News::count(),
            'published' => News::where('is_published', true)->count(),
            'draft' => News::where('is_published', false)->count(),
            'this_month' => News::whereMonth('created_at', now()->month)->count()
        ];

        // Prepare pagination info
        $paginationInfo = $this->getPaginationInfo($news);

        return view('backend.pages.news.index', compact('news', 'categories', 'stats', 'paginationInfo'));
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