@extends('layout.principal')

@section('conteudo')
<div class="container">
@if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
<div> Prazo No Dia {{$percPrazo}} </div>
<div> Prazo No Mês Corrente {{$percPrazoMes}} </div>
<div> Prazo No Ano Corrente {{$percPrazoAno}} </div>
<h1>Atividades</h1>
  @if ($aberta->isEmpty())
    <form name='TabAtividades' action="/atividade/iniciar" method="post"> 
  @else
    <form name='TabAtividades' action="/atividade/parar" method="post">
  @endif
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <input type="hidden" name="usuario" value="{{{ $usuario_id }}}" />      
    <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <td> Tipo </td>
          <td> Nome Processo </td>
          <td> Status </td>
          <td> Data Meta </td>
          <td> Data Ultima Atualização </td>
          <td> Data Conciliada </td>
          <td> Ação </td>
          @if (!$aberta->isEmpty() and !$classificacoes->isEmpty())
            <td> Classificação </td>         
          @endif 
          @if (!$aberta->isEmpty())
            <td> Observação </td>         
          @endif      
        <tr>
      </thead>
      <tbody>
        @foreach ($atividades as $index => $a)
          <tr>
              <td> {{$a->tipoNome}} <input type="hidden" name="tipoId[]" value="{{{ $a->tipoId }}}" /> </td>
              <td> {{$a->processoNome}} <input type="hidden" name="id_processo[]" value="{{{ $a->processoId }}}" /> </td>
              @if (($a->ultima_data)<($a->data_meta))
              <td><span style="color: Red;"> <i class="fa fa-circle fa-lg"></i></span> </td>
              @elseif (($a->ultima_data)>($a->data_meta))
                <td><span style="color: Green;"> <i class="fa fa-circle fa-lg"></i></span> </td>
              @else
                <td><span style="color: Yellow;"> <i class="fa fa-circle fa-lg"></i></span> </td>
              @endif
              <td> <input type="date" name="data_meta[]" value="{{{ $a->data_meta }}}" readonly/> </td>
              <td> <input type=date name="ultima_data[]" value="{{{ $a->ultima_data }}}" readonly/> </td>
              <td> <input type=date name="data_conciliada[]" value="{{{ $a->data_conciliada }}}" <?php if (!$aberta->isEmpty()){ ?> readonly <?php   } ?>/> </td>
              @if (($a->hora_fim)=="aberta")
                <td>  
                  <button id='P{{{ $a->processoId }}}' type="submit" class="waves-effect waves-light btn" name="submit" value="P{{{ $index }}}">
                    <i class="fa fa-pause" aria-hidden="true"></i>
                  </button>
                  @if($a->tipoId == 3)
                    <button id='C{{{ $a->processoId }}}' type="submit" class="waves-effect waves-light btn" name="submit" value="C{{{ $index }}}">
                      <i class="fa fa-stop" aria-hidden="true"></i>
                    </button>
                    @if(date('H') < 10)
                      <div>
                        <input type="checkbox" name = "tolerancia[]" id="checkTolerancia" />
                        <label for="checkTolerancia">Atividade Concluída na Tolerância</label>
                      </div>
                    @endif
                  @endif
                </td>

                @if ($aberta[0]->id == $a->processoId and !$classificacoes->isEmpty())
                  <td>
                    <div class="form-group col s6">
                      <select name="classificacao" class="form-control">
                          @foreach($classificacoes as $c)
                              <option value="{{$c->id}}">{{$c->opcao}}</option>
                          @endforeach
                      </select>
                    </div>
                  </td>
                @endif
                <td><textarea name="observacao"> </textarea> </td>
              @else
                <td>  <button id='{{{ $a->processoId }}}' type="submit" class="waves-effect waves-light btn" <?php if (!$aberta->isEmpty()){ ?> disabled <?php   } ?>  name="submit" value="{{{ $index }}}"> 
                        <i class="fa fa-play" aria-hidden="true"></i>
                      </button> </td>
              @endif
            </tr>
        @endforeach
      </tbody>
    </table>
  </form>
</div>  
@stop