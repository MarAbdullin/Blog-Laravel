<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\http\Requests\TagRequest;

class TagController extends Controller
{

    //Показывает список всех тегов блога
    public function index()
    {
        $items = Tag::paginate(8);

        return view('admin.tag.index', compact('items'));
    }

    //Показывает форму для создания тега
    public function create()
    {
        return view('admin.tag.create');
    }

    //Сохраняет новый тег в базу данных
    public function store(TagRequest $request)
    {
        Tag::create($request->all());

        return redirect()->route('admin.tag.index')->with(['success' => 'Тег успешно добавлен']);
    }

    
    public function show(Tag $tag)
    {
        
    }

    //Показывает форму для редактирования тега
    public function edit(Tag $tag)
    {
        return view('admin.tag.edit', compact('tag'));
    }

    //Обновляет тег блога в базе данных
    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update($request->all());

        return redirect()->route('admin.tag.index')->with(['success' => 'Тег успешно обнавлен']);
    }

    //Удаляет тег блога из базы данных
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tag.index')->with(['success' => 'Тег успешно удалён']);
    }
}
