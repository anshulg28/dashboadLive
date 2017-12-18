<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Offer Validation :: Doolally</title>
	<?php echo $globalStyle; ?>
</head>
<body>
    <?php echo $headerView; ?>
    <main class="offerPage">
        <div class="container">
            <div class="row my-marginLR-zero">
                <h2 class="text-center">Offer Validator</h2>
                <br>
                <div class="col-sm-2 col-xs-1"></div>
                <div class="col-sm-8 col-xs-10">
                    <div class="form-group my-marginLR-zero">
                        <div class="col-sm-1 col-xs-0"></div>
                        <div class="col-sm-10 col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon">DO-</span>
                                <input type="number" name="offerCode" id="offerCode"
                                       class="form-control offer-input" placeholder="11111">
                            </div>
                            <h4 class="text-center">OR</h4>
                            <div class="input-group">
                                <span class="input-group-addon">BR-</span>
                                <input type="number" name="breakOfferCode" id="breakOfferCode"
                                       class="form-control offer-input" placeholder="11111">
                            </div>
                            <h4 class="text-center">OR</h4>
                            <div class="input-group">
                                <span class="input-group-addon">EV-</span>
                                <input type="number" name="eventOfferCode" id="eventOfferCode"
                                       class="form-control offer-input" placeholder="11111">
                            </div>
                            <h4 class="text-center">OR</h4>
                            <div class="input-group">
                                <span class="input-group-addon">ORG-</span>
                                <input type="number" name="orgOfferCode" id="orgOfferCode"
                                       class="form-control offer-input" placeholder="11111">
                            </div>
                            <h4 class="text-center">OR</h4>
                            <div class="input-group">
                                <span class="input-group-addon">TW-</span>
                                <input type="number" name="oldOfferCode" id="oldOfferCode"
                                       class="form-control offer-input" placeholder="11111">
                            </div>
                        </div>
                        <div class="col-sm-1 col-xs-0"></div>
                    </div>
                    <br><br>
                    <div class="form-group my-marginLR-zero">
                        <div class="col-sm-12 col-xs-12 text-center my-marginUp">
                            <button type="button" class="btn btn-primary offerCheck-btn">Verify</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 col-xs-1"></div>
            </div>
        </div>
    </main>
    <div id="breakfastModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Fnb Redemption</h4>
                </div>
                <div class="modal-body text-center">
                    <input type="hidden" id="offerCode" value=""/>
                    <div class="breakfast-icons-holder">

                    </div>
                    <div class="beer-icons-holder">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submitBreak">Submit</button>
                </div>
            </div>

        </div>
    </div>
<?php echo $footerView;?>
</body>
<?php echo $globalJs; ?>

