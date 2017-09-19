/* ***************************
 * Module developed at the University of Valladolid
 * Designed and directed by Juan Pablo de Castro at telecommunication engineering school
 * Copyright 2017 onwards EdUVaLab http://www.eduvalab.uva.es
 * @author Juan Pablo de Castro
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package msocial
 * *******************************************************************************
 */
function init_view(Y, msocialid, user, startdate, enddate) {
	deferred_init_view(Y, msocialid, user, startdate, enddate);
}
function deferred_init_view(Y, msocialid, user, startdate, enddate) {
	$(document).ready(function () { 

		var tg1 = $("#my-timeglider").timeline({
				"min_zoom":1, 
				"max_zoom":29, 
				"data_source":"view/timeglider/jsonized.php?id=" + msocialid + "&startdate=" + startdate + "&enddate=" + enddate,
				"icon_folder":""
		});
		
    }); // end document-ready
	
}
