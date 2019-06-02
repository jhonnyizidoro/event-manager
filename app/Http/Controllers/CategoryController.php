<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\Category\NewCategory as NewCategoryRequest;
use App\Http\Requests\Category\UpdateCategory as UpdateCategoryRequest;
use Illuminate\Http\Request;
use Auth;

class CategoryController extends Controller
{
    /**
     * Retorna um json com todas as categorias cadastradas
     * @return Resource com todas as categorias
     */
    public function index(Request $request)
    {
        if ($request->get('all')) {
            $categories = Category::orderBy('name', 'asc')->get();
        } else {
            $categories = Category::paginate(10);
        }
		return json($categories, 'Categorias buscadas.');
    }

    /**
     * Grava uma categoria
     * @return Category categoria criada
     */
    public function store(NewCategoryRequest $request)
    {
		$category = Category::create($request->all());
		return json($category, 'Categoria criada.');
    }

    /**
     * Busca uma categoria
     * @param int $id da categoria que será buscada
	 * @return Resource categoria buscada
     */
    public function show($id)
    {
		$category = Category::findOrFail($id);
		return json($category, 'Categoria encontrada.');
    }

    /**
     * Atualiza uma categoria
     * @return Category a categoria atualizada
     */
    public function update(UpdateCategoryRequest $request)
    {
		$category = Category::find($request->category_id);
		$category->update($request->all());
		return json($category, 'Categoria atualizada.');
    }

    /**
     * Ativa ou desativa uma categoria
	 * @return Category: categoria ativada/desativada
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
		$category->update([
			'is_active' => !$category->is_active
		]);
        return json($category, 'Categoria ativada/desativada.');
    }

    public function searchInterest()
    {
        $term = request('term');

        $interestsIds = Auth::user()->interests->pluck('id')->toArray();

        $categories = Category::whereNotIn('id', $interestsIds)->where('name', 'like', "%$term%")->where('is_active', true)->get();

        return response()->json($categories, 200);
    }
}
