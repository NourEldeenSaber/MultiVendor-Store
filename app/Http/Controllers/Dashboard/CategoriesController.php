<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{

    public function index()
    {
        $request = request();


        $categories = Category::with('parent')
            // leftJoin('categories as parents' , 'parents.id', '=', 'categories.parent_id')
            // ->select([
            //     'categories.*',
            //     'parents.name as parent_name'
            // ])
            // ->select('categories.*')
            // ->selectRaw('(SELECT COUNT(*) FROM products WHERE category_id = categories.id) as products_count')
            ->withCount([
                'products' => function ($query) {
                    $query->where('status', '=', 'active');
                }
            ])
            ->filter($request->query())
            ->orderBy('categories.name')
            ->paginate();
        // $categories = Category::status('active')->paginate(); سهل اكتر فى التعامل مع الموديل عن الكويرى الخاص بالداتا بيز
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::all();
        $category = new Category();
        return view('dashboard.categories.create', compact('category', 'parents'));
    }


    public function store(Request $request)
    {
        $request->validate(Category::rules());
        // Request Merge
        $request->merge([
            'slug' => Str::slug($request->post('name'))
        ]);
        $data = $request->except('image');
        $data['image'] = $this->uploadImage($request);

        $category = Category::create($data);

        // PRG  Post Redirect Get
        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category Created Successfully!');
    }

    public function show(Category $category)
    {
        return view('dashboard.categories.show', [
            'category' => $category
        ]);
    }


    public function edit(string $id)
    {
        try {
            $category = Category::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('dashboard.categories.index')
                ->with('info', 'Record not Found!');
        }



        $parents = Category::where('id', '<>', $id) //عشان ميرجعش بنفس الكاتيجورى
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id') //يرجع القيم بتاع primary category
                    ->orWhere('parent_id', '<>', $id); //عشان ميرجعش الابناء
            })
            ->get();
        return view('dashboard.categories.edit', compact('category', 'parents'));
    }


    public function update(CategoryRequest $request, string $id)
    {
        // $request->validate(Category::rules($id));

        $category = Category::findOrFail($id);

        $old_image = $category->image;
        $data = $request->except('image');


        $new_image = $this->uploadImage($request);
        if ($new_image) {
            $data['image'] = $new_image;
        }

        $category->update($data);

        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category Updated Successfully!');
    }

    public function destroy(string $id)
    {
        $category = Category::find($id);
        $category->delete();


        // Category::destroy($id);

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category Deleted Successfully!');
    }


    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return;
        }
        $file = $request->file('image');
        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate();
        return view('dashboard.categories.trash', compact('categories'));
    }

    public function restore(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.categories.trash')
            ->with('success', 'Category Restored Successfully!');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        return redirect()->route('dashboard.categories.trash')
            ->with('success', 'Category Deleted Successfully!');
    }
}
