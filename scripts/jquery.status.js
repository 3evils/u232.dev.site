function status_count(){if(void 0===(t=$("#status").val()))var t=$("#box_status").val();var s=t.length;s>140?$("#status").val(t.substr(0,140)):$("#status_count").html(140-s)}function status_slide(){$("#status_archive").is(":hidden")?($("#status_archive").slideDown("slow"),$("#status_archive_click").html("-")):($("#status_archive").slideUp("slow"),$("#status_archive_click").html("+"))}function status_pedit(){var t=$("#current_status").html();$("#current_holder").hide(),$("#status").val("").val(t).focus(),$("#status").after("<div id='status_buttons'><input type='button' onclick='status_edit()' value='Edit' /><input type='button' onclick='status_cancel()' value='Cancel' /></div>"),status_count()}function status_edit(){var t=$("#status").val();$.post("ajax.status.php",{action:"edit",ss:t},function(t){t.status?($("#status_buttons").fadeOut().remove(),$("#status").val(""),$("#current_status").empty().html(t.msg),$("#current_holder").fadeIn(),status_count()):alert(t.msg)},"json")}function status_showbox(t){if(void 0===t)t="";var s="<div id='status_box' style='display:none;'><div id='status_title' >Status update</div><div id='status_content'><textarea name='status' id='box_status' onkeyup='status_count()' cols='50' style='width:50%;'rows='4'>"+t+"</textarea><br/><div style='text-align:right;'><input type='button' value='Update' onclick='status_update()' /><input type='button' value='Cancel' onclick='status_distroy_box()'/></div></div><div id='status_tool'><div style='float:left;'>NO bbcode or html allowed</div><div style='float:right;' id='status_count'>140</div><div style='clear:both;'></div></div>";$("body").after(s),$("#status_box").css("top",$(window).height()/2-$("#status_box").height()/2),$("#status_box").css("left",$(window).width()/2-$("#status_box").width()/2),$("#status_box").fadeIn("slow")}function status_distroy_box(){$("#status_box").fadeOut("slow").remove()}function status_update(t){var s=$("#box_status").val();s.length>0&&$.post("ajax.status.php",{action:"new",ss:s},function(t){t.status?($("#status_content").empty(),$("#status_tool").remove(),$("#status_content").html(t.msg),window.setTimeout(function(){status_distroy_box()},1e3)):alert(t.msg)},"json")}function status_cancel(){$("#status").val(""),$("#status_buttons").fadeOut().remove(),$("#current_holder").fadeIn(),status_count()}function status_delete(t){confirm("Are you sure you want to do this ?")&&$.post("ajax.status.php",{action:"delete",id:t},function(s){s.status?$("#status_"+t).fadeOut():alert(s.msg)},"json")}$(document).ready(function(){$("#status_button").click(function(){alert("should edit")}),$("#status_button_cancel").click(function(){alert("Should cancel")})});