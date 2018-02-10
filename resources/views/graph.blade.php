
@for ($value=1;$value<=$counter;$value++)
	
	<?php $div_id = "chart-div-".$value; ?>
	<?php $graph = "graph".$value; ?>


	<div id="{{$div_id}}"></div>

	<?= Lava::render('ScatterChart', "{$graph}", "{$div_id}") ?>

@endfor