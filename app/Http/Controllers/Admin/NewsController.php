<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('author');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'published') {
                $query->where('is_published', true);
            } else if ($request->status == 'draft') {
                $query->where('is_published', false);
            }
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('backend.pages.news.index', compact('news'));
    }

    public function create()
    {
        $categories = [
            'kegiatan' => 'Kegiatan',
            'kesehatan' => 'Kesehatan',
            'ekonomi' => 'Ekonomi',
            'infrastruktur' => 'Infrastruktur',
            'pendidikan' => 'Pendidikan',
            'olahraga' => 'Olahraga',
            'lainnya' => 'Lainnya'
        ];

        return view('backend.pages.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:news,slug',
            'content' => 'required|string',
            'category' => 'required|in:kegiatan,kesehatan,ekonomi,infrastruktur,pendidikan,olahraga,lainnya',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published'
        ]);

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Check for duplicate slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (News::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('news', $imageName, 'public');
            $data['featured_image'] = $imagePath;
        }

        // Set publish status
        $data['is_published'] = ($request->status === 'published');
        if ($data['is_published']) {
            $data['published_at'] = now();
        }

        // Set author
        $data['author_id'] = Auth::id();

        $news = News::create($data);

        $message = $request->status === 'published' ? 'Berita berhasil dipublikasikan!' : 'Berita berhasil disimpan sebagai draft!';
        
        return redirect()->route('backend.news.index')->with('success', $message);
    }

    public function show(News $news)
    {
        return view('backend.pages.news.show', compact('news'));
    }

    public function edit(News $news)
    {
        $categories = [
            'kegiatan' => 'Kegiatan',
            'kesehatan' => 'Kesehatan',
            'ekonomi' => 'Ekonomi',
            'infrastruktur' => 'Infrastruktur',
            'pendidikan' => 'Pendidikan',
            'olahraga' => 'Olahraga',
            'lainnya' => 'Lainnya'
        ];

        return view('backend.pages.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:news,slug,' . $news->id,
            'content' => 'required|string',
            'category' => 'required|in:kegiatan,kesehatan,ekonomi,infrastruktur,pendidikan,olahraga,lainnya',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published'
        ]);

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }

            $image = $request->file('featured_image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('news', $imageName, 'public');
            $data['featured_image'] = $imagePath;
        }

        // Set publish status
        $data['is_published'] = ($request->status === 'published');
        if ($data['is_published'] && !$news->published_at) {
            $data['published_at'] = now();
        } elseif (!$data['is_published']) {
            $data['published_at'] = null;
        }

        $news->update($data);

        $message = $request->status === 'published' ? 'Berita berhasil dipublikasikan!' : 'Berita berhasil disimpan sebagai draft!';
        
        return redirect()->route('backend.news.index')->with('success', $message);
    }

    public function destroy(News $news)
    {
        // Delete image if exists
        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }

        $news->delete();

        return redirect()->route('backend.news.index')->with('success', 'Berita berhasil dihapus!');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->get('action');
        $ids = $request->get('ids', []);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada item yang dipilih']);
        }

        switch ($action) {
            case 'delete':
                $news = News::whereIn('id', $ids)->get();
                foreach ($news as $item) {
                    if ($item->featured_image) {
                        Storage::disk('public')->delete($item->featured_image);
                    }
                    $item->delete();
                }
                return response()->json(['success' => true, 'message' => 'Berita berhasil dihapus']);

            case 'publish':
                News::whereIn('id', $ids)->update([
                    'is_published' => true,
                    'published_at' => now()
                ]);
                return response()->json(['success' => true, 'message' => 'Berita berhasil dipublikasikan']);

            case 'unpublish':
                News::whereIn('id', $ids)->update([
                    'is_published' => false,
                    'published_at' => null
                ]);
                return response()->json(['success' => true, 'message' => 'Berita berhasil dijadikan draft']);

            default:
                return response()->json(['success' => false, 'message' => 'Aksi tidak dikenali']);
        }
    }

    public function toggleFeatured(News $news)
    {
        $news->update(['is_featured' => !$news->is_featured]);
        
        $message = $news->is_featured ? 'Berita berhasil ditandai sebagai unggulan' : 'Berita berhasil dihapus dari unggulan';
        
        return response()->json(['success' => true, 'message' => $message]);
    }

    public function updateStatus(Request $request, News $news)
    {
        $request->validate([
            'status' => 'required|in:draft,published'
        ]);

        $data = ['is_published' => ($request->status === 'published')];
        
        if ($data['is_published'] && !$news->published_at) {
            $data['published_at'] = now();
        } elseif (!$data['is_published']) {
            $data['published_at'] = null;
        }

        $news->update($data);

        $message = $request->status === 'published' ? 'Berita berhasil dipublikasikan!' : 'Berita berhasil dijadikan draft!';
        
        return response()->json(['success' => true, 'message' => $message]);
    }

    public function export(Request $request)
    {
        // Implementation for export functionality
        return response()->json(['message' => 'Export feature coming soon']);
    }
}
