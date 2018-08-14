$(document).ready(function () {
    var y = [];
    $('#panel_group').change(function(e) {
    var origLength = $('#minProjApp > option').length;
    var x = origLength;
    
    for(var i=0; i < $('#panel_group option').length; i++) {
      if ($($('#panel_group option')[i]).prop('selected') ) {
        if (!y.includes(i)) {
            y.push(i);
        }
      } else {
        if (y.includes(i)) {
            y.splice(vals.indexOf(i), 1);
        }
      }
    }
    var order1 = [];
    y.forEach(function(ele) {
    order1.push( $($('#panel_group option')[ele]).val() );
    });
    
    var $minProjApp = $("#minProjApp").select2();

    if(order1.length > origLength) {
        count = count + 1;
        $('#minProjApp').append($('<option>', {
        value: count,
        text: count,
        id: 'mps'+count
        }));
        $minProjApp.val(order1.length);
    } else {
        count = count - 1;
        $('#minProjApp option').each(function() {
            if ( $(this).val() == order1.length +1 ) {
                $(this).remove();
            }
        });
        $minProjApp.val(order1.length);
    }

    

  });

  $('#panel_group').change(function(e) {
    for(var i=0; i <$('#panel_group option').length; i++) {
      if ($($('#panel_group option')[i]).prop('selected') ) {
        if (!vals.includes(i)) {
          vals.push(i);
        }
      } else {
        if (vals.includes(i)) {
          vals.splice(vals.indexOf(i), 1);
        }
      }
    }
  });
    $("#sub1").click(function(){
        var order = [];
        vals.forEach(function(ele) {
        order.push( $($('#panel_group option')[ele]).val() );
        });
        $("#panel_select").val(order);
        
    });
});

