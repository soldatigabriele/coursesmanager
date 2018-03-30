@extends('layouts.forms')

@section('style')
<style type="text/css" media="screen">
.subtitle{
  font-weight: 800;
}
.righe>div{
  text-align: left;
  border-bottom: 1px solid #eee;
}
</style>
@endsection

@section('content')
<br>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10 col-sm-12">
      <div class="card">
        <div class="card-header"><b>SCHEDA DI ISCRIZIONE AL CORSO</b></div>
        <div class="card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          
          <form action="{{ route('partecipant-store') }}" method="post" id='i-recaptcha'>
                {{ csrf_field() }}
            
                @yield('formcontent')

          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="t_and_c_m" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Termini e Condizioni</h4>
      </div>
      <div class="modal-body">
        <div id="full-width">
  <!--content-->
    <p style="text-align: center;">ASSOCIAZIONE CULTURALE<br>
NUOVE PROSPETTIVE<br>
via del Carso, 28 – 34078 Sagrado (GO)<br>
C.F. 91033080317<br>
____________________________</p>
<p style="text-align: justify;"><strong>CONDIZIONI GENERALI PER LE ATTIVITÀ ORGANIZZATE DALLA NOSTRA ASSOCIAZIONE</strong></p>
<p style="text-align: justify;">La quota di partecipazione riportata nelle locandine si riferisce al contributo spese richiesto per l’evento in questione. Con il primo corso/seminario/evento che si frequenta nell’anno solare corrente, si diventa automaticamente soci dell’Associazione Culturale Nuove Prospettive, la cui quota associativa di 50€ verrà evidenziata nella regolare ricevuta rilasciata agli interessati.</p>
<p style="text-align: justify;">Tale quota è da ritenersi già compresa nel costo del primo corso/seminario/evento dell’anno solare.</p>
<p style="text-align: justify;">Per gli eventi successivi, la quota di partecipazione riportata nelle locandina si riferisce esclusivamente all’evento in questione e quindi non si deve scorporare la quota associativa.</p>
<p style="text-align: justify;">Diventando soci dell’Associazione si gode inoltre della copertura assicurativa di responsabilità civile nei confronti di terzi.</p>
<p style="text-align: justify;">Una volta compilata la scheda di partecipazione, quando richiesto, bisogna effettuare il versamento della caparra e mandarci una mail con l’avvenuto pagamento. <strong>L’iscrizione viene confermata solo quando ci arriva la mail con la copia della ricevuta. Vi verrà inviata a quel punto una risposta di conferma.</strong></p>
<p style="text-align: justify;"><strong>Nel caso di ritiro</strong>, la quota versata a scopo confirmatorio è rimborsabile o permutabile ESCLUSIVAMENTE se sarà formalmente inviata una mail di disdetta entro e non oltre 15 giorni dall’inizio del corso; verranno comunque trattenuti 20€ per ogni quota versata quali spese amministrative. Non è previsto alcun rimborso nel caso di ritiro successivo al termine.</p>
<p style="text-align: justify;">Il cambio di corso è ammesso, se espressamente richiesto con una mail entro 15 giorni dall’inizio del corso; non è ammesso successivamente.<br>
Nel caso in cui il corso sia sospeso/annullato, l’importo versato sarà restituito totalmente.</p>
<p style="text-align: justify;">Qualche giorno prima del corso/seminario verrà inviata una mail a tutti gli iscritti con gli ultimi dettagli e <em>‘chi viene, da dove’</em> in modo da potersi accordare per gli spostamenti con le auto riducendo il numero di veicoli in movimento e limitare il nostro impatto sull’ambiente.</p>

  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">I Agree</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('scripts')

<script type="text/javascript">
$(document).ready(function(){

$(".decimals").keydown(function (event) {
      if (event.shiftKey === true) {
          event.preventDefault();
      }

      if ((event.keyCode >= 48 && event.keyCode <= 57) ||
          (event.keyCode >= 96 && event.keyCode <= 105) ||
          event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
          event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

      } else {
          event.preventDefault();
      }

  });
});

</script>

@endsection
