// function that writes the list order to a cookie
function saveOrder() {
    $(".column-portlet").each(function(index, value){
        var colid = value.id;
        var cookieName = "cookie-" + colid;
        // Get the order for this column.
        var order = $('#' + colid).sortable("toArray");
        // For each portlet in the column
        for ( var i = 0, n = order.length; i < n; i++ ) {
            // Determine if it is 'opened' or 'closed'
            var v = $('#' + order[i] ).find('.card-section').is(':visible');
            // Modify the array we're saving to indicate what's open and
            //  what's not.
            order[i] = order[i] + ":" + v;
        }
        $.cookie(cookieName, order, { 
			path: "/", 
			expires: new Date(2055, 2, 1)});
    });
}

// function that restores the list order from a cookie
function restoreOrder() {
    $(".column-portlet").each(function(index, value) {
        var colid = value.id;
        var cookieName = "cookie-" + colid
        var cookie = $.cookie(cookieName);
        if ( cookie == null ) { 
			return; 
		}
        var IDs = cookie.split(",");
        for (var i = 0, n = IDs.length; i < n; i++ ) {
            var toks = IDs[i].split(":");
            if ( toks.length != 2 ) {
                continue;
            }
            var portletID = toks[0];
            var visible = toks[1]
            var portlet = $(".column-portlet")
                .find('#' + portletID)
                .appendTo($('#' + colid));
            if (visible === 'false') {
                portlet.find(".ui-icon").toggleClass("ui-icon-minus");
                portlet.find(".ui-icon").toggleClass("ui-icon-plus");
                portlet.find(".portlet-content").hide();
            }
        }
    });
} 
  $( function() {
    $( ".column-portlet" ).sortable({
      connectWith: ".column-portlet",
      placeholder: ".ui-sortable-placeholder ui-corner-all",
	  stop: function() { saveOrder(); }
    });
	 $(".portlet")
	 .addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
	 .find(".portlet-header")
	 .addClass("ui-widget-header ui-corner-all")
	 .prepend('<span class="ui-icon ui-icon-minus portlet-toggle"></span>')
	 .end()
	 .find(".portlet-content");
		restoreOrder();
 
    $( ".portlet-toggle" ).on( "click", function() {
      var icon = $( this );
      icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
      icon.closest( ".portlet" ).find( ".card-section" ).toggle();
	  saveOrder(); // This is important
    });
  } );