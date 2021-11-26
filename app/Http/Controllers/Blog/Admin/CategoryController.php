<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    //права на доступ к действиям контроллера
    public function __construct() {
        $this->middleware('perm:manage-categories')->only('index');
        $this->middleware('perm:create-category')->only(['create', 'store']);
        $this->middleware('perm:edit-category')->only(['edit', 'update']);
        $this->middleware('perm:delete-category')->only('destroy');
    }

    public function index()
    {
        $categories = Category::all();

        return view('admin.category.index', compact('categories'));
    }

    // Показывает форму для создания категории
    public function create()
    {
        return view('admin.category.create');
    }

    // сохранение новой категории в БД
    public function store(CategoryRequest $request)
    {
        Category::create($request->all());

        return redirect()->route('admin.category.index')->with(['success' => 'Новая категория успешно создана']);
    }

    
    public function show(Category $category)
    {
        //
    }

    //Показывает форму для редактирования категории
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        return redirect()->route('admin.category.index')->with(['success' => 'Категория была успешно исправлена']);
    }

    // удаление категории
    public function destroy(Category $category)
    {
        if($category->children()->count()){
            $errors[] = 'Нельзя удалить категорию с дочерними категориями';
        }

        if($category->posts()->count()){
            $errors[] = 'Нельзя удалить категорию, которая содержит посты';
        }

        if(! empty($errors)){
            return back()->withErrors($errors);
        }

        $category->delete();
        return redirect()->route('admin.category.index')->with(['success' => 'Категория успешно удалена']);
    }
}
