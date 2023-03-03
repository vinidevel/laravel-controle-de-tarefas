<?php

namespace App\Http\Controllers;

use App\Mail\NovaTarefaMail;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Exports\TarefasExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class TarefaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*if (Auth::check()) {
            $id = Auth::user()->id;
            $name = Auth::user()->name;
            $email = Auth::user()->email;

            return "ID -> $id <br>  Nome -> $name <br> Email -> $email";
        }*/
        $user_id = auth()->user()->id;
        $tarefas = Tarefa::where('user_id', $user_id)->paginate(10);
        return view('tarefa.index', ['tarefas' => $tarefas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tarefa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dados = $request->all('tarefa', 'data_limite_conclusao');
        $dados['user_id'] = auth()->user()->id;
        //dd($dados);
        $tarefa = Tarefa::create($dados);
        $destinatario = auth()->user()->email;
        Mail::to($destinatario)->send(new NovaTarefaMail($tarefa));
        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
        //dd($tarefa->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function show(Tarefa $tarefa)
    {
        return view('tarefa.show', ['tarefa' => $tarefa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function edit(Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;
        $tarefa->user_id;
        if ($tarefa->user_id == $user_id) {
            return view('tarefa.edit', ['tarefa' => $tarefa]);
        } else {
            return view('acesso-negado');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;


        if ($tarefa->user_id == $user_id) {

            $tarefa->update($request->all());
            return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
        } else {
            return view('acesso-negado');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarefa $tarefa)
    {

        //dd($tarefa);
        $user_id = auth()->user()->id;

        if ($tarefa->user_id == $user_id) {
            $tarefa->delete();
        } else {
            return view('acesso-negado');
        }
        return redirect()->route('tarefa.index');
    }
    public function exportacao($extensao)
    {
        $nome_arquivo = 'lista_de_tarefas';

        if ($extensao == 'xlsx') {
            $nome_arquivo .= '.' . $extensao;
        } elseif ($extensao == 'csv') {
            $nome_arquivo .= '.' . $extensao;
        } elseif ($extensao == 'pdf') {
            $nome_arquivo .= '.' . $extensao;
        } else {
            return redirect()->route('tarefa.index');
        }

        return Excel::download(new TarefasExport, $nome_arquivo);
    }

    public function exportar()
    {   
        $tarefas = auth()->user()->tarefas()->get();
        $pdf = PDF::loadView('tarefa.pdf', ['tarefas' => $tarefas]);
        $pdf->setPaper('a4', 'landscape');
        //return $pdf->download('lista_de_tarefas.pdf');
        //orientacao: landscape (paisagem), portrait(retato)
        return $pdf->stream('lista_de_tarefas.pdf');
    }
}
