@extends('layout.principal')

@section('conteudo')
<br>

<div class="card demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid cad_card">
        <div class="card-content">
            <div class="row">
                <div class="container">
                    <ul class="collapsible" data-collapsible="accordion">
                        <li>
                            <div class="collapsible-header">
                                <i class="fa fa-plus-square-o fa-sm"></i>Adicionar
                            </div>
                            <div class="collapsible-body">
                                <form action="{{ route('coordenacao.adiciona') }}" method="post">
                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

                                    <div class="form-group">
                                        <label for="nome">Nome</label>
                                        <input name="nome" class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_gestor">Gestor</label>
                                        <select name="id_gestor" class="form-control">
                                            <option value="" disabled selected></option>
                                            @foreach($users as $u)
                                                <option value="{{$u->id}}">{{$u->email}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn waves-effect light-green accent-3"> Salvar</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                    <table class="bordered">
                        <thead>
                            <tr>
                                <th>ID </th>
                                <th>Nome </th>
                                <th>Gestor </th>
                                <th> Alterar/Excluir </th>
                            </tr>
                        </thead>
                          <tbody>
                          @foreach ($coordenacaos as $c)
                            <tr>
                                <td scope="row">{{$c->id}}</td>
                                <td> {{$c->nome}} </td>
                                <td>{{$c->id_gestor_FK->email}}</td>
                                <td>
                                    <div class="row">
                                        <a class="waves-effect waves-light btn green accent-3  modal-trigger" href="#modal1{{$c->id}}">Editar</a>
                                        <div id="modal1{{$c->id}}" class="modal">
                                            <div class="modal-content">
                                                <form action="{{ route('coordenacao.salvaAlt') }}" method="post">
                                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                <input type="hidden" name="id" value="{{{ $c->id }}}" />
                                                    <!--<input type="hidden" name="_method" value="put">-->
                                                    <div class="form-group">
                                                      <label for="nome">Nome</label>
                                                      <input name="nome" class="form-control" value="{{$c->nome}}"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="id_gestor">Usuários</label>
                                                        <select name="id_gestor" class="form-control">
                                                            <option value="{{{$c->id_gestor}}}" disabled selected>{{$c->id_gestor_FK->email}}</option>
                                                            @foreach($users as $u)
                                                                <option value="{{$u->id}}">{{$u->email}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="waves-effect waves-light btn green accent-3 ">Atualizar</button>
                                                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                </form>
                                            </div>
                                        </div>
                                        <a class="waves-effect waves-light btn red accent-4" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{action('CoordenacaoController@remove', $c->id)}}' : false)">Deletar</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
            
                        </tbody>
                    </table>
                </div>   
            </div>
        </div>
    </div>
</div>

@stop
