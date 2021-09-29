<div class="h-full bg-gray-200 flex justify-center px-12 py-8">
  
    {{-- Side --}}

    <div class="h-auto bg-white mt-14 w-1/5 rounded-md shadow-md flex justify-center p-4">
        <ul class="w-full">
            <li class="border-b-2 border-gray-200 px-3 py-8 text-center hover:bg-gray-50 cursor-pointer">HOME</li>  
            <li class="border-b-2 border-gray-200 px-3 py-8 text-center hover:bg-gray-50 cursor-pointer">USUÁRIO</li>    
            <li class="border-b-2 border-gray-200 px-3 py-8 text-center hover:bg-gray-50 cursor-pointer">HISTÓRICO</li>    
            <li class="border-b-2 border-gray-200 px-3 py-8 text-center hover:bg-gray-50 cursor-pointer">RELATÓRIO</li>    
        </ul>    
    </div> 

    {{-- Content --}}
    
    <div class="h-auto bg-white ml-5 mt-14 w-3/5 rounded-md shadow-md p-4">
        {{-- Header --}}  
        <div class="flex justify-between p-3">
            <div>
                <h2>{{ Auth::user()->name }}</h2>
                <p>{{ Auth::user()->email}}</p>
            </div>
            <button wire:click="logout" class="text-blue-400 hover:text-blue-700" >Sair</button>
        </div> 

        <div class="flex justify-between p-3 border-b-2 border-gray-200">
            <div class="">
                <p>Saldo</p>
                <h2 class="text-sm">R$ <span class="text-lg font-bold"> {{ $saldo }}</span></h2>
            </div>

            <div class="">
                <p>Despesas</p>
                <h2 class="text-sm">R$ <span class="text-lg font-bold text-red-400"> {{ $todasDespesas ? $todasDespesas : 'R$ 0,00' }}</span></h2>
            </div>

            <div class="">
                <p>Receitas</p>
                <h2 class="text-sm">R$ <span class="text-lg font-bold text-green-400"> {{ $todasReceitas ? $todasReceitas : 'R$ 0,00' }}</span></h2>
            </div>
            <div class="">
                <span class="text-lg">{{ date("d-m-Y") }}</span>
            </div>
        </div>

        {{-- main --}}
        
        <div class="p-3 border-b-2 border-gray-200 pb-4">
            <p>Novo lançamento</p>
            @if ($errors)
                <span class="error text-red-400">{{ $errors->first() }}</span>
            @endif

            <div class="flex justify-between">
                <input wire:model="descricao" class="border-b-2 border-gray-200 rounded-lg outline-none"
                 type="text" placeholder="descrição">

                <input wire:model="valor" class="border-b-2 border-gray-200 rounded-lg w-32"
                 type="text" placeholder="valor">

                <select wire:model="tipo" name="tipo" class="border-b-2 border-gray-200 rounded-lg">
                    <option value="" selected>selecionar</option>
                    <option value="receita">Receita</option>
                    <option value="despesa">Despesa</option>
                </select>

                <div class="border-b-2 border-gray-200 rounded-lg flex w-36 items-center">
                    <input wire:model="competencia" class="border-none w-28" type="text">
                    <svg  xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>

                @if ($atualizando)
                    <button wire:click="update" class="bg-yellow-700 hover:bg-green-600 rounded-lg p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                @else
                    <button wire:click="store" class="bg-green-700 hover:bg-green-600 rounded-lg p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>
        
        <div class="mt-3">
            <button wire:click='seach(09)'>Setembro</button>
            <button wire:click='seach(10)'>Outubro</button>
        </div>

        {{-- grafico / lista --}}
        <div class="flex justify-between p-3 pt-8">


            <div class="flex justify-center items-center h-auto w-1/2">
                
                <svg xmlns="http://www.w3.org/2000/svg" class="h-48 w-48" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                  </svg>
            </div>

            <div class="w-1/2">
                <ul>

                    @if ($balancos)
                        @foreach ($balancos as $balanco)
                            <li class="bg-gray-100 flex justify-between w-auto p-3 rounded-md mb-2">
                                <div class="infos">
                                    <h2 class="font-bold">{{ $balanco->descricao }}</h2>
                                    <div class="flex">
                                        <p class="text-{{ $balanco->tipo == 'despesa' ?  'red' : 'green'}}-400 mr-3">R$ {{ $balanco->valor }}</p>
                                        <p>{{ date('d-m-Y', strtotime($balanco->competencia)); }}</p>
                                    </div>
                                </div>
                                <div wire:click="findUpdate({{ $balanco->id }})" class="buttons flex items-center">
                                    <button class="p-2 text-yellow-400 hover:text-yellow-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
        
                                    <button wire:click="delete({{ $balanco->id }})" class="p-2 text-red-400 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    @else
                        
                        <li class="bg-gray-100 flex justify-between w-auto p-3 rounded-md mb-2">
                            
                            <p>Olá, vamos o seu primeiro registro !!</p>
                
                        </li>
                    @endif
                     
                </ul>
            </div>
        </div>
    </div>
</div>
