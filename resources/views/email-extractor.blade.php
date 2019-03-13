@extends('layouts.app')

@section('head')
<script language="javascript" type="text/javascript">
<!-- Begin


function copy() {
	textRange = document.extractor.output.createTextRange();
	textRange.execCommand("RemoveFormat");
	textRange.execCommand("Copy");
}

function paste() {
	textRange = document.extractor.input.createTextRange();
	textRange.execCommand("RemoveFormat");
	textRange.execCommand("Paste");
}


function checksep(value){
	if (value) document.extractor.sep.value = "other";
}

function numonly(value){
	if (isNaN(value)) {
		window.alert("Please enter a number or else \nleave blank for no grouping.");
		document.extractor.groupby.focus();
	}
}

function findEmail() {
	$('.copyButton').attr('class', 'btn btn-primary copyButton');
	$('.copyButton').val('Copia');	
	var email = "none";
	var a = 0;
	var ingroup = 0;
	var separator = document.extractor.sep.value;
	var string = document.extractor.string.value;
	var groupby = Math.round(document.extractor.groupby.value);
	var address_type = document.extractor.address_type.value;
	var input = document.extractor.input.value;
	
	if (document.extractor.lowcase.checked) {
		var input = input.toLowerCase();
	}
	
	if (separator == "new") separator = "\n";
	if (separator == "new2") separator = ";\n";
	if (separator == "other") separator = document.extractor.othersep.value;
	
	if (address_type == "web") {
		rawemail = input.match(/([A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_%&\?\/.=]+)/gi);
	} else {
		rawemail = input.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);
	}
	
	var norepeat = new Array();
	var filtermail = new Array();
	if (rawemail) {
		if (string){
			x = 0;
			for (var y=0; y<rawemail.length; y++) {
				if (document.extractor.filter_type.value == 1) {
					if (rawemail[y].search(string) >= 0) {
						filtermail[x] = rawemail[y];
						x++;
					}
				} else {
					if (rawemail[y].search(string) < 0) {
						filtermail[x] = rawemail[y];
						x++;
					}
				}
			}
			rawemail = filtermail;
		}
	
		for (var i=0; i<rawemail.length; i++) {
			var repeat = 0;
			
			// Check for repeated emails routine
			for (var j=i+1; j<rawemail.length; j++) {
				if (rawemail[i] == rawemail[j]) {
					repeat++;
				}
			}
			
			// Create new array for non-repeated emails
			if (repeat == 0) {
				norepeat[a] = rawemail[i];
				a++;
			}
		}
		if (document.extractor.sort.checked) norepeat = norepeat.sort(); // Sort the array
		email = "";
		// Join emails together with separator
		for (var k = 0; k < norepeat.length; k++) {
			if (ingroup != 0) email += separator;
			email += norepeat[k];
			ingroup++;
			
			// Group emails if a number is specified in form. Each group will be separate by new line.
			if (groupby) {
				if (ingroup == groupby) {
					email += '\n\n';
					ingroup = 0;
				}
			}
		}
	}
	
	// Return array length
	var count = norepeat.length;
	
	// Print results
	document.extractor.count.value = count;
	document.extractor.output.value = email;
}
//  End -->
</script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-1">
					<a role="button" href="/" class="btn btn-outline-secondary">Indietro</a>
				</div>
			</div>
			<div class="clearfix"></div><br>

			<div class="card">
				<div class="card-header">
					<div class="row subtitle">
						<div class="col-md-12">
							Estrattore Mail
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="col-md-12">
						<form name="extractor" action="#" method="get">
							<table class="" cellpadding="4" cellspacing="0" border="0" width="600" align="center">
								<tr class="titlebarcolor" valign="middle"> 
									<td align="left" class="titlefont">
										</td>
									<td align="right" class="copyrightfont">
										</td>
								</tr>
								<tr>
									<td valign="top" align="center" width="50%">
										<strong>Input</strong><br />
										<textarea name="input" class="form-control" rows="8" cols="25" style="width:98%"></textarea></td>
									<td valign="top" align="center" width="50%">
										<strong>Output</strong><br />
										<textarea id="results" name="output" rows="8" class="form-control" cols="25" style="width:98%" readonly="readonly"></textarea></td>
								</tr>
								<tr>
									<td valign="top" align="left" colspan="2" style="border-bottom:1px solid #999999;">
									</td>
								</tr>
								<tr>
									<td valign="top" align="left">
										<br/>
										<input class="btn btn-success" type="button" value="Extract" onclick="findEmail();"/> 
										<input type="reset" class="btn btn-danger" value="Reset" />&nbsp;&nbsp;&nbsp;
										<input id="copyButton" type="button" onclick="copyToClipboard('#results'), changeClass.call(this)" class="btn btn-primary copyButton" value="Copia">
										</td>
									<td valign="top" align="right" nowrap="nowrap">
										<br />
										Numero di indirizzi: <input name="count" size="5" readonly="readonly" /></td>
								</tr>
								<tr>
									<td valign="top" align="left" colspan="2">
										<fieldset title="Output Option">
											<br />
											Separatore:
											<select name="sep">
											<option value="new2" >; e a capo</option>
											<option value=", " >Virgola</option>
											<option value=";">Punto e virgola</option>
											<option value=" : ">Due punti</option>
											<option value="new" selected="selected">A capo</option>
											<option value="other">Altro</option>
											</select>
											<input type="text" name="othersep" size="3" onblur="checksep(this.value);" />
											&nbsp;
											<label for="sortbox"><input type="checkbox" name="sort" id="sortbox" />Ordine alfabetico</label>
											&nbsp;
											<label for="casebox"><input type="checkbox" name="lowcase" id="casebox" checked="checked" />trasforma in minuscolo</label>
											<br /><br />
											Raggruppa: <input type="text" size="3" name="groupby" onblur="numonly(this.value);" /> Indirizzi <small>(Gli indirizzi saranno raggruppati in paragrafi)</small>
										</fieldset>
										<fieldset title="Filter Option">
											<br />
											<select name="filter_type">
												<option value="1" selected="selected">Indirizzi che contengono</option>
												<option value="0">Indirizzi che non contengono:</option>
											</select>
											<input type="text" size="20" name="string" />
											<br />
											<br />
											Tipo di indirizzi da estrarre: 
											<select name="address_type">
												<option value="email" selected="selected">Email</option>
												<option value="web">Indirizzi Web (URL)</option>
											</select>
										</fieldset>
									</td>
								</tr>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
	<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-2290307-14']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

	// Copia gli indirizzi email
	function copyToClipboard(element) {
			var $temp = $("<textarea>");
			$("body").append($temp);
			$temp.val($(element).val()).select();
			document.execCommand("copy");
			$temp.remove();
	}
	function changeClass(){
			$(this).attr('class', 'btn btn-primary copyButton');
			$(this).val('Copiato');
	}
	</script>
@endsection