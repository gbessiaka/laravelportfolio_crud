<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(){
        $portfolios = Portfolio::all();
        return view('backoffice.portfolio.indexPortfolio',compact('portfolios'));
    }

    public function create(){
        return view('backoffice.portfolio.createPortfolio');
    }

    public function store(Request $request){
        request()->validate([
            "filter"=>["required"],
            "nom"=>["required"],
            "titre"=>["required"],
        ]);

        $portfolio = new Portfolio();
        //STORAGE
        $request->file('nom')->storePublicly('img/portfolio', 'public');
        $portfolio->nom = $request->file('nom')->hashName();

        //DB
        $portfolio->filter = $request->filter;
        $portfolio->titre = $request->titre;
        $portfolio->save();
        return redirect()->route('portfolio.index')->with('success', 'un projet ajouté');
    }

    //CRUD
    public function destroy(Portfolio $id){
        $id->delete();
        return redirect()->route('portfolio.index')->with('warning', "un élément a été supprimé");
    }

    public function edit(Portfolio $id){
        $portfolio = $id;
        return view ('backoffice.portfolio.editPortfolio', compact('portfolio'));
    }

    public function update(Portfolio $id, Request $request){
        request()->validate([
            "filter"=>["required"],
            "Nom"=>["required"],
            "titre"=>["required"],
        ]);
        $portfolio = $id;
        if ($request->nom != null) {
            $request->file('nom')->storePublicly('img/portfolio', 'public');
            $portfolio->nom = $request->file('nom')->hashName();
            $portfolio->save();
        }

        $portfolio = $id;
        $portfolio->filter = $request->filter;
        $portfolio->lien = $request->lien;
        $portfolio->titre = $request->titre;
        $portfolio->save();
        return redirect()->route('portfolio.index')->with('success', 'mise à jour');
    }

    public function show(Portfolio $id){
        $portfolio = $id;
        return view('backoffice.portfolio.showPortfolio',compact('portfolio'));
    }

}
