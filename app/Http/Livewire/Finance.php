<?php

namespace App\Http\Livewire;

use App\Models\Balanco;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class Finance extends Component
{
    public $descricao;
    public $valor;
    public $tipo;
    public $competencia;
    public $balancos=null;
    public $saldo = 0;
    public $todasDespesas;
    public $todasReceitas;
    public $atualizando = false;
    public $keyUpdate;

   // public $numberFormated = money_format('%n', $this->valor );

    protected $rules = [
        'descricao' => 'required',
        'valor' => 'required',
        'tipo' => 'required',
    ];

    protected $messages = [
        'descricao.required' => 'Colocar uma descrição é obrigatório.',
        'valor.required' => 'Infome algum valor.',
        'tipo.required' => 'Infome o tipo de registro.',
    ];

    public function render()
    {
        return view('livewire.finance')->layout('layouts.base');
    }

    public function mount()
    {
        $this->allBalance();
        $this->saldo();
        $this->competencia = date("d-m-Y");
    }

    public function store()
    {
        $this->validate();
        
        Balanco::create([
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'tipo' => $this->tipo,
            'competencia' => date('Y-m-d', strtotime($this->competencia)),
            'user_id' => auth()->user()->id,
        ]);

        $this->clearFields();

        $this->allBalance();
        $this->saldo();
    }

    public function allBalance()
    {
        $this->balancos = Balanco::where('user_id', auth()->user()->id )->get();
    }

    public function saldo()
    {
        $balancos = Balanco::where('user_id', auth()->user()->id )->get();

        $receitas = 0;
        $despesas = 0;

        foreach($balancos as $balanco){
            if($balanco->tipo == 'receita'){
                $receitas += $balanco->valor;
            }else{
                $despesas += $balanco->valor;
            }
        }

        $this->saldo = ($receitas - $despesas);
        $this->todasReceitas = $receitas;
        $this->todasDespesas = $despesas;
        
    }

    public function delete(Balanco $balanco)
    {
        $balanco->delete();

        $this->allBalance();
        $this->saldo();
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->to('/');
    }

    public function findUpdate(Balanco $balanco)
    {
        $this->descricao = $balanco->descricao;
        $this->valor = $balanco->valor;
        $this->tipo = $balanco->tipo;
        $this->competencia = date('d-m-Y', strtotime($balanco->competencia));

        $this->atualizando = true;
        $this->keyUpdate = $balanco->id;
    }

    public function update()
    {
        $balanco = Balanco::find($this->keyUpdate);
        $balanco->update([
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'tipo' => $this->tipo,
            'competencia' => date('Y-m-d', strtotime($this->competencia)),
            'user_id' => auth()->user()->id,
        ]);

        $this->atualizando =  false;
        $this->clearFields();
        $this->allBalance();
        $this->saldo();
    }

    public function clearFields()
    {
        $this->descricao = '';
        $this->valor = '';
        $this->tipo = '';
        $this->competencia = date("d-m-Y");
    }

    public function seach($mes)
    {
        $this->balancos = Balanco::whereMonth('competencia', $mes)->get();
    }
}
