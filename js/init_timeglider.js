/* ***************************
 * Module developed at the University of Valladolid
 * Designed and directed by Juan Pablo de Castro at telecommunication engineering school
 * Copyright 2017 onwards EdUVaLab http://www.eduvalab.uva.es
 * @author Juan Pablo de Castro
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package msocial
 * *******************************************************************************
 */
function init_view(Y, msocialid, user, filterparams) {
	deferred_init_view(Y, msocialid, user, filterparams);
}
function deferred_init_view(Y, msocialid, user, filterparams) {
	$(document).ready(function () { 
		var params = $.param(filterparams);
		var tg1 = $("#my-timeglider").timeline({
				"min_zoom":1, 
				"max_zoom":32, 
				"data_source":"view/timeglider/jsonized.php?id=" + msocialid + "&" + params,
				"icon_folder":""
		});
    }); // end document-ready
	
}
