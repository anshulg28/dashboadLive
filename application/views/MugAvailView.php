<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Mug Club :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="mugClub">
        <div class="container">
            <div class="row">
                <h2 class="text-center">Mug # Check</h2>
                <br>
                <div class="col-sm-2 col-xs-1"></div>
                <div class="col-sm-8 col-xs-10">
                    <div class="form-group my-marginLR-zero">
                        <div class="col-sm-1 col-xs-0"></div>
                        <div class="col-sm-10 col-xs-12">
                            <input type="number" name="mobNum" id="mobNumCheck"
                                   class="form-control" placeholder="Mug # (Max 9999)">
                        </div>
                        <div class="col-sm-1 col-xs-0"></div>
                    </div>
                    <br><br>
                    <div class="form-group my-marginLR-zero">
                        <div class="col-sm-12 col-xs-12 text-center">
                            <button type="button" class="btn btn-primary mugCheck-btn">Check</button>
                        </div>
                    </div>
                    <br><br>
                    <div class="col-sm-1 col-xs-0"></div>
                    <div class="col-sm-10 col-xs-12">
                        <div class="panel panel-default avail-header hide">
                            <div class="panel-heading">Available Mug Number(s)</div>
                            <div class="panel-body">
                                <ul class="list-inline available-mugs-list">

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1 col-xs-0"></div>
                </div>
                <div class="col-sm-2 col-xs-1"></div>
            </div>
        </div>

</body>
<?php echo $globalJs; ?>

<script>

    $(document).on('click','.mugCheck-btn',function(){
    $('#mobNumCheck').val(Number($('#mobNumCheck').val()));
    var mugNum = $('#mobNumCheck').val();
    if(mugNum != '')
    {
        showCustomLoader();
        var errUrl = base_url+'mugclub/MugAvailability/json/0/'+mugNum;
        //send ajax request to check mobile number
        $.ajax({
            type:"GET",
            dataType:"json",
            url:base_url+'mugclub/MugAvailability/json/0/'+mugNum,
            success: function(data)
            {
                hideCustomLoader();
                if(data.status === true)
                {
                    if(!$('.avail-header').hasClass('hide'))
                    {
                        $('.avail-header').addClass('hide');
                    }
                    bootbox.alert('Mug Number is <label class="my-success-text">Available</label>');
                }
                else
                {
                    bootbox.alert('Mug Number is <label class="my-danger-text">Not Available</label>');
                    if(typeof data.availMugs != 'undefined')
                    {
                        var mugHtml = '';
                        var i = 1;
                        for(var index in data.availMugs)
                        {
                            if(i == 5)
                            {
                                $('.avail-header').removeClass('hide');
                                $('.available-mugs-list').html(mugHtml);
                                return false;
                            }
                            mugHtml += '<li><span class="label label-primary avail-mugs">'+data.availMugs[index]+'</span></li>';
                            i++;
                        }
                        $('.avail-header').removeClass('hide');
                        $('.available-mugs-list').html(mugHtml);
                    }
                }
            },
            error: function(xhr, status, error)
            {
                hideCustomLoader();
                bootbox.alert('Unable To Connect To Server!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });
    }
    else
    {
        bootbox.alert('Please Provide Mug Number!');
    }
});

$(document).on('keypress','#mobNumCheck', function(event){

    var keycode = (event.keyCode ? event.keyCode : event.which);

    if(keycode == 45)
    {
       return false;
    }
});

$(document).on('keyup','#mobNumCheck', function(event){

    var keycode = (event.keyCode ? event.keyCode : event.which);

    var finalVal;
    if($(this).val() > 9998)
    {
        finalVal = 9999 /*- (Number($(this).val()) - 9999)*/;
        $(this).val(finalVal);
    }

    else if(keycode == '13'){
        $('.mugCheck-btn').trigger('click');
    }
});
$(document).on('click','.avail-mugs', function(){
    $('#mobNumCheck').val($(this).html());
});

</script>
</html>