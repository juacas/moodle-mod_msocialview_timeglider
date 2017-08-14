/* ***************************
 * Module developed at the University of Valladolid
 * Designed and directed by Juan Pablo de Castro at telecommunication engineering school
 * Copyright 2017 onwards EdUVaLab http://www.eduvalab.uva.es
 * @author Juan Pablo de Castro
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package msocial
 * *******************************************************************************
 */
function init_view(Y, msocialid, user) {
	deferred_init_view(Y, msocialid, user);
}
function deferred_init_view(Y, msocialid, user) {
	$(document).ready(function () { 

		var tg1 = $("#my-timeglider").timeline({
				"min_zoom":1, 
				"max_zoom":40, 
				"data_source":"view/timeglider/jsonized.php?id="+msocialid,
				"icon_folder":""
		});
		
    }); // end document-ready
	
}