<script>

    var totBeer = 0;
    var totBreakfast = 0;
    var countBeer = 0;
    var countBreak = 0;

    $(document).on('click','.offerCheck-btn',function(){

        var newOffer = $('#offerCode').val();
        var oldOffer = $('#oldOfferCode').val();
        var breakOffer = $('#breakOfferCode').val();
        var eventOffer = $('#eventOfferCode').val();
        var orgOffer = $('#orgOfferCode').val();
        var offerUrl,offerPrifix,finalCode;
        var filledFields = 0;
        $('.offer-input').each(function(i,val){
            if($(val).val() != '')
            {
                filledFields++;
            }
        });
        if(filledFields > 1)
        {
            bootbox.alert('Enter Only 1 Code!');
            return false;
        }
        else if(filledFields == 0)
        {
            bootbox.alert('Please Provide Offer Code!');
            return false;
        }

        if(newOffer != '')
        {
            finalCode = newOffer;
            offerUrl = base_url+'offers/offerCheck/'+newOffer;
            offerPrifix = 'DO';
        }
        else if(breakOffer != '')
        {
            finalCode = breakOffer;
            offerUrl = base_url+'offers/offerCheck/'+breakOffer;
            offerPrifix = 'BR';
        }
        else if(eventOffer != '')
        {
            finalCode = eventOffer;
            offerUrl = base_url+'offers/offerCheck/'+eventOffer;
            offerPrifix = 'EV';
        }
        else if(orgOffer != '')
        {
            finalCode = orgOffer;
            offerUrl = base_url+'offers/offerCheck/'+orgOffer;
            offerPrifix = 'ORG';
        }
        else if(oldOffer != '')
        {
            finalCode = oldOffer;
            offerUrl = base_url+'offers/oldOfferCheck/'+oldOffer;
            offerPrifix = 'TW';
        }

        var errUrl = offerUrl+'/0';
        //Checking offer Code valid
        showCustomLoader();
        $.ajax({
            type:"GET",
            dataType:"json",
            url: offerUrl+'/0',
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    console.log(data);
                    if(typeof data.totBeer !== 'undefined')
                    {
                        totBeer = data.totBeer;
                    }
                    if(typeof data.totBreakfast !== 'undefined')
                    {
                        totBreakfast = data.totBreakfast;
                    }
                    if(typeof data.countBeer !== 'undefined')
                    {
                        countBeer = data.countBeer;
                    }
                    if(typeof data.countBreak !== 'undefined')
                    {
                        countBreak = data.countBreak;
                    }

                    if(data.offerType == 'Beer')
                    {
                        bootbox.alert('<label class="my-success-text">Valid for 330 ml Pint. Mug Club 500 ml</label>', function(){
                            redeemOffer(offerPrifix, finalCode, offerUrl);
                        });
                    }
                    else if(data.offerType == 'Breakfast2')
                    {
                        bootbox.alert('<label class="my-success-text">Valid for Two Breakfasts and Two Beers </label>',
                        function(){
                            redeemOffer(offerPrifix, finalCode, offerUrl,data.offerType);
                        });
                    }
                    else if(data.offerType == 'Breakfast')
                    {
                        bootbox.alert('<label class="my-success-text">Valid for One Breakfast and One Beer. </label>', function(){
                            redeemOffer(offerPrifix, finalCode, offerUrl,data.offerType);
                        });
                    }
                    else if(data.offerType == 'Workshop')
                    {
                        bootbox.alert('<label class="my-success-text">Valid for F&B upto Rs.300</label>', function(){
                            redeemOffer(offerPrifix, finalCode, offerUrl);
                        });
                    }
                    else
                    {
                        bootbox.alert('<label class="my-success-text">Valid for '+data.offerType+' </label>', function(){
                            redeemOffer(offerPrifix, finalCode, offerUrl);
                        });
                    }
                }
                else
                {
                    bootbox.alert('<label class="my-danger-text">'+data.errorMsg+'</label>');
                }

            },
            error: function(xhr, status, error){
                hideCustomLoader();
                bootbox.alert('Unable To Connect To Server!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });

    });

    function redeemOffer(offerPrifix, finalCode, offerUrl,offerType='')
    {
        bootbox.confirm("Would you like to redeem "+offerPrifix+"-"+finalCode+" now?", function(result) {
            if(result === true)
            {
                if(offerType != '')
                {
                    if($.isNumeric(countBeer) && $.isNumeric(countBreak))
                    {
                        if(totBeer != 0 || totBreakfast != 0)
                        {
                            var breakHtml = '<ul class="list-inline">';
                            for(var i=0;i<totBreakfast;i++)
                            {
                                breakHtml += '<li>';
                                breakHtml += '<input type="checkbox" name="breakRedemps" value="'+i+'" id="break'+i+'">';
                                breakHtml += '<label for="break'+i+'">';
                                breakHtml += '<i class="fa fa-cutlery fa-5x"></i><br>';
                                breakHtml += '<span>Breakfast</span>';
                                breakHtml += '</label></li>';
                            }
                            breakHtml += '</ul>';

                            $('#breakfastModal .breakfast-icons-holder').html(breakHtml);

                            var beerHtml = '<ul class="list-inline">';
                            for(i=0;i<totBeer;i++)
                            {
                                beerHtml += '<li>';
                                beerHtml += '<input type="checkbox" name="beerRedemps" value="'+i+'" id="beer'+i+'">';
                                beerHtml += '<label for="beer'+i+'">';
                                beerHtml += '<i class="fa fa-beer fa-5x"></i><br>';
                                beerHtml += '<span>Beer</span>';
                                beerHtml += '</label></li>';
                            }
                            beerHtml += '</ul>';

                            $('#breakfastModal .beer-icons-holder').html(beerHtml);
                            $('#breakfastModal #offerCode').val(finalCode);
                            $('#breakfastModal').modal('show');
                        }
                    }
                    else
                    {
                        var breakHtml = '<ul class="list-inline">';
                        for(var i=0;i<countBreak.length;i++)
                        {
                            breakHtml += '<li>';
                            breakHtml += '<label class="item-disabled">';
                            breakHtml += '<div class="overlay">Redeemed</div>';
                            breakHtml += '<i class="fa fa-cutlery fa-5x"></i><br>';
                            breakHtml += '<span>Breakfast</span>';
                            breakHtml += '</label>';
                            var dt = new Date(countBreak[i].useDT);
                            breakHtml += '<br><span>'+dt.toDateString()+' '+dt.toLocaleTimeString()+'</span></li>';
                        }
                        for(i=0;i<totBreakfast;i++)
                        {
                            breakHtml += '<li>';
                            breakHtml += '<input type="checkbox" name="breakRedemps" value="'+i+'" id="break'+i+'">';
                            breakHtml += '<label for="break'+i+'">';
                            breakHtml += '<i class="fa fa-cutlery fa-5x"></i><br>';
                            breakHtml += '<span>Breakfast</span>';
                            breakHtml += '</label></li>';
                        }
                        breakHtml += '</ul>';

                        $('#breakfastModal .breakfast-icons-holder').html(breakHtml);

                        var beerHtml = '<ul class="list-inline">';
                        for(i=0;i<countBeer.length;i++)
                        {
                            beerHtml += '<li>';
                            beerHtml += '<label class="item-disabled">';
                            breakHtml += '<div class="overlay">Redeemed</div>';
                            beerHtml += '<i class="fa fa-beer fa-5x"></i><br>';
                            beerHtml += '<span>Beer</span>';
                            beerHtml += '</label>';
                            var dt = new Date(countBeer[i].useDT);
                            beerHtml += '<br><span>'+dt.toDateString()+' '+dt.toLocaleTimeString()+'</span></li>';
                        }
                        for(i=0;i<totBeer;i++)
                        {
                            beerHtml += '<li>';
                            beerHtml += '<input type="checkbox" name="beerRedemps" value="'+i+'" id="beer'+i+'">';
                            beerHtml += '<label for="beer'+i+'">';
                            beerHtml += '<i class="fa fa-beer fa-5x"></i><br>';
                            beerHtml += '<span>Beer</span>';
                            beerHtml += '</label></li>';
                        }
                        beerHtml += '</ul>';

                        $('#breakfastModal .beer-icons-holder').html(beerHtml);
                        $('#breakfastModal #offerCode').val(finalCode);
                        $('#breakfastModal').modal('show');
                    }
                }
                else
                {
                    var errUrl = offerUrl+'/1';
                    showCustomLoader();
                    //send ajax request to check mobile number
                    $.ajax({
                        type:"GET",
                        dataType:"json",
                        url:offerUrl+'/1',
                        success: function(data)
                        {
                            hideCustomLoader();
                            if(data.status === true)
                            {
                                if(data.offerType == 'Beer')
                                {
                                    bootbox.alert('<label class="my-success-text">Congrats, you get a 330ml Beer! Mug Club members get 500ml</label>');
                                }
                                else if(data.offerType == 'Breakfast2')
                                {
                                    bootbox.alert('<label class="my-success-text">Congrats, you get Two Breakfasts. This includes Two pint. </label>');
                                }
                                else if(data.offerType == 'Breakfast')
                                {
                                    bootbox.alert('<label class="my-success-text">Congrats, you get a Breakfast. This includes one pint. </label>');
                                }
                                else if(data.offerType == 'Workshop')
                                {
                                    bootbox.alert('<label class="my-success-text">Congrats, you are eligible for 330ml Beer! Mug Club members get 500ml' +
                                        '<span class="my-danger-text"> OR </span>Drink & Garlic Bread<span class="my-danger-text"> OR </span>House Fries </label>');
                                }
                                else
                                {
                                    bootbox.alert('<label class="my-success-text">Success, '+data.offerType+' </label>');
                                }

                            }
                            else
                            {
                                bootbox.alert('<label class="my-danger-text">'+data.errorMsg+'</label>');
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
            }
        });
    }
    $(document).on('keypress','#offerCode,#oldOfferCode', function(event){

        var keycode = (event.keyCode ? event.keyCode : event.which);

        if(keycode == 45)
        {
           return false;
        }
        if(keycode == 13)
        {
            $('.offerCheck-btn').trigger('click');
        }
    });

    $(document).on('click','#submitBreak', function(e){
        console.log('in');
        var totBreakSelect = 0;
        var totBeerSelect = 0;
        var offerCode = $('#breakfastModal #offerCode').val();

        $('#breakfastModal .breakfast-icons-holder input[name="breakRedemps"]').each(function(i,val){
            if($(val).is(':checked'))
            {
                totBreakSelect += 1;
            }
        });
        $('#breakfastModal .beer-icons-holder input[name="beerRedemps"]').each(function(i,val){
            if($(val).is(':checked'))
            {
                totBeerSelect += 1;
            }
        });

        if(totBreakSelect == 0 && totBeerSelect == 0)
        {
            bootbox.alert('Please Select at least 1 item!');
            return false;
        }

        showCustomLoader();
        var errUrl=base_url+'offers/breakfastRedemption';
        $.ajax({
            type:'POST',
            dataType:'json',
            url:base_url+'offers/breakfastRedemption',
            data: {offerCode:offerCode,breakfast:totBreakSelect,beer:totBeerSelect},
            success: function(data){
                hideCustomLoader();
                if(data.status === true)
                {
                    var sucText = '<label class="my-success-text">Congrats, you get ';
                    if(totBreakSelect != 0)
                    {
                        sucText += totBreakSelect +' Breakfast(s) ';
                    }
                    if(totBeerSelect != 0)
                    {
                        if(totBreakfast != 0)
                        {
                            sucText += 'and '+totBeerSelect +' Beer(s) ';
                        }
                        else
                        {
                            sucText += totBeerSelect +' Beer(s) ';
                        }
                    }
                    sucText += '</label>';
                    bootbox.alert(sucText,function() {
                        window.location.reload();
                    });
                }
                else
                {
                    bootbox.alert(data.errorMsg);
                }

            },
            error: function(xhr, status, error)
            {
                hideCustomLoader();
                bootbox.alert('Error Connecting Server!');
                var err = 'Url: '+errUrl+' StatusText: '+xhr.statusText+' Status: '+xhr.status+' resp: '+xhr.responseText;
                saveErrorLog(err);
            }
        });

    });

</script>
</html>