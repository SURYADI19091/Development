<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\News;
use Carbon\Carbon;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display a listing of news
     */
    public function index(Request $request)
    {
        if (!Gate::allows('manage-content')) {
            abort(403, 'Unauthorized to manage content');
        }

        $query = News::query()->with(['author']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get stats for the page
        $stats = [
            'total' => News::count(),
            'published' => News::where('status', 'published')->count(),
            'draft' => News::where('status', 'draft')->count(),
            'total_views' => News::sum('views_count') ?? 0,
        ];

        if ($request->ajax()) {
            return view('backend.pages.news.partials.table', compact('news'))->render();
        }

        return view('backend.pages.news.index', compact('news', 'stats'));
    }

    /**
     * Show the form for creating new news
     */
    public function create()
    {
        if (!Gate::allows('create-content')) {
            abort(403, 'Unauthorized to create content');
        }

        return view('backend.pages.news.create');
    }

    /**
     * Store a newly created news
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create-content')) {
            abort(403, 'Unauthorized to create content');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|string|in:pengumuman,berita,artikel,informasi',
            'status' => 'required|string|in:draft,published,archived',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        try {
            // Handle featured image upload
            $featuredImagePath = null;
            if ($request->hasFile('featured_image')) {
                $featuredImagePath = $request->file('featured_image')->store('news', 'public');
            }

            // Create news
            $news = News::create([
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'] ?? \Str::limit(strip_tags($validated['content']), 200),
                'category' => $validated['category'],
                'status' => $validated['status'],
                'featured_image' => $featuredImagePath,
                'is_featured' => $validated['is_featured'] ?? false,
                'published_at' => $validated['status'] === 'published' ? ($validated['published_at'] ?? now()) : null,
                'author_id' => Auth::id(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'News created successfully',
                    'redirect' => route('admin.news.show', $news)
                ]);
            }

            return redirect()->route('admin.news.index')
                ->with('success', 'News created successfully');

        } catch (\Exception $e) {
            \Log::error('News creation error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create news'
                ]);
            }

            return back()->withInput()
                ->with('error', 'Failed to create news');
        }
    }

    /**
     * Display the specified news
     */
    public function show(News $news)
    {
        if (!Gate::allows('view-content')) {
            abort(403, 'Unauthorized to view content');
        }

        return view('backend.pages.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified news
     */
    public function edit(News $news)
    {
        if (!Gate::allows('edit-content')) {
            abort(403, 'Unauthorized to edit content');
        }

        return view('backend.pages.news.edit', compact('news'));
    }

    /**
     * Update the specified news
     */
    public function update(Request $request, News $news)
    {
        if (!Gate::allows('edit-content')) {
            abort(403, 'Unauthorized to edit content');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news,slug,' . $news->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|string|in:pengumuman,berita,artikel,informasi',
            'status' => 'required|string|in:draft,published,archived',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        try {
            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($news->featured_image) {
                    Storage::disk('public')->delete($news->featured_image);
                }
                $validated['featured_image'] = $request->file('featured_image')->store('news', 'public');
            }

            // Update published_at based on status
            if ($validated['status'] === 'published' && !$news->published_at) {
                $validated['published_at'] = $validated['published_at'] ?? now();
            } elseif ($validated['status'] !== 'published') {
                $validated['published_at'] = null;
            }

            $news->update($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'News updated successfully'
                ]);
            }

            return redirect()->route('admin.news.show', $news)
                ->with('success', 'News updated successfully');

        } catch (\Exception $e) {
            \Log::error('News update error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update news'
                ]);
            }

            return back()->withInput()
                ->with('error', 'Failed to update news');
        }
    }

    /**
     * Remove the specified news
     */
    public function destroy(News $news)
    {
        if (!Gate::allows('delete-content')) {
            abort(403, 'Unauthorized to delete content');
        }

        try {
            // Delete featured image if exists
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }

            $newsTitle = $news->title;
            $news->delete();

            return response()->json([
                'success' => true,
                'message' => 'News deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('News deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete news'
            ]);
        }
    }

    /**
     * Update news status
     */
    public function updateStatus(Request $request, News $news)
    {
        if (!Gate::allows('manage-content')) {
            abort(403, 'Unauthorized to manage content status');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:draft,published,archived'
        ]);

        try {
            $oldStatus = $news->status;
            
            // Set published_at when publishing
            if ($validated['status'] === 'published' && !$news->published_at) {
                $news->published_at = now();
            }
            
            $news->update([
                'status' => $validated['status']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'News status updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('News status update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update news status'
            ]);
        }
    }

    /**
     * Bulk actions for news
     */
    public function bulkAction(Request $request)
    {
        if (!Gate::allows('manage-content')) {
            abort(403, 'Unauthorized to perform bulk actions');
        }

        $validated = $request->validate([
            'action' => 'required|string|in:publish,unpublish,delete',
            'news_ids' => 'required|array|min:1',
            'news_ids.*' => 'exists:news,id'
        ]);

        try {
            $newsItems = News::whereIn('id', $validated['news_ids'])->get();
            $count = 0;

            foreach ($newsItems as $news) {
                switch ($validated['action']) {
                    case 'publish':
                        if (Gate::allows('manage-content')) {
                            $news->update([
                                'status' => 'published',
                                'published_at' => $news->published_at ?? now()
                            ]);
                            $count++;
                        }
                        break;
                    
                    case 'unpublish':
                        if (Gate::allows('manage-content')) {
                            $news->update(['status' => 'draft']);
                            $count++;
                        }
                        break;
                    
                    case 'delete':
                        if (Gate::allows('delete-content')) {
                            if ($news->featured_image) {
                                Storage::disk('public')->delete($news->featured_image);
                            }
                            $news->delete();
                            $count++;
                        }
                        break;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Bulk action completed successfully on {$count} news items"
            ]);

        } catch (\Exception $e) {
            \Log::error('Bulk action error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action'
            ]);
        }
    }

    /**
     * Export news data
     */
    public function export(Request $request)
    {
        if (!Gate::allows('export-content')) {
            abort(403, 'Unauthorized to export news');
        }

        try {
            $query = News::with(['author']);

            // Apply filters from request
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }

            if ($request->filled('category')) {
                $query->where('category', $request->get('category'));
            }

            $news = $query->orderBy('created_at', 'desc')->get();

            // Create CSV content
            $csvContent = "Title,Category,Status,Author,Views,Created At,Published At\n";
            foreach ($news as $article) {
                $csvContent .= implode(',', [
                    '"' . str_replace('"', '""', $article->title) . '"',
                    '"' . str_replace('"', '""', $article->category) . '"',
                    '"' . str_replace('"', '""', $article->status) . '"',
                    '"' . str_replace('"', '""', $article->author->name ?? 'Unknown') . '"',
                    '"' . ($article->views_count ?? 0) . '"',
                    '"' . $article->created_at->format('Y-m-d H:i:s') . '"',
                    '"' . ($article->published_at ? $article->published_at->format('Y-m-d H:i:s') : '') . '"',
                ]) . "\n";
            }

            $filename = 'news_export_' . date('Y-m-d_H-i-s') . '.csv';

            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export news data');
        }
    }
}