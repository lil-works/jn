var neck = {

    init:function(canvasId,instrument){

        Neck = this;

        //this.getSession();

        this.canvasId = canvasId;
        this.c = document.getElementById(canvasId);
        this.width = this.c.width;
        this.height = this.c.height;
        this.ctx = this.c.getContext("2d");


        this.instrument = instrument;
        this.instrumentId = instrument.id;


        this.sound=this.session["sound"];


        if(!this.instrumentId)
            this.instrumentId = 0;



        this.setControls();
        this.getInstrument( this.instrumentId );

        return this;
    },

    insertAllRootScale:function(){
        $.each(Neck.rsBasket,function(index,value){
            var splited =  value.split("_");
            Neck.insertRootScale(splited[0],splited[1]);
        });

    },
    insertRootScale:function(r,s){

        var ajax_neck_rootScale = Routing.generate('ajax_neck_rootScale');
        Neck.rsBasket[0] = r+"_"+s;

        var request = $.ajax({
            url: ajax_neck_rootScale,
            method: "POST",
            data: {i: Neck.instrumentId, r: r, s: s},
            dataType: "html",
            async: false
        });


        request.done(function (msg) {
            $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function (index, value) {
                Neck.formatedMatrice[value.currentString][value.currentCase].intervale[r + "_" + s] = {intervaleName:value.currentIntervale,toneName:value.wsName};
            });


            Neck.draw();

        });

    },
    insertFingerings:function(jsonobj){
        this.fBasket = $.parseJSON(jsonobj.replace(/&quot;/g, '\"'));

    },

    getInstrument:function(instrument_id){
        if(instrument_id == 0){
            formatedMatrice = [];
            formatedMatrice[0] = [];
            for(i=0;i<=12;i++) {

                var octave = 2;
                if(i == 12)
                    var octave = 3;

                formatedMatrice[0][i] = {
                    "case": i,
                    "string": 0,
                    "digit": i,
                    "digitA": i+24,
                    "infoTone": this.tones[i],
                    "intervale": {},
                    "octave": octave
                };
            }
            Neck.formatedMatrice = formatedMatrice;

            Neck.draw();
            return;
        }

        var ajax_neck_instrument = Routing.generate('ajax_neck_instrument');
        this.cleanSelection();
        var request = $.ajax({
            url: ajax_neck_instrument,
            method: "POST",
            data: { instrument_id : instrument_id },
            dataType: "html",
            async: false
        });

        request.done(function( msg ) {

            JSONNodes = msg;
            this.matrice = $.parseJSON(JSONNodes.replace(/&quot;/g, '\"'));
            formatedMatrice = [];
            Neck.formatedMatrice = [];
            for(i=0;i<this.matrice.length;i++){
                if(!formatedMatrice[this.matrice[i].currentString]){ formatedMatrice[this.matrice[i].currentString] = [];}
                if(!formatedMatrice[this.matrice[i].currentString][this.matrice[i].currentCase]){formatedMatrice[this.matrice[i].currentString][this.matrice[i].currentCase] = [];}
                formatedMatrice[this.matrice[i].currentString][this.matrice[i].currentCase] = {
                    "case": this.matrice[i].currentCase,
                    "string": this.matrice[i].currentString,
                    "digit": this.matrice[i].currentDigit,
                    "digitA": this.matrice[i].currentDigitA,
                    "infoTone": this.matrice[i].currentInfoTone,
                    "intervale": {},
                    "octave": this.matrice[i].currentOctave
                };
            }
            Neck.matrice = msg;
            Neck.formatedMatrice = formatedMatrice;

            Neck.draw();
        });


        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });

    },
    cleanIntervalesInFormatedMatrice:function(index){
        /*
         * r_s
         * 21_1
         */
        if(!index){
            $.each(Neck.formatedMatrice,function(k1,v1){
                $.each(Neck.formatedMatrice[k1],function(k2,v2){
                    $.each(Neck.formatedMatrice[k1][k2].intervale,function(k3,v3){
                        delete Neck.formatedMatrice[k1][k2].intervale[k3];
                    });
                });
            });
        }else{
            $.each(Neck.formatedMatrice,function(k1,v1){
                $.each(Neck.formatedMatrice[k1],function(k2,v2){
                    if(Neck.formatedMatrice[k1][k2].intervale[index])
                        delete Neck.formatedMatrice[k1][k2].intervale[index];
                });
            });
        }
        $( "#clearRootScaleSelection").hide();
    },
    setControls:function(){


        $( "#inlaysSelector" ).change(function() {
            if(this.checked){
                Neck.showInlays = true;
            }else{
                Neck.showInlays = false;
            }
            Neck.draw();
        });
        $( "#fretNumSelector" ).change(function() {
            if(this.checked){
                Neck.showFretNum = true;
            }else{
                Neck.showFretNum = false;
            }
            Neck.draw();
        });
        this.fillInstrumentSelector();
        $( "#instrumentSelector" ).change(function() {
            //Neck.getInstrument(this.value);
            Neck.instrumentId =this.value;
            //Neck.storeSession();
            location.reload();

            //Neck.insertAllRootScale();
        });



        $( "#nbrCasesMax" ).click(function() {
            Neck.displayedCase = Neck.displayedCaseMax
            Neck.draw();
        });
        $( "#nbrCasesReset" ).click(function() {
            Neck.displayedCase = Neck.displayedCaseMin
            Neck.draw();
        });
        $( "#nbrCasesAdd" ).click(function() {
            if(Neck.displayedCase<Neck.displayedCaseMax)
                Neck.displayedCase++;
            Neck.draw();
        });
        $( "#nbrCasesRemove" ).click(function() {
            if(Neck.displayedCase>Neck.displayedCaseMin)
                Neck.displayedCase--;
            Neck.draw();
        });

        $( "#clearRootScaleSelection").hide();
        $( "#clearRootScaleSelection" ).click(function() {
            Neck.cleanIntervalesInFormatedMatrice();
            Neck.rsBasket = [];
            $( "#clearRootScaleSelection").hide();
            Neck.draw();
        });


        if(Neck.rsBasket.length>1){
            $( "#rootScaleSelection").show();
            $( "#rootScaleSelection" ).click(function() {
                $( "#rootScaleSelection").hide();
                Neck.draw();
            });
        }else{
            $( "#rootScaleSelection").hide();
        }




        if(Neck.scBasket.length>0){
            $( "#clearSelection").show();
            $( "#fingeringSelection").show();
        }else{
            $( "#clearSelection").hide();
            $( "#fingeringSelection").hide();
        }

        $( "#clearSelection" ).click(function() {
            Neck.scBasket = [];
            Neck.draw();
        });
        $( "#fingeringSelection" ).click(function() {
            Neck.fingeringAction();
        });


        $( "#searchScaleFromSelection" ).click(function() {

            Neck.createFingerprint();

            $('#loading').show();

            var ajax_neck_searchRootScaleByDigits = Routing.generate('ajax_neck_searchRootScaleByDigits');

            var digits = [];
            $.each(Neck.scBasket, function(key,value){
                var splited = value.split("_");
                if($.inArray(Neck.formatedMatrice[splited[0]][splited[1]].digit,digits) < 0)
                    digits.push(Neck.formatedMatrice[splited[0]][splited[1]].digit);

            });
            var request = $.ajax({
                url: ajax_neck_searchRootScaleByDigits,
                method: "POST",
                data: { digits:digits },
                dataType: "html",
                async: false
            });


            request.done(function (msg) {


                $('#loading').hide();

                $("#neckResults").empty();

                var html="<h2>Results</h2><ul <ul class=\"list-inline\" id=\"neckResultsList\"></ul>";
                $("#neckResults").append(html);
                $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function (index, value) {

                    var x = [];
                    var y = [];
                    var d = [];
                    var i = [];
                    var w = [];

                    var datas = [];
                    var nameList = value.intervaleNameList.split(",");
                    var digitList = value.dList.split(",").map(function(item) {return parseInt(item);});
                    var wsList = value.wsList.split(",");
                    var deltaList = value.intervaleDeltaList.split(",");
                    var colorList = value.intervaleColorList.split(",");

                    $.each(Neck.scBasket,function(k,v){
                        var splited = v.split("_");
                        x.push(splited[1]);
                        y.push(splited[0]);

                        var ak = $.inArray(Neck.formatedMatrice[splited[0]][splited[1]].digitA%12,digitList);

                        if(ak != -1 ){
                            d.push(Neck.formatedMatrice[splited[0]][splited[1]].digitA);
                            i.push(nameList[ak]);
                            w.push(wsList[ak]);
                        }
                    });

                    var basket_fingering_add = Routing.generate('basket_fingering_add',{
                        instrumentId: Neck.instrument.id ,
                        instrumentName:Neck.instrument.name,
                        westernSystemId:value.wsId,
                        scaleId:value.scaleId,
                        fingeringId:(!Neck.isFingering)?0:Neck.isFingering,
                        xList: x.join(","),
                        yList: y.join(","),
                        dList: d.join(","),
                        wList: w.join(","),
                        iList: i.join(",")
                    });


                    for(i=0;i<nameList.length;i++){
                        datas[deltaList[i]] = ["C",nameList[i],colorList[i]];
                    }


                    var site_rootscale_index = Routing.generate('site_rootscale_instrumented_index',{
                        instrumentId:Neck.instrument.id,
                        instrumentName:Neck.instrument.name,
                        scaleName:value.scaleName,
                        scaleId:value.scaleId,
                        rootName:value.wsName,
                        rootId:value.wsId
                    });

                    var htmlBasket = '<a class="btn btn-default btn-xs" href="'+basket_fingering_add+'"><i class="glyphicon glyphicon-record"></i>add in basket</a>';
                    html="<li><div class=\"titleInVignette\"><a href=\""+site_rootscale_index+"\">"+value.rootInfoTone+" "+value.scaleName+"</a></div><div><canvas id=\"root_"+value.rootInfoTone+"_scale_"+value.scaleId+"\" width=\"180\" height=\"180\"></canvas>"+htmlBasket+"</div></li>";
                    $("#neckResultsList").append(html);

                    new diagram("root_"+value.rootInfoTone+"_scale_"+value.scaleId,datas);
                });




            });


            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });


        });

        this.fillRootScaleSelector();

        $( "#addRootScale" ).click(function() {
            Neck.rsAction();
        });

    },

        fingeringAction:function(){
            var ajax_neck_fingering = Routing.generate('ajax_neck_fingering');
            var request = $.ajax({
                url: ajax_neck_fingering,
                method: "POST",
                data: {i: Neck.scBasket},
                dataType: "html",
                async: false
            });


            request.done(function (msg) {

            });


            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });

        },
        rsAction:function(){

            var ajax_neck_rootScale = Routing.generate('ajax_neck_rootScale');
            var r = $("#rootSelector").attr('option','selected').val();
            var s = $("#scaleSelector").attr('option','selected').val();

            var i = $.inArray(r+"_"+s, Neck.rsBasket )
            if(i>=0){
                return false;
            }else{
                Neck.rsBasket.push(r+"_"+s);
            }

            $.each(Neck.rsBasket, function( index, value ) {

                var splitedValue = value.split("_");
                var r =  splitedValue[0];
                var s =  splitedValue[1];
                var newRs = true;

                for(i=0;i<Neck.formatedMatrice.length;i++){
                    for(j=0;j<Neck.formatedMatrice[i].length;j++){
                        if(Neck.formatedMatrice[i][j].intervale[r+"_"+s])
                            newRs = false;
                        break;
                    }
                }
                if(newRs) {
                    $('#loading').show();
                    var request = $.ajax({
                        url: ajax_neck_rootScale,
                        method: "POST",
                        data: {i: Neck.instrumentId, r: r, s: s},
                        dataType: "html",
                        async: false
                    });


                    request.done(function (msg) {
                        $('#loading').hide();
                        $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function (index, value) {
                            Neck.formatedMatrice[value.currentString][value.currentCase].intervale[r + "_" + s] = { intervaleName:value.currentIntervale , toneName:value.wsName };
                        });

                    });


                    request.fail(function (jqXHR, textStatus) {
                        alert("Request failed: " + textStatus);
                    });
                }
            });
            Neck.draw();
        },
    fillRootScaleSelector:function(){
        var ajax_neck_scale = Routing.generate('ajax_neck_scale');
        var request = $.ajax({
            url: ajax_neck_scale,
            method: "POST",
            data: { },
            dataType: "html",
            async: false
        });
        request.done(function( msg ) {
            $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function( index, value ) {
                $('#scaleSelector').append($('<option>', {
                    value: value.id,
                    text: value.name
                }));
            });
        });


        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });

        var ajax_neck_root = Routing.generate('ajax_neck_root');
        var request = $.ajax({
            url: ajax_neck_root,
            method: "POST",
            data: { },
            dataType: "html",
            async: false
        });
        request.done(function( msg ) {
            $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function( index, value ) {
                $('#rootSelector').append($('<option>', {
                    value: value.id,
                    text: value.name
                }));
            });
        });


        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });

        return true;
    },

    fillInstrumentSelector:function(){
        var ajax_neck_instruments = Routing.generate('ajax_neck_instruments');
        var request = $.ajax({
            url: ajax_neck_instruments,
            method: "POST",
            data: { },
            dataType: "html",
            async: false
        });

        request.done(function( msg ) {

            $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function( index, value ) {
                $('#instrumentSelector').append($('<option>', {
                    value: value.id ,
                    text: value.name
                }));

            });

            $('#instrumentSelector').val(Neck.instrumentId);

        });


        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });

        return true;
    },

    draw:function(){


        this.ctx.clearRect(0, 0, this.width, this.height);

        if(this.instrument.id > 0 ){
            this.drawNeck();
        }else{
            this.drawDiagram();
        }

        // RS SELECTION
        $("#currentRootScaleSelectionUl").empty();
        if(this.rsBasket.length > 0){
            $("#clearRootScaleSelection").show();
        }
        $.each(this.rsBasket, function( index, value ) {
            var rsBasketKey = $.inArray(value, Neck.rsBasket);

            var splitedValue = value.split("_");
            var rootName = $("#rootSelector option[value="+splitedValue[0]+"]").text()
            var scaleName = $("#scaleSelector option[value="+splitedValue[1]+"]").text()
            $("#currentRootScaleSelectionUl").append('<li  id="rootScale-'+value+'" ><button style="background-color: '+ Neck.rsColors[rsBasketKey]+';" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-remove"></i> '+rootName+' '+scaleName+'</button></li>');
            $("#rootScale-" + value).click(function () {
                Neck.cleanIntervalesInFormatedMatrice(value);
                Neck.rsBasket.splice(rsBasketKey, 1);
                Neck.draw();
            });
        });

        if(Neck.rsBasket.length>1){
            $( "#rootScaleSelection").show();
            $( "#rootScaleSelection" ).click(function() {
                var site_rootscale_instrumented_against = Routing.generate('site_rootscale_instrumented_against',{
                    instrumentId: Neck.instrument.id ,
                    instrumentName:Neck.instrument.name,
                    rootScaleList: Neck.rsBasket.join(",")
                });

                window.location.href = site_rootscale_instrumented_against;

            });
        }else{
            $( "#rootScaleSelection").hide();
        }


    },
    drawPoint:function(angle,distance,label,pseudoCase){

        var x = this.center_x + this.radius * Math.cos(-angle*Math.PI/180) * distance ;
        var y = this.center_y + this.radius * Math.sin(-angle*Math.PI/180) * distance;
        var x2 = this.center_x + this.radius * Math.cos(-angle*Math.PI/180) * distance *.7 - 10;
        var y2 = this.center_y + this.radius * Math.sin(-angle*Math.PI/180) * distance *.7;

        this.ctx.beginPath();
        this.ctx.font = "11px Arial";
        this.ctx.fillText(label, x2  ,y2 );
        this.ctx.fillStyle = 'black';
        this.ctx.arc(x, y, this.point_size, 0, 2 * Math.PI);
        this.ctx.fill();

        this.rectsC.push({x: x-20, y:y-20 , w: 40, h: 40 , case:pseudoCase,string:0});
        this.rectsCPoint.push({x: x, y:y , w: 1, h: 1 , case:pseudoCase,string:0});

        return [x,y];
    },
    drawDiagram:function(){

        this.marginC=5;
        this.marginB=5;
        this.widthC = this.c.height-2*this.marginC;
        this.heightC = this.c.height-2*this.marginC;
        this.x0B = this.widthC +  this.marginB /2;
        this.widthD = this.c.width - this.widthC - 2*this.marginB ;
        this.heightD = this.c.height-2*this.marginC;

        this.radius =  (this.widthC-50)/2;
        this.point_size = 2;
        this.center_x = this.widthC/2;
        this.center_y = this.heightC/2;
        this.points = [];

        this.ctx.clearRect(0, 0, this.width, this.height);
        this.ctx.beginPath();
        this.ctx.arc(this.center_x, this.center_y, this.radius, 0, 2 * Math.PI);
        this.ctx.stroke();

        this.ctx.lineWidth=1;
        this.ctx.moveTo(  this.x0B , this.height/2);
        this.ctx.lineTo(  this.x0B+this.widthD, this.height /2)  ;
        this.ctx.stroke();

        for(i=0;i<=12;i++){

            if(i<12)
            var p = this.drawPoint(- 360 * i / 12 +  3*360/12,1,this.tones[i] , i  );


            this.ctx.lineWidth=1;
            this.ctx.moveTo( this.x0B+this.widthD * i/12 , this.height/2 - 3);
            this.ctx.lineTo( this.x0B+this.widthD * i/12 , this.height/2 + 3)  ;
            this.ctx.stroke();
            this.ctx.font = "11px Arial";
            if( this.tones[i%12].indexOf("#")>=0) {
                this.ctx.fillText(this.tones[i%12], this.x0B+this.widthD * i/12 - 5  , this.height/2 + 17);
            }else{
                this.ctx.fillText(this.tones[i%12], this.x0B+this.widthD * i/12 - 5  , this.height/2 - 7);
            }

            this.rects.push({x:  this.x0B+this.widthD * i/12 -20, y:this.height/2-20 , w: 40, h: 40 , case:i,string:0});

        }

        // SC SELECTION
        $("#currentSelectionUl").empty();
        $.each(this.scBasket, function( index, value ) {
            var splitedValue = value.split("_");
            var s =  splitedValue[0];
            var c =  splitedValue[1];

            Neck.ctx.fillStyle = "rgba(120, 65, 65, .6)";

            if(c == 0){
                Neck.ctx.fillRect( Neck.x0B+Neck.widthD  - 3 ,Neck.height/2 - 3 ,6,6);
            }

            Neck.ctx.fillRect( Neck.x0B+Neck.widthD * c/12 - 3 ,Neck.height/2 - 3 ,6,6);
            Neck.ctx.fillRect( Neck.rectsCPoint[c].x - 3 ,Neck.rectsCPoint[c].y - 3 ,6,6);

                $.each(Neck.scBasket, function( index2, value2 ) {
                    var splitedValue2 = value2.split("_");
                    var s2 =  splitedValue2[0];
                    var c2 =  splitedValue2[1];
                    Neck.ctx.beginPath();
                    Neck.ctx.moveTo(Neck.rectsCPoint[c].x, Neck.rectsCPoint[c].y);
                    Neck.ctx.lineTo(Neck.rectsCPoint[c2].x, Neck.rectsCPoint[c2].y);
                    Neck.ctx.strokeStyle = 'rgba(0,0,0,.1)';
                    Neck.ctx.stroke();
                });



            $("#currentSelectionUl").append("<li>[ "+s+"-"+c+" ]"+Neck.formatedMatrice[s][c].infoTone+Neck.formatedMatrice[s][c].octave+"</li>");
        });

        $.each(Neck.points, function (index1, value1) {
            $.each(Neck.points, function (index2, value2) {
                Neck.ctx.beginPath();
                Neck.ctx.moveTo(value1[0], value1[1]);
                Neck.ctx.lineTo(value2[0], value2[1]);
                Neck.ctx.strokeStyle = 'rgba(0,0,0,.1)';
                Neck.ctx.stroke();
            });
        });


        $("#"+this.canvasId).unbind();

        $("#"+this.canvasId).bind( "click", function( e ) {

            var rect = Neck.collides(Neck.rects, e.offsetX, e.offsetY);
            var rectC = Neck.collides(Neck.rectsC, e.offsetX, e.offsetY);

            if (rect) {

                Neck.selectStringCase(rect.string , rect.case );


                jnSynth.play(Array(
                    Neck.formatedMatrice[rect.string][rect.case].digitA,
                    Neck.formatedMatrice[rect.string][rect.case].digitA
                ), 'chord');
            }
            if (rectC) {
                Neck.selectStringCase(rectC.string , rectC.case );

                jnSynth.play(Array(
                    Neck.formatedMatrice[rectC.string][rectC.case].digitA,
                    Neck.formatedMatrice[rectC.string][rectC.case].digitA
                ), 'chord');
            }
        });

    },
    drawNeck:function(){


        var caseW = this.width/(this.displayedCase);
        var caseH = this.height/this.formatedMatrice.length;


        Neck.rectsInNeck = [];

        for(i=0;i<this.formatedMatrice.length;i++){

            this.ctx.beginPath();
            this.ctx.strokeStyle='gold';
            this.ctx.lineWidth=100/this.formatedMatrice[i][0].digitA;
            this.ctx.moveTo( caseW, this.height - i*caseH - caseH/2);
            this.ctx.lineTo(this.width, this.height - i*caseH - caseH/2)  ;
            this.ctx.stroke();

            this.ctx.fillStyle = "#666";
            this.ctx.font="9px Georgia black";

            this.ctx.fillText(Translator.trans(this.formatedMatrice[i][0].infoTone)+this.formatedMatrice[i][0].octave,   3, Neck.height-i*caseH -caseH/2 +2 );

            for(j=0;j<this.displayedCase+1;j++){
                Neck.rectsInNeck.push({x: j*caseW, y:(this.height-caseH) - i*caseH, w: caseW, h: caseH , case:j,string:i});

                // dont draw in case 0
                if(j>0){
                    this.ctx.beginPath();
                    this.ctx.strokeStyle='silver';
                    this.ctx.lineWidth=1;
                    this.ctx.moveTo( j*(caseW), (caseH/2)-5);
                    this.ctx.lineTo(j*(caseW), 5 + this.height - caseH/2)  ;
                    this.ctx.stroke();
                    this.ctx.rect(j*caseW-2,i*caseH-2,caseW-2,caseH-2);
                    if(i==0 && Neck.showFretNum){
                        Neck.ctx.fillStyle = "#666";
                        Neck.ctx.font="10px Georgia";
                        Neck.ctx.fillText(this.roman[j],   j*caseW , Neck.height-i*caseH  -5);
                    }
                }


                var len = $.map(this.formatedMatrice[i][j].intervale, function(n, i) { return i; }).length;
                k=1;
                $.each(this.formatedMatrice[i][j].intervale, function(key, value)
                {
                    var rsBasketKey = $.inArray(key,Neck.rsBasket);
                    Neck.ctx.fillStyle = Neck.rsColors[rsBasketKey];
                    Neck.ctx.fillRect(   j*caseW + caseW*k*1/(1+len)  ,Neck.height-i*caseH -caseH/2 ,5,5);
                    Neck.ctx.font="12px Georgia";
                    Neck.ctx.fillText(value.intervaleName,   j*caseW + caseW*k*1/(1+len), Neck.height-i*caseH -caseH/2 -5);
                    Neck.ctx.font="10px Georgia";
                    Neck.ctx.fillText(Translator.trans(value.toneName),   j*caseW + caseW*k*1/(1+len), Neck.height-i*caseH -caseH/2 +15);

                    k++;
                });

            }

        }


        $.each(this.fBasket, function(key, value)
        {
                var yvalues = value.yList.split(",");
                var xvalues = value.xList.split(",");
                var wsname = value.wsNameList.split(",");
                var intervals= value.intervaleList.split(",");

            $.each(yvalues, function(yk, vk)
            {
                Neck.ctx.beginPath();
                Neck.ctx.arc(xvalues[yk]*caseW + caseW/2, Neck.height-yvalues[yk]*caseH -caseH/2, (caseW/2)-8, 0, 2 * Math.PI, false);
                Neck.ctx.fillStyle = Neck.fColors[key];
                Neck.ctx.fill();
            })


        });


        this.ctx.beginPath();
        this.ctx.strokeStyle='#000';
        this.ctx.lineWidth=1;
        this.ctx.moveTo( caseW-3, (caseH/2));
        this.ctx.lineTo(caseW-3, this.height - caseH/2)  ;
        this.ctx.stroke();

        if(this.showInlays){
            $.each(this.inlays, function(key, value)
            {
                var centerX = value*caseW + caseW/2;
                var centerY = Neck.height/2;
                var radius = caseW/10;
                if(value == 12){
                    var radius = caseW/12;
                    Neck.ctx.beginPath();
                    Neck.ctx.arc(centerX, centerY-caseH, radius, 0, 2 * Math.PI, false);
                    Neck.ctx.fillStyle = "rgba(88, 163, 127,.2)";
                    Neck.ctx.fill();
                    Neck.ctx.beginPath();
                    Neck.ctx.arc(centerX, centerY+caseH, radius, 0, 2 * Math.PI, false);
                    Neck.ctx.fill();
                }else{
                    Neck.ctx.beginPath();
                    Neck.ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI, false);
                    Neck.ctx.fillStyle = "rgba(88, 163, 127,.2)";
                    Neck.ctx.fill();
                }

            });
        }

        $("#"+this.canvasId).unbind();
        $("#"+this.canvasId).bind( "click", function( e ) {
            var rect = Neck.collides(Neck.rectsInNeck, e.offsetX, e.offsetY);
            if (rect) {
                Neck.selectStringCase(rect.string , rect.case );

                Neck.playTones(
                    Array(
                        Neck.formatedMatrice[rect.string][rect.case].infoTone +
                        Neck.formatedMatrice[rect.string][rect.case].octave  ),"arpeggio");
            }
        });

        // SC SELECTION
        $("#currentSelectionUl").empty();
        $.each(this.scBasket, function( index, value ) {
            var splitedValue = value.split("_");
            var s =  splitedValue[0];
            var c =  splitedValue[1];

            Neck.ctx.fillStyle = "rgba(10, 10, 10, 1)";
            Neck.ctx.fillRect( caseW/2 + c*caseW,Neck.height-s*caseH -caseH/2 - 3,6,6);

            $("#currentSelectionUl").append("<li>[ "+s+"-"+c+" ]"+Neck.formatedMatrice[s][c].infoTone+Neck.formatedMatrice[s][c].octave+"</li>");
        });

        if(Neck.scBasket.length>0){
            $( "#clearSelection").show();
            $( "#searchScaleFromSelection").show();
            $( "#fingeringSelection").show();
        }else{
            $( "#clearSelection").hide();
            $( "#searchScaleFromSelection").hide();
            $( "#fingeringSelection").hide();
        }
    },
    cleanSelection:function () {
        $("#currentSelectionUl").empty();
        this.scBasket = [];
    },
    selectStringCase:function (s,c) {
        var i = $.inArray(s+"_"+c, this.scBasket )
        if(i>=0)
            this.scBasket.splice(i,1);
        else
            this.scBasket.push(s+"_"+c);

        this.draw();
    },

    collides:function (rects, x, y) {
        var isCollision = false;
        for (var i = 0, len = rects.length; i < len; i++) {
            var left = rects[i].x, right = rects[i].x+rects[i].w;
            var top = rects[i].y, bottom = rects[i].y+rects[i].h;
            if (right >= x
                && left <= x
                && bottom >= y
                && top <= y) {
                isCollision = rects[i];
            }
        }
        return isCollision;
    },
    createFingerprint:function(){
        var memString = [];
        var xList = [];
        var xListNoOs = [];
        var yList = [];
        Neck.fingerprint = [];
        Neck.fingerprintIncremented = [];
        Neck.isArpeggio = false;
        Neck.isFingering = 0;


        $.each(Neck.scBasket,
            function(k,v){
            var splited = v.split('_');
            if($.inArray( splited[0],memString ) != -1 || Neck.scBasket.length>Neck.formatedMatrice.length){
                Neck.isArpeggio = true;
            }
            memString.push(splited[0]);

                if(splited[1] > 0){
                    xListNoOs.push(parseInt(splited[1]));
                }
                xList.push(parseInt(splited[1]));
                yList.push(parseInt(splited[0]));


        });

        var minY = Math.min.apply(Math,yList);
        var minX = Math.min.apply(Math,xList);

        if(minY>0){
            $.each(yList,function(k,v){yList[k]=v-minY;});
        }
        if(minX == 0){
            var minXNoOs = Math.min.apply(Math, xListNoOs);
            $.each(xList,function(k,v){if(xList[k]>0){xList[k]=v-minXNoOs+1;}});
        }else if(minX > 1){
            $.each(xList,function(k,v){xList[k]=v-minX+1;});
        }

        $.each(yList,function(k,v){
            if(minX == 0){
                if( xList[k] != 0){
                    var newX = xList[k]+minXNoOs;
                }else{
                    var newX = 1    ;
                }
                Neck.fingerprintIncremented.push(v+"_"+newX);

            }
            Neck.fingerprint.push(v+"_"+xList[k]);
        });




        var ajax_neck_searchFingering = Routing.generate('ajax_neck_searchFingering');
        var request = $.ajax({
            url: ajax_neck_searchFingering,
            method: "POST",
            data: {
                fingerprint:Neck.fingerprint ,
                fingerprintIncremented:Neck.fingerprintIncremented ,
                isArpeggio:Neck.isArpeggio
            },
            dataType: "html",
            async: false
        });

        request.done(function( msg ) {
            $.each($.parseJSON(msg.replace(/&quot;/g, '\"')), function (index, value) {
                Neck.isFingering = value.fId;
            });

        });


        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });




    },
    playTones:function(tones,arpeggio){

        if(arpeggio){
            var myJNSYNTH = JNSYNTH.setSynth("triangle");
            //var piano = new Tone.Synth().toMaster();
            var piano = myJNSYNTH.synth;

            var pianoPart = new Tone.Sequence(function(time, note){
                var n = DIGIT.toneForTone(note);
                piano.triggerAttackRelease(n, "4n", time);
            }, tones,"4n").start();

            pianoPart.loop = false;
            pianoPart.loopEnd = "1m";
            pianoPart.humanize = false;

            Tone.Transport.bpm.value = 120;

            Tone.Transport.start("+0.1");

        }else{
            var myJNSYNTH = JNSYNTH.setSynth("triangle","poly");


            var d = [];

            $.each(tones,function(k,v){
                tones[k] = DIGIT.toneForTone(v);
                d.push("4n");
            });

            myJNSYNTH.synth.triggerAttackRelease(tones, d);
        }



    },
    tones:Array("C","C#/Db","D","D#/Eb","E","F","F#/Gb","G","G#/Ab","A","A#/Bb","B","C"),
    rects:[],
    rectsC:[],
    rectsCPoint:[],
    points:[],
    session:Array,
    instrument:null,
    instrumentId:null,
    instrument:null,
    formatedMatrice:null,
    matrice:null,
    scBasket:[],
    rsBasket:[],
    showInlays:true,
    fingerprint:[],
    isArpeggio:false,
    inlays:[3,5,7,9,12,15,17,19,21,24],
    showFretNum:true,
    roman:['0','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVII','XIX','XX','XXI','XXII','XXIII','XXIV'],
    rsColors:["hsl(265, 33%, 67%)", "hsl(344, 95%, 77%)","hsl(159, 96%, 63%)","hsl(27, 76%, 73%)","hsl(359, 96%, 90%)"],
    fColors:["rgba(100,30,20,.1)", "rgba(87,120,20,.1)", "rgba(30,100,20,.1)","rgba(20,30,100,.1)","rgba(100,30,100,.1)","rgba(100,100,20,.1)"],
    firstCase:1,
    displayedCase:5,
    displayedCaseMin:5,
    displayedCaseMax:17
};